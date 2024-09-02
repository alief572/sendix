<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->load->model('master_model');
		$this->load->database();
        if(!$this->session->userdata('isORIlogin')){
			redirect('login');
		}
    }

	public function index(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$get_Data			= $this->master_model->getData('menus');
		$menu_akses			= $this->master_model->getMenu();
		
		$data = array(
			'title'			=> 'Indeks Of Menus',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Menu');
		$this->load->view('Menus/index',$data);
	}
	public function add(){		
		if($this->input->post()){
			$Arr_Kembali			= array();			
			$data					= $this->input->post();
			$data_session			= $this->session->userdata;
			$data['created_by']		= $data_session['ORI_User']['username']; 
			$data['created_date']	= date('Y-m-d H:i:s');
			$data['flag_active']	= 1;
			if($this->master_model->simpan('menus',$data)){
				$Arr_Kembali		= array(
					'status'		=> 1,
					'pesan'			=> 'Add Menu Success. Thank you & have a nice day.......'
				);
				history('Add Data Menu'.$data['name']);
			}else{
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Add Menu failed. Please try again later......'
				);
				
			}
			echo json_encode($Arr_Kembali);
		}else{		
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('menu'));
			}
			$arr_Where			= array('flag_active'=>'1');
			$get_Data			= $this->master_model->getMenu($arr_Where);
			$data = array(
				'title'			=> 'Add Menus',
				'action'		=> 'add',
				'data_menu'		=> $get_Data
			);
			$this->load->view('Menus/add',$data);
		}
	}
	public function edit($id=''){
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$data					= $this->input->post();
			$Arr_Kembali			= array();
			$data['flag_active']	= $this->input->post('flag_active');
			unset($data['id']);
			$data_session			= $this->session->userdata;			
			$data['modified_by']	= $data_session['ORI_User']['username'];  
			$data['modified_date']	= date('Y-m-d H:i:s');
			if($this->master_model->getUpdate('menus',$data,'id',$this->input->post('id'))){
				$Arr_Kembali		= array(
					'status'		=> 1,
					'pesan'			=> 'Edit Menu Success. Thank you & have a nice day.......'
				);
				history('Edit Data Menu'.$data['name']);
				
			}else{
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Add Menu failed. Please try again later......'
				);
			}
			echo json_encode($Arr_Kembali);
		}else{		
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['update'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('menus'));
			}
			$arr_Where			= array('flag_active'=>'1');
			$get_Data			= $this->master_model->getMenu($arr_Where);
			
			$detail				= $this->master_model->getData('menus','id',$id); 
			$data = array(
				'title'			=> 'Edit Menus',
				'action'		=> 'edit',
				'data_menu'		=> $get_Data,
				'row'			=> $detail
			);
			
			$this->load->view('Menus/edit',$data);
		}
	}

	function delete($id_lokasi){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['delete'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('menus'));
		}
		
		$this->db->where('id', $id_lokasi);
		$this->db->delete("menus");
		if($this->db->affected_rows()>0){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-success\" id=\"flash-message\">Data has been successfully deleted...........!!</div>");
			history('Delete Data Menu id'.$id_lokasi);
			redirect(site_url('menus'));
		}
	}
}