<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		// check apakah user sudah memiliki sesi atau token login
		if($_SESSION['user_login'] == null){
			redirect('login');
		}
	}

	
	

	public function index()
	{
		$this->load->view('welcome_message');
	}
}
