<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

$utype = '';

$path = '';

if(isset($_SERVER['KChat'])){
	switch($_SERVER['KChat']){
		case 'PATH_INFO':
			if(isset($_SERVER["PATH_INFO"])){
				$path = $_SERVER["PATH_INFO"];
			}
			$utype = '/index.php';
		break;
		case 'MOD_REWRITE':
			if(isset($_GET['kroute'])){
				$path = $_GET['kroute'];
			}
		break;
	}
}else{
	if(isset($_SERVER["QUERY_STRING"])){
		$path = $_SERVER["QUERY_STRING"];
		$utype = '/index.php?';
	}
}

$path = explode("/",trim($path,"/"));

$p = 2;
while(isset($path[$p])){
	$data["param"][] = $path[$p++];
}

//reqps($data);

$data['config'] = include "config/config.php";

//timezone 
date_default_timezone_set($data['config']['timezone']);

$data['KChat_conf']['installed'] = false;

$data['KChat_conf'] = _config($data['config']['key']);

if(!$data['KChat_conf']['installed']){
	if(isset($data['db'])){
		$_cache = array_merge($data['config'],$data['db']);
		$t = new TempCache('~S~kchat~'.$data['config']['key'].'~@~');
		$t->setcache(base64_encode(serialize($_cache)));
	}
}

$data['config']['purl'] = $data['config']['url'].$utype;

$access = array(
	"login",
	"verify",
	"install",
);

$allow = true;
if(isset($path[0]) && isset($path[1])){
	if(($path[0] == 'ajax') && in_array($path[1],$access)){
		$allow = false;
	}
}

if(session::get_Data($data)){
	$data['user'] = session::get_Data($data);
}

if(isset($data['user']['role'])){
	$data = permission::sitebar_access($data);
	$path = permission::access($path,$data['user']['role']);
}

if($allow){
	if(!session::isValid($data)){
		$path[0] = 'login';
		if(!isset($path[1])){
			$path[1] = "index";
		}
	}else{
		if(isset($path[0])){
			if($path[0] == 'login'){
				$path = array(
					"main",
					"index",
				);
			}
		}
		if(isset($_SESSION['KChat_Token']) && isAjax($data)){
			if(!isset($_POST['token'])){
				$_POST['token'] = rand(1000,9999);
			}
			if(($_SESSION['KChat_Token'] != $_POST['token'])){
				unset($_SESSION[$data['config']['session']]);
				die('token mismatch');
			}
		}
	}
}

if(empty($_POST['token'])){
	$_POST['token'] = rand(1000,9999);
}

$data['path'] = $path;

if(isset($path[0])){
	if(file_exists($data['config']['path']."/kchat/ctrl/".$path[0].".php")){
		require_once $data['config']['path']."/kchat/ctrl/".$path[0].".php";
		if(class_exists($path[0])){
			$data['active'] = $path[0];
			$ctrl = new $path[0]($data);
		}else{
			require_once $data['config']['path']."/kchat/ctrl/main.php";
			$data['active'] = "main";
			$ctrl = new main($data);
		}
	}else{
		require_once $data['config']['path']."/kchat/ctrl/main.php";
		$data['active'] = "main";
		$ctrl = new main($data);
	}
}else{
	require_once $data['config']['path']."/kchat/ctrl/main.php";
	$data['active'] = "main";
	$ctrl = new main($data);
}

if(isset($path[1])){
	if(method_exists($ctrl,$path[1])){
		$ctrl->{$path[1]}($data);
	}else{
		$ctrl->index($data);
	}
}else{
	$ctrl->index($data);
}

?>
