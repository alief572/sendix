<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cycletime extends CI_Controller {

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

		$data_Group		= $this->master_model->getArray('groups',array(),'id','name');
        $result         = $this->db->order_by('diameter','ASC')->get_where('cycletime', array('deleted'=>'N'))->result_array();

		$data = array(
			'title'			=> 'Indeks Of Cycletime',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'result'		=> $result,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data cycletime');
		$this->load->view('Cycletime/index',$data);
	}

	public function add(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$id		    	= (!empty($data['id']))?$data['id']:'';
			$tanda_edit		= $data['tanda_edit'];

			$diameter	= str_replace(',','',$data['diameter']);
			$pressure	= $data['pressure'];
			$liner		= $data['liner'];
			$mp			= str_replace(',','',$data['mp']);
			$ct			= str_replace(',','',$data['ct']);
			$mh			= $mp * $ct;

			//insert
			if(empty($tanda_edit)){
				$Hist = 'Add ';

				$ArrInsert = array(
					'diameter' => $diameter,
					'pressure' => $pressure,
					'liner' => $liner,
					'mp' => $mp,
					'ct' => $ct,
					'mh' => $mh,
					'created_by' => $data_session['ORI_User']['username'],
					'created_date' => $dateTime
				);

				$this->db->trans_start();
					$this->db->insert('cycletime', $ArrInsert);
				$this->db->trans_complete();
			}

			//edit
			if(!empty($tanda_edit)){
				$Hist = 'Edit ';

				$ArrInsert = array(
					'diameter' => $diameter,
					'pressure' => $pressure,
					'liner' => $liner,
					'mp' => $mp,
					'ct' => $ct,
					'mh' => $mh,
					'updated_by' => $data_session['ORI_User']['username'],
					'updated_date' => $dateTime
				);

				$this->db->trans_start();
					$this->db->where('id', $id);
					$this->db->update('cycletime', $ArrInsert);
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
				history($Hist.'cycletime '.$id);
			}
			echo json_encode($Arr_Kembali);
		}
		else{
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('cycletime'));
			}

			$id = $this->uri->segment(3);
			$data = array();
			$tanda1 = 'Add';
			if(!empty($id)){
				$data	= $this->db->get_where('cycletime', array('id'=>$id))->result_array();
				$tanda1 = 'Edit';
			}

			$data = array(
				'title'		=> $tanda1.' Cycletime',
				'action'	=> strtolower($tanda1),
				'data'		=> $data
			);
			$this->load->view('Cycletime/add',$data);
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
			$this->db->update('cycletime', $ArrPlant);
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
			history('Delete cycletime id : '.$id);
		}
		echo json_encode($Arr_Data);
	}

}
