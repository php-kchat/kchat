<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class verify extends action{
	function action($data){
		
		$username = $_POST['username'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];
		$secret = $_POST['secret'];
		$dept = $_POST['dept'];
		
		if($password != $repassword){
			echo json_encode(array("error" => "Password Not Matched"));
			return false;
		}
		
		$stmt = $data['pdo']->prepare("SELECT secret FROM {$this->dbprefix}pusers where uname = :username");
		$stmt->execute(array('username' => $username));
		$row = $stmt->fetch();
		$secret2 = $row['secret'];
		if($secret == $secret2){
			
			$stmt = $data['pdo']->prepare("insert into {$this->dbprefix}users (`id`,`fname`,`lname`,`uname`,`password`,`role`,`dept`) values (:id, :fname, :lname, :uname, :password , 2 ,:dept)");
			
			$stmt->execute(array(
				'id' => kchat_rand(),
				'fname' => $fname,
				'lname' => $lname,
				'uname' => $username,
				'password' => $password,
				'dept' => $dept,
			));
			
			$secret2 = $row['secret'];
			
			$stmt = $data['pdo']->prepare("delete from {$this->dbprefix}pusers where uname=:uname;");
			$stmt->execute(array('uname' => $username));	
		}
		
	}
}