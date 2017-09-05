<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class verify extends action{
	function action(){
		
		$username = $_POST['username'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];
		$secret = $_POST['secret'];
		$dept = $_POST['dept'];
		$email = $_POST['email'];
		
		if($password != $repassword){
			echo json_encode(array("error" => "Password Not Matched"));
			return false;
		}
		
		$stmt = $this->data['pdo']->prepare("SELECT secret FROM {$this->dbprefix}pusers where uname = :username");
		$stmt->execute(array('username' => $username));
		$row = $stmt->fetch();
		$secret2 = $row['secret'];
		if($secret == $secret2){
			
			$stmt = $this->data['pdo']->prepare("insert into {$this->dbprefix}users (`id`,`fname`,`lname`,`uname`,`password`,`role`,`dept`,`email`) values (:id, :fname, :lname, :uname, :password , 2 ,:dept,:email);");
			try {
				$stmt->execute(array(
					'id' => kchat_rand(),
					'fname' => $fname,
					'lname' => $lname,
					'uname' => $username,
					'password' => $password,
					'dept' => $dept,
					'email' => $email,
				));
				$this->data['Admin'] = $this->data['config']['Admin'];
				set_notification($this->data,"User $fname $lname Verified.<br/>User ID - $username");
			} catch (PDOException $e) {
				if ($e->getCode() == 1062) {
					alertify::alert("User Exist All ready");
				} else {
					throw $e;
				}
			}
			
			$secret2 = $row['secret'];
			
			$stmt = $this->data['pdo']->prepare("delete from {$this->dbprefix}pusers where uname=:uname;");
			$stmt->execute(array('uname' => $username));	
		}else{
			alertify::alert("verify link is Invalid");
		}
		
	}
}