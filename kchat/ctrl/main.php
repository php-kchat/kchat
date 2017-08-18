<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class main extends ctrl{
	function index($data){
		$array = array(
			'title' => "Dashboard"
		);
		$assets = array(
			"jsh" => array(
				"assets/leaflet/leaflet.js",
				"assets/plotly/plotly-latest.min.js"
			),
			"css" => array(
				"assets/leaflet/leaflet.css",
			),
		);
		$this->load->appendfile($assets);
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('dash');
		$this->load->view('footer');
	}
}