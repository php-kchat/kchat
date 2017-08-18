<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class test extends ctrl{
	function index($data){
		$array = array(
			'title' => "mytitle"
		);
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('test');
		$this->load->view('footer');
	}
}