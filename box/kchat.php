<?php 

$global = array();

session_name('KChat_Client_SESSION');
session_start();

if(isset($_POST['key'])){
	$global['key'] = $_POST['key'];
}

ini_set('log_errors', true);
ini_set('error_log', 'logs/error.log');

require_once('core/TempCache.php');
require_once('core/sandesh.php');
require_once('core/config.php');
require_once('core/kchat.php');