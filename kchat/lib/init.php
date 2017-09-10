<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

session_name('KChat_SESSION');
session_start();

ini_set('log_errors', true);
ini_set('error_log', __DIR__ .'/../../logs/kchat.log.php');
ini_set('html_errors', false);
ini_set('display_errors', false);

$e_all = defined('E_DEPRECATED') ? E_ALL & ~E_DEPRECATED : E_ALL;
error_reporting($e_all);

header("x-server-os:KChat");
header("X-Powered-By:KChat");
header("X-Frame-Options:SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header('Content-Type: text/html; charset=utf-8');

$data['_start'] = microtime(true);
$data['reqps'] = explode('.',$data['_start']);
$data['reqps'] = $data['reqps'][0];

require_once "kchat/lib/global.php";
require_once "kchat/lib/action.php";
require_once "kchat/lib/TempCache.php";
require_once "kchat/lib/alertify.php";
require_once "kchat/lib/permission.php";
require_once "kchat/lib/session.php";
require_once "kchat/lib/config.php";
require_once "kchat/lib/mvc.php";
require_once "kchat/lib/route.php";

