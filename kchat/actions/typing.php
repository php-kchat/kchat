<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class typing extends action{
	function action(){
		$grp = $this->getGroup();
		if($grp){
			$sql = "UPDATE `{$this->dbprefix}cache` 
			SET `time` = UNIX_TIMESTAMP() 
			WHERE uname = :uname AND
			process = 3;";
			$sql_array = array(
				'uname' => $this->data['user']['uname']
			);
			$stmt = $this->data['pdo']->prepare($sql);
			$stmt->execute($sql_array);	
			$count = $stmt->rowCount();
			if($count == 0){
				// process 3 typing
				$sql = "INSERT INTO `{$this->dbprefix}cache` (`fname`,`lname`,`time`,`uname`,`group`,`process`,`value`,`dept`,`support_id`)
				VALUES (:fname,:lname,UNIX_TIMESTAMP(),:uname,:group,3,1,:dept,:support_id);";
				$sql_array = array(
					'fname' => $this->data['user']['fname'],
					'lname' => $this->data['user']['lname'],
					'uname' => $this->data['user']['uname'],
					'group' => $grp,
					'dept' => $this->data['user']['dept'],
					'support_id' => $this->data['user']['id'],
				);
				$stmt = $this->data['pdo']->prepare($sql);
				$stmt->execute($sql_array);
			}
		}
		return $this->data;
	}
	
	function getGroup(){
		if(isset($this->data['param'][0])){
			return base64_decode(urldecode($this->data['param'][0]));
		}else{
			return 'NO_GROUP';
		}
	}
}