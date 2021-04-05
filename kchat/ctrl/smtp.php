<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class smtp extends ctrl{
	
	function index(){
		$array = array(
			'title' => "SMTP Configuration"
		);
		$array['smtp'] = array('host' => '','port' => '','secure' => '','auth' => '','email' => '','pass' => '');
		if(file_exists('config/smtp.php')){
			$array['smtp'] = include 'config/smtp.php';
		}
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('smtp');
		$this->load->view('footer');
	}
	
}