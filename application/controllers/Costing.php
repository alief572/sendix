<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Costing extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		$this->load->model('costing_model');

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
			'title'			=> 'Indeks Of COGS',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data COGS');
		$this->load->view('Costing/index',$data);
	}

	public function data_side_costing(){
		$this->costing_model->get_json_costing();
	}

	public function edit(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$project_code 	= $data['project_code'];
			$region 		= $data['region_code'];

			if(!empty($data['ListMeal'])){
				$ListMeal		= $data['ListMeal'];
			}
			if(!empty($data['ListDetailHouse'])){
				$ListDetailHouse= $data['ListDetailHouse'];
			}
			if(!empty($data['ListDetailTrans'])){
				$ListDetailTrans= $data['ListDetailTrans'];
			}
			if(!empty($data['ListDetailEtc'])){
				$ListDetailEtc	= $data['ListDetailEtc'];
			}
			if(!empty($data['ListDetailHouse2'])){
				$ListDetailHouse2= $data['ListDetailHouse2'];
			}
			if(!empty($data['ListDetailTrans2'])){
				$ListDetailTrans2= $data['ListDetailTrans2'];
			}
			if(!empty($data['ListDetailEtc2'])){
				$ListDetailEtc2	= $data['ListDetailEtc2'];
			}
			if(!empty($data['ListDetailTest2'])){
				$ListDetailTest2	= $data['ListDetailTest2'];
			}
			if(!empty($data['ListDetailSurvey2'])){
				$ListDetailSurvey2	= $data['ListDetailSurvey2'];
			}
			if(!empty($data['ListDetailMDE2'])){
				$ListDetailMDE2	= $data['ListDetailMDE2'];
			}
			if(!empty($data['ListDetailCovid2'])){
				$ListDetailCovid2	= $data['ListDetailCovid2'];
			}
			if(!empty($data['ListHE'])){
				$ListHE			= $data['ListHE'];
			}
			if(!empty($data['ListVT'])){
				$ListVT			= $data['ListVT'];
			}
			if(!empty($data['ListCN'])){
				$ListCN			= $data['ListCN'];
			}
			if(!empty($data['ListMP'])){
				$ListMP			= $data['ListMP'];
			}

			// print_r($ListCN);

			//project_detail_akomodasi
			$TOTAL_COST = 0;
			if(!empty($data['ListMeal'])){
				$ArrMeal = array();
				foreach($ListMeal AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrMeal[$val]['id'] 			= $valx['id'];
					$ArrMeal[$val]['area'] 			= strtolower($valx['area']);
					$ArrMeal[$val]['rate'] 			= str_replace(',', '', $valx['rate']);
					$ArrMeal[$val]['total_unit'] 	= str_replace(',', '', $valx['total_unit']);
					$ArrMeal[$val]['total_rate'] 	= str_replace(',', '', $valx['total_rate']);
					$ArrMeal[$val]['jml_orang'] 	= $valx['jml_orang'];
					$ArrMeal[$val]['jml_hari'] 		= $valx['jml_hari'];
					$ArrMeal[$val]['note'] 			= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailHouse'])){
				$ArrHouse = array();
				foreach($ListDetailHouse AS $val => $valx){
					$restDataHu = $this->db->query("SELECT category, spec FROM akomodasi_new WHERE code_group='".$valx['code_group']."' LIMIT 1 ")->result();
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrHouse[$val]['project_code'] = $project_code;
					$ArrHouse[$val]['category_ak'] 	= 'house';
					$ArrHouse[$val]['code_group'] 	= $valx['code_group'];
					$ArrHouse[$val]['category'] 	= (!empty($restDataHu))?$restDataHu[0]->category:'not found';
					$ArrHouse[$val]['spec'] 		= (!empty($restDataHu))?$restDataHu[0]->spec:'not found';
					$ArrHouse[$val]['qty'] 			= $valx['qty'];
					$ArrHouse[$val]['rate'] 		= str_replace(',', '', $valx['rate']);
					$ArrHouse[$val]['total_rate'] 	= str_replace(',', '', $valx['total_rate']);
					$ArrHouse[$val]['jml_orang'] 	= $valx['value'];
					$ArrHouse[$val]['area'] 		= $valx['satuan'];
					$ArrHouse[$val]['note'] 		= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailHouse2'])){
				$ArrHouse2 = array();
				foreach($ListDetailHouse2 AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrHouse2[$val]['id'] 			= $valx['id'];
					$ArrHouse2[$val]['qty'] 			= $valx['qty'];
					$ArrHouse2[$val]['rate'] 		= str_replace(',', '', $valx['rate']);
					$ArrHouse2[$val]['total_rate'] 	= str_replace(',', '', $valx['total_rate']);
					$ArrHouse2[$val]['jml_orang'] 	= $valx['value'];
					$ArrHouse2[$val]['area'] 		= $valx['satuan'];
					$ArrHouse2[$val]['note'] 		= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailTrans'])){
				$ArrTrans = array();
				foreach($ListDetailTrans AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrTrans[$val]['project_code'] = $project_code;
					$ArrTrans[$val]['category_ak'] 	= 'trans';
					$ArrTrans[$val]['category'] 	= $valx['item_cost'];
					$ArrTrans[$val]['spec'] 		= $valx['kendaraan'];
					$ArrTrans[$val]['asal'] 		= strtolower($valx['asal']);
					$ArrTrans[$val]['tujuan'] 		= strtolower($valx['tujuan']);
					$ArrTrans[$val]['pulang_pergi'] = strtolower($valx['pulang_pergi']);
					$ArrTrans[$val]['jml_orang'] 	= $valx['value'];
					$ArrTrans[$val]['rate'] 		= str_replace(',', '', $valx['rate']);
					$ArrTrans[$val]['total_rate'] 	= str_replace(',', '', $valx['total_rate']);
					$ArrTrans[$val]['note'] 		= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailTrans2'])){
				$ArrTrans2 = array();
				foreach($ListDetailTrans2 AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrTrans2[$val]['id'] 			= $valx['id'];
					$ArrTrans2[$val]['spec'] 		= $valx['kendaraan'];
					$ArrTrans2[$val]['asal'] 		= strtolower($valx['asal']);
					$ArrTrans2[$val]['tujuan'] 		= strtolower($valx['tujuan']);
					$ArrTrans2[$val]['pulang_pergi']= strtolower($valx['pulang_pergi']);
					$ArrTrans2[$val]['jml_orang'] 	= $valx['value'];
					$ArrTrans2[$val]['rate'] 		= str_replace(',', '', $valx['rate']);
					$ArrTrans2[$val]['total_rate'] 	= str_replace(',', '', $valx['total_rate']);
					$ArrTrans2[$val]['note'] 		= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailEtc'])){
				$ArrEtc = array();
				foreach($ListDetailEtc AS $val => $valx){
					$restDataEtc = $this->db->query("SELECT category, spec FROM akomodasi_new WHERE code_group='".$valx['code_group']."' LIMIT 1 ")->result();
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrEtc[$val]['project_code'] 	= $project_code;
					$ArrEtc[$val]['category_ak'] 	= 'etc';
					$ArrEtc[$val]['code_group'] 	= $valx['code_group'];
					$ArrEtc[$val]['category'] 		= (!empty($restDataEtc))?$restDataEtc[0]->category:'not found';
					$ArrEtc[$val]['spec'] 			= (!empty($restDataEtc))?$restDataEtc[0]->spec:'not found';
					$ArrEtc[$val]['rate'] 			= str_replace(',', '', $valx['rate']);
					$ArrEtc[$val]['total_rate'] 	= str_replace(',', '', $valx['total_rate']);
					$ArrEtc[$val]['qty'] 			= $valx['qty'];
					$ArrEtc[$val]['note'] 			= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailEtc2'])){
				$ArrEtc2 = array();
				foreach($ListDetailEtc2 AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrEtc2[$val]['id'] 			= $valx['id'];
					$ArrEtc2[$val]['rate'] 			= str_replace(',', '', $valx['rate']);
					$ArrEtc2[$val]['total_rate'] 	= str_replace(',', '', $valx['total_rate']);
					$ArrEtc2[$val]['qty'] 			= $valx['qty'];
					$ArrEtc2[$val]['note'] 			= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailTest2'])){
				$ArrTest2 = array();
				foreach($ListDetailTest2 AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrTest2[$val]['id'] 			= $valx['id'];
					$ArrTest2[$val]['rate'] 		= str_replace(',', '', $valx['rate']);
					$ArrTest2[$val]['total_rate'] 	= str_replace(',', '', $valx['total_rate']);
					$ArrTest2[$val]['qty'] 			= $valx['qty'];
					$ArrTest2[$val]['note'] 		= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailSurvey2'])){
				$Arrsurvey2 = array();
				foreach($ListDetailSurvey2 AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$Arrsurvey2[$val]['id'] 			= $valx['id'];
					$Arrsurvey2[$val]['rate'] 			= str_replace(',', '', $valx['rate']);
					$Arrsurvey2[$val]['total_rate'] 	= str_replace(',', '', $valx['total_rate']);
					$Arrsurvey2[$val]['jml_orang'] 		= str_replace(',', '', $valx['jml_orang']);
					$Arrsurvey2[$val]['qty'] 			= str_replace(',', '', $valx['qty']);
					$Arrsurvey2[$val]['jml_hari'] 		= str_replace(',', '', $valx['jml_hari']);
					$Arrsurvey2[$val]['note'] 			= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailCovid2'])){
				$ArrCovid2 = array();
				foreach($ListDetailCovid2 AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);
					$durasi = str_replace(',','',$valx['jml_hari']);
					if($durasi < 1){
						$durasi = 1;
					}
					$ArrCovid2[$val]['id'] 			= $valx['id'];
					$ArrCovid2[$val]['rate'] 			= str_replace(',', '', $valx['rate']);
					$ArrCovid2[$val]['total_rate'] 	= str_replace(',', '', $valx['total_rate']);
					$ArrCovid2[$val]['jml_orang'] 		= str_replace(',', '', $valx['jml_orang']);
					$ArrCovid2[$val]['jml_hari'] 		= $durasi;
					$ArrCovid2[$val]['note'] 			= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailMDE2'])){
				$ArrMDE2 = array();
				foreach($ListDetailMDE2 AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrMDE2[$val]['id'] 			= $valx['id'];
					$ArrMDE2[$val]['rate'] 			= str_replace(',', '', $valx['rate']);
					$ArrMDE2[$val]['total_rate'] 	= str_replace(',', '', $valx['total_rate']);
					$ArrMDE2[$val]['jml_orang'] 	= str_replace(',', '', $valx['jml_orang']);
					// $ArrMDE2[$val]['pulang_pergi'] 	= str_replace(',', '', $valx['jml_hari']);
					$ArrMDE2[$val]['note'] 			= strtolower($valx['note']);
				}
			}


			if(!empty($data['ListHE'])){
				$ArrHE = array();
				foreach($ListHE AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrHE[$val]['id'] 		= $valx['id'];
					$ArrHE[$val]['area'] 			= $region;
					$ArrHE[$val]['unit'] 			= $valx['unit'];
					$ArrHE[$val]['jml_hari'] 		= $valx['jml_hari'];
					$ArrHE[$val]['rate'] 			= str_replace(',', '', $valx['rate']);
					$ArrHE[$val]['rate_unit'] 		= str_replace(',', '', $valx['rate_unit']);
					$ArrHE[$val]['total_rate'] 		= str_replace(',', '', $valx['total_rate']);
				}
			}

			if(!empty($data['ListVT'])){
				$ArrVT = array();
				foreach($ListVT AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrVT[$val]['id'] 				= $valx['id'];
					$ArrVT[$val]['area'] 			= $region;
					// $ArrVT[$val]['unit'] 			= $valx['unit'];
					$ArrVT[$val]['jml_hari'] 		= $valx['jml_hari'];
					$ArrVT[$val]['rate'] 			= str_replace(',', '', $valx['rate']);
					$ArrVT[$val]['rate_unit'] 		= str_replace(',', '', $valx['rate_unit']);
					$ArrVT[$val]['total_rate'] 		= str_replace(',', '', $valx['total_rate']);
				}
			}

			if(!empty($data['ListCN'])){
				$ArrCN = array();
				// print_r($ListCN);
				foreach($ListCN AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrCN[$val]['id'] 				= $valx['id'];
					$ArrCN[$val]['area'] 			= $region;
					// $ArrCN[$val]['unit'] 			= strtolower($valx['unit']);
					$ArrCN[$val]['jml_hari'] 		= 0;
					$ArrCN[$val]['rate'] 			= str_replace(',', '', $valx['rate']);
					$ArrCN[$val]['rate_unit'] 		= str_replace(',', '', $valx['rate_unit']);
					$ArrCN[$val]['total_rate'] 		= str_replace(',', '', $valx['total_rate']);
				}
			}

			if(!empty($data['ListMP'])){
				$ArrMP = array();
				foreach($ListMP AS $val => $valx){
					$TOTAL_COST += str_replace(',', '', $valx['total_rate']);

					$ArrMP[$val]['id'] 				= $valx['id'];
					$ArrMP[$val]['area'] 			= $region;
					$ArrMP[$val]['unit'] 			= $valx['unit'];
					$ArrMP[$val]['jml_hari'] 		= $valx['jml_hari'];
					$ArrMP[$val]['jml_jam'] 		= str_replace(',', '', $valx['jml_jam']);
					$ArrMP[$val]['rate'] 			= str_replace(',', '', $valx['rate']);
					$ArrMP[$val]['rate_ot'] 		= str_replace(',', '', $valx['rate_ot']);
					$ArrMP[$val]['rate_us'] 		= str_replace(',', '', $valx['rate_us']);
					$ArrMP[$val]['rate_um'] 		= str_replace(',', '', $valx['rate_um']);
					$ArrMP[$val]['rate_unit'] 		= str_replace(',', '', $valx['rate_unit']);
					$ArrMP[$val]['total_rate'] 		= str_replace(',', '', $valx['total_rate']);
				}
			}

			$ArrUpdate = array(
				'rate' => $TOTAL_COST,
			);

			// print_r($ArrMeal);
			// print_r($ArrHouse);
			// print_r($ArrHouse2);
			// print_r($ArrTrans);
			// print_r($ArrTrans2);
			// print_r($ArrEtc);
			// print_r($ArrEtc2);
			// print_r($ArrHE);
			// print_r($ArrVT);
			// print_r($ArrCN);
			// print_r($ArrMP);
			// exit;

			//DELETE BELUM
			$this->db->trans_start();
				$this->db->where('project_code', $project_code);
				$this->db->update('project_header',$ArrUpdate);

				if(!empty($ArrMeal)){
					$this->db->update_batch('project_detail_akomodasi', $ArrMeal,'id');
				}
				if(!empty($ArrHouse2)){
					$this->db->update_batch('project_detail_akomodasi', $ArrHouse2,'id');
				}
				if(!empty($ArrTrans2)){
					$this->db->update_batch('project_detail_akomodasi', $ArrTrans2,'id');
				}
				if(!empty($ArrEtc2)){
					$this->db->update_batch('project_detail_akomodasi', $ArrEtc2,'id');
				}
				if(!empty($ArrTest2)){
					$this->db->update_batch('project_detail_akomodasi', $ArrTest2,'id');
				}
				if(!empty($Arrsurvey2)){
					$this->db->update_batch('project_detail_akomodasi', $Arrsurvey2,'id');
				}
				if(!empty($ArrCovid2)){
					$this->db->update_batch('project_detail_akomodasi', $ArrCovid2,'id');
				}
				if(!empty($ArrMDE2)){
					$this->db->update_batch('project_detail_akomodasi', $ArrMDE2,'id');
				}
				//Add tambahan
				if(!empty($ArrHouse)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrHouse);
				}
				if(!empty($ArrTrans)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrTrans);
				}
				if(!empty($ArrEtc)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrEtc);
				}

				if(!empty($ArrVT)){
					$this->db->update_batch('project_detail_process', $ArrVT,'id');
				}
				if(!empty($ArrHE)){
					$this->db->update_batch('project_detail_process', $ArrHE, 'id');
				}
				if(!empty($ArrCN)){
					$this->db->update_batch('project_detail_process', $ArrCN,'id');
				}
				if(!empty($ArrMP)){
					$this->db->update_batch('project_detail_process', $ArrMP,'id');
				}
			$this->db->trans_complete();


			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> 'Update Cost Instalation data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> 'Update Cost Instalation data success. Thanks ...',
					'status'	=> 1
				);
				history('Edit Costing Instalation code '.$project_code);
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

			$project_code = $this->uri->segment(3);

			$header 	= $this->db->get_where('project_header', array('project_code'=>$project_code))->result();
			$detail_bq 	=  $this->db->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'project'))->result_array();
			$detail_cus = $this->db->group_by('pekerjaan')->order_by('id','asc')->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'custom'))->result_array();
			
			$rMeal 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'meal'))->result_array();
			$rHouse 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'house'))->result_array();
			$rTrans 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'trans'))->result_array();
			$rEtc 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'etc'))->result_array();
			$rTesting 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'testing'))->result_array();
			$rSurvey 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'survey'))->result_array();
			$rCovid 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'covid'))->result_array();
			$rMDE 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'mde'))->result_array();

			$qTransport	 	= "SELECT * FROM list WHERE category='transportasi' AND category_='item cost' AND flag='N' ORDER BY urut ASC";
			$transx	= $this->db->query($qTransport)->result_array();

			$qDetailHE	= "SELECT a.*, MAX(a.qty) AS qty_, SUM(b.std_time) AS jml_hari_ FROM project_detail_process a INNER JOIN project_detail_header b ON a.project_code_det=b.project_code_det WHERE a.project_code='".$project_code."' AND a.tipe='heavy equipment' AND a.deleted='N' GROUP BY a.code_group, a.qty";
			$rHE 		= $this->db->query($qDetailHE)->result_array();

			// $rVT 		= $this->db->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>'tools','deleted'=>'N'))->result_array();
			$rVT 		= $this->db
							->select('*')
							->from('project_detail_process')
							->where("project_code='$project_code' AND deleted='N' AND (tipe='tools' OR tipe='safety')")
							->get()
							->result_array();
			$rCN		= $this->db->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>'consumable','deleted'=>'N'))->result_array();
			$rMP		= $this->db->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>'man power','deleted'=>'N'))->result_array();

			$data = array(
				'title'			=> 'Edit COGS',
				'action'		=> 'edit',
				'header' => $header,
				'detail_bq' => $detail_bq,
				'detail_cus' => $detail_cus,
				'meal' => $rMeal,
				'house_' => $rHouse,
				'trans' => $rTrans,
				'etc_' => $rEtc,
				'survey_' => $rSurvey,
				'test_' => $rTesting,
				'covid_' => $rCovid,
				'mde_' => $rMDE,
				'transx' => $transx,
				'he' => $rHE,
				'vt' => $rVT,
				'cn' => $rCN,
				'mp' => $rMP
			);
			$this->load->view('Costing/edit',$data);
		}
	}

	public function get_project(){
				$project_code 	= $this->uri->segment(3);

				$header	= $this->db->query("SELECT * FROM project_header WHERE project_code='".$project_code."' LIMIT 1")->result();
				$detail_bq	= $this->db->query("SELECT * FROM project_detail_bq WHERE project_code='".$project_code."' AND deleted='N'")->result_array();
				$detail	= $this->db->query("SELECT * FROM project_detail_header WHERE project_code='".$project_code."' AND deleted='N'")->result_array();

				$qDetailMeal 	= "SELECT *, MAX(jml_orang) AS jml_orang_, SUM(jml_hari) AS jml_hari_ FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='meal' AND deleted='N' GROUP BY code_group";
				$meal 				= $this->db->query($qDetailMeal)->result_array();

				$qDetailOT 	= "SELECT *, MAX(jml_orang) AS jml_orang_, SUM(jml_hari) AS jml_hari_ FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='overtime' AND deleted='N' GROUP BY code_group";
				$overtime 			= $this->db->query($qDetailOT)->result_array();

				$qDetailHouse = "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='house' AND deleted='N'";
				$house_ 			= $this->db->query($qDetailHouse)->result_array();

				$qDetailTrans = "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='trans' AND deleted='N'";
				$trans 			= $this->db->query($qDetailTrans)->result_array();

				$qDetailEtc 	= "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='etc' AND deleted='N'";
				$etc_ 				= $this->db->query($qDetailEtc)->result_array();

				$qTransport	 	= "SELECT * FROM list WHERE category='transportasi' AND category_='item cost' AND flag='N' ORDER BY urut ASC";
				$transx		= $this->db->query($qTransport)->result_array();

				$qDetailHE	= "SELECT a.*, MAX(a.qty) AS qty_, SUM(b.std_time) AS std_time FROM project_detail_process a INNER JOIN project_detail_header b ON a.project_code_det=b.project_code_det WHERE a.project_code='".$project_code."' AND a.tipe='heavy equipment' AND a.deleted='N' GROUP BY a.code_group, a.qty";
				$he 		= $this->db->query($qDetailHE)->result_array();

				$qDetailVT	= "SELECT a.*, MAX(a.qty) AS qty_, SUM(b.std_time) AS std_time FROM project_detail_process a INNER JOIN project_detail_header b ON a.project_code_det=b.project_code_det WHERE a.project_code='".$project_code."' AND a.tipe='tools' AND a.deleted='N' GROUP BY a.code_group, a.qty";
				$vt 		= $this->db->query($qDetailVT)->result_array();

				$qDetailCN 	= "SELECT a.*, SUM(a.qty) AS qty_, SUM(b.std_time) AS std_time FROM project_detail_process a INNER JOIN project_detail_header b ON a.project_code_det=b.project_code_det WHERE a.project_code='".$project_code."' AND a.tipe='consumable' AND a.deleted='N' GROUP BY a.code_group, a.qty";
				$cn			= $this->db->query($qDetailCN)->result_array();

				$qDetailMP 	= "SELECT a.*, MAX(a.qty) AS qty_, SUM(b.std_time) AS std_time FROM project_detail_process a INNER JOIN project_detail_header b ON a.project_code_det=b.project_code_det WHERE a.project_code='".$project_code."' AND a.tipe='man power' AND a.deleted='N' GROUP BY a.code_group";
				$mp			= $this->db->query($qDetailMP)->result_array();

				$qSat	 	= "SELECT * FROM list WHERE category='tempat tinggal' AND category_='satuan' AND flag='N' ORDER BY urut ASC";
				$restSat = $this->db->query($qSat)->result_array();

				$d_Header = "";
				$d_Header .= "<div class='form-group row'>
												<label class='label-control col-sm-2'><b>Project Name</b></label>
												<div class='col-sm-4'>
													<input type='hidden' id='project_code' name='project_code' class='form-control input-md' readonly='readonly' value='".$header[0]->project_code."'>
													<input type='text' id='project_name' name='project_name' class='form-control input-md' readonly='readonly' value='".strtoupper($header[0]->project_name)."'>
												</div>
												<label class='label-control col-sm-2'><b>Region</b></label>
												<div class='col-sm-4'>
													<input type='text' id='region_code' name='region_code' class='form-control input-md' readonly='readonly' value='".strtoupper($header[0]->region_code)."'>
												</div>
											</div>
											<div class='form-group row'>
												<label class='label-control col-sm-2'><b>Project Location</b></label>
												<div class='col-sm-4'>
													<textarea type='text' id='location' name='location' class='form-control input-md' readonly='readonly' rows='3'>".strtoupper($header[0]->location)."</textarea>
												</div>
												<label class='label-control col-sm-2'><b>Time/Day (Hours)</b></label>
												<div class='col-sm-4'>
													<input type='text' id='total_time' name='total_time' class='form-control input-md' readonly='readonly' value='".strtoupper($header[0]->total_time)."'>
												</div>
											</div>
											<div class='form-group row'>
												<label class='label-control col-sm-2'><b>BQ Project <span class='text-red'>*</span></b></label>
												<div class='col-sm-4'>
													<table id='my-grid' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
														<thead id='head_table_bq'>
															<tr class='bg-purple'>
																<th class='text-center' style='width: 25%;'>Diameter</th>
																<th class='text-center' style='width: 25%;'>Qty</th>
																<th class='text-center' style='width: 25%;'>Satuan</th>
																<th class='text-center' style='width: 25%;'>Cycletime</th>
															</tr>
														</thead>
								            <tbody>";
															$tot_ot = $header[0]->total_time - 7;
															$total_ot = ($tot_ot < 0)?0:$tot_ot;
								              foreach ($detail_bq as $key => $value) {
								                  $d_Header .= "<tr>";
																		$d_Header .= "<td align='left'>";
																		$d_Header .= "<input type='text' name='ListDetailBq[0".$key."][diameter]' class='form-control input-md' readonly='readonly' value='".$value['diameter']."'>";
																		$d_Header .= "</td>";
								              			$d_Header .= "<td align='left'>";
								              			$d_Header .= "<input type='text' name='ListDetailBq[0".$key."][qty]' class='form-control input-md' readonly='readonly' value='".$value['qty']."'>";
								              			$d_Header .= "</td>";
								              			$d_Header .= "<td align='left'>";
																		$d_Header .= "<input type='text' name='ListDetailBq[0".$key."][satuan_code]' class='form-control input-md' readonly='readonly' value='".$value['satuan_code']."'>";
								         					  $d_Header .= "</td>";
																		$d_Header .= "<td align='left'>";
																		$d_Header .= "<input type='text' name='ListDetailBq[0".$key."][cycletime]' class='form-control input-md' readonly='readonly' value='".$value['cycletime']."'>";
								         					  $d_Header .= "</td>";
								            			$d_Header .= "</tr>";
								              }
				  $d_Header .=	     "</tbody>
															<tfoot>";
									$d_Header .= "<tr>";
										$d_Header .= "<td align='left'>";
										$d_Header .= "<input type='text' name='bq_qty' class='form-control input-md' readonly='readonly' value='".$header[0]->bq_qty."'>";
										$d_Header .= "</td>";
										$d_Header .= "<td align='left'>";
										$d_Header .= "<input type='text' name='bq_mp' class='form-control input-md' readonly='readonly' value='".$header[0]->bq_mp."'>";
										$d_Header .= "</td>";
										$d_Header .= "<td align='left'>";
										$d_Header .= "<input type='text' name='bq_ct' class='form-control input-md' readonly='readonly' value='".$header[0]->bq_ct."'>";
										$d_Header .= "</td>";
										$d_Header .= "<td align='left'>";
										$d_Header .= "<input type='text' name='bq_total' class='form-control input-md' readonly='readonly' value='".$header[0]->bq_total."'>";
										$d_Header .= "</td>";
									$d_Header .= "</tr>";
								$d_Header .= "</tfoot>
														</table>
													</div>
												</div>";

					$d_Header .= "<div class='form-group row'>
									      <div class='box-body'>
									        <table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
									            <thead id='head_table'>
									              <tr class='bg-blue'>
									                <th colspan='8'>&nbsp;&nbsp;&nbsp;Heavy Equipment</th>
									              </tr>
									              <tr class='bg-purple'>
									                <th class='text-center' style='width: 17%;'>Name Alat</th>
									                <th class='text-center'>Capacity</th>
									                <th class='text-center' style='width: 10%;'>Qty</th>
									                <th class='text-center' style='width: 10%;'>Time (Day)</th>
																	<th class='text-center' style='width: 10%;'>Unit</th>
																	<th class='text-center' style='width: 9%;'>Cost</th>
									                <th class='text-center' style='width: 9%;'>Cost Unit (Days)</th>
									                <th class='text-center' style='width: 9%;'>Total Cost</th>
									              </tr>
									            </thead>
									            <tbody>";
									              if(!empty($he)){
									                $no_vt = 0;
									                foreach ($he as $key => $value) {
									                  $no_vt++;
																		$rate = rate($value['code_group'], 'day', 'vehicle tool', $header[0]->region_code);
									                  $d_Header .= "<tr>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='hidden' name='ListHE[0".$no_vt."][code_group]' class='form-control input-md' value='".$value['code_group']."'>";
									                    $d_Header .= "<input type='text' name='ListHE[0".$no_vt."][category]' class='form-control input-md' readonly value='".strtoupper($value['category'])."'>";
									                    $d_Header .= "</td>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListHE[0".$no_vt."][spec]' class='form-control input-md' readonly value='".strtoupper($value['spec'])."'>";
									                    $d_Header .= "</td>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListHE[0".$no_vt."][qty]' class='form-control input-md text-center' readonly placeholder='Amount People' value='".$value['qty_']."'>";
									                    $d_Header .= "</td>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListHE[0".$no_vt."][jml_hari]' class='form-control input-md text-center' readonly placeholder='Amount (Day)' value='".$value['std_time']."'>";
									                    $d_Header .= "</td>";
																			$d_Header .= "<td>";
																			$d_Header .= "<select name='ListHE[0".$no_vt."][unit]' data-code='".$value['code_group']."' data-category='vehicle_tool' class='chosen_select form-control inline-blockd clSelect ch_rate'>";
																			foreach($restSat AS $val_vtList => $valx_vtList){
																				$d_Header .= "<option value='".$valx_vtList['category_list']."' >".strtoupper($valx_vtList['view_'])."</option>";
																			}
									                    // $d_Header .= "<input type='text' name='ListHE[0".$no_vt."][jml_hari]' class='form-control input-md text-center' readonly placeholder='Amount (Day)' value='".$value['std_time']."'>";
									                    $d_Header .= "</td>";
																			$d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListHE[0".$no_vt."][rate]' class='form-control input-md text-right' readonly value='".number_format($rate)."'>";
									                    $d_Header .= "</td>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListHE[0".$no_vt."][rate_unit]' class='form-control input-md text-right' readonly value='".number_format($rate * $value['std_time'])."'>";
									                    $d_Header .= "</td>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListHE[0".$no_vt."][total_rate]' class='form-control input-md text-right sum_he' readonly value='".number_format($rate * $value['std_time'] * $value['qty_'])."'>";
									                    $d_Header .= "</td>";
									                  $d_Header .= "</tr>";
									                }
									              }
								$d_Header .=	"	</tbody>
																<tfoot>
																	<tr>
																		<td colspan='7'><b>TOTAL HEAVY EQUIPMENT</b></td>
																		<td><input type='text' id='sum_he' class='form-control input-md text-right' readonly></td>
																	</tr>
																</tfoot>
									          </table>
									        </div>
									      </div>";
							
										  $d_Header .= "<div class='form-group row'>
									      <div class='box-body'>
									        <table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
									            <thead id='head_table'>
									              <tr class='bg-blue'>
									                <th colspan='8'>&nbsp;&nbsp;&nbsp;Tools Equipment</th>
									              </tr>
									              <tr class='bg-purple'>
									                <th class='text-center' style='width: 17%;'>Name Alat</th>
									                <th class='text-center'>Capacity</th>
									                <th class='text-center' style='width: 10%;'>Qty</th>
									                <th class='text-center' style='width: 10%;'>Time (Day)</th>
																	<th class='text-center' style='width: 10%;'>Unit</th>
																	<th class='text-center' style='width: 9%;'>Cost</th>
									                <th class='text-center' style='width: 9%;'>Cost Unit</th>
									                <th class='text-center' style='width: 9%;'>Total Cost</th>
									              </tr>
									            </thead>
									            <tbody>";
									              if(!empty($vt)){
									                $no_vt = 0;
									                foreach ($vt as $key => $value) {
									                  $no_vt++;
																		$rate = rate($value['code_group'], 'day', 'vehicle tool', $header[0]->region_code);
									                  $d_Header .= "<tr>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='hidden' name='ListVT[0".$no_vt."][code_group]' class='form-control input-md' value='".$value['code_group']."'>";
									                    $d_Header .= "<input type='text' name='ListVT[0".$no_vt."][category]' class='form-control input-md' readonly value='".strtoupper($value['category'])."'>";
									                    $d_Header .= "</td>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListVT[0".$no_vt."][spec]' class='form-control input-md' readonly value='".getMP($value['code_group'],'vehicle_tool_new')."'>";
									                    $d_Header .= "</td>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListVT[0".$no_vt."][qty]' class='form-control input-md text-center' readonly placeholder='Amount People' value='".$value['qty_']."'>";
									                    $d_Header .= "</td>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListVT[0".$no_vt."][jml_hari]' class='form-control input-md text-center' readonly placeholder='Amount (Day)' value='".$value['std_time']."'>";
									                    $d_Header .= "</td>";
																			$d_Header .= "<td>";
																			$d_Header .= "<select name='ListVT[0".$no_vt."][unit]' data-code='".$value['code_group']."' data-category='vehicle_tool' class='chosen_select form-control inline-blockd clSelect ch_rate'>";
																			foreach($restSat AS $val_vtList => $valx_vtList){
																				$d_Header .= "<option value='".$valx_vtList['category_list']."' >".strtoupper($valx_vtList['view_'])."</option>";
																			}
									                    // $d_Header .= "<input type='text' name='ListVT[0".$no_vt."][jml_hari]' class='form-control input-md text-center' readonly placeholder='Amount (Day)' value='".$value['std_time']."'>";
									                    $d_Header .= "</td>";
																			$d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListVT[0".$no_vt."][rate]' class='form-control input-md text-right' readonly value='".number_format($rate)."'>";
									                    $d_Header .= "</td>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListVT[0".$no_vt."][rate_unit]' class='form-control input-md text-right' readonly value='".number_format($rate * $value['std_time'])."'>";
									                    $d_Header .= "</td>";
									                    $d_Header .= "<td>";
									                    $d_Header .= "<input type='text' name='ListVT[0".$no_vt."][total_rate]' class='form-control input-md text-right sum_vt' readonly value='".number_format($rate * $value['std_time'] * $value['qty_'])."'>";
									                    $d_Header .= "</td>";
									                  $d_Header .= "</tr>";
									                }
									              }
								$d_Header .=	"	</tbody>
																<tfoot>
																	<tr>
																		<td colspan='7'><b>TOOLS EQUIPMENT</b></td>
																		<td><input type='text' id='sum_vt' class='form-control input-md text-right' readonly></td>
																	</tr>
																</tfoot>
									          </table>
									        </div>
									      </div>";

							$d_Header .= "<div class='form-group row'>
											      <div class='box-body'>
											        <table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
											            <thead id='head_table'>
											              <tr class='bg-blue'>
											                <th colspan='7'>&nbsp;&nbsp;&nbsp;Consumable & APD</th>
											              </tr>
											              <tr class='bg-purple'>
											                <th class='text-center' style='width: 17%;'>Consumable Name</th>
											                <th class='text-center'>Capacity</th>
											                <th class='text-center' style='width: 10%;'>Qty</th>
											                <th class='text-center' style='width: 10%;'>Unit</th>
																			<th class='text-center' style='width: 9%;'>Cost</th>
											                <th class='text-center' style='width: 9%;'>Cost Unit</th>
											                <th class='text-center' style='width: 9%;'>Total Cost</th>
											              </tr>
											            </thead>
											            <tbody>";
											              if(!empty($cn)){
											                $no_cn = 0;
											                foreach ($cn as $key => $value) {
											                  $no_cn++;
																				$rate = rate($value['code_group'], $value['unit'], 'consumable', 'all region');
											                  $d_Header .= "<tr>";
											                    $d_Header .= "<td>";
											                    $d_Header .= "<input type='hidden' name='ListCN[0".$no_cn."][code_group]' class='form-control input-md' value='".$value['code_group']."'>";
											                    $d_Header .= "<input type='text' name='ListCN[0".$no_cn."][category]' class='form-control input-md' readonly value='".strtoupper($value['category'])."'>";
											                    $d_Header .= "</td>";
											                    $d_Header .= "<td>";
											                    $d_Header .= "<input type='text' name='ListCN[0".$no_cn."][spec]' class='form-control input-md' readonly value='".getMP($value['code_group'],'con_nonmat_new')."'>";
											                    $d_Header .= "</td>";
											                    $d_Header .= "<td>";
											                    $d_Header .= "<input type='text' name='ListCN[0".$no_cn."][qty]' class='form-control input-md text-center' readonly placeholder='Amount People' value='".$value['qty_']."'>";
											                    $d_Header .= "</td>";
											                    $d_Header .= "<td>";
											                    $d_Header .= "<input type='text' name='ListCN[0".$no_cn."][unit]' class='form-control input-md text-center' readonly placeholder='Amount (Day)' value='".$value['unit']."'>";
											                    $d_Header .= "</td>";
																					$d_Header .= "<td>";
											                    $d_Header .= "<input type='text' name='ListCN[0".$no_cn."][rate]' class='form-control input-md text-right' readonly value='".number_format($rate)."'>";
											                    $d_Header .= "</td>";
											                    $d_Header .= "<td>";
											                    $d_Header .= "<input type='text' name='ListCN[0".$no_cn."][rate_unit]' class='form-control input-md text-right' readonly value='".number_format($rate)."'>";
											                    $d_Header .= "</td>";
											                    $d_Header .= "<td>";
											                    $d_Header .= "<input type='text' name='ListCN[0".$no_cn."][total_rate]' class='form-control input-md text-right sum_cn' readonly value='".number_format($rate * $value['qty_'])."'>";
											                    $d_Header .= "</td>";
											                  $d_Header .= "</tr>";
											                }
											              }
										$d_Header .=	"	</tbody>
																		<tfoot>
																			<tr>
																				<td colspan='6'><b>TOTAL CONSUMABLE & APD</b></td>
																				<td><input type='text' id='sum_cn' class='form-control input-md text-right' readonly></td>
																			</tr>
																		</tfoot>
											          </table>
											        </div>
											      </div>";


			$d_Header .= "<div class='form-group row'>
							      <div class='box-body'>
							        <table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
							            <thead id='head_table'>
							              <tr class='bg-blue'>
							                <th colspan='8'>&nbsp;&nbsp;&nbsp;Man Power</th>
							              </tr>
							              <tr class='bg-purple'>
							                <th class='text-center' style='width: 17%;'>Man Power</th>
							                <th class='text-center'>Competensi</th>
							                <th class='text-center' style='width: 10%;'>Qty</th>
							                <th class='text-center' style='width: 10%;'>Time (Day)</th>
															<th class='text-center' style='width: 10%;'>Unit</th>
															<th class='text-center' style='width: 9%;'>Cost</th>
							                <th class='text-center' style='width: 9%;'>Cost Unit</th>
							                <th class='text-center' style='width: 9%;'>Total Cost</th>
							              </tr>
							            </thead>
							            <tbody>";
							              if(!empty($mp)){
							                $no_mp = 0;
							                foreach ($mp as $key => $value) {
							                  $no_mp++;
																$rate = rate($value['code_group'], 'day', 'man power', $header[0]->region_code);
							                  $d_Header .= "<tr>";
							                    $d_Header .= "<td>";
							                    $d_Header .= "<input type='hidden' name='ListMP[0".$no_mp."][code_group]' class='form-control input-md' value='".$value['code_group']."'>";
							                    $d_Header .= "<input type='text' name='ListMP[0".$no_mp."][category]' class='form-control input-md' readonly value='".strtoupper($value['category'])."'>";
							                    $d_Header .= "</td>";
							                    $d_Header .= "<td>";
							                    $d_Header .= "<input type='text' name='ListMP[0".$no_mp."][spec]' class='form-control input-md' readonly value='".getMP($value['code_group'],'man_power_new')."'>";
							                    $d_Header .= "</td>";
							                    $d_Header .= "<td>";
							                    $d_Header .= "<input type='text' name='ListMP[0".$no_mp."][qty]' class='form-control input-md text-center' readonly placeholder='Amount People' value='".$value['qty_']."'>";
							                    $d_Header .= "</td>";
							                    $d_Header .= "<td>";
							                    $d_Header .= "<input type='text' name='ListMP[0".$no_mp."][jml_hari]' class='form-control input-md text-center' readonly placeholder='Amount (Day)' value='".$value['std_time']."'>";
							                    $d_Header .= "</td>";
																	$d_Header .= "<td>";
																	$d_Header .= "<select name='ListMP[0".$no_mp."][unit]'  data-code='".$value['code_group']."' data-category='man_power' class='chosen_select form-control inline-blockd clSelect ch_rate'>";
																	foreach($restSat AS $val_vtList => $valx_vtList){
																		$d_Header .= "<option value='".$valx_vtList['category_list']."' >".strtoupper($valx_vtList['view_'])."</option>";
																	}
							                    // $d_Header .= "<input type='text' name='ListMP[0".$no_mp."][jml_hari]' class='form-control input-md text-center' readonly placeholder='Amount (Day)' value='".$value['std_time']."'>";
							                    $d_Header .= "</td>";
																	$d_Header .= "<td>";
							                    $d_Header .= "<input type='text' name='ListMP[0".$no_mp."][rate]' class='form-control input-md text-right' readonly value='".number_format($rate)."'>";
							                    $d_Header .= "</td>";
							                    $d_Header .= "<td>";
							                    $d_Header .= "<input type='text' name='ListMP[0".$no_mp."][rate_unit]' class='form-control input-md text-right' readonly value='".number_format($rate * $value['std_time'])."'>";
							                    $d_Header .= "</td>";
							                    $d_Header .= "<td>";
							                    $d_Header .= "<input type='text' name='ListMP[0".$no_mp."][total_rate]' class='form-control input-md text-right sum_mp' readonly value='".number_format($rate * $value['std_time'] * $value['qty_'])."'>";
							                    $d_Header .= "</td>";
							                  $d_Header .= "</tr>";
							                }
							              }
						$d_Header .=	"	</tbody>
														<tfoot>
															<tr>
																<td colspan='7'><b>TOTAL MAN POWER</b></td>
																<td><input type='text' id='sum_mp' class='form-control input-md text-right' readonly></td>
															</tr>
														</tfoot>
							          </table>
							        </div>
							      </div>";


						$d_Header .=	"<div class='form-group row hideSP'>
												<div class='box-body'>
												<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
														<thead id='head_table'>
															<tr class='bg-blue'>
																<th colspan='2'>&nbsp;&nbsp;&nbsp;Job Process</th>
															</tr>
															<tr class='bg-purple'>
																<th class='text-center' style='width: 20%;'>Job Name</th>
																<th class='text-center' style='width: 80%;'>Detail</th>
															</tr>
														</thead>
							              <tbody>";

							                $no = 0;
							                foreach ($detail as $val => $valx) {
							                    $no++;
																	$restVT = $this->db->query("SELECT * FROM project_detail_w_det_vehicle_tool WHERE project_code_det='".$valx['project_code_det']."' AND deleted='N' ORDER BY id ASC ")->result_array();
																	$restCN = $this->db->query("SELECT * FROM project_detail_w_det_con_nonmat WHERE project_code_det='".$valx['project_code_det']."' AND deleted='N' ORDER BY id ASC ")->result_array();
																	$restMP = $this->db->query("SELECT * FROM project_detail_w_det_man_power WHERE project_code_det='".$valx['project_code_det']."' AND deleted='N' ORDER BY id ASC ")->result_array();

							                    $d_Header .= "<tr>";
							                			$d_Header .= "<td align='left'  width='10%'>";
							                  			$d_Header .= "<div class='labDet'>Job Name</div>";
																			$d_Header .= "<input type='hidden' name='ListDetail[0".$no."][code_work]' class='form-control input-md' readonly='readonly' value='".$valx['code_work']."'>";
																			$d_Header .= "<input type='text' name='ListDetail[0".$no."][category]' class='form-control input-md' readonly='readonly' value='".strtoupper($valx['category'])."'>";
									         					$d_Header .="</td>";
							                			$d_Header .= "<td align='left'  width='90%'>";
							                  			$d_Header .= "<table class='table table-striped table-bordered table-hover table-condensed' width='100%'>";
							                    			$d_Header .= "<tbody>";

							                          $qDet	 		= "SELECT * FROM view_project_detail_w_det WHERE project_code_det='".$valx['project_code_det']."' AND deleted='N'";
							                    			$resDet	= $this->db->query($qDet)->result_array();
							                          $nox = 0;
							                          foreach($resDet AS $val2 => $valx2){
							                            $nox++;
							                            $d_Header .= "<tr>";
							                      				$d_Header .= "<td width='50%'>";
							                        				$d_Header .= "<div class='labDet'>Job Process</div>";
							                            		$d_Header .= "<input type='text' name='ListDetail[0".$no."][work_process][0".$nox."][work_process]' class='form-control input-md' readonly='readonly' value='".strtoupper($valx2['work_process'])."'>";
							                            	$d_Header .= "</td>";
																						$d_Header .= "<td width='50%'>";
							                        				$d_Header .= "<div class='labDet'>Standart Time</div>";
							                        				$d_Header .= "<input type='text' name='ListDetail[0".$no."][work_process][0".$nox."][std_time]' class='form-control input-md' readonly='readonly' placeholder='Standart Time' value='".$valx2['std_time']."'>";
							                              $d_Header .= "</td>";
							                      		  $d_Header .= "</tr>";
							                          }


																				$d_Header .= "<tr>";
																					$d_Header .= "<td width='100%' colspan='2'>";
																						$d_Header .= "<table id='my-grid' class='table table-striped table-bordered table-hover table-condensed' width='100%'>";
																							$d_Header .= "<thead id='head_table_bq'>";
																								$d_Header .= "<tr class='bg-purple'>";
																									$d_Header .= "<th class='text-center' style='width: 33%;'>Heavy Equipment & Tools</th>";
																									$d_Header .= "<th class='text-center' style='width: 36%;'>Consumable & APD</th>";
																									$d_Header .= "<th class='text-center' style='width: 31%;'>Man Power</th>";
																								$d_Header .= "</tr>";
																							$d_Header .= "</thead>";
																							$d_Header .= "<tbody>";
																							$d_Header .= "<tr>";
																								$d_Header .= "<td>";
																									$num1 = 0;
																									foreach($restVT AS $val_vt => $valx_vt){
																										$num1++;
																										$d_Header .= "<div style='margin-bottom: 6px;'>";
																											$d_Header .= "<div class='input-group'>";
																											$d_Header .= "<input type='hidden' name='ListDetail[0".$no."][vt][$num1][code_group]' class='form-control input-md' readonly='readonly' value='".$valx_vt['code_group']."'>";
																											$d_Header .= "<input type='text' name='ListDetail[0".$no."][vt][$num1][category]' class='form-control input-md widC' readonly='readonly' value='".strtoupper($valx_vt['category']." - ".$valx_vt['spec'])."'>";
																											$d_Header .= "<span class='input-group-addon'></span>";
																											$d_Header .= "<input type='text' name='ListDetail[0".$no."][vt][$num1][qty]' class='form-control input-md widCtr' readonly='readonly' value='".$valx_vt['qty']."'>";
																											$d_Header .= "</div>";
																										$d_Header .= "</div>";
																									}
																								$d_Header .= "</td>";
																								$d_Header .= "<td>";
																									//Consumable
																									$num2 = 0;
																									foreach($restCN AS $val_cn => $valx_cn){
																										$num2++;
																										$d_Header .= "<div style='margin-bottom: 6px;'>";
																											$d_Header .= "<div class='input-group'>";
																												$d_Header .= "<input type='hidden' name='ListDetail[0".$no."][cn][$num2][code_group]' class='form-control input-md' readonly='readonly' value='".$valx_cn['code_group']."'>";
																												$d_Header .= "<input type='text' name='ListDetail[0".$no."][cn][$num2][category]' class='form-control input-md widC' readonly='readonly' value='".strtoupper($valx_cn['category']." - ".$valx_cn['spec'])."'>";
																												$d_Header .= "<span class='input-group-addon'></span>";
																												$d_Header .= "<input type='text' name='ListDetail[0".$no."][cn][$num2][qty]' class='form-control input-md widCtr' readonly='readonly' value='".$valx_cn['qty']."'>";
																												$d_Header .= "<span class='input-group-addon'></span>";
																												$d_Header .= "<input type='text' name='ListDetail[0".$no."][cn][$num2][unit]' class='form-control input-md widCtr' readonly='readonly' value='".$valx_cn['unit']."'>";
																											$d_Header .= "</div>";
																										$d_Header .= "</div>";
																									}
																								$d_Header .= "</td>";
																								$d_Header .= "<td>";
																									//Man Power
																									$num3 = 0;
																									foreach($restMP AS $val_vt => $valx_vt){
																										$num3++;
																										$d_Header .= "<div style='margin-bottom: 6px;'>";
																											$d_Header .= "<div class='input-group'>";
																												$d_Header .= "<input type='hidden' name='ListDetail[0".$no."][mp][$num3][code_group]' class='form-control input-md' readonly='readonly' value='".$valx_vt['code_group']."'>";
																												$d_Header .= "<input type='text' name='ListDetail[0".$no."][mp][$num3][category]' class='form-control input-md widC' readonly='readonly' value='".strtoupper($valx_vt['category']." - ".$valx_vt['spec'])."'>";
																												$d_Header .= "<span class='input-group-addon'></span>";
																												$d_Header .= "<input type='text' name='ListDetail[0".$no."][mp][$num3][qty]' class='form-control widCtr' readonly='readonly' value='".$valx_vt['qty']."'>";
																											$d_Header .= "</div>";
																										$d_Header .= "</div>";
																									}
																								$d_Header .= "</td>";
																							$d_Header .= "</tr>";
																							$d_Header .= "</tbody>";
																						$d_Header .= "</table>";
																					$d_Header .= "</td>";
																				$d_Header .= "</tr>";


							                          $d_Header .= "</tbody>";
							                  			$d_Header .= "</table>";
							                			$d_Header .= "</td>";
							              		  $d_Header .= "</tr>";
							                }
												  $d_Header .= "
																	</table>
																	</div>
																</div>
																<div class='form-group row'>
																<div class='box-body'>
																	<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
																			<thead id='head_table'>
																				<tr class='bg-blue'>
																					<th colspan='7'>&nbsp;&nbsp;&nbsp;Meal & Pocket Money</th>
																				</tr>
																				<tr class='bg-purple'>
																					<th class='text-center' style='width: 25%;'>Level Man Power</th>
																					<th class='text-center' style='width: 13%;'>Area</th>
																					<th class='text-center' style='width: 10%;'>Qty MP</th>
																					<th class='text-center' style='width: 10%;'>Time (Day)</th>
																					<th class='text-center' >Note</th>
																					<th class='text-center' style='width: 9%;'>Cost</th>
																					<th class='text-center' style='width: 9%;'>Total Cost</th>
																				</tr>
																			</thead>
																			<tbody>";
																			if(!empty($meal)){
																				$no_meal = 0;
																				foreach ($meal as $key => $value) {
																					$no_meal++;
																					$d_Header .= "<tr>";
																						$d_Header .= "<td width='20%'>";
																						$d_Header .= "<input type='hidden' name='ListMeal[0".$no_meal."][code_group]' id='code_groupm_0".$no_meal."' class='form-control input-md' value='".$value['code_group']."'>";
																						$d_Header .= "<input type='text' name='ListMeal[0".$no_meal."][spec]' id='specm_0".$no_meal."' class='form-control input-md' readonly value='".getMP($value['code_group'],'tb_view_man_power')."'>";
																						$d_Header .= "</td>";
																						$d_Header .= "<td width='13%'>";
																						$d_Header .= "<input type='text' name='ListMeal[0".$no_meal."][area]' id='aream_0".$no_meal."' class='form-control input-md text-center' readonly value='".strtoupper($value['area'])."'>";
																						$d_Header .= "</td>";
																						$d_Header .= "<td width='12%'>";
																						$d_Header .= "<input type='text' name='ListMeal[0".$no_meal."][jml_orang]' id='jml_orangm_0".$no_meal."' class='form-control input-md text-center' readonly placeholder='Amount People' value='".$value['jml_orang_']."'>";
																						$d_Header .= "</td>";
																						$d_Header .= "<td width='12%'>";
																						$d_Header .= "<input type='text' name='ListMeal[0".$no_meal."][jml_hari]' id='jml_harim_0".$no_meal."' class='form-control input-md text-center numberFull day_meal' readonly placeholder='Amount (Day)' value='".$value['jml_hari_']."'>";
																						$d_Header .= "</td>";
																						$d_Header .= "<td>";
																						$d_Header .= "<input type='text' name='ListMeal[0".$no_meal."][note]' id=notem_0".$no_meal."' class='form-control input-md' placeholder='Note' value='".$value['note']."'>";
																						$d_Header .= "</td>";
																						$d_Header .= "<td align='center' width='6%'>";
																						$d_Header .= "<input type='text' name='ListMeal[0".$no_meal."][rate]' id=ratem_0".$no_meal."' class='form-control input-md text-right rate rate_meal' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format(rate('AK00007', 'day', 'akomodasi', $header[0]->region_code))."'>";
																						$d_Header .= "<input type='hidden' name='ListMeal[0".$no_meal."][rate_unit]' id=rate_unitm_0".$no_meal."' class='form-control input-md text-right rate' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format((rate('AK00007', 'day', 'akomodasi', $header[0]->region_code)) * $value['jml_hari_'])."'>";
																						$d_Header .= "</td>";
																						$d_Header .= "<td align='center' width='6%'>";
																						$d_Header .= "<input type='text' name='ListMeal[0".$no_meal."][total_rate]' id=total_ratem_0".$no_meal."' class='form-control input-md text-right rate sum_meal' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format((rate('AK00007', 'day', 'akomodasi', $header[0]->region_code)) * $value['jml_hari_'] * $value['jml_orang_'])."'>";
																						$d_Header .= "</td>";
																					$d_Header .= "</tr>";
																				}
																			}
										$d_Header .=			"</tbody>
																			<tfoot>
																				<tr>
																					<td colspan='6'><b>TOTAL MEAL & POCKET MONEY</b></td>
																					<td><input type='text' id='sum_meal' class='form-control input-md text-right' readonly></td>
																				</tr>
																			</tfoot>
																		</table>
																	</div>
																</div>
																<div class='form-group row'>
																<div class='box-body'>
																	<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
																			<thead id='head_table'>
																				<tr class='bg-blue'>
																					<th colspan='7'>&nbsp;&nbsp;&nbsp;Overtime</th>
																				</tr>
																				<tr class='bg-purple'>
																					<th class='text-center' style='width: 24%;'>Level Man Power</th>
																					<th class='text-center' style='width: 13%;'>Qty MP</th>
																					<th class='text-center' style='width: 10%;'>Time (Day)</th>
																					<th class='text-center' style='width: 10%;'>Time (Hours)</th>
																					<th class='text-center'>Note</th>
																					<th class='text-center' style='width: 9%;'>Cost</th>
																					<th class='text-center' style='width: 9%;'>Total Cost</th>
																				</tr>
																			</thead>
																			<tbody>";
																				if(!empty($overtime)){
																					$no_ot = 0;
																					foreach ($overtime as $key => $value) {
																						$no_ot++;
																						$d_Header .= "<tr>";
																							$d_Header .= "<td>";
																							$d_Header .= "<input type='hidden' name='ListOvertime[0".$no_ot."][code_group]' id='code_groupo_0".$no_ot."' class='form-control input-md' value='".$value['code_group']."'>";
																							$d_Header .= "<input type='text' name='ListOvertime[0".$no_ot."][spec]' id='speco_0".$no_ot."_".$no."' class='form-control input-md' readonly value='".getMP($value['code_group'],'tb_view_man_power')."'>";
																							$d_Header .= "</td>";
																							$d_Header .= "<td>";
																							$d_Header .= "<input type='text' name='ListOvertime[0".$no_ot."][jml_orang]' id='jml_orango_0".$no_ot."' class='form-control input-md text-center' readonly placeholder='Amount People' value='".$value['jml_orang_']."'>";
																							$d_Header .= "</td>";
																							$d_Header .= "<td>";
																							$d_Header .= "<input type='text' name='ListOvertime[0".$no_ot."][jml_hari]' id='jml_hario_0".$no_ot."' class='form-control input-md text-center numberFull day_ot' readonly placeholder='Amount (Day)' value='".$value['jml_hari_']."'>";
																							$d_Header .= "</td>";
																							$d_Header .= "<td>";
																							$d_Header .= "<input type='text' name='ListOvertime[0".$no_ot."][jml_jam]' id='jml_jamo_0".$no_ot."' class='form-control input-md text-center numberFull jam_ot' readonly placeholder='Amount (Hour)' value='".$total_ot."'>";
																							$d_Header .= "</td>";
																							$d_Header .= "<td>";
																							$d_Header .= "<input type='text' name='ListOvertime[0".$no_ot."][note]' id=noteo_0".$no_ot."' class='form-control input-md' placeholder='Note' value='".$value['note']."'>";
																							$d_Header .= "</td>";
																							$d_Header .= "<td>";
																							$d_Header .= "<input type='text' name='ListOvertime[0".$no_ot."][rate]' id=rateo_0".$no_ot."' class='form-control input-md text-right rate rate_ot' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format(rate('AK00008', 'day', 'akomodasi', $header[0]->region_code)/8)."'>";
																							$d_Header .= "<input type='hidden' name='ListOvertime[0".$no_ot."][rate_unit]' id=rate_unito_0".$no_ot."' class='form-control input-md text-right rate' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format((rate('AK00008', 'day', 'akomodasi', $header[0]->region_code)/8) * $value['jml_hari_'] * $total_ot)."'>";
																							$d_Header .= "</td>";
																							$d_Header .= "<td>";
																							$d_Header .= "<input type='text' name='ListOvertime[0".$no_ot."][total_rate]' id=total_rateo_0".$no_ot."' class='form-control input-md text-right rate sum_ot' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format((rate('AK00008', 'day', 'akomodasi', $header[0]->region_code)/8) * $value['jml_hari_'] * $total_ot * $value['jml_orang_'])."'>";
																							$d_Header .= "</td>";
																						$d_Header .= "</tr>";
																					}
																				}
													$d_Header .=	"</tbody>
																				<tfoot>
																					<tr>
																						<td colspan='6'><b>TOTAL OVERTIME</b></td>
																						<td><input type='text' id='sum_ot' class='form-control input-md text-right' readonly></td>
																					</tr>
																				</tfoot>
																		</table>
																	</div>
																</div>
																<div class='form-group row'>
																<input type='hidden' name='numberHouse' id='numberHouse' value='0'>
																<button type='button' id='add_house' style='min-width:130px; margin-top:10px; margin-bottom:1px; margin-left:10px; float:left;' class='btn btn-success btn-sm'>Add Acomodation & Transportation on Site</button>
																<br><br>
																<div class='box-body'>
																	<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
																			<thead id='head_table'>
																				<tr class='bg-blue'>
																					<th colspan='7'>&nbsp;&nbsp;&nbsp;Acomodation & Transportation on Site</th>
																				</tr>
																				<tr class='bg-purple'>
																					<th class='text-center' style='width: 30%;'>Item Cost</th>
																					<th class='text-center' style='width: 13%;'>Qty</th>
																					<th class='text-center' style='width: 10%;'>Time (Day)</th>
																					<th class='text-center' style='width: 10%;'>Unit</th>
																					<th class='text-center'>Note</th>
																					<th class='text-center' style='width: 9%;'>Cost</th>
																					<th class='text-center' style='width: 9%;'>Total Cost</th>
																				</tr>
																			</thead>
																			<tbody>";
																				$qHouse	 	= "SELECT * FROM akomodasi_new WHERE id_category='2' ORDER BY category ASC, spec ASC";
																				$restHouse	= $this->db->query($qHouse)->result_array();

																				if(!empty($house_)){
																					$no_house = 0;
																					foreach ($house_ as $key => $value) {
																						$no_house++;
																						$d_Header .= 	"<tr>";
																						$d_Header .= 		"<td align='left'>";
																						$d_Header .= "<div class='input-group'>";
																						$d_Header .= "<select name='ListDetailHouse[0".$no_house."][code_group]' id='codeh_0".$no_house."' data-nomor='0".$no_house."' class='chosen_select form-control inline-blockd clSelect houseSelect'>";
																						foreach($restHouse AS $val_vtList => $valx_vtList){
																							$sel1 = ($valx_vtList['code_group'] == $value['code_group'])?'selected':'';
																							$d_Header .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
																						}
																						$d_Header .= 		"</select>";
																						$d_Header .= 		"<span class='input-group-addon cldelete delRowsH'><i class='fa fa-close'></i></span>";
																						$d_Header .= 		"</div>";
																						$d_Header .= 		"</td>";
																						$d_Header .= 		"<td align='left'>";
																						$d_Header .=				"<input type='text' name='ListDetailHouse[0".$no_house."][qty]' id='qtyh_0".$no_house."' class='form-control input-md numberFull qty_house' placeholder='Qty' value='".$value['qty']."'>";
																						$d_Header .= 		"</td>";
																						$d_Header .=		"<td align='left'>";
																						$d_Header .=				"<input type='text' name='ListDetailHouse[0".$no_house."][value]' id='valueh_0".$no_house."' class='form-control input-md numberFull day_house' placeholder='Value' value='".$value['jml_orang']."'>";
																						$d_Header .= 		"</td>";
																						$d_Header .= 		"<td align='left'>";
																						$d_Header .= "<select name='ListDetailHouse[0".$no_house."][satuan]' id='satuanh_0".$no_house."' class='chosen_select form-control inline-blockd clSelect unit_house'>";
																						foreach($restSat AS $val_vtList => $valx_vtList){
																							$sel2 = ($valx_vtList['category_list'] == $value['area'])?'selected':'';
																							$d_Header .= "<option value='".$valx_vtList['category_list']."' $sel2>".strtoupper($valx_vtList['view_'])."</option>";
																						}
																						$d_Header .= 		"</td>";
																						$d_Header .= 		"<td align='left'>";
																						$d_Header .=				"<input type='text' name='ListDetailHouse[0".$no_house."][note]' id='noteh_0".$no_house."' class='form-control input-md' placeholder='Note' value='".$value['note']."'>";
																						$d_Header .= 		"</td>";
																						$d_Header .= 		"<td align='left'>";
																						$d_Header .=				"<input type='text' name='ListDetailHouse[0".$no_house."][rate]' id='rateh_0".$no_house."' class='form-control input-md text-right rate rate_house' readonly placeholder='Cost' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['rate'])."'>";
																						$d_Header .= 		"</td>";
																						$d_Header .= 		"<td align='left'>";
																						$d_Header .=				"<input type='text' name='ListDetailHouse[0".$no_house."][total_rate]' id='total_rateh_0".$no_house."' class='form-control input-md text-right rate sum_house' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
																						$d_Header .= 		"</td>";
																						$d_Header .= 	"</tr>";
																					}
																				}
												$d_Header .=	"</tbody>
																			<tbody id='detail_body_house'></tbody>
																			<tbody id='detail_body_house_empty'>
																				<tr>
																					<td colspan='7'>Add Acomodation & Transportation on Site empty ...</td>
																				</tr>
																			</tbody>
																			<tfoot>
																				<tr>
																					<td colspan='6'><b>TOTAL Acomodation & Transportation on Site</b></td>
																					<td><input type='text' id='sum_house' class='form-control input-md text-right' readonly></td>
																				</tr>
																			</tfoot>
																		</table>
																	</div>
																</div>
																<div class='form-group row'>
																<input type='hidden' name='numberTrans' id='numberTrans' value='0'>
																<button type='button' id='add_trans' style='min-width:130px; margin-top:10px; margin-bottom:1px; margin-left:10px; float:left;' class='btn btn-success btn-sm'>Add OPC to Site Transportation</button>
																<br><br>
																<div class='box-body'>
																	<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
																			<thead id='head_table'>
																				<tr class='bg-blue'>
																					<th colspan='9'>&nbsp;&nbsp;&nbsp;OPC to Site Transportation</th>
																				</tr>
																				<tr class='bg-purple'>
																					<th class='text-center' style='width: 18%;'>Item Cost</th>
																					<th class='text-center' style='width: 12%;'>Transportation</th>
																					<th class='text-center' style='width: 10%;'>Origin</th>
																					<th class='text-center' style='width: 10%;'>Destination</th>
																					<th class='text-center' style='width: 10%;'>Qty MP</th>
																					<th class='text-center' style='width: 10%;'>Round-Trip</th>
																					<th class='text-center'>Note</th>
																					<th class='text-center' style='width: 9%;'>Cost</th>
																					<th class='text-center' style='width: 9%;'>Total Cost</th>
																				</tr>
																			</thead>
																			<tbody>";
																				if(!empty($trans)){
																					$no_trans = 0;
																					foreach ($trans as $key => $value) {
																						$qTransport	 	= "SELECT * FROM list WHERE category='transportasi' AND category_='".$value['category']."' AND flag='N' ORDER BY urut ASC";
																						$restTransx	= $this->db->query($qTransport)->result_array();
																						$ArrTransY = array();
																						foreach($restTransx AS $val => $valx){
																							$ArrTransY[$valx['category_list']] = strtoupper($valx['view_']);
																						}
																						$no_trans++;
																							$d_Header .= 	"<tr>";
																							$d_Header .= 		"<td align='left'  width='10%'>";
																							$d_Header .= "<div class='input-group'>";
																							$d_Header .= "<select name='ListDetailTrans[0".$no_trans."][item_cost]' class='chosen_select form-control inline-blockd clSelect'>";
																							foreach($transx AS $val_vtList => $valx_vtList){
																								$sel1 = ($valx_vtList['category_list'] == $value['category'])?'selected':'';
																							$d_Header .= "<option value='".$valx_vtList['category_list']."' $sel1>".strtoupper($valx_vtList['view_'])."</option>";
																							}
																							$d_Header .= 		"</select>";
																							$d_Header .= 		"<span class='input-group-addon cldelete delRowsT'><i class='fa fa-close'></i></span>";
																							$d_Header .= 		"</div>";
																							$d_Header .= 		"</td>";
																							$d_Header .= 		"<td align='left'>";
																							$d_Header .= "<select name='ListDetailTrans[0".$no_trans."][kendaraan]' class='chosen_select form-control inline-blockd clSelect'>";
																							foreach($restTransx AS $val_vtList => $valx_vtList){
																								$sel1 = ($valx_vtList['category_list'] == $value['spec'])?'selected':'';
																								$d_Header .= "<option value='".$valx_vtList['category_list']."' $sel1>".strtoupper($valx_vtList['view_'])."</option>";
																							}
																							$d_Header .= 		"</select>";
																							$d_Header .= 		"</td>";
																							$d_Header .= 		"<td align='left'>";
																							$d_Header .=				"<input type='text' name='ListDetailTrans[0".$no_trans."][asal]' id='asalt_0".$no_trans."' class='form-control input-md' placeholder='Origin' value='".strtoupper($value['asal'])."'>";
																							$d_Header .= 		"</td>";
																							$d_Header .= 		"<td align='left'>";
																							$d_Header .=				"<input type='text' name='ListDetailTrans[0".$no_trans."][tujuan]' id='tujuant_0".$no_trans."' class='form-control input-md' placeholder='Destination' value='".strtoupper($value['tujuan'])."'>";
																							$d_Header .= 		"</td>";
																							$d_Header .= 		"<td align='left'>";
																							$d_Header .=				"<input type='text' name='ListDetailTrans[0".$no_trans."][value]' id='valuet_0".$no_trans."' class='form-control input-md numberFull' placeholder='Value' value='".$value['jml_orang']."'>";
																							$d_Header .= 		"</td>";
																							$d_Header .= 		"<td align='left'>";
																							$d_Header .=				"<input type='text' name='ListDetailTrans[0".$no_trans."][pulang_pergi]' id='pulang_pergit_0".$no_trans."' class='form-control input-md numberFull pp_trans' placeholder='Round-Trip' value='".$value['pulang_pergi']."'>";
																							$d_Header .= 		"</td>";
																							$d_Header .= 		"<td align='left'>";
																							$d_Header .=				"<input type='text' name='ListDetailTrans[0".$no_trans."][note]' id='notet_0".$no_trans."' class='form-control input-md' placeholder='Note' value='".$value['note']."'>";
																							$d_Header .= 		"</td>";
																							$d_Header .= 		"<td align='left'>";
																							$d_Header .=				"<input type='text' name='ListDetailTrans[0".$no_trans."][rate]' id='ratet_0".$no_trans."' class='form-control input-md text-right rate rate_trans' placeholder='Cost' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['rate'])."'>";
																							$d_Header .= 		"</td>";
																							$d_Header .= 		"<td align='left'>";
																							$d_Header .=				"<input type='text' name='ListDetailTrans[0".$no_trans."][total_rate]' id='total_ratet_0".$no_trans."' class='form-control input-md text-right rate sum_trans' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
																							$d_Header .= 		"</td>";
																							$d_Header .= 	"</tr>";
																					}
																				}
												$d_Header .= "</tbody>
																			<tbody id='detail_body_trans'></tbody>
																			<tbody id='detail_body_trans_empty'>
																				<tr>
																					<td colspan='9'>Add OPC to Site Transportation empty ...</td>
																				</tr>
																			</tbody>
																			<tfoot>
																				<tr>
																					<td colspan='8'><b>TOTAL OPC TO SITE TRANSPORTATION</b></td>
																					<td><input type='text' id='sum_trans' class='form-control input-md text-right' readonly></td>
																				</tr>
																			</tfoot>
																		</table>
																	</div>
																</div>
																<div class='form-group row'>
																<input type='hidden' name='numberEtc' id='numberEtc' value='0'>
																<button type='button' id='add_etc' style='width:130px; margin-top:10px; margin-bottom:1px; margin-left:10px; float:left;' class='btn btn-success btn-sm'>Add Etc</button>
																<br><br>
																<div class='box-body'>
																	<table id='getTab' class='table table-striped table-bordered table-hover table-condensed' width='100%'>
																			<thead id='head_table'>
																				<tr class='bg-blue'>
																					<th colspan='5'>&nbsp;&nbsp;&nbsp;Etc</th>
																				</tr>
																				<tr class='bg-purple'>
																					<th class='text-center' style='width: 30%;'>Item Name</th>
																					<th class='text-center' style='width: 13%;'>Qty</th>
																					<th class='text-center'>Note</th>
																					<th class='text-center' style='width: 9%;'>Cost</th>
																					<th class='text-center' style='width: 9%;'>Total Cost</th>
																				</tr>
																			</thead>
																			<tbody>";
																				$qEtc	 	= "SELECT * FROM akomodasi_new WHERE category='biaya lain lain' ORDER BY category ASC, spec ASC";
																				$etc = $this->db->query($qEtc)->result_array();
																				if(!empty($etc_)){
																					$no_etc = 0;
																					foreach ($etc_ as $key => $value) {
																						$no_etc++;
																						$d_Header .= 	"<tr>";
																						$d_Header .= 		"<td align='left'  width='10%'>";
																						$d_Header .= "<div class='input-group'>";
																						$d_Header .= "<select name='ListDetailEtc[0".$no_etc."][code_group]' data-nomor='0".$no_etc."' class='chosen_select form-control inline-blockd clSelect etcSelect'>";
																						foreach($etc AS $val_vtList => $valx_vtList){
																							$sel1 = ($valx_vtList['code_group'] == $value['code_group'])?'selected':'';
																							$d_Header .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
																						}
																						$d_Header .= 		"</select>";
																						$d_Header .= 		"<span class='input-group-addon cldelete delRowsH'><i class='fa fa-close'></i></span>";
																						$d_Header .= 		"</div>";
																						$d_Header .= 		"</td>";
																						$d_Header .= 		"<td align='left'>";
																						$d_Header .=				"<input type='text' name='ListDetailEtc[0".$no_etc."][qty]' id='qtye_0".$no_etc."' class='form-control input-md numberFull qty_etc' placeholder='Qty' value='".$value['qty']."'>";
																						$d_Header .= 		"</td>";
																						$d_Header .= 		"<td align='left'>";
																						$d_Header .=				"<input type='text' name='ListDetailEtc[0".$no_etc."][note]' id='notee_0".$no_etc."' class='form-control input-md' placeholder='Note' value='".$value['note']."'>";
																						$d_Header .= 		"</td>";
																						$d_Header .= 		"<td align='left'>";
																						$d_Header .=				"<input type='text' name='ListDetailEtc[0".$no_etc."][rate]' id='ratee_0".$no_etc."' class='form-control input-md text-right rate rate_etc' readonly placeholder='Cost' data-decimal='.' data-thousand='' data-precision='0' data-allow-zero='' value='".number_format($value['rate'])."'>";
																						$d_Header .= 		"</td>";
																						$d_Header .= 		"<td align='left'>";
																						$d_Header .=				"<input type='text' name='ListDetailEtc[0".$no_etc."][total_rate]' id='total_ratee_0".$no_etc."' class='form-control input-md text-right rate sum_etc' readonly data-decimal='.' data-thousand='' data-precision='0' data-allow-zero=''>";
																						$d_Header .= 		"</td>";
																						$d_Header .= 	"</tr>";
																					}
																				}
												$d_Header .= "</tbody>
																			<tbody id='detail_body_etc'></tbody>
																			<tbody id='detail_body_etc_empty'>
																				<tr>
																					<td colspan='5'>Add Etc empty ...</td>
																				</tr>
																			</tbody>
																			<tfoot>
																				<tr>
																					<td colspan='4'><b>TOTAL ETC</b></td>
																					<td><input type='text' id='sum_etc' class='form-control input-md text-right' readonly></td>
																				</tr>
																				<tr>
																					<td colspan='4'><b>TOTAL ALL</b></td>
																					<td><input type='text' id='sum_total' class='form-control input-md text-right' readonly></td>
																				</tr>
																			</tfoot>
																		</table>
																	</div>
																</div>
															";


		 echo json_encode(array(
				'header'			=> $d_Header,
		 ));
	}

	public function modalDetail(){
		$project_code = $this->uri->segment(3);
		$tanda = $this->uri->segment(4);
		$tanda2 = $this->uri->segment(5);

		$header 		= $this->db->get_where('project_header', array('project_code'=>$project_code))->result();
		$restDetail 	= $this->db->get_where('project_detail_header', array('project_code'=>$project_code,'deleted'=>'N'))->result_array();

		$detail_bq 	= $this->db->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'project'))->result_array();
		$detail_cus = $this->db->group_by('pekerjaan')->order_by('id','asc')->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'custom'))->result_array();

		$rMeal 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'meal'))->result_array();
		$rHouse 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'house'))->result_array();
		$rTrans 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'trans'))->result_array();
		$rEtc 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'etc'))->result_array();
		$rTesting 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'testing'))->result_array();
		$rSurvey 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'survey'))->result_array();
		$rMDE 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'mde'))->result_array();
		$rCovid 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'covid'))->result_array();

		$restCheck 	= $this->db->get_where('project_budget', array('project_code'=>$project_code))->result_array();

		$qDetailHE	= "SELECT a.*, MAX(a.qty) AS qty_, SUM(b.std_time) AS std_time FROM project_detail_process a INNER JOIN project_detail_header b ON a.project_code_det=b.project_code_det WHERE a.project_code='".$project_code."' AND a.tipe='heavy equipment' AND a.deleted='N' GROUP BY a.code_group, a.qty";
		$rHE 		= $this->db->query($qDetailHE)->result_array();

		// $rVT 		= $this->db->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>'tools','deleted'=>'N'))->result_array();
		$rVT 		= $this->db
							->select('*')
							->from('project_detail_process')
							->where("project_code='$project_code' AND deleted='N' AND (tipe='tools' OR tipe='safety')")
							->get()
							->result_array();
		$rCN		= $this->db->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>'consumable','deleted'=>'N'))->result_array();
		$rMP		= $this->db->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>'man power','deleted'=>'N'))->result_array();


		$data = array(
			'header' => $header,
			'restDetail' => $restDetail,
			'detail_bq' => $detail_bq,
			'detail_cus' => $detail_cus,
			'meal' => $rMeal,
			'house' => $rHouse,
			'trans' => $rTrans,
			'etc' => $rEtc,
			'testing' => $rTesting,
			'survey' => $rSurvey,
			'mde' => $rMDE,
			'covid' => $rCovid,
			'budget' => $restCheck,
			'he' => $rHE,
			'vt' => $rVT,
			'cn' => $rCN,
			'mp' => $rMP,
			'tanda' => $tanda,
			'tanda2' => $tanda2
		);

		$this->load->view('Costing/modalDetail', $data);
	}

	public function print_project(){
		$project_code	= $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$printby			= $data_session['ORI_User']['username'];
		$koneksi			= akses_server_side();

		include 'plusPrint.php';
		$data_url			= base_url();
		$Split_Beda		= explode('/',$data_url);
		$Jum_Beda			= count($Split_Beda);
		$Nama_Beda		= $Split_Beda[$Jum_Beda - 2];

		history('Print Project Costing '.$project_code);
		PrintProject($Nama_Beda, $project_code, $koneksi, $printby);
	}

	public function getPrice(){
		$code_group 	= $this->uri->segment(3);
		$unit 				= strtolower($this->uri->segment(4));
		$region 		= str_replace('_',' ',strtolower($this->uri->segment(5)));
		$category 		= strtolower($this->uri->segment(6));

 		$rate = rate_ak($code_group, $unit, $category, $region);
	 	echo json_encode(array(
	 		'rate' => number_format($rate)
	 	));
  }

	public function update_price_consumable(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$result = $this->db->get_where('project_detail_process',array('project_code'=>$id,'tipe'=>'consumable'))->result_array();
		$ArrUpdate = array();
		foreach ($result as $val => $value) {
			$tot_qty = $value['qty'];
			$get_rate = $this->db->get_where('price_ref', array('code_group'=>$value['code_group'],'LOWER(unit_material)'=>$value['unit'],'sts_price'=>'N','deleted'=>'N'))->result();
			$rate = (!empty($get_rate))?$get_rate[0]->rate:0;
			$ArrUpdate[$val]['id'] 			= $value['id'];
			$ArrUpdate[$val]['rate'] 		= $rate;
			$ArrUpdate[$val]['rate_unit'] 	= $rate * $tot_qty;
			$ArrUpdate[$val]['total_rate'] 	= $rate * $tot_qty;
		}

		// print_r($ArrUpdate); exit;
		$this->db->trans_start();
				$this->db->update_batch('project_detail_process', $ArrUpdate, 'id');
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data failed. Please try again later ...',
				'status'	=> 2,
				'code'	=> $id
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data success. Thanks ...',
				'status'	=> 1,
				'code'	=> $id
			);
			history('Update price consumable & material in costing : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	public function update_price_man_power(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$result = $this->db->get_where('project_detail_process',array('project_code'=>$id,'tipe'=>'man power'))->result_array();
		$ArrUpdate = array();
		foreach ($result as $val => $value) {
			$tot_qty = $value['qty'];
			$tot_hari = $value['jml_hari'];
			$get_price_ref 	= $this->db->get_where('price_ref', array('code_group'=>$value['code_group'], 'deleted'=>'N', 'sts_price'=>'N', 'region'=>'all region'))->result();
			$rate	 		= (!empty($get_price_ref))?$get_price_ref[0]->rate / 22 : 0;
			$rate_ot 		= (!empty($get_price_ref))?$get_price_ref[0]->rate_ot : 0;
			$rate_us 		= (!empty($get_price_ref))?$get_price_ref[0]->rate_us : 0;
			$rate_um 		= (!empty($get_price_ref))?$get_price_ref[0]->rate_um : 0;

			$cal_tot_mp 	= $tot_qty * $tot_hari * $rate;
			$cal_tot_ot 	= $tot_qty * $tot_hari * 4 * $rate_ot;
			$cal_tot_us 	= $tot_qty * $tot_hari * $rate_us;
			$cal_tot_um 	= $tot_qty * $tot_hari * $rate_um;

			$cal_tot 		= $cal_tot_mp + $cal_tot_ot + $cal_tot_us + $cal_tot_um;
			
			$ArrUpdate[$val]['id'] 			= $value['id'];
			$ArrUpdate[$val]['rate'] 		= $rate;
			$ArrUpdate[$val]['rate_ot'] 	= $rate_ot;
			$ArrUpdate[$val]['rate_us'] 	= $rate_us;
			$ArrUpdate[$val]['rate_um'] 	= $rate_um;
			$ArrUpdate[$val]['rate_unit'] 	= $cal_tot_mp;
			$ArrUpdate[$val]['total_rate'] 	= $cal_tot;
		}

		// print_r($ArrUpdate); exit;
		$this->db->trans_start();
				$this->db->update_batch('project_detail_process', $ArrUpdate, 'id');
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data failed. Please try again later ...',
				'status'	=> 2,
				'code'		=> $id
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data success. Thanks ...',
				'status'	=> 1,
				'code'		=> $id
			);
			history('Update price man power & uang makan in costing : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	public function update_price_tools(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$result = $this->db->get_where('project_detail_process',array('project_code'=>$id,'tipe'=>'tools'))->result_array();
		$ArrUpdate = array();
		foreach ($result as $val => $value) {
			$tot_qty = $value['qty'];
			$get_rate = $this->db->get_where('price_ref', array('code_group'=>$value['code_group'],'LOWER(unit_material)'=>$value['unit'],'sts_price'=>'N','deleted'=>'N'))->result();
			$rate = (!empty($get_rate))?$get_rate[0]->rate:0;
			$ArrUpdate[$val]['id'] 			= $value['id'];
			$ArrUpdate[$val]['rate'] 		= $rate;
			$ArrUpdate[$val]['rate_unit'] 	= $rate * $tot_qty;
			$ArrUpdate[$val]['total_rate'] 	= $rate * $tot_qty;
		}

		$result = $this->db->get_where('project_detail_process',array('project_code'=>$id,'tipe'=>'safety'))->result_array();
		// $ArrUpdate = array();
		$beda = 99;
		foreach ($result as $val => $value) {
			$tot_qty = $value['qty'];
			$get_rate = $this->db->get_where('price_ref', array('code_group'=>$value['code_group'],'LOWER(unit_material)'=>$value['unit'],'sts_price'=>'N','deleted'=>'N'))->result();
			$rate = (!empty($get_rate))?$get_rate[0]->rate:0;
			$ArrUpdate[$beda.$val]['id'] 			= $value['id'];
			$ArrUpdate[$beda.$val]['rate'] 		= $rate;
			$ArrUpdate[$beda.$val]['rate_unit'] 	= $rate * $tot_qty;
			$ArrUpdate[$beda.$val]['total_rate'] 	= $rate * $tot_qty;
		}

		// print_r($ArrUpdate); exit;
		$this->db->trans_start();
				$this->db->update_batch('project_detail_process', $ArrUpdate, 'id');
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data failed. Please try again later ...',
				'status'	=> 2,
				'code'	=> $id
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data success. Thanks ...',
				'status'	=> 1,
				'code'	=> $id
			);
			history('Update price tools equipment & savety in costing : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	public function update_price_heavy(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$result = $this->db->get_where('project_detail_process',array('project_code'=>$id,'tipe'=>'heavy equipment'))->result_array();
		$ArrUpdate = array();
		foreach ($result as $val => $value) {
			$tot_qty = $value['qty'];
			$get_rate = $this->db->get_where('price_ref', array('code_group'=>$value['code_group'],'LOWER(unit_material)'=>$value['unit'],'LOWER(region)'=>strtolower($value['area']),'sts_price'=>'N','deleted'=>'N'))->result();
			$rate = (!empty($get_rate))?$get_rate[0]->rate:0;
			$unit = $value['unit'];
			$pengali 	= 1;
			if($unit == 'week'){
				$pengali 	= 6;
			}
			if($unit == 'month'){
				$pengali 	= 22;
			}
			if($unit == 'six_months'){
				$pengali 	= 132;
			}
			if($unit == 'year'){
				$pengali 	= 164;
			}

			$rate2 = 0;
			if($rate > 0){
				$rate2 = $rate / $pengali;
			}
			
			$ArrUpdate[$val]['id'] 			= $value['id'];
			$ArrUpdate[$val]['rate'] 		= $rate2;
			$ArrUpdate[$val]['rate_unit'] 	= $rate2 * $tot_qty;
			$ArrUpdate[$val]['total_rate'] 	= $rate2 * $tot_qty;
		}

		// print_r($ArrUpdate); exit;
		$this->db->trans_start();
				$this->db->update_batch('project_detail_process', $ArrUpdate, 'id');
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data failed. Please try again later ...',
				'status'	=> 2,
				'code'	=> $id
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data success. Thanks ...',
				'status'	=> 1,
				'code'	=> $id
			);
			history('Update price heavy & rental equipment in costing : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	public function update_price_house(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');
		$data = $this->input->post();
		$region_code = strtolower($data['region_code']);

		$result = $this->db->get_where('project_detail_akomodasi',array('project_code'=>$id,'category_ak'=>'house'))->result_array();
		$ArrUpdate = array();
		foreach ($result as $val => $value) {
			$tot_qty = $value['qty'];
			$jumlah = $value['jml_orang'];
			$get_rate = $this->db->get_where('price_ref', array('code_group'=>$value['code_group'],'LOWER(unit_material)'=>$value['area'],'region'=>$region_code,'sts_price'=>'N','deleted'=>'N'))->result();
			$rate = (!empty($get_rate))?$get_rate[0]->rate:0;
			$ArrUpdate[$val]['id'] 			= $value['id'];
			$ArrUpdate[$val]['rate'] 		= $rate;
			$ArrUpdate[$val]['total_unit'] 	= $rate * $tot_qty * $jumlah;
			$ArrUpdate[$val]['total_rate'] 	= $rate * $tot_qty * $jumlah;
		}

		// print_r($ArrUpdate); exit;
		$this->db->trans_start();
				$this->db->update_batch('project_detail_akomodasi', $ArrUpdate, 'id');
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data failed. Please try again later ...',
				'status'	=> 2,
				'code'	=> $id
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data success. Thanks ...',
				'status'	=> 1,
				'code'	=> $id
			);
			history('Update price heavy & rental equipment in costing : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	public function update_price_etc(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');
		$data = $this->input->post();
		$region_code = strtolower($data['region_code']);

		$result = $this->db->get_where('project_detail_akomodasi',array('project_code'=>$id,'category_ak'=>'etc'))->result_array();
		$ArrUpdate = array();
		foreach ($result as $val => $value) {
			$tot_qty = $value['qty'];
			$get_rate = $this->db->get_where('price_ref', array('code_group'=>$value['code_group'],'region'=>$region_code,'sts_price'=>'N','deleted'=>'N'))->result();
			$rate = (!empty($get_rate))?$get_rate[0]->rate:0;
			$ArrUpdate[$val]['id'] 			= $value['id'];
			$ArrUpdate[$val]['rate'] 		= $rate;
			$ArrUpdate[$val]['total_unit'] 	= $rate * $tot_qty;
			$ArrUpdate[$val]['total_rate'] 	= $rate * $tot_qty;
		}

		// print_r($ArrUpdate); exit;
		$this->db->trans_start();
				$this->db->update_batch('project_detail_akomodasi', $ArrUpdate, 'id');
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data failed. Please try again later ...',
				'status'	=> 2,
				'code'	=> $id
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data success. Thanks ...',
				'status'	=> 1,
				'code'	=> $id
			);
			history('Update price Acomodation & Transportation on Site in costing : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	public function update_price_mde(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');
		$data = $this->input->post();
		$region_code = strtolower($data['region_code']);

		$result = $this->db->get_where('project_detail_akomodasi',array('project_code'=>$id,'category_ak'=>'mde'))->result_array();
		$ArrUpdate = array();
		foreach ($result as $val => $value) {
			$jml_orang = $value['jml_orang'];
			$rate = api_get_cost_truck($value['area'], $value['tujuan'], $value['truck']);
			$ArrUpdate[$val]['id'] 			= $value['id'];
			$ArrUpdate[$val]['rate'] 		= $rate;
			$ArrUpdate[$val]['total_unit'] 	= $rate * $jml_orang;
			$ArrUpdate[$val]['total_rate'] 	= $rate * $jml_orang;
		}

		// print_r($ArrUpdate); exit;
		$this->db->trans_start();
				$this->db->update_batch('project_detail_akomodasi', $ArrUpdate, 'id');
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data failed. Please try again later ...',
				'status'	=> 2,
				'code'	=> $id
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data success. Thanks ...',
				'status'	=> 1,
				'code'	=> $id
			);
			history('Update price Mob Demob Equipment in costing : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	public function update_price_survey(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');
		$data = $this->input->post();
		$region_code = strtolower($data['region_code']);

		$result = $this->db->get_where('project_detail_akomodasi',array('project_code'=>$id,'category_ak'=>'survey'))->result_array();
		$ArrUpdate = array();
		foreach ($result as $val => $value) {
			$tot_qty = $value['qty'];
			$jml_orang = $value['jml_orang'];
			$jml_hari = $value['jml_hari'];
			$get_rate = $this->db->get_where('price_ref', array('code_group'=>$value['code_group'],'region'=>$region_code,'sts_price'=>'N','deleted'=>'N'))->result();
			$rate = (!empty($get_rate))?$get_rate[0]->rate:0;

			$rate_t1 		= $rate * $jml_orang;
			$rate_t2 		= $rate * $tot_qty;
			$rate_t 		= ($rate_t1 + $rate_t2) * $jml_hari;

			$ArrUpdate[$val]['id'] 			= $value['id'];
			$ArrUpdate[$val]['rate'] 		= $rate;
			$ArrUpdate[$val]['total_unit'] 	= $rate_t;
			$ArrUpdate[$val]['total_rate'] 	= $rate_t;
		}

		// print_r($ArrUpdate); exit;
		$this->db->trans_start();
				$this->db->update_batch('project_detail_akomodasi', $ArrUpdate, 'id');
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data failed. Please try again later ...',
				'status'	=> 2,
				'code'	=> $id
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data success. Thanks ...',
				'status'	=> 1,
				'code'	=> $id
			);
			history('Update price survey in costing : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	public function update_price_covid(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');
		$data = $this->input->post();
		$region_code = strtolower($data['region_code']);

		$result = $this->db->get_where('project_detail_akomodasi',array('project_code'=>$id,'category_ak'=>'covid'))->result_array();
		$ArrUpdate = array();
		foreach ($result as $val => $value) {
			$orang = $value['jml_orang'];
			$hari = $value['jml_hari'];
			$get_rate = $this->db->get_where('price_ref', array('code_group'=>$value['code_group'],'region'=>$region_code,'sts_price'=>'N','deleted'=>'N'))->result();
			$rate = (!empty($get_rate))?$get_rate[0]->rate:0;
			$ArrUpdate[$val]['id'] 			= $value['id'];
			$ArrUpdate[$val]['rate'] 		= $rate;
			$ArrUpdate[$val]['total_unit'] 	= $rate * $orang * $hari;
			$ArrUpdate[$val]['total_rate'] 	= $rate * $orang * $hari;
		}

		// print_r($ArrUpdate); exit;
		$this->db->trans_start();
				$this->db->update_batch('project_detail_akomodasi', $ArrUpdate, 'id');
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data failed. Please try again later ...',
				'status'	=> 2,
				'code'	=> $id
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Update data success. Thanks ...',
				'status'	=> 1,
				'code'	=> $id
			);
			history('Update price covid in costing : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	//===================================================================================================================
	//========================================SELLING PRICE==============================================================
	//===================================================================================================================

	public function budget(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_Group			= $this->master_model->getArray('groups',array(),'id','name');
		$data = array(
			'title'			=> 'Indeks Of Selling Price',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Selling Price');
		$this->load->view('Costing/budget',$data);
	}

	public function data_side_budget(){
		$this->costing_model->get_json_budget();
	}

	public function edit_budget(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$project_code 		= $data['project_code'];
			// $ListDetailProfit	= $data['DetailProfit'];
			$ListDetailProfit2	= $data['DetailProfit2'];

			$profit			= str_replace(',','',$data['profit']);
			$allowance		= str_replace(',','',$data['allowance']);
			$ed				= str_replace(',','',$data['ed']);
			$interest		= str_replace(',','',$data['interest']);
			$pph			= str_replace(',','',$data['pph']);
			$harga_dia_inch	= str_replace(',','',$data['sp_15']);
			$net_profit		= str_replace(',','',$data['sp_16']);

			//project_detail_bq
			// $ArrProfit = array();
			// foreach($ListDetailProfit AS $val => $valx){
			// 	$ArrProfit[$val]['project_code'] 	= $project_code;
			// 	$ArrProfit[$val]['jenis_profit'] 	= $valx['jenis_profit'];
			// 	$ArrProfit[$val]['persen'] 			= (!empty($valx['persen']))?str_replace(',','',$valx['persen']):NULL;
			// 	$ArrProfit[$val]['cost_2'] 			= (!empty($valx['cost']))?str_replace(',','',$valx['cost']):NULL;
			// 	$ArrProfit[$val]['created_by'] 		= $data_session['ORI_User']['username'];
			// 	$ArrProfit[$val]['created_date'] 	= $dateTime;
			// }

			$ArrProfit2 = array();
			foreach($ListDetailProfit2 AS $val => $valx){

				$selling = (!empty($valx['selling_price']))?str_replace(',','',$valx['selling_price']):NULL;
				if($val == 15){
					$selling = $harga_dia_inch;
				}	
				if($val == 16){
					$selling = $net_profit;
				}
				$ArrProfit2[$val]['project_code'] 	= $project_code;
				$ArrProfit2[$val]['jenis_profit'] 	= $valx['jenis_profit'];
				$ArrProfit2[$val]['total_cost'] 		= (!empty($valx['total_cost']))?str_replace(',','',$valx['total_cost']):NULL;
				$ArrProfit2[$val]['profit'] 			= (!empty($valx['profit']))?str_replace(',','',$valx['profit']):NULL;
				$ArrProfit2[$val]['total_profit'] 	= (!empty($valx['total_profit']))?str_replace(',','',$valx['total_profit']):NULL;
				$ArrProfit2[$val]['allowance'] 		= (!empty($valx['allowance']))?str_replace(',','',$valx['allowance']):NULL;
				$ArrProfit2[$val]['ed'] 				= (!empty($valx['ed']))?str_replace(',','',$valx['ed']):NULL;
				$ArrProfit2[$val]['interest'] 		= (!empty($valx['interest']))?str_replace(',','',$valx['interest']):NULL;
				$ArrProfit2[$val]['pph'] 			= (!empty($valx['pph']))?str_replace(',','',$valx['pph']):NULL;
				$ArrProfit2[$val]['selling_price'] 	= $selling;
				$ArrProfit2[$val]['view_'] 	= $val;
				$ArrProfit2[$val]['created_by'] 		= $data_session['ORI_User']['username'];
				$ArrProfit2[$val]['created_date'] 	= $dateTime;
			}

			// print_r($ArrProfit2);
			// exit;

			//project_header
			$ArrHeader = array(
				'rate_budget' 	=> str_replace(',','',$ListDetailProfit2[14]['selling_price']),
				'profit' 		=> $profit,
				'allowance' 	=> $allowance,
				'ed' 			=> $ed,
				'interest' 		=> $interest,
				'pph' 			=> $pph,
				'harga_dia_inch'=> $harga_dia_inch,
				'net_profit' 	=> $net_profit
			);

			$this->db->trans_start();
				$this->db->where('project_code', $project_code);
				$this->db->update('project_header', $ArrHeader);

				$this->db->delete('project_budget', array('project_code' => $project_code));
				$this->db->insert_batch('project_budget', $ArrProfit2);

			$this->db->trans_complete();


			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> 'Process data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> 'Process data success. Thanks ...',
					'status'	=> 1
				);
				history('Setting selling price code '.$project_code);
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

			$project_code = $this->uri->segment(3);

			$header 	= $this->db->get_where('project_header', array('project_code'=>$project_code))->result();
			$detail_bq 	= $this->db->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'project'))->result_array();
			$detail_cus = $this->db->group_by('pekerjaan')->order_by('id','asc')->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'custom'))->result_array();
			
			$rMeal 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'meal'))->result_array();
			$rHouse 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'house'))->result_array();
			$rTrans 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'trans'))->result_array();
			$rEtc 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'etc'))->result_array();
			$rTesting 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'testing'))->result_array();
			$rSurvey 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'survey'))->result_array();
			$rMDE 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'mde'))->result_array();
			$rCovid 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'covid'))->result_array();

			// sampai check sudah ada atau belum
			$restCheck 	= $this->db->get_where('project_budget', array('project_code'=>$project_code))->result_array();

			$qDetailHE	= "SELECT a.*, MAX(a.qty) AS qty_, SUM(b.std_time) AS jml_hari_ FROM project_detail_process a INNER JOIN project_detail_header b ON a.project_code_det=b.project_code_det WHERE a.project_code='".$project_code."' AND a.tipe='heavy equipment' AND a.deleted='N' GROUP BY a.code_group, a.qty";
			$rHE 		= $this->db->query($qDetailHE)->result_array();

			// $rVT 		= $this->db->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>'tools','deleted'=>'N'))->result_array();
			$rVT 		= $this->db
							->select('*')
							->from('project_detail_process')
							->where("project_code='$project_code' AND deleted='N' AND (tipe='tools' OR tipe='safety')")
							->get()
							->result_array();
			$rCN		= $this->db->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>'consumable','deleted'=>'N'))->result_array();
			$rMP		= $this->db->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>'man power','deleted'=>'N'))->result_array();

			if(empty($restCheck)){
				// $qListBudget 	= "SELECT * FROM list WHERE origin='check budget'  AND category='profit' AND flag='N' ORDER BY urut ASC";
				$qListBudget2 	= "SELECT * FROM list WHERE origin='selling price'  AND category='set profit' AND flag='N' ORDER BY id ASC";
			}
			if(!empty($restCheck)){
				// $qListBudget 	= "SELECT a.category_list, b.persen AS view_, b.rev_budget FROM list a LEFT JOIN project_budget b ON a.category_list=b.jenis_profit WHERE b.project_code='".$project_code."' AND a.origin='check budget'  AND a.category='profit' AND a.flag='N' ORDER BY a.urut ASC";
				$qListBudget2 	= "SELECT a.category_list, a.view_, b.* FROM list a LEFT JOIN project_budget b ON a.view_=b.view_ WHERE b.project_code='".$project_code."' AND a.origin='selling price'  AND a.category='set profit' AND a.flag='N'";
			}
			// $rBudget 			= $this->db->query($qListBudget)->result_array();
			$rBudget2 			= $this->db->query($qListBudget2)->result_array();

			$data = array(
				'title'		=> 'Edit Selling Price',
				'action'	=> 'edit',
				'header' 	=> $header,
				'detail_bq' => $detail_bq,
				'detail_cus' => $detail_cus,
				'meal' 		=> $rMeal,
				'house' 	=> $rHouse,
				'trans' 	=> $rTrans,
				'etc' 		=> $rEtc,
				'testing' 	=> $rTesting,
				'survey' 	=> $rSurvey,
				'mde' 		=> $rMDE,
				'covid' 	=> $rCovid,
				'he' 		=> $rHE,
				'vt' 		=> $rVT,
				'cn' 		=> $rCN,
				'mp' 		=> $rMP,
				// 'list_budget' => $rBudget,
				'list_budget2' => $rBudget2
			);

			$this->load->view('Costing/edit_budget',$data);
		}
	}

	public function get_rate(){
		$code 		= $this->uri->segment(3);
		$category 	= str_replace('_', ' ', $this->uri->segment(4));
		$unit 		= $this->uri->segment(5);
		$time 		= $this->uri->segment(6);
		$qty 		= $this->uri->segment(7);
		$region 	= $this->uri->segment(8);
		if($category == 'man power' OR $category == 'consumable'){
			$region 	= "all region";
		}

		$pengali 	= 1;
		if($unit == 'week'){
			$pengali 	= 6;
		}
		if($unit == 'month'){
			$pengali 	= 22;
		}
		if($unit == 'six_months'){
			$pengali 	= 132;
		}
		if($unit == 'year'){
			$pengali 	= 164;
		}

		$rate = rate($code, $unit, $category, $region) / $pengali;

		echo json_encode(array(
	 		'cost' => number_format($rate),
			'cost_unit' => number_format($rate * $time),
			'total_cost' => number_format($rate * $time * $qty)
	 	));

	}

	public function back_to_engineering(){
		$id = $this->uri->segment(3);
		$no_ipp 		= get_name('project_header','no_ipp','project_code',$id);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$ArrUpdate = array(
			'status' => 'WAITING ESTIMATION PROJECT',
			'approved' => 'N',
			'approved_by' => $data_session['ORI_User']['username'],
			'approved_date' => $dateTime,
			'aju_approved' => 'N',
			'aju_approved_by' => $data_session['ORI_User']['username'],
			'aju_approved_date' => $dateTime
		);

		$ArrUpdate2 = array(
			'status' => 'WAITING ESTIMATION PROJECT'
		);

		$this->db->trans_start();
				$this->db->where('project_code', $id);
				$this->db->update('project_header', $ArrUpdate);

				$this->db->where('no_ipp', $no_ipp);
				$this->db->update('ipp_header', $ArrUpdate2);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Process data failed. Please try again later ...',
				'status'	=> 2
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Process data success. Thanks ...',
				'status'	=> 1
			);
			history('Reject ipp costing to engineering : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	public function approve_to_quotation(){
		$id = $this->uri->segment(3);
		$no_ipp 		= get_name('project_header','no_ipp','project_code',$id);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$ArrUpdate = array(
			'status' => 'PROCESS QUOTATION',
			'cost_approved' => 'Y',
			'cost_approved_by' => $data_session['ORI_User']['username'],
			'cost_approved_date' => $dateTime
		);

		$ArrUpdate2 = array(
			'status' => 'PROCESS QUOTATION'
		);

		$this->db->trans_start();
				$this->db->where('project_code', $id);
				$this->db->update('project_header', $ArrUpdate);

				$this->db->where('no_ipp', $no_ipp);
				$this->db->update('ipp_header', $ArrUpdate2);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Process data failed. Please try again later ...',
				'status'	=> 2
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Process data success. Thanks ...',
				'status'	=> 1
			);
			history('Approve costing to quotation code project : '.$id);
		}

		echo json_encode($Arr_Kembali);


	}

}
