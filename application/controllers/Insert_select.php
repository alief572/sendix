<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insert_select extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');

		if(!$this->session->userdata('isORIlogin')){
			redirect('login');
		}
    }

    function insert_select_mp(){
			$this->db->trans_start();
				$this->db->truncate('tb_view_man_power');

				$sqlUpdate = "
	                INSERT INTO tb_view_man_power ( code_group,
			                category,
			                spec,
			                jawa,
			                sumatra,
			                kalimantan,
			                sulawesi,
			                indonesia_timur,
			                updated_by,
			        				updated_date)
	                SELECT
	          					a.code_group,
	          					a.category,
	          					a.spec,
	          					a.jawa,
	          					a.sumatra,
	          					a.kalimantan,
	          					a.sulawesi,
	          					a.indonesia_timur,
	          					'".$this->session->userdata['ORI_User']['username']."',
	          					'".date('Y-m-d H:i:s')."'
	          				FROM
	                      view_man_power a";

				$this->db->query($sqlUpdate);

			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Data	= array(
					'pesan'		=>'Update Failed. Please try again later ...',
					'status'	=> 0
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Data	= array(
					'pesan'		=>'Update Success. Thanks ...',
					'status'	=> 1
				);
				history('Success insert select update master man power');
			}

	    // print_r($Arr_Data);
	    // exit;
			echo json_encode($Arr_Data);
    }

		function insert_select_vt(){
			$this->db->trans_start();
				$this->db->truncate('tb_view_vehicle_tool');

				$sqlUpdate = "
	                INSERT INTO tb_view_vehicle_tool ( code_group,
			                category,
			                spec,
			                jawa,
			                sumatra,
			                kalimantan,
			                sulawesi,
			                indonesia_timur,
			                updated_by,
			        				updated_date)
	                SELECT
	          					a.code_group,
	          					a.category,
	          					a.spec,
	          					a.jawa,
	          					a.sumatra,
	          					a.kalimantan,
	          					a.sulawesi,
	          					a.indonesia_timur,
	          					'".$this->session->userdata['ORI_User']['username']."',
	          					'".date('Y-m-d H:i:s')."'
	          				FROM
	                      view_vehicle_tool a";

				$this->db->query($sqlUpdate);

			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Data	= array(
					'pesan'		=>'Update Failed. Please try again later ...',
					'status'	=> 0
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Data	= array(
					'pesan'		=>'Update Success. Thanks ...',
					'status'	=> 1
				);
				history('Success insert select update master vehicle tools');
			}

	    // print_r($Arr_Data);
	    // exit;
			echo json_encode($Arr_Data);
    }

		function insert_select_cn(){
			$this->db->trans_start();
				$this->db->truncate('tb_view_con_nonmat');

				$sqlUpdate = "
	                INSERT INTO tb_view_con_nonmat ( code_group,
			                category,
			                spec,
			                jawa,
			                sumatra,
			                kalimantan,
			                sulawesi,
			                indonesia_timur,
			                updated_by,
			        				updated_date)
	                SELECT
	          					a.code_group,
	          					a.category,
	          					a.spec,
	          					a.jawa,
	          					a.sumatra,
	          					a.kalimantan,
	          					a.sulawesi,
	          					a.indonesia_timur,
	          					'".$this->session->userdata['ORI_User']['username']."',
	          					'".date('Y-m-d H:i:s')."'
	          				FROM
	                      view_con_nonmat a";

				$this->db->query($sqlUpdate);

			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Data	= array(
					'pesan'		=>'Update Failed. Please try again later ...',
					'status'	=> 0
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Data	= array(
					'pesan'		=>'Update Success. Thanks ...',
					'status'	=> 1
				);
				history('Success insert select update master consumable apd');
			}

	    // print_r($Arr_Data);
	    // exit;
			echo json_encode($Arr_Data);
    }

		function insert_select_ak(){
			$this->db->trans_start();
				$this->db->truncate('tb_view_akomodasi');

				$sqlUpdate = "
	                INSERT INTO tb_view_akomodasi ( code_group,
			                category,
			                spec,
			                jawa,
			                sumatra,
			                kalimantan,
			                sulawesi,
			                indonesia_timur,
			                updated_by,
			        				updated_date)
	                SELECT
	          					a.code_group,
	          					a.category,
	          					a.spec,
	          					a.jawa,
	          					a.sumatra,
	          					a.kalimantan,
	          					a.sulawesi,
	          					a.indonesia_timur,
	          					'".$this->session->userdata['ORI_User']['username']."',
	          					'".date('Y-m-d H:i:s')."'
	          				FROM
	                      view_akomodasi a";

				$this->db->query($sqlUpdate);

			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Data	= array(
					'pesan'		=>'Update Failed. Please try again later ...',
					'status'	=> 0
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Data	= array(
					'pesan'		=>'Update Success. Thanks ...',
					'status'	=> 1
				);
				history('Success insert select update master akomodasi');
			}

	    // print_r($Arr_Data);
	    // exit;
			echo json_encode($Arr_Data);
    }



}
