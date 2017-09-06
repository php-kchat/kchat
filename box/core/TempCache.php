<?php

class TempCache{
	
	private $cache = null;
	
	function __construct($cache){
		$this->cache = $cache;
	}
	
	function getTempDir(){
		$temp = array();
		$temp[] = getenv('temp');
		$temp[] = sys_get_temp_dir();
		$temp[] = ini_get('upload_tmp_dir');
		foreach($temp as $key => $value){
			$temp[$key] = rtrim(rtrim($value,'\\'),'/');
		}
		return array_filter($temp);
	}

	function getTemp($temp){
		$i = 0;
		while(isset($temp[$i])){
			if(is_writable($temp[$i])){
				return $temp[$i];
			}
			$i++;
		}	
	}

	function setcache($data,$i = 0){
		$tempfile = $this->cache;
		$file = str_replace("@",$i,$tempfile);
		$temp = $this->getTempDir();
		$tempdir = $this->getTemp($temp).'/';
		if(!file_exists($tempdir.$file)){
			file_put_contents($tempdir.$file,$data);
		}else{
			if(!is_writable($tempdir.$file)){
				$i++;
				$this->setcache($data,$i);
			}
		}
	}

	function getcache($i = 0){
		$tempfile = $this->cache;
		$file = str_replace("@",$i,$tempfile);
		$temp = $this->getTempDir();
		$tempdir = $this->getTemp($temp).'/';
		if(file_exists($tempdir.$file)){
			if(!is_writable($tempdir.$file)){
				$i++;
				$return = $this->getcache($i);
			}else{
				$return = file_get_contents($tempdir.$file);
			}
			return $return;
		}
		return false;
	}

	function rmcache($i = 0){
		$tempfile = $this->cache;
		$file = str_replace("@",$i,$tempfile);
		$temp = $this->getTempDir();
		$tempdir = $this->getTemp($temp).'/';
		if(file_exists($tempdir.$file)){
			if(!is_writable($tempdir.$file)){
				$i++;
				$this->rmcache($i);
			}else{
				unlink($tempdir.$file);
				return true;
			}
		}
	}

}

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
