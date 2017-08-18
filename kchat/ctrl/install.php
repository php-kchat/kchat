<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class install extends ctrl{
	function index($data){
		$array = array(
			'title' => "install"
		);
		$this->load->set($array);
		$this->load->view('install');
	}
}