<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class users extends ctrl{
	
	function index(){
		$array = array(
			'title' => "profile"
		);
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('profile');
		$this->load->view('footer');
	}
	
	function ulist(){
		$array = array(
			'title' => "Users"
		);
		$array['users'] = $this->model->process("userslist");
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('users');
		$this->load->view('footer');
	}
	
	function glist(){
		$array = array(
			'title' => "Guest list"
		);
		$array['users'] = $this->model->process("guestlist");
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('glist');
		$this->load->view('footer');
	}
	
	function groups(){
		$array = array(
			'title' => "Group Chat"
		);
		$array['users'] = $this->model->process("userslist");
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('groups');
		$this->load->view('footer');
	}
	
	function cuser(){
		$array = array(
			'title' => "Create User"
		);
		$assets = array(
			"jsh" => array(
				"assets/clipboard/clipboard.min.js"
			)
		);
		$this->load->appendfile($assets);
		$array['department'] = $this->model->process("getdepart");
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('cuser');
		$this->load->view('footer');
	}
	
	function profile(){
		$array = array(
			'title' => "profile"
		);
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('profile');
		$this->load->view('footer');
	}
	
}