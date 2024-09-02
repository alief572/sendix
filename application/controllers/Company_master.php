<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_master extends CI_Controller {
	public function __construct(){
        parent::__construct();		
		$this->load->model('master_model');
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
		
		$get_Data			= $this->master_model->getData('identitas');	
		$data = array(
			'title'			=> 'Indeks Of Master Company',
			'action'		=> 'index',
			'rows_data'		=> $get_Data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Master Company');
		$this->load->view('Master_Company/index',$data);
	}
	
	
	public function edit($id=''){
		if($this->input->post()){
			$Companyid		= $this->input->post('ididentitas');
			$Company_Name	= $this->input->post('company_name');
			$Address		= $this->input->post('company_address');
			$Provinsi		= $this->input->post('company_province');
			$Phone			= $this->input->post('company_phone');
			$Fax			= $this->input->post('company_fax');
			$Website		= $this->input->post('company_web');
			
			$det_Session	= $this->session->userdata;
			$date 			= date('Y-m-d H:i:s');
			$data_Update	= array(
				'nm_perusahaan'		=> strtoupper($Company_Name),
				'alamat'			=> $Address,
				'kota'				=> $Provinsi,
				'no_telp'			=> $Phone,
				'fax'				=> $Fax,
				'website'			=> $Website
			);
			$this->db->trans_start();
			$this->db->update('identitas',$data_Update, array('ididentitas'	=> $Companyid));		
			$this->db->trans_complete();	
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Update Process Failed. Please Try Again...'
			   );	
			}else{
				$this->db->trans_commit();
				$Arr_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Update Process Success. Thank You & Have A Nice Day......'
			   );
			}
			echo json_encode($Arr_Return);
		}else{
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['update'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('company_master'));
			}
			
			$det_Province		= $this->master_model->getArray('provinsi',array(),'nama','nama');
			$det_Detail			= $this->master_model->getData('identitas','ididentitas',$id);
			$data = array(
				'title'			=> 'Edit Master Company',
				'action'		=> 'edit',
				'rows_data'		=> $det_Detail,
				'rows_province'	=> $det_Province
			);				
			$this->load->view('Master_Company/edit',$data);
		}
	}
	
	
	public function view($id=''){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('company'));
		}
		
		$det_Province		= $this->master_model->getData('provinsi');
		$det_Detail			= $this->master_model->getData('identitas','ididentitas',$id);
		$data = array(
			'title'			=> 'Detail Master Company',
			'action'		=> 'view',
			'rows_data'		=> $det_Detail,
			'rows_province'	=> $det_Province
		);			
		$this->load->view('Master_Company/view',$data);
	}
	
	
}