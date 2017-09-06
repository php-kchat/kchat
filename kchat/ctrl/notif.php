<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class notif extends ctrl{
	
	function index(){
		
		$array = array(
			'title' => "Notification",
			'Notification' => $this->model->process('notification')
		);
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('notification');
		$this->load->view('footer');
	}
	
	function n(){
		$this->index($this->data);
	}
	
}