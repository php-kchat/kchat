<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class users extends ctrl{
	
	function index($data){
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
	
	function ulist($data){
		$array = array(
			'title' => "Users"
		);
		$array['users'] = $this->ajax->process("userslist");
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('users');
		$this->load->view('footer');
	}
	
	function glist($data){
		$array = array(
			'title' => "Guest list"
		);
		$array['users'] = $this->ajax->process("guestlist");
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('glist');
		$this->load->view('footer');
	}
	
	function groups($data){
		$array = array(
			'title' => "Group Chat"
		);
		$array['users'] = $this->ajax->process("userslist");
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('groups');
		$this->load->view('footer');
	}
	
	function cuser($data){
		$array = array(
			'title' => "Create User"
		);
		$array['department'] = $this->ajax->process("getdepart");
		$this->load->set($array);
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('sitebar');
		$this->load->view('cuser');
		$this->load->view('footer');
	}
	
	function profile($data){
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