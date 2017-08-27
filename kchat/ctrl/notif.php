<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class notif extends ctrl{
	
	function index($data){
		
		$array = array(
			'title' => "Notification",
			'Notification' => $this->ajax->process('notification')
		);
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('Notification');
		$this->load->view('footer');
	}
	
}