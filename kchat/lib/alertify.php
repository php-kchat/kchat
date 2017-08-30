<?php

class alertify{
	
	public static function alert($alert){
		if(isset($_SESSION['alert'])){
			$_SESSION['alert'] .= '<br/>' . $alert;
		}else{
			$_SESSION['alert'] = $alert;
		}
	}
	
	public static function get_alert(){
		if(isset($_SESSION['alert'])){
			echo "<script> alertify.alert('".$_SESSION['alert']."'); </script>";
			unset($_SESSION['alert']);
		}
	}
	
	public static function is_Ajax_alert(){
		if(isset($_SESSION['alert'])){
			return true;
		}
		return false;
	}
	
	public static function get_Ajax_alert(){
		$session = false;
		if(isset($_SESSION['alert'])){
			$session = $_SESSION['alert'];
			unset($_SESSION['alert']);
		}
		return $session;
	}
}
