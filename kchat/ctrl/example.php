<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class example extends ctrl{
	
	function index(){
		$array = array(
			'title' => "Example"
		);
		$assets = array(
			"jsh" => array(
				$this->data['config']['url']."/box/assets/js/kchat.js"
			)
		);
		$this->load->appendfile($assets);
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('example');
	}
	
}