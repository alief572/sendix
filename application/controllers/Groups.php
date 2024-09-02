<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends CI_Controller {
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
		
		$get_Data			= $this->master_model->getData('groups');
		
		
		$data = array(
			'title'			=> 'Indeks Of Access Group',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Group');
		$this->load->view('Groups/index',$data);
	}
	public function add(){
		if($this->input->post()){
			$Group_Name			= $this->input->post('name');
			$Keterangan			= $this->input->post('descr');
			$Cek_Data			= $this->master_model->getCount('groups',"LOWER(name)",strtolower($Group_Name));
			if($Cek_Data > 0){
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Group Already Exist. Please Different Group Name.......'
				);
			}else{
				$data_session		= $this->session->userdata;
				$det_Insert			= array(
					'name'				=> ucwords(strtolower($Group_Name)),
					'descr'				=> $Keterangan,
					'created'			=> date('Y-m-d H:i:s'),
					'created_by'		=> $data_session['ORI_User']['username']
					
				);
				if($this->master_model->simpan('groups',$det_Insert)){
					$Get_Data			= $this->master_model->getData('groups',"LOWER(name)",strtolower($Group_Name));
					$Arr_Kembali		= array(
						'status'		=> 1,
						'pesan'			=> 'Add Group Success. Thank you & have a nice day.......',
						'urut'			=> $Get_Data[0]->id
					);
					history('Add Data Group'.$Group_Name);
				}else{
					$Arr_Kembali		= array(
						'status'		=> 2,
						'pesan'			=> 'Add Group failed. Please try again later......'
					);
					
				}
			}
			echo json_encode($Arr_Kembali);
		}else{
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('groups'));
			}
			$data = array(
				'title'			=> 'ADD GROUP',
				'action'		=> 'add'
			);
			
			$this->load->view('Groups/add_group',$data);
		}
	}
	public function access_menu($id=''){
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			
			$Group_id				= $this->input->post('id');
			$Cek_Data				= $this->master_model->getCount('group_menus','group_id',$Group_id);
			
			$data_session			= $this->session->userdata;
			$Jam					= date('Y-m-d H:i:s');
			$Arr_Detail				= array();
			$Loop					= 0;
			$dataDetail				= $this->input->post('tree');
			foreach($dataDetail as $key=>$value){
				if(isset($value['read']) || isset($value['create']) || isset($value['update']) || isset($value['delete']) || isset($value['approve']) || isset($value['download'])){
					$Loop++;
					$a_read			= (isset($value['read']) && $value['read'])?$value['read']:0;
					$a_create		= (isset($value['create']) && $value['create'])?$value['create']:0;
					$a_update		= (isset($value['update']) && $value['update'])?$value['update']:0;
					$a_delete		= (isset($value['delete']) && $value['delete'])?$value['delete']:0;
					$a_download		= (isset($value['download']) && $value['download'])?$value['download']:0;
					$a_approve		= (isset($value['approve']) && $value['approve'])?$value['approve']:0;
					$det_Detail		= array(
						'menu_id'		=> $value['menu_id'],
						'group_id'		=> $Group_id,
						'read'			=> $a_read,
						'create'		=> $a_create,
						'update'		=> $a_update,
						'delete'		=> $a_delete,
						'approve'		=> $a_approve,
						'download'		=> $a_download,
						'created'		=> $Jam,
						'created_by'	=> $data_session['ORI_User']['username']
					);
					$Arr_Detail[$Loop]	= $det_Detail;
					
				}
			}
			$this->db->trans_begin();
			if($Cek_Data > 0){
				$Q_Del				= "DELETE FROM `group_menus` WHERE `group_id`='".$Group_id."'";
				$this->db->query($Q_Del);
			}
			$this->db->insert_batch('group_menus',$Arr_Detail);
			
			
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Manage Access Group Failed. Please Try Again.......'
				);
			}else{
				$this->db->trans_commit();
				$Arr_Kembali		= array(
					'status'		=> 1,
					'pesan'			=> 'Manage Access Group Success. Thank you & have a nice day.......'
				);				
				history('Manage Access Group '.$this->input->post('name'));
				
			}			
			echo json_encode($Arr_Kembali);
		}else{		
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['update'] !='1' || $Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('group'));
			}
			
			$get_Data			= $this->master_model->getDataArray('menus','flag_active','1');
			$detail				= group_access($id);
			
			$int_data			= $this->master_model->getData('groups','id',$id);
			
			$data = array(
				'title'			=> 'Manage Access Group',
				'action'		=> 'access_menu',
				'data_menu'		=> $get_Data,
				'row_akses'		=> $detail,
				'rows'			=> $int_data
			);
			
			$this->load->view('Groups/akses_menu',$data);
		}
	}
	
	public function edit_group($kode=''){
		if($this->input->post()){
			$Group_id			= $this->input->post('id');
			$Group_Name			= $this->input->post('name');
			$Keterangan			= $this->input->post('descr');
			$Query_Cek			= "SELECT * FROM groups WHERE LOWER(name)='".strtolower($Group_Name)."' AND id <> '".$Group_id."'";
			$Cek_Data			= $this->db->query($Query_Cek)->num_rows();
			if($Cek_Data > 0){
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Group Already Exist. Please Different Group Name.......'
				);
			}else{
				$data_session		= $this->session->userdata;
				$det_Insert			= array(
					'name'				=> ucwords(strtolower($Group_Name)),
					'descr'				=> $Keterangan,
					'modified'			=> date('Y-m-d H:i:s'),
					'modified_by'		=> $data_session['ORI_User']['username']
					
				);
				if($this->master_model->getUpdate('groups',$det_Insert,'id',$Group_id)){
					
					$Arr_Kembali		= array(
						'status'		=> 1,
						'pesan'			=> 'Update Group Success. Thank you & have a nice day.......'
					);
					history('Edit Data Group ID : '.$Group_id);
				}else{
					$Arr_Kembali		= array(
						'status'		=> 2,
						'pesan'			=> 'Update Group failed. Please try again later......'
					);
					
				}
			}
			echo json_encode($Arr_Kembali);
		}else{
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['update'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('groups'));
			}
			$int_data			= $this->master_model->getData('groups','id',$kode);
			$data = array(
				'title'			=> 'EDIT GROUP',
				'action'		=> 'edit',
				'rows_data'		=> $int_data
			);
			
			$this->load->view('Groups/edit_group',$data);
		}
	}
	
	
	
}