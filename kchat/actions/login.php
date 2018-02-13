<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class login extends action{
	function action(){
		if(isset($_POST['username'])){
			$user = $_POST['username'];
			$password = $_POST['password'];
			$stmt = $this->data['pdo']->prepare("SELECT * FROM `{$this->dbprefix}users` where uname = :uname;");
			$stmt->execute(array('uname' => $user));
			$success = 'fail';
			while ($row = $stmt->fetch())
			{
				if(CheckValid($password,$row['password'])){
					session::setSession($this->data,$row);
					$_SESSION['KChat_Token'] = k_random(32);
					$success = 'success';
				}else{
					$success = 'fail';
				}
			}
			echo $success;
		}
	}
}