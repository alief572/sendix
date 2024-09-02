<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		$this->load->model('unit_model');

		// Your own constructor code
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

		$data_Group			= $this->master_model->getArray('groups',array(),'id','name');

		$data = array(
			'title'			=> 'Indeks Of Unit',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Unit Satuan');
		$this->load->view('Unit/index',$data);
	}

	public function data_side_unit(){
		$this->unit_model->get_json_unit();
	}

	public function add(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$unit			= strtolower($data['unit']);
			$id		    = $data['id'];
      $tanda_edit		  = $data['tanda_edit'];

			//Pencarian data yang sudah ada
			$ValueProduct	= "SELECT * FROM unit WHERE unit='".$unit."'";
			$NumProduct		= $this->db->query($ValueProduct)->num_rows();

			if($NumProduct > 0){
				$Arr_Kembali		= array(
					'status'		=> 3,
					'pesan'			=> 'Unit sudah digunakan. Input unit lain ...'
				);
			}
			else{
				//insert
				if(empty($tanda_edit)){
					$Hist = 'Add ';

					$ArrInsert = array(
						'unit' => $unit,
						'created_by' => $data_session['ORI_User']['username'],
						'created_date' => $dateTime
					);

					$this->db->trans_start();
					$this->db->insert('unit', $ArrInsert);
					$this->db->trans_complete();
				}

				//edit
				if(!empty($tanda_edit)){
					$Hist = 'Edit ';

					$ArrInsert = array(
						'unit' => $unit,
						'updated_by' => $data_session['ORI_User']['username'],
						'updated_date' => $dateTime
					);

					$this->db->trans_start();
						$this->db->where('id', $id);
						$this->db->update('unit', $ArrInsert);
					$this->db->trans_complete();
				}

				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$Arr_Kembali	= array(
						'pesan'		=> $Hist.' data failed. Please try again later ...',
						'status'	=> 2
					);
				}
				else{
					$this->db->trans_commit();
					$Arr_Kembali	= array(
						'pesan'		=> $Hist.' data success. Thanks ...',
						'status'	=> 1
					);
					history($Hist.'Unit '.$id);
				}
			}

			echo json_encode($Arr_Kembali);
		}
		else{
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('users'));
			}

			$id = $this->uri->segment(3);


			$data = array();
			$tanda1 = 'Add';
			if(!empty($id)){
				$qRegion	= "SELECT * from unit WHERE id='".$id."' LIMIT 1";
        $data	= $this->db->query($qRegion)->result_array();
				$tanda1 = 'Edit';
			}

			$data = array(
				'title'			=> $tanda1.' Unit',
				'action'		=> strtolower($tanda1),
				'data'		=> $data
			);
			$this->load->view('Unit/add',$data);
		}
	}

	public function hapus(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'deleted' 		=> 'Y',
			'deleted_by' 	=> $data_session['ORI_User']['username'],
			'deleted_date' 	=> date('Y-m-d H:i:s')
			);

		$this->db->trans_start();
    $this->db->where('id', $id);
		$this->db->update('unit', $ArrPlant);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Delete data failed. Please try again later ...',
				'status'	=> 0
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Delete data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete unit id : '.$id);
		}
		echo json_encode($Arr_Data);
	}

}
