<?php 

$global = array();

date_default_timezone_set("Asia/Kolkata");

session_name('KChat_Client_SESSION');
session_start();

if(isset($_POST['key'])){
	$global['key'] = $_POST['key'];
}

ini_set('log_errors', true);
ini_set('error_log', 'logs/error.log');

require_once('core/sandesh.php');
require_once('core/kchat.php');