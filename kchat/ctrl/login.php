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
			$chrs = range('a','z');
			$array['dbprefix'] = 'kc'.$chrs[rand(0,25)].$chrs[rand(0,25)].'_';
			$array['host']     = $_SERVER['HTTP_HOST'];
			$install = array();
			$timezone = get_cfg_var('date.timezone');
			$install['extensions'] = array(
				"json",
				"pdo"
			);
			$install['writable'] = array(
				'config',
				'logs',
				'cache',
				'logs/kchat.log.php',
				'box/config',
				'box/logs/error.log',
			);
			$install['modules'] = array(
				//"mod_rewrite"
			);
			if(!$timezone){
				$array['error'][] = "Please set timezone in php.ini";
			}
			foreach($install['extensions'] as $extension){
				if(!extension_loaded($extension)){
					$array['error'][] = $extension." Extension is Not Loaded";
				}
			}
			foreach($install['writable'] as $writable){
				if(!is_writable($writable)){
					$array['error'][] = $writable." is Not Writable";
				}
			}
			
			/*
			$modules = apache_get_modules();
			foreach($install['modules'] as $module){
				if(!in_array($module,$modules)){
					$array['error'][] = $module." is Not Installed";
				}
			}
			*/
			
			if(isset($_SESSION['ERROR'])){
				$array['error'][] = $_SESSION['ERROR'];
				unset($_SESSION['ERROR']);
			}
			
			$this->load->set($array);
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