<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class ajax extends ctrl{
		
	function login($data){
		if(isAjax($data)){
			if(isset($_POST['action'])){
				$this->ajax->process($_POST['action']);
			}
		}else{
			$this->load->view('deny');
		}
	}
	
	function smtp($data){
		if(isAjax($data)){
			if(empty($_POST['smtp_host']) || empty($_POST['smtp_port']) || empty($_POST['smtp_auth'])){
					return false;
			}
			
			if(file_exists('config/smtp.php') && empty($_POST['smtp_pass'])){
				$smtp_conf = include 'config/smtp.php';
				$_POST['smtp_pass'] = $smtp_conf['pass'];
			}
			
			$smtp = array(
				"host" => $_POST['smtp_host'],
				"port" => (int)$_POST['smtp_port'],
				"secure" => (int)$_POST['smtp_secure'],
				"auth" => json_decode($_POST['smtp_auth']),
				"email" => $_POST['smtp_email'],
				"pass" => $_POST['smtp_pass'],
			);
			
			if(fcreate("config/smtp.php",pcode($smtp))){
				echo "Success";
				alertify::alert('Successfull');
			}
			
		}else{
			$this->load->view('deny');
		}
	}
	
	function createuser($data){
		if(isAjax($data)){
			if(isset($_POST['action'])){
				$this->ajax->process($_POST['action']);
			}
		}else{
			$this->load->view('deny');
		}
	}
	
	function verify($data){
		
		if(isAjax($data)){
			if(isset($_POST['action'])){
				$this->ajax->process("verify");
			}
		}else{
			$this->load->view('deny');
		}
	}
	
	function logout($data){
		if(isAjax($data)){
			unset($_SESSION[$data['config']['session']]);
		}else{
			$this->load->view('deny');
		}
	}
	
	function index($data){
		if(!isAjax($data)){
			$this->load->view('deny');
		}
	}
	
	function update($data){
		if(isAjax($data)){
			$this->ajax->process("profile");
		}else{
			$this->load->view('deny');
		}
	}

	function chat($data){
		if(isAjax($data)){
			$this->ajax->process("msgs");
		}else{
			$this->load->view('deny');
		}
	}
	
	function typing($data){
		$array = $this->ajax->process("typing");
	}
	
	function create_chat($data){
		$this->ajax->process("createchat");
	}
	
	function deleteuser($data){
		if($data['user']['role'] !== 1){
			return false;
		}
		
		if(!empty($_POST['uname'])){
			$stmt = $data['pdo']->prepare("DELETE FROM {$this->dbprefix}users WHERE id=:id;");
			$stmt->execute(
				array(
					'id' => $_POST['uname'],
					)
			);
		}
	}
	
	function adddept($data){
		if($data['user']['role'] !== 1){
			return false;
		}
		
		if(!empty($_POST['dept']) && !empty($_POST['desc'])){
			
			$stmt = $data['pdo']->prepare("SELECT `dept` FROM {$this->dbprefix}department where dept =:dept");
			$stmt->execute(array('dept' => $_POST['dept']));
			$row = $stmt->fetch();
			if(!empty($row)){
				return false;
			}
			$stmt = $data['pdo']->prepare("INSERT INTO {$this->dbprefix}department (`dept`,`discription`) VALUES (:dept,:desc)");
			$stmt->execute(
				array(
					'dept' => $_POST['dept'],
					'desc' => $_POST['desc'],
					)
			);
		}
	}
	
	function settings($data){
		$setting = array();
		if(!empty($_POST['settings'])){
			$setting = $_POST['settings'];
		}
		
		foreach($setting[0] as $k => $v){
			if(isset($setting[1][$k])){
				$sql = "UPDATE {$this->dbprefix}setting SET `value` = :value WHERE `key` = :key";
				$stmt = $data['pdo']->prepare($sql);
				$stmt->execute(array(
					'value' => $setting[1][$k],
					'key' => $setting[0][$k],
				));
			}
		}
	}
	
	function install($data){
		if(isset($data['db'])){
			return false;
		}
		
		if(
			empty($_POST['database']) ||
			empty($_POST['username']) ||
			empty($_POST['host'])
		){
			return false;
		}
		
		if(empty($_POST['port'])){
			$_POST['host'] = 3306;
		}
		
		$opt = array(
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
			PDO::MYSQL_ATTR_FOUND_ROWS => TRUE
		);
		
		$data['pdo'] = new PDO("mysql:host=".$_POST['host'].";dbname=".$_POST['database'].";port=".$_POST['port'].";charset=utf8", $_POST['username'], $_POST['password'],$opt);
		
		$db = array(
			"db_host" => $_POST['host'],
			"db_user" => $_POST['username'],
			"db_pass" => $_POST['password'],
			"db_db" => $_POST['database'],
			"db_port" => $_POST['port'],
			"db_prefix" => $_POST['dbprefix'],
		);
		
		
		if($data['pdo']){
			
			if(!fcreate("config/db.php",pcode($db))){
				return false;
			}
			
			$fsql = fopen( $data['config']['path'] . '/kchat/sql/kchat.sql.php', "r") or die("The SQL File could not be opened.");
			$data['db']['db_prefix'] = $_POST['dbprefix'];
			while($sql = nextQuery($fsql)){
				$sql = presql($data,$sql);
				$stmt = $data['pdo']->prepare($sql);
				$stmt->execute(array());
				echo '.';
			}
			
		}
	}
}