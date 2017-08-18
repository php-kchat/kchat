<?php

/* $cache = glob('cache\*');

foreach($cache as $c){
	if(filemtime($c) < (time() - 5)){
		unlink($c);
	}
}

if(!isset($_SESSION['TIME'])){
	$_SESSION['TIME'] = 0;
}

if($_SESSION['TIME'] != time()){
	$_SESSION['TIME'] = time();
	file_put_contents('cache/'.time().'.php',time());
} */

class cache{
	public static function start($cache){
		if(!isset($_SESSION['KCHAT_CACHE'])){
			$_SESSION['KCHAT_CACHE'] = time();
			$c = array(
				'__FILE__' => '__FILE__',
				'cache'    => $cache['user']['id'],
				'GROUP' => getGroup($data),
				'TYPING' => false
			);
			if(!empty($cache['user']['id'])){
				file_put_contents('cache/'.$cache['user']['id'].'.php',"<?php return " . var_export_pretty($c) . "; ?>");
			}
		}
		if(isset($cache['user']['id'])){
			touch('cache/'.$cache['user']['id'].'.php');
		}
	}
}