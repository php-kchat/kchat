<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class typing extends action{
	function action($data){
		$grp = $this->getGroup($data);
		if($grp){
			$sql = "UPDATE `{$this->dbprefix}cache` 
			SET `time` = UNIX_TIMESTAMP() 
			WHERE uname = :uname AND
			process = 3";
			$sql_array = array(
				'uname' => $data['user']['uname']
			);
			$stmt = $data['pdo']->prepare($sql);
			$this->qfired++;
			$stmt->execute($sql_array);	
			$count = $stmt->rowCount();
			if($count == 0){
				// process 3 typing
				$sql = "INSERT INTO `{$this->dbprefix}cache` (`fname`,`lname`,`time`,`uname`,`group`,`process`,`value`)
				VALUES (:fname,:lname,UNIX_TIMESTAMP(),:uname,:group,3,1);";
				$sql_array = array(
					'fname' => $data['user']['fname'],
					'lname' => $data['user']['lname'],
					'uname' => $data['user']['uname'],
					'group' => $grp,
				);
				$stmt = $data['pdo']->prepare($sql);
				$stmt->execute($sql_array);
			}
		}
		typing($data,$grp,false);
	}
	
	function getGroup($data){
		if(isset($data['param'][0])){
			return base64_decode(urldecode($data['param'][0]));
		}else{
			return 'NO_GROUP';
		}
	}
}