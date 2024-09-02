<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model('master_model');			
		
	}

	public function index() {
		//echo $this->uri->segment(1);
		if ($this->input->post()) {			
			$UserName			= $this->input->post('username');
			$Password			= $this->input->post('password');
			$PassData			= cryptSHA1($Password);
			$WHERE				= array(
				'username'			=> $UserName,
				'password'			=> $PassData
			);
			$Cek_Data			= $this->master_model->getArray('users',$WHERE);
			//echo "<pre>";print_r($Cek_Data);exit;
			if($Cek_Data){
				$Group_ID		= $Cek_Data[0]['group_id'];
				$Aktif			= $Cek_Data[0]['st_aktif'];
				if($Aktif==1){
					$Arr_Daftar	= array();
					$Arr_Daftar['isORIlogin']	= 1;
					$Arr_Daftar['ORI_User']		= $Cek_Data[0];
					unset($Arr_Daftar['ORI_User']['password']);
					unset($Arr_Daftar['ORI_User']['ip']);
					unset($Arr_Daftar['ORI_User']['login_terakhir']);
					unset($Arr_Daftar['ORI_User']['created_on']);
					unset($Arr_Daftar['ORI_User']['deleted']);
					if($Group_ID){
						$WHR_Group		= array(
							'id'			=> $Group_ID
						);
						$Cek_Group		= $this->master_model->getArray('groups',$WHR_Group);
						if($Cek_Group){
							$Arr_Daftar['ORI_Group']	= $Cek_Group[0];
							unset($Cek_Group);
						}						
					}
					
					$this->session->set_userdata($Arr_Daftar);
					$Arr_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Login Process Success. Thank You & Have A Nice Day..'
					);
				}else{
					$Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Inactive Account. Please Contact Your Administrator....'
					);
				}
			}else{
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Incorrect Username Or Password. Please Try Again....'
				);
			}
			echo json_encode($Arr_Return);
		} else {
			$Data_Identitas			= $this->master_model->getData('identitas');
			$data = array(
				'title'			=> 'Login',
				'idt'			=> $Data_Identitas[0]
			);
			
			$this->load->view('login',$data);
		}
	}
	
}
