<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class view{
	
	var $view = null;
	var $data = array();
	
	function __construct($data){
		$this->data = $data;
	}
	
	function view($view){
		$this->view = $view;
		$this->load($this->data);
	}
	
	function set($array){
		foreach($array as $key => $value){
			$this->data[$key] = $value;
		}
	}
	
	function load(){
		require_once $this->data['config']['path']."/kchat/view/".$this->view.".php";
	}
	
	function appendfile($files){
		if(isset($_GET['start'])){
			$this->data["jsh"][] = "<script src=\"".$this->data['config']['url']."/kchat/assets/enjoyhint/enjoyhint.js\" ></script>";
			$this->data["css"][] = "<link rel=\"stylesheet\" href=\"".$this->data['config']['url']."/kchat/assets/enjoyhint/enjoyhint.css\" />";
			$this->data["js"][] = "<script src=\"".$this->data['config']['url']."/kchat/assets/js/enjoyhint.js\" ></script>";
		}
		if(isset($files["jsh"])){
			foreach($files["jsh"] as $jsh){
				if (strpos($jsh, 'http://') !== false || strpos($jsh, 'https://') !== false || strpos($jsh, '//') !== false) {
					$this->data["jsh"][] = "<script src=\"".$jsh."\" ></script>";
				}else{
					$this->data["jsh"][] = "<script src=\"".$this->data['config']['url']."/kchat/".$jsh."\" ></script>";
				}
			}
		}
		if(isset($files["js"])){
			foreach($files["js"] as $js){
				if (strpos($js, 'http://') !== false || strpos($js, 'https://') !== false || strpos($js, '//') !== false) {
					$this->data["js"][] = "<script src=\"".$js."\" ></script>";
				}else{
					$this->data["js"][] = "<script src=\"".$this->data['config']['url']."/kchat/".$js."\" ></script>";
				}
			}
		}
		if(isset($files["css"])){
			foreach($files["css"] as $css){
				if (strpos($css, 'http://') !== false || strpos($css, 'https://') !== false || strpos($css, '//') !== false) {
					$this->data["css"][] = "<link rel=\"stylesheet\" href=\"".$css."\" />";
				}else{
					$this->data["css"][] = "<link rel=\"stylesheet\" href=\"".$this->data['config']['url']."/kchat/".$css."\" />";
				}
			}
		}
	}
}

class model{
	
	var $data = null;
	
	function __construct($data){
		$this->data = $data;
	}
	
	function process($action){
		$action = explode(".",$action);
		if(isset($action[0])){
			if(!isset($action[1])){
				$action[1] = 'action';
			}
			$path = $this->data['config']['path']."/kchat/actions/".$action[0].".php";
			if(file_exists($path)){
				include $path;
				$actn = new $action[0]($this->data);
				if(method_exists($actn,$action[1])){
					$func = (string)$action[1];
					return $actn->$func($this->data);
				}
			}else{
				echo "file Not Found";
			}
		}
	}
}

class ctrl{
	
	var $data = array();
	var $dbprefix = array();
	var $load = null;
	var $model = null;
	
	function __construct($data){
		$this->load = new view($data);
		$this->load->appendfile(array());
		$this->model = new model($data);
		$this->data = $data;
		if(isset($data['db']['db_prefix'])){
			$this->dbprefix = $data['db']['db_prefix'];
		}else{
			$this->dbprefix = '';
		}
	}
}
