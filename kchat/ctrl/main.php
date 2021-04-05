<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class main extends ctrl{
	function index(){
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
		$array['conline'] = $this->model->process("conline");
		$array['plotly'] = $this->model->process("plotly");
		$this->load->set($array);
		$this->load->appendfile($assets);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('dash');
		$this->load->view('footer');
	}
}