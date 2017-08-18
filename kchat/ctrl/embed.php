<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class embed extends ctrl{
	function index($data){
		$array = array(
			'title' => "Embed",
		);
		$assets = array(
			"jsh" => array(
				"assets/clipboard/clipboard.min.js"
			)
		);
		$this->load->appendfile($assets);
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('embed');
		$this->load->view('footer');
	}
	function setting($data){
		$array = array(
			'title' => "Embed Settnig",
		);
		$assets = array(
			"jsh" => array(
				"assets/clipboard/clipboard.min.js"
			)
		);
		$this->load->appendfile($assets);
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('embedset');
		$this->load->view('footer');
	}
}