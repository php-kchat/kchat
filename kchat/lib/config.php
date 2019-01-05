<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

function var_export_pretty($var, $indent=""){
    switch (gettype($var)) {
        case "string":
            return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
        case "array":
            $indexed = array_keys($var) === range(0, count($var) - 1);
            $r = array();
            foreach ($var as $key => $value) {
                $r[] = "$indent    "
                    . ($indexed ? "" : var_export_pretty($key) . " => ")
                    . var_export_pretty($value, "$indent    ");
            }

            return "array(\n" . implode(",\n", $r) . "\n" . $indent . ")";
        case "boolean":
            return $var ? "true" : "false";
        default:
            return var_export($var, true);
    }
}

function pcode($a){
	return "<?php\n\n return " . var_export_pretty($a) . "; \n\n?>";
}

function config(){
	if(isset($_SERVER['HTTPS'])){
		$protocol = 'https';
	}else{
		$protocol = 'http';
	}
	$host     = $_SERVER['HTTP_HOST'];
	$script   = $_SERVER['SCRIPT_NAME'];
	$Url = $protocol . '://' . $host . $script;
	$Url = substr($Url,0,(strlen($Url) - 10));
	
	// write config array here
	return array(
		"path" => getcwd(),
		"url" => $Url,
		"ds" => DIRECTORY_SEPARATOR,
		"secret" => _rand(64),
		"session" => "KChat_"._rand(8),
		"salt" => _rand(32),
		"key" => _rand(12),
		"Admin" => "KkEtq2SNzvl02OR", //_rand(8),
		"version" => "1.0.6",
		"timezone" => date_default_timezone_get(),
	);
}

function _config($key){
	$data['installed'] = false;
	$data = @include "config/kchat_conf.php";

	if($data['installed']){
		return $data;
	}else{
		$t = new TempCache('~C~kchat~'.$key.'~@~');
		if($cache = $t->getcache()){
			$cache = unserialize(base64_decode($cache));
			$cache['installed'] = true;
			fcreate("config/kchat_conf.php",pcode($cache));
			return $cache;
		}
	}
}

if(!file_exists("config/config.php")){
	fcreate("config/config.php",pcode(config()));
}

