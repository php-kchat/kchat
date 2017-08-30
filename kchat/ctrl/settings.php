<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class settings extends ctrl{
	
	function index(){
		$array = array(
			'title' => "Settings"
		);
		$stmt = $this->data['pdo']->prepare("SELECT `id`,`option`,`tab`,`value`,`key`,`type` FROM {$this->dbprefix}setting where 1");
		$stmt->execute(array());
		$settings = array();
		while ($row = $stmt->fetch())
		{
			$settings['settings'][$row['tab']][] = $row;
		}
		$this->load->set($settings);
		
		$assets = array(
			"jsh" => array(
				"assets/jscolor/jscolor.js"
			)
		);
		
		$this->load->appendfile($assets);
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('settings');
		$this->load->view('footer');
	}
	
}