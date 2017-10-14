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
		return array_values(array_values(array_filter($temp)));
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
			@file_put_contents($tempdir.$file,$data);
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
				$return = @file_get_contents($tempdir.$file);
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

