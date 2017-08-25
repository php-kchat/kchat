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
			__("<script> alertify.alert('".$_SESSION['alert']."'); </script>");
			unset($_SESSION['alert']);
		}
	}
}