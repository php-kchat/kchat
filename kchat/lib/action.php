<?php

class action{
	
	var $data = array();
	var $dbprefix = array();
	
	function __construct($data){
		$this->data = $data;
		$this->dbprefix = $data['db']['db_prefix'];
	}
}