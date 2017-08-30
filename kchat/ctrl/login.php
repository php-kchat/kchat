<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class login extends ctrl{
	
	function index(){
		if(isset($this->data['db'])){
			$this->load->view('login');
		}else{
			$this->load->view('install');
		}
	}
	
	function verify(){
		
		if(isset($this->data['param'][0])){
			$arr = $this->data['param'][0];
		}
		
		if(!isset($this->data['param'][0])){
			$this->load->view('login');
			return $this->data;
		}
		
		$arr = @unserialize(trim(substr(base64_decode($arr),64,strlen(base64_decode($arr)))));
		
		$stmt = $this->data['pdo']->prepare("SELECT `dept` FROM {$this->dbprefix}department where id =:id");
		$stmt->execute(array('id' => $arr['verify']['dept']));
		$row = $stmt->fetch();
		if(isset($row['dept'])){
			$arr['verify']['dept_name'] = $row['dept'];
		}else{
			return false;
		}
		$this->load->set($arr);
		$this->load->view('verify');
	}
}