<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

function isValid($data){
	if(!isAjax()){
		$_SESSION['online'] = array();
	}
	if(isset($_SESSION[$data['config']['session']])){
		$login = @unserialize(base64_decode($_SESSION[$data['config']['session']]));
	}else{
		$login = false;
	}
	if(isset($login['machine_id'])){
		if($login['machine_id'] != md5(getip().getBrowser())){
			return false;
		}
	}
	if($login !== false){
		return true;
	}
	return false;
}

function setSession($data,$ar){
	$ar['machine_id'] = md5(getip().getBrowser());
	$_SESSION[$data['config']['session']] = base64_encode(serialize($ar));
}
