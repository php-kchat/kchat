<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class ajax extends ctrl{
		
	function login(){
		if(isAjax($this->data)){
			if(isset($_POST['action'])){
				$this->model->process($_POST['action']);
			}
		}else{
			$this->load->view('deny');
		}
	}
	
	function smtp(){
		if(isAjax($this->data)){
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
				"auth" => json_decode($_POST['smtp_auth'], 1),
				"email" => $_POST['smtp_email'],
				"pass" => $_POST['smtp_pass'],
			);
			
			if(fcreate("config/smtp.php",pcode($smtp))){
				echo "Success";
				if(isset($smtp_conf)){
					alertify::alert('Successfully Updateed');
					set_notification($this->data,'SMTP Detail Updateed');
				}else{
					alertify::alert('Successfully Configured');
					set_notification($this->data,'SMTP Detail Configurred');
				}
			}
			
		}else{
			$this->load->view('deny');
		}
	}
	
	function createuser(){
		if(isAjax($this->data)){
			if(isset($_POST['action'])){
				$this->model->process($_POST['action']);
			}
		}else{
			$this->load->view('deny');
		}
	}
	
	function notification(){
		if(isAjax($this->data)){
			echo json_encode($this->model->process('notification'));
		}else{
			$this->load->view('deny');
		}
	}
	
	function verify(){
		
		if(isAjax($this->data)){
			if(isset($_POST['action'])){
				$this->model->process("verify");
			}
		}else{
			$this->load->view('deny');
		}
	}
	
	function logout(){
		if(isAjax($this->data)){
			unset($_SESSION[$this->data['config']['session']]);
		}else{
			$this->load->view('deny');
		}
	}
	
	function index(){
		if(!isAjax($this->data)){
			$this->load->view('deny');
		}
	}
	
	function update(){
		if(isAjax($this->data)){
			$this->model->process("profile");
		}else{
			$this->load->view('deny');
		}
	}

	function chat(){
		if(isAjax($this->data)){
			$this->model->process("msgs");
		}else{
			$this->load->view('deny');
		}
	}
	
	function typing(){
		$array = $this->model->process("typing");
	}
	
	function create_chat(){
		$this->model->process("createchat");
	}
	
	function deleteuser(){
		if($this->data['user']['role'] !== 1){
			return false;
		}
		
		if(!empty($_POST['uname'])){
			$stmt = $this->data['pdo']->prepare("DELETE FROM {$this->dbprefix}users WHERE id=:id;");
			$stmt->execute(
				array(
					'id' => $_POST['uname'],
					)
			);
		}
	}
	
	function adddept(){
		if($this->data['user']['role'] !== 1){
			return false;
		}
		
		if(!empty($_POST['dept']) && !empty($_POST['desc'])){
			
			$stmt = $this->data['pdo']->prepare("SELECT `dept` FROM {$this->dbprefix}department where dept =:dept");
			$stmt->execute(array('dept' => $_POST['dept']));
			$row = $stmt->fetch();
			if(!empty($row)){
				return false;
			}
			$stmt = $this->data['pdo']->prepare("INSERT INTO {$this->dbprefix}department (`dept`,`discription`) VALUES (:dept,:desc)");
			$stmt->execute(
				array(
					'dept' => $_POST['dept'],
					'desc' => $_POST['desc'],
					)
			);
		}
	}
	
	function settings(){
		$setting = array();
		if(!empty($_POST['settings'])){
			$setting = $_POST['settings'];
		}
		
		foreach($setting[0] as $k => $v){
			if(isset($setting[1][$k])){
				$sql = "UPDATE {$this->dbprefix}setting SET `value` = :value WHERE `key` = :key;";
				$stmt = $this->data['pdo']->prepare($sql);
				$stmt->execute(array(
					'value' => $setting[1][$k],
					'key' => $setting[0][$k],
				));
			}
		}
	}
	
	function install(){
		if(isset($this->data['db'])){
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
		
		try{
			$this->data['pdo'] = new PDO("mysql:host=".$_POST['host'].";dbname=".$_POST['database'].";port=".$_POST['port'].";charset=utf8", $_POST['username'], $_POST['password'],$opt);
		}catch(Exception $e){
			$_SESSION['ERROR'] = $e->getMessage();
			exit;
		}
		
		$db = array(
			"db_host" => $_POST['host'],
			"db_user" => $_POST['username'],
			"db_pass" => $_POST['password'],
			"db_db" => $_POST['database'],
			"db_port" => $_POST['port'],
			"db_prefix" => $_POST['dbprefix'],
		);
		
		
		if($this->data['pdo']){
			
			if(!fcreate("config/db.php",pcode($db))){
				return false;
			}
			//create mysql structure
			$fsql = fopen( $this->data['config']['path'] . '/kchat/sql/kchat.struct.sql.php', "r") or die("The SQL File could not be opened.");
			$this->data['db']['db_prefix'] = $_POST['dbprefix'];
			while($sql = trim(nextQuery($fsql))){
				if(empty($sql)){
					continue;
				}
				$sql = presql($this->data,$sql);
				$stmt = $this->data['pdo']->prepare($sql);
				$stmt->execute(array());
				echo '.';
			}
			//insert mysql data
			$fsql = fopen( $this->data['config']['path'] . '/kchat/sql/kchat.data.sql.php', "r") or die("The SQL File could not be opened.");
			$this->data['db']['db_prefix'] = $_POST['dbprefix'];
			while($sql = trim(nextQuery($fsql))){
				if(empty($sql)){
					continue;
				}
				$sql = presql($this->data,$sql);
				$stmt = $this->data['pdo']->prepare($sql);
				$stmt->execute(array());
				echo '.';
			}
		}
	}
}