<?php

class config{
	
	function var_export_pretty($var, $indent="")
	{
		switch (gettype($var)) {
			case "string":
				return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
			case "array":
				$indexed = array_keys($var) === range(0, count($var) - 1);
				$r = array();
				foreach ($var as $key => $value) {
					$r[] = "$indent    "
						. ($indexed ? "" : $this->var_export_pretty($key) . " => ")
						. $this->var_export_pretty($value, "$indent    ");
				}

				return "array(\n" . implode(",\n", $r) . "\n" . $indent . ")";
			case "boolean":
				return $var ? "true" : "false";
			default:
				return var_export($var, true);
		}
	}

	function getconfig($a){
		return "<?php\n\n return " . $this->var_export_pretty($a) . "; \n\n?>";
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
			"kchat_path" => getcwd(),
			"kchat_url" => $Url,
			"kchat_ds" => DIRECTORY_SEPARATOR
		);
	}
}
