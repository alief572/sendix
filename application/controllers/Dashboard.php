<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		if(!$this->session->userdata('isORIlogin')){
			redirect('login');
		}		
	}	
	public function index() {
		//$this->load->view('include/header', array('title'=>'Dashboard'));
		// history('View Dashboard');
		$data			= array(
			'action'	=>'index',
			'title'		=>'Dashboard'
		);
		
		$this->load->view('dashboard',$data);
		
	}
	public function logout() {
		$this->session->sess_destroy();
		history('Logout');
		$this->session->set_userdata(array());
		redirect('login');		
	}
}
