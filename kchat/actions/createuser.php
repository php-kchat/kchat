<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class createuser extends action{
	
	function action($data){
				
		$arr = array(
			'verify' => array(
				'fname' => $_POST['first_name'],
				'lname' => $_POST['last_name'],
				'uname' => $_POST['user_name'],
				'secret' => $_POST['secret'],
				'dept' => $_POST['dept'],
			)
		);
		
		$stmt = $data['pdo']->prepare("SELECT uname FROM {$this->dbprefix}users where uname =:uname");
		$stmt->execute(array('uname' => $_POST['user_name']));
		$row = $stmt->fetch();
		if(isset($row['uname'])){
			echo json_encode(array('error' => 'User All Ready Exist'));
			return false;
		}
		
		$stmt = $data['pdo']->prepare("insert into {$this->dbprefix}pusers values(:fname,:lname,:uname,:secret,:dept)");
		$stmt->execute($arr['verify']);
		
		
		echo json_encode(array('vlink' => base64_encode($arr['verify']['secret'].serialize($arr))));
	}
}