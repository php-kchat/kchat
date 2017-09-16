<?php

$data = array();
$data['installed'] = false;

// Used in URL
$path = array();
if(isset($_SERVER["QUERY_STRING"])){
	$path = explode("/",trim($_SERVER["QUERY_STRING"],"/"));
	$path = array_filter($path);
}

if(isset($path[1])){
	$global['key'] = $path[1];
}

if(!($data = @include "config/config.php")){
	$config = new config();
	$data = $conf = $config->config();
	$conf = $config->getconfig($conf);
	file_put_contents('config/config.php',$conf);
}

if(isset($data['timezone'])){
	date_default_timezone_set($data['timezone']);
}

if(!isset($data['installed'])){
	$config = new config();
	$t = new TempCache('~C~kchat~'.$global['key'].'~@~');
	$conf = $config->config();
	$t->setcache(base64_encode(serialize($conf)));
}

if(!isset($data['key'])){
	$t = new TempCache('~S~kchat~'.$global['key'].'~@~');
	$cache = $t->getcache();
	$sdata = $cache = unserialize(base64_decode($cache));
	if(is_array($sdata)){
		$data = array_merge($data,$sdata);
		$data['installed'] = true;
		$config = new config();
		$conf = $config->getconfig($data);
		file_put_contents('config/config.php',$conf);
	}
}

function _p($e){echo $e;}

function getip(){
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

function getBrowser(){
	if(!empty($_SERVER['HTTP_USER_AGENT'])){
		return $_SERVER['HTTP_USER_AGENT'];
	}else{
		return false;
	}
}

function getUniqe(){
	return md5(getip().getBrowser());
}

function k_random($n){
	$char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$result = '';
	while($n > 0){
		$result .= $char[rand(0,61)];
		$n--;
	}
	return $result;
}

function base($n,$bf,$bt){
	$char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$result = '';
	while($n != 0){
		$result .= $char[($n%$bt)];
		$n = floor($n/$bt);
	}
	return strrev($result);
}

function ago($datetime, $full = false)
{
	$now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function kchat_rand(){
	return base(time(),10,62).k_random(10);
}

function psql($string,$data) {
	$indexed=$data==array_values($data);
	foreach($data as $k=>$v) {
		if(is_string($v)) $v="'$v'";
		if($indexed) $string=preg_replace('/\?/',$v,$string,1);
		else $string=str_replace(":$k",$v,$string);
	}
	return $string;
}
	
if(isset($path[0])){
	
	$kc = new KChat($data);
	
	switch($path[0]){
		case 'js':
			header("Content-Type: text/js");
			$kc->js($data);
		break;
		case 'css':
			header("Content-Type: text/css");
			$kc->css($data);
		break;
		case 'start':
			$kc->start($data);
		break;
		case 'msg':
			$kc->msg($data);
		break;
		case 'getmsg':
			_p(json_encode($kc->getmsg($_POST,$data),128));
		break;
	}
}



function reverse($a){
	$b = array();
	for($i = count($a) - 1;$i >= 0 ; $i--){
		$b[] = $a[$i];
	}
	return $b;
}



function msgencode($txt){
	return trim(json_encode($txt),'"');
}

function msgdecode($txt){
	return json_decode('"'.$txt.'"', 1);
}

function isReq($id){
	if(isset($_POST[$id])){
		if($_POST[$id] == 'true'){
			return true;
		}
	}
	return false;
}