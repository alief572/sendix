<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instalation extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		$this->load->model('instalation_model');

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
			'title'			=> 'Indeks Of Instalation',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Instalation');
		$this->load->view('Instalation/index',$data);
	}

	public function data_side_instalation(){
		$this->instalation_model->get_json_instalation();
	}

	public function add(){
		if($this->input->post()){
			$Arr_Kembali		= array();
			$data				= $this->input->post();
			$data_session		= $this->session->userdata;
			$dateTime			= date('Y-m-d H:i:s');
			// print_r($data);
			// exit;

			$no_ipp			= $data['no_ipp'];
			$project_name	= strtolower($data['project_name']);
			$region_code	= strtolower($data['region_code']);
			$location		= strtolower($data['location']);
			$total_time		= $data['total_time'];
			$tipe			= (!empty($data['tipe']))?json_encode($data['tipe']):'';
			$bq_qty			= $data['bq_qty'];
			$bq_mp			= $data['bq_mp'];
			$bq_ct			= $data['bq_ct'];
			$bq_total		= $data['bq_total'];
			
			$ListDetailBq	= $data['ListDetailBq'];

			if(!empty($data['ListDetail'])){
				$ListDetail		= $data['ListDetail'];
			}
			if(!empty($data['ListDetailHouse'])){
				$ListDetailHouse	= $data['ListDetailHouse'];
			}
			if(!empty($data['ListDetailTrans'])){
				$ListDetailTrans	= $data['ListDetailTrans'];
			}
			if(!empty($data['ListDetailEtc'])){
				$ListDetailEtc		= $data['ListDetailEtc'];
			}
			if(!empty($data['ListDetailTest'])){
				$ListDetailTest		= $data['ListDetailTest'];
			}

			$Ym						= date('ym');
			$Y						= date('y');
			//pengurutan kode
			$srcMtr			= "SELECT MAX(project_code) as maxP FROM project_header WHERE project_code LIKE 'P".$Y."%' ";
			$numrowMtr		= $this->db->query($srcMtr)->num_rows();
			$resultMtr		= $this->db->query($srcMtr)->result_array();
			$angkaUrut2		= $resultMtr[0]['maxP'];
			$urutan2		= (int)substr($angkaUrut2, 5, 4);
			$urutan2++;
			$urut2			= sprintf('%04s',$urutan2);
			$project_code	= "P".$Ym.$urut2;
			$region 		= str_replace('_',' ',$region_code);

			//project_header
			$ArrHeader = array(
				'project_code' 	=> $project_code,
				'no_ipp' 	=> $no_ipp,
				'project_name' 	=> $project_name,
				'region_code' 	=> $region_code,
				'region' 				=> str_replace('_',' ',$region_code),
				'location' 			=> $location,
				'total_time' 		=> $total_time,
				'tipe' 		=> $tipe,
				'bq_qty' 			=> $bq_qty,
				'bq_mp' 			=> $bq_mp,
				'bq_ct' 			=> $bq_ct,
				'bq_total' 		=> $bq_total,
				'created_by' 		=> $data_session['ORI_User']['username'],
				'created_date' 	=> $dateTime
			);
			//project_detail_bq
			$ArrHeaderDetail = array();
			foreach($ListDetailBq AS $val => $valx){
				$ArrHeaderDetail[$val]['project_code'] 	= $project_code;
				$ArrHeaderDetail[$val]['satuan_code'] 	= $valx['satuan_code'];
				$ArrHeaderDetail[$val]['qty'] 					= $valx['qty'];
				$ArrHeaderDetail[$val]['mp'] 					= $valx['mp'];
				$ArrHeaderDetail[$val]['ct'] 					= $valx['ct'];
				$ArrHeaderDetail[$val]['diameter'] 			= $valx['diameter'];
				$ArrHeaderDetail[$val]['cycletime'] 			= $valx['cycletime'];
			}

			if(!empty($data['ListDetailHouse'])){
				$ArrHouse = array();
				foreach($ListDetailHouse AS $val => $valx){
					$restDataHu = $this->db->query("SELECT category, spec FROM akomodasi_new WHERE code_group='".$valx['code_group']."' LIMIT 1 ")->result();
					$ArrHouse[$val]['project_code'] = $project_code;
					$ArrHouse[$val]['category_ak'] 	= 'house';
					$ArrHouse[$val]['code_group'] 	= $valx['code_group'];
					$ArrHouse[$val]['category'] 		= (!empty($restDataHu))?$restDataHu[0]->category:'not found';
					$ArrHouse[$val]['spec'] 				= (!empty($restDataHu))?$restDataHu[0]->spec:'not found';
					$ArrHouse[$val]['qty'] 					= $valx['qty'];
					$ArrHouse[$val]['rate'] 			= rate($valx['code_group'], $valx['satuan'], 'akomodasi', $region_code);
					$ArrHouse[$val]['jml_orang'] 				= $valx['value'];
					$ArrHouse[$val]['area'] 			= $valx['satuan'];
					$ArrHouse[$val]['note'] 				= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailTrans'])){
				$ArrTrans = array();
				foreach($ListDetailTrans AS $val => $valx){
					$ArrTrans[$val]['project_code'] = $project_code;
					$ArrTrans[$val]['category_ak'] 	= 'trans';
					$ArrTrans[$val]['category'] 		= $valx['item_cost'];
					$ArrTrans[$val]['spec'] 		= $valx['kendaraan'];
					$ArrTrans[$val]['asal'] 				= strtolower($valx['asal']);
					$ArrTrans[$val]['tujuan'] 			= strtolower($valx['tujuan']);
					$ArrTrans[$val]['pulang_pergi'] = strtolower($valx['pulang_pergi']);
					$ArrTrans[$val]['jml_orang'] 				= $valx['value'];
					$ArrTrans[$val]['note'] 				= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailEtc'])){
				$ArrEtc = array();
				foreach($ListDetailEtc AS $val => $valx){
					$restDataEtc = $this->db->query("SELECT category, spec FROM akomodasi_new WHERE code_group='".$valx['code_group']."' LIMIT 1 ")->result();
					$ArrEtc[$val]['project_code'] = $project_code;
					$ArrEtc[$val]['category_ak'] 	= 'etc';
					$ArrEtc[$val]['code_group'] 	= $valx['code_group'];
					$ArrEtc[$val]['category'] 		= (!empty($restDataEtc))?$restDataEtc[0]->category:'not found';
					$ArrEtc[$val]['spec'] 				= (!empty($restDataEtc))?$restDataEtc[0]->spec:'not found';
					$ArrEtc[$val]['rate'] 				= rate($valx['code_group'], 'day', 'akomodasi', $region_code);
					$ArrEtc[$val]['qty'] 					= $valx['qty'];
					$ArrEtc[$val]['note'] 				= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailTest'])){
				$ArrTest = array();
				foreach($ListDetailTest AS $val => $valx){
					$ArrTest[$val]['project_code'] 	= $project_code;
					$ArrTest[$val]['category_ak'] 	= 'testing';
					$ArrTest[$val]['spec'] 			= $valx['spec'];
					$ArrTest[$val]['qty'] 			= $valx['qty'];
					$ArrTest[$val]['note'] 			= strtolower($valx['note']);
				}
			}

			//detail
			$ArrIntHead = array();
			$ArrInsert 	= array();
			$ArrInsert4 = array();
			$nomor = 0;
			$no_day=0;
			$no_timeline = 0;
			if(!empty($data['ListDetail'])){
				foreach($ListDetail AS $val => $valx){
					$nomor++;
					$numPlus = sprintf('%03s',$nomor);
					$project_code_det = $project_code.'-'.$numPlus;
					$code_work_detailx = $valx['code_work'].'-'.$numPlus;
					$NmWork = $this->db->query("SELECT category FROM work_header WHERE code_work='".$valx['code_work']."' AND status='N' ")->result();
					
					$timeline = array();
					for ($i=0; $i < $valx['std_time']; $i++) { 
						$no_timeline++;
						$timeline[] = $no_timeline;
					}
					$no_timeline = $no_timeline;

					$ArrIntHead[$val]['project_code'] 		= $project_code;
					$ArrIntHead[$val]['project_code_det'] 	= $project_code_det;
					$ArrIntHead[$val]['code_work'] 			= $valx['code_work'];
					$ArrIntHead[$val]['std_time'] 			= str_replace(',','',$valx['std_time']);
					$ArrIntHead[$val]['category'] 			= $NmWork[0]->category;
					$ArrIntHead[$val]['timeline'] 			= json_encode($timeline);

					$no1=0;
					$no4=0;
					if(!empty($valx['he'])){
						foreach($valx['he'] AS $valvt => $valxvt){
							$restData = $this->db->query("SELECT category, spec FROM heavy_equipment_new WHERE code_group='".$valxvt['code_group']."' LIMIT 1 ")->result();
							if(!empty($restData)){
								$no4++;
								$num_vt_1 		= sprintf('%03s',$nomor);
								$num_vt_2 		= sprintf('%03s',$no1);
								$ArrInsert4[$nomor.$no4]['project_code'] 		= $project_code;
								$ArrInsert4[$nomor.$no4]['project_code_det'] 	= $project_code.'-'.$num_vt_1;
								$ArrInsert4[$nomor.$no4]['code_work_detail_d'] 	= $valx['code_work'].'-'.$num_vt_1.'-'.$num_vt_2;
								$ArrInsert4[$nomor.$no4]['code_work_detail'] 	= $valx['code_work'].'-'.$num_vt_1;
								$ArrInsert4[$nomor.$no4]['code_work'] 			= $valx['code_work'];
								$ArrInsert4[$nomor.$no4]['code_group'] 			= $valxvt['code_group'];
								$ArrInsert4[$nomor.$no4]['category'] 			= $restData[0]->category;
								$ArrInsert4[$nomor.$no4]['spec'] 				= $restData[0]->spec;
								$ArrInsert4[$nomor.$no4]['qty'] 				= str_replace(',','',$valxvt['qty']);
								$ArrInsert4[$nomor.$no4]['tipe'] 				= 'heavy equipment';
								$ArrInsert4[$nomor.$no4]['rate'] 				= rate($valxvt['code_group'], $region, 'vehicle tool', $region_code);
							}
						}
					}

				}
			}

			// print_r($ArrHeader);
			// exit;


			$this->db->trans_start();
				$this->db->insert('project_header', $ArrHeader);
				$this->db->insert_batch('project_detail_bq', $ArrHeaderDetail);
				if(!empty($ArrHouse)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrHouse);
				}
				if(!empty($ArrTrans)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrTrans);
				}
				if(!empty($ArrEtc)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrEtc);
				}
				if(!empty($ArrTest)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrTest);
				}

				$this->db->insert_batch('project_detail_header', $ArrIntHead);
				if(!empty($ArrInsert4)){
					$this->db->insert_batch('project_detail_process', $ArrInsert4);
				}
			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> 'Insert BQ Instalation data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> 'Insert BQ Instalation data success. Thanks ...',
					'status'	=> 1
				);
				history('Insert BQ Instalation code '.$project_code);
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

			$qRegion	= "SELECT * FROM region ORDER BY urut ASC";
			$restRegion	= $this->db->query($qRegion)->result_array();

			$qIPP	= "SELECT no_ipp, nm_customer, project FROM ipp_header WHERE deleted='N' AND status='WAITING ESTIMATION PROJECT' ORDER BY no_ipp ASC";
			$restIPP	= $this->db->query($qIPP)->result_array();

			$list_kend 	= $this->db->get_where('akomodasi_new', array('id_category'=>'2','deleted_date'=>NULL))->result_array();
			$satuan 	= $this->db->order_by('urut','asc')->get_where('list', array('category'=>'tempat tinggal','category_'=>'satuan','flag'=>'N'))->result_array();

			$data = array(
				'title'		=> 'Add Instalasi Project',
				'action'	=> 'add',
				'region'	=> $restRegion,
				'list_kend'	=> $list_kend,
				'satuan'	=> $satuan,
				'no_ipp' 	=> $restIPP
			);
			$this->load->view('Instalation/add',$data);
		}
	}

	public function edit(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data					= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime			= date('Y-m-d H:i:s');

			// print_r($data);
			// exit;

			$project_code	= $data['project_code'];
			$project_name	= strtolower($data['project_name']);
			$region_code	= strtolower($data['region_code']);
			$location		= strtolower($data['location']);
			$total_time		= $data['total_time'];
			$tipe			= (!empty($data['tipe']))?json_encode($data['tipe']):'';
			$bq_qty			= $data['bq_qty'];
			$bq_mp			= $data['bq_mp'];
			$bq_ct			= $data['bq_ct'];
			$bq_total		= $data['bq_total'];
			$day_in_total	= $data['day_in_total'];
			$include_check			= (!empty($data['check_include']))?json_encode($data['check_include']):'';
			$exclude_check			= (!empty($data['check_exclude']))?json_encode($data['check_exclude']):'';
			$include_text			= (!empty($data['text_include']))?json_encode($data['text_include']):'';
			$exclude_text			= (!empty($data['text_exclude']))?json_encode($data['text_exclude']):'';
			
			if(!empty($data['ListDetailBq'])){
				$ListDetailBq	= $data['ListDetailBq'];
			}
			if(!empty($data['ListDetail'])){
				$ListDetail		= $data['ListDetail'];
			}

			if(!empty($data['ListDetailHouse'])){
				$ListDetailHouse	= $data['ListDetailHouse'];
			}
			if(!empty($data['ListDetailTrans'])){
				$ListDetailTrans	= $data['ListDetailTrans'];
			}
			if(!empty($data['ListDetailEtc'])){
				$ListDetailEtc		= $data['ListDetailEtc'];
			}
			if(!empty($data['ListDetailSurvey'])){
				$ListDetailSurvey		= $data['ListDetailSurvey'];
			}
			if(!empty($data['ListDetailTest'])){
				$ListDetailTest		= $data['ListDetailTest'];
			}
			if(!empty($data['ListDetailCovid'])){
				$ListDetailCovid		= $data['ListDetailCovid'];
			}
			if(!empty($data['ListDetailMDE'])){
				$ListDetailMDE		= $data['ListDetailMDE'];
			}

			$Ym						= date('ym');
			$region = str_replace('_',' ',$region_code);

			// print_r($ListDetail);
			// exit;

			//project_header
			$ArrHeader = array(
				'project_code' 	=> $project_code,
				'project_name' 	=> $project_name,
				'region_code' 	=> $region_code,
				'region' 		=> str_replace('_',' ',$region_code),
				'location' 		=> $location,
				'total_time' 	=> $total_time,
				'tipe' 			=> $tipe,
				'bq_qty' 		=> $bq_qty,
				'bq_mp' 		=> $bq_mp,
				'bq_ct' 		=> $bq_ct,
				'bq_total' 		=> $bq_total,
				'include_check' 		=> $include_check,
				'exclude_check' 		=> $exclude_check,
				'include_text' 		=> $include_text,
				'exclude_text' 		=> $exclude_text,
				'day_in_total' 	=> $day_in_total,
				'updated_by' 	=> $data_session['ORI_User']['username'],
				'updated_date' 	=> $dateTime
			);
			//project_detail_bq
			$ArrHeaderDetail = array();
			if(!empty($data['ListDetailBq'])){
				foreach($ListDetailBq AS $val => $valx){
					$ArrHeaderDetail[$val]['project_code'] 	= $project_code;
					$ArrHeaderDetail[$val]['category'] 		= 'project';
					$ArrHeaderDetail[$val]['satuan_code'] 	= $valx['satuan_code'];
					$ArrHeaderDetail[$val]['qty'] 			= $valx['qty'];
					$ArrHeaderDetail[$val]['mp'] 			= $valx['mp'];
					$ArrHeaderDetail[$val]['ct'] 			= $valx['ct'];
					$ArrHeaderDetail[$val]['day_in'] 		= $valx['day_in'];
					$ArrHeaderDetail[$val]['diameter'] 		= $valx['diameter'];
					$ArrHeaderDetail[$val]['diameter2'] 	= $valx['diameter2'];
					$ArrHeaderDetail[$val]['cycletime'] 	= $valx['cycletime'];
				}
			}

			$ArrCustom	= array();
			if(!empty($data['Detail'])){
				foreach($data['Detail'] AS $val => $valx){
					foreach($valx['detail'] AS $val2 => $valx2){
						$ArrCustom[$val2.$val]['project_code'] 		= $project_code;
						$ArrCustom[$val2.$val]['category'] 			= 'custom';
						$ArrCustom[$val2.$val]['pekerjaan'] 		= $valx['pekerjaan'];
						$ArrCustom[$val2.$val]['pekerjaan_detail'] 	= $valx2['pekerjaan_detail'];
						$ArrCustom[$val2.$val]['qty'] 				= str_replace(',','',$valx2['qty']);
						$ArrCustom[$val2.$val]['satuan_code'] 		= $valx2['satuan'];
						$ArrCustom[$val2.$val]['mp'] 				= str_replace(',','',$valx2['mp']);
						$ArrCustom[$val2.$val]['day_in'] 			= str_replace(',','',$valx2['jumlah_hari']);
					}
				}
			}

			if(!empty($data['ListDetailHouse'])){
				$ArrHouse = array();
				foreach($ListDetailHouse AS $val => $valx){
					$restDataAk = $this->db->query("SELECT category, spec FROM akomodasi_new WHERE code_group='".$valx['code_group']."' LIMIT 1 ")->result();
					$ArrHouse[$val]['project_code'] = $project_code;
					$ArrHouse[$val]['category_ak'] 	= 'house';
					$ArrHouse[$val]['code_group'] 	= $valx['code_group'];
					$ArrHouse[$val]['category'] 		= (!empty($restDataAk))?$restDataAk[0]->category:'not found';
					$ArrHouse[$val]['spec'] 				= (!empty($restDataAk))?$restDataAk[0]->spec:'not found';
					$ArrHouse[$val]['rate'] 			= rate($valx['code_group'], $valx['satuan'], 'akomodasi', $region_code);
					$ArrHouse[$val]['qty'] 					= $valx['qty'];
					$ArrHouse[$val]['jml_orang'] 				= $valx['value'];
					$ArrHouse[$val]['area'] 			= $valx['satuan'];
					$ArrHouse[$val]['note'] 				= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailTrans'])){
				$ArrTrans = array();
				foreach($ListDetailTrans AS $val => $valx){
					$ArrTrans[$val]['project_code'] = $project_code;
					$ArrTrans[$val]['category_ak'] 	= 'trans';
					$ArrTrans[$val]['category'] 		= $valx['item_cost'];
					$ArrTrans[$val]['spec'] 		= $valx['kendaraan'];
					$ArrTrans[$val]['asal'] 				= strtolower($valx['asal']);
					$ArrTrans[$val]['tujuan'] 			= strtolower($valx['tujuan']);
					$ArrTrans[$val]['pulang_pergi'] = strtolower($valx['pulang_pergi']);
					$ArrTrans[$val]['jml_orang'] 				= $valx['value'];
					$ArrTrans[$val]['note'] 				= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailEtc'])){
				$ArrEtc = array();
				foreach($ListDetailEtc AS $val => $valx){
					$restDataAk = $this->db->query("SELECT category, spec FROM akomodasi_new WHERE code_group='".$valx['code_group']."' LIMIT 1 ")->result();
					$ArrEtc[$val]['project_code'] = $project_code;
					$ArrEtc[$val]['category_ak'] 	= 'etc';
					$ArrEtc[$val]['code_group'] 	= $valx['code_group'];
					$ArrEtc[$val]['category'] 		= (!empty($restDataAk))?$restDataAk[0]->category:'not found';
					$ArrEtc[$val]['spec'] 				= (!empty($restDataAk))?$restDataAk[0]->spec:'not found';
					$ArrEtc[$val]['rate'] 				= rate($valx['code_group'], 'day', 'akomodasi', $region_code);
					$ArrEtc[$val]['qty'] 					= $valx['qty'];
					$ArrEtc[$val]['note'] 				= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailSurvey'])){
				$ArrSurvey = array();
				foreach($ListDetailSurvey AS $val => $valx){
					$restDataAk = $this->db->query("SELECT category, spec FROM akomodasi_new WHERE code_group='".$valx['code_group']."' LIMIT 1 ")->result();
					$ArrSurvey[$val]['project_code'] 	= $project_code;
					$ArrSurvey[$val]['category_ak'] 	= 'survey';
					$ArrSurvey[$val]['code_group'] 		= $valx['code_group'];
					$ArrSurvey[$val]['category'] 		= (!empty($restDataAk))?$restDataAk[0]->category:'not found';
					$ArrSurvey[$val]['spec'] 			= (!empty($restDataAk))?$restDataAk[0]->spec:'not found';
					$ArrSurvey[$val]['rate'] 			= rate($valx['code_group'], 'day', 'akomodasi', $region_code);
					$ArrSurvey[$val]['jml_orang'] 		= str_replace(',','',$valx['jml_orang']);
					$ArrSurvey[$val]['qty'] 			= str_replace(',','',$valx['qty']);
					$ArrSurvey[$val]['jml_hari'] 		= str_replace(',','',$valx['jml_hari']);
					$ArrSurvey[$val]['note'] 			= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailCovid'])){
				$ArrCovid= array();
				foreach($ListDetailCovid AS $val => $valx){
					$restDataAk = $this->db->query("SELECT category, spec FROM akomodasi_new WHERE code_group='".$valx['code_group']."' LIMIT 1 ")->result();
					$durasi = str_replace(',','',$valx['jml_hari']);
					if($durasi < 1){
						$durasi = 1;
					}
					
					$ArrCovid[$val]['project_code'] 	= $project_code;
					$ArrCovid[$val]['category_ak'] 	= 'covid';
					$ArrCovid[$val]['code_group'] 		= $valx['code_group'];
					$ArrCovid[$val]['category'] 		= (!empty($restDataAk))?$restDataAk[0]->category:'not found';
					$ArrCovid[$val]['spec'] 			= (!empty($restDataAk))?$restDataAk[0]->spec:'not found';
					$ArrCovid[$val]['jml_orang'] 		= str_replace(',','',$valx['jml_orang']);
					$ArrCovid[$val]['jml_hari'] 		= $durasi;
					$ArrCovid[$val]['note'] 			= strtolower($valx['note']);
				}
			}

			if(!empty($data['ListDetailMDE'])){
				$ArrMDE = array();
				foreach($ListDetailMDE AS $val => $valx){
					$rate = 0;
					if($valx['area'] <> 0 AND $valx['tujuan'] <> 0 AND $valx['kendaraan'] <> 0){
						$rate = api_get_cost_truck($valx['area'], $valx['tujuan'], $valx['kendaraan']);
					}
					$restDataAk = $this->db->query("SELECT category, spec FROM akomodasi_new WHERE code_group='".$valx['code_group']."' LIMIT 1 ")->result();
					$ArrMDE[$val]['project_code'] 	= $project_code;
					$ArrMDE[$val]['category_ak'] 	= 'mde';
					$ArrMDE[$val]['code_group'] 		= $valx['code_group'];
					$ArrMDE[$val]['category'] 		= (!empty($restDataAk))?$restDataAk[0]->category:'not found';
					$ArrMDE[$val]['spec'] 			= (!empty($restDataAk))?$restDataAk[0]->spec:'not found';
					$ArrMDE[$val]['jml_orang'] 		= str_replace(',','',$valx['jml_orang']);
					$ArrMDE[$val]['area'] 			= (!empty($valx['area']))?$valx['area']:'';
					$ArrMDE[$val]['tujuan'] 		= (!empty($valx['tujuan']))?$valx['tujuan']:'';
					$ArrMDE[$val]['truck'] 			= (!empty($valx['kendaraan']))?$valx['kendaraan']:'';
					$ArrMDE[$val]['note'] 			= strtolower($valx['note']);
					$ArrMDE[$val]['rate'] 			= $rate;
					$ArrMDE[$val]['total_unit'] 	= str_replace(',','',$valx['jml_orang']) * $rate;
					$ArrMDE[$val]['total_rate'] 	= str_replace(',','',$valx['jml_orang']) * $rate;
				}
			}

			if(!empty($data['ListDetailTest'])){
				$ArrTest = array();
				foreach($ListDetailTest AS $val => $valx){
					$ArrTest[$val]['project_code'] 	= $project_code;
					$ArrTest[$val]['category_ak'] 	= 'testing';
					$ArrTest[$val]['spec'] 			= $valx['spec'];
					$ArrTest[$val]['qty'] 			= $valx['qty'];
					$ArrTest[$val]['note'] 			= strtolower($valx['note']);
				}
			}
			// print_r($ArrHeader);
			// print_r($ArrHeaderDetail);
			// print_r($ArrHeaderAko);
			// exit;
			//detail
			$ArrIntHead = array();
			$ArrInsert 	= array();
			$ArrInsert4 = array();
			$nomor = 0;
			$no_day=0;
			$no_timeline = 0;
			if(!empty($data['ListDetail'])){
				foreach($ListDetail AS $val => $valx){
					$nomor++;
					$numPlus = sprintf('%03s',$nomor);
					$project_code_det = $project_code.'-'.$numPlus;
					$code_work_detailx = $valx['code_work'].'-'.$numPlus;
					$NmWork = $this->db->query("SELECT category FROM work_header WHERE code_work='".$valx['code_work']."' AND status='N' ")->result();
					$nm_work = (!empty($NmWork))?$NmWork[0]->category:'';
					$timeline = array();
					for ($i=0; $i < $valx['std_time']; $i++) { 
						$no_timeline++;
						$timeline[] = $no_timeline;
					}
					$no_timeline = $no_timeline;

					$ArrIntHead[$val]['project_code'] 			= $project_code;
					$ArrIntHead[$val]['project_code_det'] 	= $project_code_det;
					$ArrIntHead[$val]['code_work'] 					= $valx['code_work'];
					$ArrIntHead[$val]['std_time'] 					= str_replace(',','',$valx['std_time']);
					$ArrIntHead[$val]['category'] 					= $nm_work;
					$ArrIntHead[$val]['timeline'] 			= json_encode($timeline);

					$no1=0;
					$no4=0;
					if(!empty($valx['he'])){
						foreach($valx['he'] AS $valvt => $valxvt){
							
							$restData = $this->db->query("SELECT category, spec FROM heavy_equipment_new WHERE code_group='".$valxvt['code_group']."' LIMIT 1 ")->result();
							if(!empty($restData)){
								$no4++;
								$num_vt_1 		= sprintf('%03s',$nomor);
								$num_vt_2 		= sprintf('%03s',$no1);
								$ArrInsert4[$nomor.$no4]['project_code'] 		= $project_code;
								$ArrInsert4[$nomor.$no4]['project_code_det'] 	= $project_code.'-'.$num_vt_1;
								$ArrInsert4[$nomor.$no4]['code_work_detail_d'] 	= $valx['code_work'].'-'.$num_vt_1.'-'.$num_vt_2;
								$ArrInsert4[$nomor.$no4]['code_work_detail'] 	= $valx['code_work'].'-'.$num_vt_1;
								$ArrInsert4[$nomor.$no4]['code_work'] 			= $valx['code_work'];
								$ArrInsert4[$nomor.$no4]['code_group'] 			= $valxvt['code_group'];
								$ArrInsert4[$nomor.$no4]['category'] 			= $restData[0]->category;
								$ArrInsert4[$nomor.$no4]['spec'] 				= $restData[0]->spec;
								$ArrInsert4[$nomor.$no4]['qty'] 				= str_replace(',','',$valxvt['qty']);
								$ArrInsert4[$nomor.$no4]['jml_hari'] 			= str_replace(',','',$valxvt['durasi']);
								$ArrInsert4[$nomor.$no4]['tipe'] 				= 'heavy equipment';
								$ArrInsert4[$nomor.$no4]['unit'] 				= 'month';
								$ArrInsert4[$nomor.$no4]['area'] 				= strtoupper($region_code);
								$ArrInsert4[$nomor.$no4]['rate'] 				= rate($valxvt['code_group'], $region, 'vehicle tool', $region_code);
							}
						}
					}

				}
			}

			// print_r($ArrHeader);
			// exit;


			$this->db->trans_start();

				$this->db->delete('project_detail_bq', array('project_code' => $project_code));
				$this->db->delete('project_detail_akomodasi', array('project_code' => $project_code, 'category_ak <>' => 'meal'));
				$this->db->delete('project_detail_header', array('project_code' => $project_code));
				$this->db->delete('project_detail_process', array('project_code' => $project_code, 'tipe' => 'heavy equipment'));


				$this->db->where('project_code', $project_code);
				$this->db->update('project_header', $ArrHeader);

				if(!empty($ArrHeaderDetail)){
					$this->db->insert_batch('project_detail_bq', $ArrHeaderDetail);
				}
				if(!empty($ArrCustom)){
					$this->db->insert_batch('project_detail_bq', $ArrCustom);
				}
				if(!empty($ArrHouse)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrHouse);
				}
				if(!empty($ArrTrans)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrTrans);
				}
				if(!empty($ArrEtc)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrEtc);
				}
				if(!empty($ArrSurvey)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrSurvey);
				}
				if(!empty($ArrCovid)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrCovid);
				}
				if(!empty($ArrMDE)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrMDE);
				}
				if(!empty($ArrTest)){
					$this->db->insert_batch('project_detail_akomodasi', $ArrTest);
				}
				if(!empty($ArrIntHead)){
					$this->db->insert_batch('project_detail_header', $ArrIntHead);
				}
				if(!empty($ArrInsert4)){
					$this->db->insert_batch('project_detail_process', $ArrInsert4);
				}
			$this->db->trans_complete();


			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> 'Edit BQ Instalation data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> 'Edit BQ Instalation data success. Thanks ...',
					'status'	=> 1
				);
				history('Edit BQ Instalation code '.$project_code);
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

			$restHeader = $this->db->get_where('project_header', array('project_code'=>$project_code))->result();
			$restDetail = $this->db->get_where('project_detail_header', array('project_code'=>$project_code,'deleted'=>'N'))->result_array();
			$restDetBQ 	= $this->db->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'project'))->result_array();
			$restCustom = $this->db->group_by('pekerjaan')->order_by('id','asc')->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'custom'))->result_array();

			//list region
			$qRegion		= "SELECT * FROM region ORDER BY urut ASC";
			$restRegion	= $this->db->query($qRegion)->result_array();

			//list satuan
			$qSatuan		= "SELECT * FROM satuan_bq_project";
			$restSatuan	= $this->db->query($qSatuan)->result_array();

			$qWork		= "SELECT * FROM work_header WHERE deleted_date IS NULL ORDER BY category ASC";
			$restWork	= $this->db->query($qWork)->result_array();

			$qHouse	 	= "SELECT * FROM akomodasi_new WHERE id_category='2' AND deleted = 'N' ORDER BY category ASC, spec ASC";
			$restHouse	= $this->db->query($qHouse)->result_array();

			$qSat	 	= "SELECT * FROM list WHERE category='tempat tinggal' AND category_='satuan' AND flag='N' ORDER BY urut ASC";
			$restSat = $this->db->query($qSat)->result_array();

			$qTransport	 	= "SELECT * FROM list WHERE category='transportasi' AND category_='item cost' AND flag='N' ORDER BY urut ASC";
			$restTransx	= $this->db->query($qTransport)->result_array();

			$qDetailMeal 	= "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='meal' AND deleted='N'";
			$rMeal 				= $this->db->query($qDetailMeal)->result_array();

			$qDetailOT 	= "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='overtime' AND deleted='N'";
			$rOT 			= $this->db->query($qDetailOT)->result_array();

			$qDetailHouse = "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='house' AND deleted='N'";
			$rHouse 			= $this->db->query($qDetailHouse)->result_array();

			$qDetailTrans = "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='trans' AND deleted='N' ORDER BY spec ASC";
			$rTrans 			= $this->db->query($qDetailTrans)->result_array();

			$qDetailEtc 	= "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='etc' AND deleted='N'";
			$rEtc 				= $this->db->query($qDetailEtc)->result_array();

			$qDetailSurvey 	= "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='survey' AND deleted='N'";
			$rSurvey 				= $this->db->query($qDetailSurvey)->result_array();

			$qDetailTest 	= "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='testing' AND deleted='N'";
			$rTest 				= $this->db->query($qDetailTest)->result_array();

			$qDetailCovid 	= "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='covid' AND deleted='N'";
			$rCovid 				= $this->db->query($qDetailCovid)->result_array();

			$qDetailMDE 	= "SELECT * FROM project_detail_akomodasi WHERE project_code='".$project_code."' AND category_ak='mde' AND deleted='N'";
			$rMDE 				= $this->db->query($qDetailMDE)->result_array();

			$unit				= $this->db->query("SELECT * FROM list WHERE origin='con_nonmat' AND category='satuan' ORDER BY urut ASC")->result_array();
			
			$list_kend 		= $this->db->order_by('spec','asc')->get_where('akomodasi_new', array('id_category'=>'2','deleted_date'=>NULL))->result_array();
			$list_etc 		= $this->db->order_by('spec','asc')->get_where('akomodasi_new', array('id_category'=>'1','deleted_date'=>NULL))->result_array();
			$list_survey 	= $this->db->order_by('spec','asc')->get_where('akomodasi_new', array('id_category'=>'5','deleted_date'=>NULL))->result_array();
			$list_covid 	= $this->db->order_by('spec','asc')->get_where('akomodasi_new', array('id_category'=>'6','deleted_date'=>NULL))->result_array();
			$list_mde 		= $this->db->order_by('spec','asc')->get_where('akomodasi_new', array('id_category'=>'7','deleted_date'=>NULL))->result_array();
			
			$list_include 	= $this->db->get_where('include_exclude', array('category'=>'include','deleted_date'=>NULL))->result_array();
			$list_exclude 	= $this->db->get_where('include_exclude', array('category'=>'exclude','deleted_date'=>NULL))->result_array();

			$data = array(
				'title'			=> 'Edit Instalasi Project',
				'action'		=> 'edit',
				'region'		=> $restRegion,
				'satuan'		=> $restSatuan,
				'work'			=> $restWork,
				'header'		=> $restHeader,
				'detail'		=> $restDetail,
				'detail_bq'		=> $restDetBQ,
				'detail_custom'	=> $restCustom,
				'meal' 			=> $rMeal,
				'overtime' 		=> $rOT,
				'house_' 		=> $rHouse,
				'trans' 		=> $rTrans,
				'etc_' 			=> $rEtc,
				'survey_' 		=> $rSurvey,
				'test_' 		=> $rTest,
				'covid_' 		=> $rCovid,
				'mde_' 			=> $rMDE,
				'house' 		=> $restHouse,
				'sat' 			=> $restSat,
				'transx' 		=> $restTransx,
				'unit' 			=> $unit,
				'list_kend' 	=> $list_kend,
				'list_etc' 		=> $list_etc,
				'list_survey' 	=> $list_survey,
				'list_covid' 	=> $list_covid,
				'list_mde' 		=> $list_mde,
				'list_include' 		=> $list_include,
				'list_exclude' 		=> $list_exclude

			);
			$this->load->view('Instalation/edit',$data);
		}
	}

	public function list_bq_project(){
	   	$query	 	= "SELECT * FROM satuan_bq_project";
	  	$Q_result	= $this->db->query($query)->result();
	  	$option 	= "";
	  	foreach($Q_result as $row)	{
		   $option .= "<option value='".$row->satuan_code."'>".$row->satuan_view."</option>";
	   	}
		echo json_encode(array(
			'option' => $option
		));
   }

	 public function list_work($tipe=null){
 	   	$Q_result	= $this->db->order_by('category','ASC')->get_where('work_header', array('status'=>'N'))->result();
 	  	$option 	= "<option value='0'>Select an Option</option>";
 	  	foreach($Q_result as $row)	{
 		   $option .= "<option value='".$row->code_work."'>".ucwords(strtolower($row->category))."</option>";
 	   	}
 		echo json_encode(array(
 			'option' => $option
 		));
  }

	//tempat tinggal
	public function list_tempat_tinggal(){
			$query	 	= "SELECT * FROM akomodasi_new WHERE category='tempat tinggal dan kendaraan' ORDER BY category ASC, spec ASC";
			$Q_result	= $this->db->query($query)->result();
			$option 	= "<option value='0'>Select an Option</option>";
			// $option 	= "";
			foreach($Q_result as $row)	{
			$option .= "<option value='".$row->code_group."'>".strtoupper($row->spec)."</option>";
			}
		 echo json_encode(array(
			 'option' => $option
		 ));
 	}

	 //satuan
	public function list_satuan(){
		$query	 	= "SELECT * FROM list WHERE category='tempat tinggal' AND category_='satuan' AND flag='N' ORDER BY urut ASC";
		$Q_result	= $this->db->query($query)->result();
		// $option 	= "<option value='0'>Select an Option</option>";
		$option 	= "";
		foreach($Q_result as $row)	{
		 $option .= "<option value='".$row->category_list."'>".strtoupper(strtolower($row->view_))."</option>";
		}
		echo json_encode(array(
			'option' => $option
		));
 	}

	 //tiket
	public function list_tiket(){
		$query	 	= "SELECT * FROM list WHERE category='transportasi' AND category_='item cost' AND flag='N' ORDER BY urut ASC";
		$Q_result	= $this->db->query($query)->result();
		$option 	= "<option value='0'>Select an Option</option>";
		// $option 	= "";
		foreach($Q_result as $row)	{
		 $option .= "<option value='".$row->category_list."'>".strtoupper(strtolower($row->view_))."</option>";
		}
		echo json_encode(array(
			'option' => $option
		));
	}

	//sewa kendaraan
	public function list_sewa_kendaraan(){
		$cty = $this->uri->segment(3);
		$query	 	= "SELECT * FROM list WHERE category='transportasi' AND category_='".$cty."' AND flag='N' ORDER BY urut ASC";
		$Q_result	= $this->db->query($query)->result();
		$option 	= "<option value='0'>Select an Option</option>";
		foreach($Q_result as $row)	{
			$option .= "<option value='".$row->category_list."'>".strtoupper(strtolower($row->view_))."</option>";
		}
		echo json_encode(array(
		'option' => $option
		));
	}

	//etc
	public function list_etc(){
		$query	 	= "SELECT * FROM akomodasi_new WHERE id_category='1' AND deleted='N' ORDER BY category ASC, spec ASC";
		$Q_result	= $this->db->query($query)->result();
		$option 	= "<option value='0'>Select an Option</option>";
		foreach($Q_result as $row)	{
			$option .= "<option value='".$row->code_group."'>".strtoupper($row->spec)."</option>";
		}
		echo json_encode(array(
		'option' => $option
		));
	}

	//survey
	public function list_survey(){
		$query	 	= "SELECT * FROM akomodasi_new WHERE id_category='5' AND deleted='N' ORDER BY category ASC, spec ASC";
		$Q_result	= $this->db->query($query)->result();
		$option 	= "<option value='0'>Select an Option</option>";
		foreach($Q_result as $row)	{
			$option .= "<option value='".$row->code_group."'>".strtoupper($row->spec)."</option>";
		}
		echo json_encode(array(
		'option' => $option
		));
	}

	//covid
	public function list_covid(){
		$query	 	= "SELECT * FROM akomodasi_new WHERE id_category='6' AND deleted='N' ORDER BY category ASC, spec ASC";
		$Q_result	= $this->db->query($query)->result();
		$option 	= "<option value='0'>Select an Option</option>";
		foreach($Q_result as $row)	{
			$option .= "<option value='".$row->code_group."'>".strtoupper($row->spec)."</option>";
		}
		echo json_encode(array(
		'option' => $option
		));
	}

	//mde
	public function list_mde(){
		$query	 	= "SELECT * FROM akomodasi_new WHERE id_category='7' AND deleted='N' ORDER BY category ASC, spec ASC";
		$Q_result	= $this->db->query($query)->result();
		$option 	= "<option value='0'>Select an Option</option>";
		foreach($Q_result as $row)	{
			$option .= "<option value='".$row->code_group."'>".strtoupper($row->spec)."</option>";
		}
		echo json_encode(array(
		'option' => $option
		));
	}

	public function get_satuan_mde($id=null){
		$get_unit	= $this->db->select('unit')->get_where('akomodasi_new',array('code_group'=>$id))->result();
		$unit 		= get_name('unit','unit','id',$get_unit[0]->unit);
		echo json_encode(array(
			'unit' => $unit
		));
	}

	public function list_work_det(){
			$code_work 	= $this->uri->segment(3);
			$nomor 		= $this->uri->segment(4);

			//list
			// $consumable	= $this->db->query("SELECT * FROM con_nonmat_new ORDER BY category ASC")->result_array();
			// $man_power	= $this->db->query("SELECT a.code_group, a.spec, b.category FROM man_power_new a LEFT JOIN man_power_category b ON a.category=b.id WHERE a.deleted='N' ORDER BY b.category ASC, a.spec ASC")->result_array();
			// $vehicle	= $this->db->query("SELECT * FROM vehicle_tool_new ORDER BY category ASC")->result_array();
			$heavy		= $this->db->query("SELECT * FROM heavy_equipment_new ORDER BY category ASC")->result_array();
			$location	= $this->db->query("SELECT * FROM region ORDER BY urut ASC")->result_array();
			$unit		= $this->db->query("SELECT * FROM list WHERE origin='con_nonmat' AND category='satuan' ORDER BY urut ASC")->result_array();

			$restDet1 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'heavy equipment','deleted'=>'N'))->result_array();
			// $restDet2 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'tools','deleted'=>'N'))->result_array();
			// $restDet3 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'consumable','deleted'=>'N'))->result_array();
			// $restDet4 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'man power','deleted'=>'N'))->result_array();

		 	$query	 	= "SELECT * FROM work_detail WHERE code_work='".$code_work."' AND deleted='N'";
			$get_work	= $this->db->select('total_time')->get_where('work_header', array('code_work'=>$code_work))->result();
			$std_time 	= (!empty($get_work))?$get_work[0]->total_time:0;
			$no = 0;
			$Rowx = "";
			$RowxMeal = "";
			$RowxOvertime = "";
			$SumTP = 0;
			$Rowx .= "<tr id='tr_".$nomor."'>";
				$Rowx .= "<td width='100%' colspan='2'>";
					$Rowx .= "<table id='my-grid' class='table table-striped table-bordered table-hover table-condensed' width='100%'>";
						$Rowx .= "<tr class='bg-purple'>";
							$Rowx .= "<th class='text-center' width='100%'>Heavy Equipment</th>";
							// $Rowx .= "<th class='text-center' width='50%'>Tools Equipment</th>";
						$Rowx .= "</tr>";
						$Rowx .= "<tr class='tr_".$nomor."'>";
							$Rowx .= "<td>";
								$num1 = 0;
								foreach($restDet1 AS $val_vt => $valx_vt){
									$num1++;
									$Rowx .= "<div style='margin-bottom: 6px;'>";
										$Rowx .= "<div class='input-group'>";
										$Rowx .= "<select name='ListDetail[".$nomor."][he][".$num1."][code_group]' class='chosen_select form-control he_".$nomor."_".$no."_".$num1."'>";
										foreach($heavy AS $val_vtList => $valx_vtList){
											$sel1 = ($valx_vtList['code_group'] == $valx_vt['code_group'])?'selected':'';
											$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
										}
										$Rowx .= "</select>";
										$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
										$Rowx .= "<input type='text' name='ListDetail[".$nomor."][he][".$num1."][qty]' class='form-control widCtr autoNumeric0' placeholder='Qty'>";
										$Rowx .= "<input type='text' name='ListDetail[".$nomor."][he][".$num1."][durasi]' class='form-control widCtr autoNumeric' placeholder='Durasi'>";
										$Rowx .= "</div>";
									$Rowx .= "</div>";
								}
								//add komponent
								$Rowx .= "<div id='he_add_".$nomor."_".$no."'></div>";
								$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num1' data-tanda='he_add_' data-tanda2='new'>Add Heavy Equipment</button></div>";
							$Rowx .= "</td>";
							// $Rowx .= "<td>";
							// 	$num1 = 0;
							// 	foreach($restDet2 AS $val_vt => $valx_vt){
							// 		$num1++;
							// 		$Rowx .= "<div style='margin-bottom: 6px;'>";
							// 			$Rowx .= "<div class='input-group'>";
							// 			$Rowx .= "<select name='ListDetail[".$nomor."][vt][".$num1."][code_group]' class='chosen_select form-control vt_".$nomor."_".$no."_".$num1."'>";
							// 			foreach($vehicle AS $val_vtList => $valx_vtList){
							// 				$sel1 = ($valx_vtList['code_group'] == $valx_vt['code_group'])?'selected':'';
							// 				$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
							// 			}
							// 			$Rowx .= "</select>";
							// 			$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
							// 			$Rowx .= "<input type='text' name='ListDetail[".$nomor."][vt][".$num1."][qty]' class='form-control widCtr autoNumeric0' placeholder='Qty'>";
							// 			$Rowx .= "</div>";
							// 		$Rowx .= "</div>";
							// 	}
							// 	//add komponent
							// 	$Rowx .= "<div id='vt_add_".$nomor."_".$no."'></div>";
							// 	$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num1' data-tanda='vt_add_' data-tanda2='new'>Add Tools Equipment</button></div>";
							// $Rowx .= "</td>";
						$Rowx .= "</tr>";
						// $Rowx .= "<tr class='bg-purple'>";
						// 	$Rowx .= "<th class='text-center'>Consumable & APD</th>";
						// 	$Rowx .= "<th class='text-center'>Man Power</th>";
						// $Rowx .= "</tr>";
						// $Rowx .= "<tr class='tr_".$nomor."'>";
						// 	$Rowx .= "<td>";
						// 		//Consumable
						// 		$num2 = 0;
						// 		foreach($restDet3 AS $val_cn => $valx_cn){
						// 			$num2++;
						// 			$Rowx .= "<div style='margin-bottom: 6px;'>";
						// 			$Rowx .= "<div class='input-group'>";
						// 			$Rowx .= "<select name='ListDetail[".$nomor."][cn][".$num2."][code_group]' class='chosen_select form-control inline-blockd cn_".$nomor."_".$no."_".$num2."''>";
						// 			foreach($consumable AS $val_vtList => $valx_vtList){
						// 				$sel2 = ($valx_vtList['code_group'] == $valx_cn['code_group'])?'selected':'';
						// 				$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel2>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
						// 			}
						// 			$Rowx .= "</select>";
						// 			$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
						// 			$Rowx .= "<input type='text' name='ListDetail[".$nomor."][cn][".$num2."][qty]' class='form-control widCtr autoNumeric0' placeholder='Qty'>";
						// 			$Rowx .= "<span class='input-group-addon'></span>";
						// 			$Rowx .= "<select name='ListDetail[".$nomor."][cn][".$num2."][unit]' class='chosen_select form-control inline-block widCtrx'>";
						// 			foreach($unit AS $val_unit => $valx_unit){
						// 				$Rowx .= "<option value='".$valx_unit['category_list']."' >".strtoupper($valx_unit['view_'])."</option>";
						// 			}
						// 			$Rowx .= "</select>";
						// 			$Rowx .= "</div>";
						// 			$Rowx .= "</div>";
						// 		}
						// 		//add komponent
						// 		$Rowx .= "<div id='cn_add_".$nomor."_".$no."'></div>";
						// 		$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num2' data-tanda='cn_add_' data-tanda2='new'>Add Consumable</button></div>";

						// 	$Rowx .= "</td>";
						// 	$Rowx .= "<td>";
						// 		//Man Power
						// 		$num3 = 0;
						// 		foreach($restDet4 AS $val_vt => $valx_vt){
						// 			$num3++;
						// 			$Rowx .= "<div style='margin-bottom: 6px;'>";
						// 			$Rowx .= "<div class='input-group'>";
						// 			$Rowx .= "<select name='ListDetail[".$nomor."][mp][".$num3."][code_group]' class='chosen_select form-control inline-block mp_".$nomor."_".$no."_".$num3."'>";
						// 			foreach($man_power AS $val_vtList => $valx_vtList){
						// 				$sel1 = ($valx_vtList['code_group'] == $valx_vt['code_group'])?'selected':'';
						// 				$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
						// 			}
						// 			$Rowx .= "</select>";
						// 			$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
						// 			$Rowx .= "<input type='text' name='ListDetail[".$nomor."][mp][".$num3."][qty]' data-no1='".$nomor."' data-no2='".$num3."' class='form-control widCtr chMPQty autoNumeric0' placeholder='Qty'>";
						// 			$Rowx .= "</div>";
						// 			$Rowx .= "</div>";

						// 			//Meal
						// 			$RowxMeal .= "<tr>";
						// 				$RowxMeal .= "<td width='30%'>";
						// 				$RowxMeal .= "<input type='hidden' name='ListMeal[".$nomor.$num3."][code_group]' id='code_groupm_".$nomor."_".$num3."' class='form-control input-md' value='".$valx_vt['code_group']."'>";
						// 				$RowxMeal .= "<input type='text' name='ListMeal[".$nomor.$num3."][spec]' id='specm_".$nomor."_".$num3."' class='form-control input-md' readonly value='".getMP($valx_vt['code_group'],'man_power_new')."'>";
						// 				$RowxMeal .= "</td>";
						// 				$RowxMeal .= "<td width='13%'>";
						// 				$RowxMeal .= "<select name='ListMeal[".$nomor.$num3."][area]' class='chosen_select form-control inline-block'>";
						// 				foreach($location AS $valL => $valxL){
						// 					$RowxMeal .= "<option value='".$valxL['region_code']."'>".strtoupper($valxL['region'])."</option>";
						// 				}
						// 				$RowxMeal .= "</select>";
						// 				$RowxMeal .= "</td>";
						// 				$RowxMeal .= "<td width='12%'>";
						// 				$RowxMeal .= "<input type='text' name='ListMeal[".$nomor.$num3."][jml_orang]' id='jml_orangm_".$nomor."_".$num3."' readonly class='form-control input-md' placeholder='Amount People'>";
						// 				$RowxMeal .= "</td>";
						// 				$RowxMeal .= "<td width='12%'>";
						// 				$RowxMeal .= "<input type='text' name='ListMeal[".$nomor.$num3."][jml_hari]' id='jml_harim_".$nomor."_".$num3."' readonly class='form-control input-md dayT_".$nomor."' placeholder='Amount (Day)' value='".$SumTP."'>";
						// 				$RowxMeal .= "</td>";
						// 				$RowxMeal .= "<td>";
						// 				$RowxMeal .= "<input type='text' name='ListMeal[".$nomor.$num3."][note]' id=notem_".$nomor."_".$num3."' class='form-control input-md' placeholder='Note'>";
						// 				$RowxMeal .= "</td>";
						// 				$RowxMeal .= "<td align='center' width='6%'>";
						// 				$RowxMeal .=		"<button type='button' disabled style='min-width:70px;' class='btn btn-danger btn-sm aDel' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
						// 				$RowxMeal .= "</td>";
						// 			$RowxMeal .= "</tr>";

						// 			//Overtime
						// 			$RowxOvertime .= "<tr>";
						// 				$RowxOvertime .= "<td width='30%'>";
						// 				$RowxOvertime .= "<input type='hidden' name='ListOvertime[".$nomor.$num3."][code_group]' id='code_groupo_".$nomor."_".$num3."' class='form-control input-md' value='".$valx_vt['code_group']."'>";
						// 				$RowxOvertime .= "<input type='text' name='ListOvertime[".$nomor.$num3."][spec]' id='speco_".$nomor."_".$num3."' class='form-control input-md' readonly value='".getMP($valx_vt['code_group'],'man_power_new')."'>";
						// 				$RowxOvertime .= "</td>";
						// 				$RowxOvertime .= "<td width='13%'>";
						// 				$RowxOvertime .= "<input type='text' name='ListOvertime[".$nomor.$num3."][jml_orang]' id='jml_orango_".$nomor."_".$num3."' readonly class='form-control input-md' placeholder='Amount People'>";
						// 				$RowxOvertime .= "</td>";
						// 				$RowxOvertime .= "<td width='12%'>";
						// 				$RowxOvertime .= "<input type='text' name='ListOvertime[".$nomor.$num3."][jml_hari]' id='jml_hario_".$nomor."_".$num3."' readonly class='form-control input-md dayT_".$nomor."' placeholder='Amount (Day)'  value='".$SumTP."'>";
						// 				$RowxOvertime .= "</td>";
						// 				$RowxOvertime .= "<td width='12%'>";
						// 				$RowxOvertime .= "<input type='text' name='ListOvertime[".$nomor.$num3."][jml_jam]' id='jml_jamo_".$nomor."_".$num3."' class='form-control input-md' placeholder='Amount (Hour)'>";
						// 				$RowxOvertime .= "</td>";
						// 				$RowxOvertime .= "<td>";
						// 				$RowxOvertime .= "<input type='text' name='ListOvertime[".$nomor.$num3."][note]' id=noteo_".$nomor."_".$num3."' class='form-control input-md' placeholder='Note'>";
						// 				$RowxOvertime .= "</td>";
						// 				$RowxOvertime .= "<td align='center' width='6%'>";
						// 				$RowxOvertime .=		"<button type='button' disabled style='min-width:70px;' class='btn btn-danger btn-sm aDel' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
						// 				$RowxOvertime .= "</td>";
						// 			$RowxOvertime .= "</tr>";
						// 		}
						// 		//add komponent
						// 		$Rowx .= "<div id='mp_add_".$nomor."_".$no."'></div>";
						// 		$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num3' data-tanda='mp_add_' data-tanda2='new'>Add Man Power</button></div>";

						// 	$Rowx .= "</td>";
						// $Rowx .= "</tr>";
					$Rowx .= "</table>";
				$Rowx .= "</td>";
			$Rowx .= "</tr>";

			$noNext = $no + 1;

	 echo json_encode(array(
		 	'std_time' => $std_time,
			'code_work' => $code_work,
		 	'nomor'			=> $nomor,
			'rowx'			=> $Rowx,
			'row_meal'	=> $RowxMeal,
			'overtime'	=> $RowxOvertime
	 ));
	}

	public function list_work_det2(){
		$code_work 	= $this->uri->segment(3);
		$nomor 		= $this->uri->segment(4);

		//list
		// $consumable	= $this->db->query("SELECT * FROM con_nonmat_new ORDER BY category ASC")->result_array();
		// $man_power	= $this->db->query("SELECT * FROM man_power_new ORDER BY category ASC")->result_array();
		// $vehicle	= $this->db->query("SELECT * FROM vehicle_tool_new ORDER BY category ASC")->result_array();
		$heavy		= $this->db->query("SELECT * FROM heavy_equipment_new ORDER BY category ASC")->result_array();
		$location	= $this->db->query("SELECT * FROM region ORDER BY urut ASC")->result_array();
		$unit		= $this->db->query("SELECT * FROM list WHERE origin='con_nonmat' AND category='satuan' ORDER BY urut ASC")->result_array();

		$restDet1 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'heavy equipment','deleted'=>'N'))->result_array();
		// $restDet2 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'tools','deleted'=>'N'))->result_array();
		// $restDet3 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'consumable','deleted'=>'N'))->result_array();
		// $restDet4 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'man power','deleted'=>'N'))->result_array();

		 $query	 	= "SELECT * FROM work_detail WHERE code_work='".$code_work."' AND deleted='N'";
		$get_work	= $this->db->select('total_time')->get_where('work_header', array('code_work'=>$code_work))->result();
		$std_time 	= (!empty($get_work))?$get_work[0]->total_time:0;
		$no = 0;
		$Rowx = "";
		$RowxMeal = "";
		$RowxOvertime = "";
		$SumTP = 0;
		// $Rowx .= "<tr id='tr_".$nomor."'>";
			$Rowx .= "<td width='100%' colspan='2'>";
				$Rowx .= "<table id='my-grid' class='table table-striped table-bordered table-hover table-condensed' width='100%'>";
					$Rowx .= "<tr class='bg-purple'>";
						$Rowx .= "<th class='text-center' width='100%'>Heavy Equipment</th>";
						// $Rowx .= "<th class='text-center' width='50%'>Tools Equipment</th>";
					$Rowx .= "</tr>";
					$Rowx .= "<tr class='tr_".$nomor."'>";
						$Rowx .= "<td>";
							$num1 = 0;
							foreach($restDet1 AS $val_vt => $valx_vt){
								$num1++;
								$Rowx .= "<div style='margin-bottom: 6px;'>";
									$Rowx .= "<div class='input-group'>";
									$Rowx .= "<select name='ListDetail[".$nomor."][he][".$num1."][code_group]' class='chosen_select form-control he_".$nomor."_".$no."_".$num1."'>";
									foreach($heavy AS $val_vtList => $valx_vtList){
										$sel1 = ($valx_vtList['code_group'] == $valx_vt['code_group'])?'selected':'';
										$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
									}
									$Rowx .= "</select>";
									$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
									$Rowx .= "<input type='text' name='ListDetail[".$nomor."][he][".$num1."][qty]' class='form-control widCtr autoNumeric0' placeholder='Qty'>";
									$Rowx .= "<input type='text' name='ListDetail[".$nomor."][he][".$num1."][durasi]' class='form-control widCtr autoNumeric' placeholder='Durasi'>";
									$Rowx .= "</div>";
								$Rowx .= "</div>";
							}
							//add komponent
							$Rowx .= "<div id='he_add_".$nomor."_".$no."'></div>";
							$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num1' data-tanda='he_add_' data-tanda2='new'>Add Heavy Equipment</button></div>";
						$Rowx .= "</td>";
						// $Rowx .= "<td>";
						// 	$num1 = 0;
						// 	foreach($restDet2 AS $val_vt => $valx_vt){
						// 		$num1++;
						// 		$Rowx .= "<div style='margin-bottom: 6px;'>";
						// 			$Rowx .= "<div class='input-group'>";
						// 			$Rowx .= "<select name='ListDetail[".$nomor."][vt][".$num1."][code_group]' class='chosen_select form-control vt_".$nomor."_".$no."_".$num1."'>";
						// 			foreach($vehicle AS $val_vtList => $valx_vtList){
						// 				$sel1 = ($valx_vtList['code_group'] == $valx_vt['code_group'])?'selected':'';
						// 				$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
						// 			}
						// 			$Rowx .= "</select>";
						// 			$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
						// 			$Rowx .= "<input type='text' name='ListDetail[".$nomor."][vt][".$num1."][qty]' class='form-control widCtr autoNumeric0' placeholder='Qty'>";
						// 			$Rowx .= "</div>";
						// 		$Rowx .= "</div>";
						// 	}
						// 	//add komponent
						// 	$Rowx .= "<div id='vt_add_".$nomor."_".$no."'></div>";
						// 	$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num1' data-tanda='vt_add_' data-tanda2='new'>Add Tools Equipment</button></div>";
						// $Rowx .= "</td>";
					$Rowx .= "</tr>";
					// $Rowx .= "<tr class='bg-purple'>";
					// 	$Rowx .= "<th class='text-center'>Consumable & APD</th>";
					// 	$Rowx .= "<th class='text-center'>Man Power</th>";
					// $Rowx .= "</tr>";
					// $Rowx .= "<tr class='tr_".$nomor."'>";
					// 	$Rowx .= "<td>";
					// 		//Consumable
					// 		$num2 = 0;
					// 		foreach($restDet3 AS $val_cn => $valx_cn){
					// 			$num2++;
					// 			$Rowx .= "<div style='margin-bottom: 6px;'>";
					// 			$Rowx .= "<div class='input-group'>";
					// 			$Rowx .= "<select name='ListDetail[".$nomor."][cn][".$num2."][code_group]' class='chosen_select form-control inline-blockd cn_".$nomor."_".$no."_".$num2."''>";
					// 			foreach($consumable AS $val_vtList => $valx_vtList){
					// 				$sel2 = ($valx_vtList['code_group'] == $valx_cn['code_group'])?'selected':'';
					// 				$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel2>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
					// 			}
					// 			$Rowx .= "</select>";
					// 			$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
					// 			$Rowx .= "<input type='text' name='ListDetail[".$nomor."][cn][".$num2."][qty]' class='form-control widCtr autoNumeric0' placeholder='Qty'>";
					// 			$Rowx .= "<span class='input-group-addon'></span>";
					// 			$Rowx .= "<select name='ListDetail[".$nomor."][cn][".$num2."][unit]' class='chosen_select form-control inline-block widCtrx'>";
					// 			foreach($unit AS $val_unit => $valx_unit){
					// 				$Rowx .= "<option value='".$valx_unit['category_list']."' >".strtoupper($valx_unit['view_'])."</option>";
					// 			}
					// 			$Rowx .= "</select>";
					// 			$Rowx .= "</div>";
					// 			$Rowx .= "</div>";
					// 		}
					// 		//add komponent
					// 		$Rowx .= "<div id='cn_add_".$nomor."_".$no."'></div>";
					// 		$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num2' data-tanda='cn_add_' data-tanda2='new'>Add Consumable</button></div>";

					// 	$Rowx .= "</td>";
					// 	$Rowx .= "<td>";
					// 		//Man Power
					// 		$num3 = 0;
					// 		foreach($restDet4 AS $val_vt => $valx_vt){
					// 			$num3++;
					// 			$Rowx .= "<div style='margin-bottom: 6px;'>";
					// 			$Rowx .= "<div class='input-group'>";
					// 			$Rowx .= "<select name='ListDetail[".$nomor."][mp][".$num3."][code_group]' class='chosen_select form-control inline-block mp_".$nomor."_".$no."_".$num3."'>";
					// 			foreach($man_power AS $val_vtList => $valx_vtList){
					// 				$sel1 = ($valx_vtList['code_group'] == $valx_vt['code_group'])?'selected':'';
					// 				$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
					// 			}
					// 			$Rowx .= "</select>";
					// 			$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
					// 			$Rowx .= "<input type='text' name='ListDetail[".$nomor."][mp][".$num3."][qty]' data-no1='".$nomor."' data-no2='".$num3."' class='form-control widCtr chMPQty autoNumeric0' placeholder='Qty'>";
					// 			$Rowx .= "</div>";
					// 			$Rowx .= "</div>";

					// 			//Meal
					// 			$RowxMeal .= "<tr>";
					// 				$RowxMeal .= "<td width='30%'>";
					// 				$RowxMeal .= "<input type='hidden' name='ListMeal[".$nomor.$num3."][code_group]' id='code_groupm_".$nomor."_".$num3."' class='form-control input-md' value='".$valx_vt['code_group']."'>";
					// 				$RowxMeal .= "<input type='text' name='ListMeal[".$nomor.$num3."][spec]' id='specm_".$nomor."_".$num3."' class='form-control input-md' readonly value='".getMP($valx_vt['code_group'],'man_power_new')."'>";
					// 				$RowxMeal .= "</td>";
					// 				$RowxMeal .= "<td width='13%'>";
					// 				$RowxMeal .= "<select name='ListMeal[".$nomor.$num3."][area]' class='chosen_select form-control inline-block'>";
					// 				foreach($location AS $valL => $valxL){
					// 					$RowxMeal .= "<option value='".$valxL['region_code']."'>".strtoupper($valxL['region'])."</option>";
					// 				}
					// 				$RowxMeal .= "</select>";
					// 				$RowxMeal .= "</td>";
					// 				$RowxMeal .= "<td width='12%'>";
					// 				$RowxMeal .= "<input type='text' name='ListMeal[".$nomor.$num3."][jml_orang]' id='jml_orangm_".$nomor."_".$num3."' readonly class='form-control input-md' placeholder='Amount People'>";
					// 				$RowxMeal .= "</td>";
					// 				$RowxMeal .= "<td width='12%'>";
					// 				$RowxMeal .= "<input type='text' name='ListMeal[".$nomor.$num3."][jml_hari]' id='jml_harim_".$nomor."_".$num3."' readonly class='form-control input-md dayT_".$nomor."' placeholder='Amount (Day)' value='".$SumTP."'>";
					// 				$RowxMeal .= "</td>";
					// 				$RowxMeal .= "<td>";
					// 				$RowxMeal .= "<input type='text' name='ListMeal[".$nomor.$num3."][note]' id=notem_".$nomor."_".$num3."' class='form-control input-md' placeholder='Note'>";
					// 				$RowxMeal .= "</td>";
					// 				$RowxMeal .= "<td align='center' width='6%'>";
					// 				$RowxMeal .=		"<button type='button' disabled style='min-width:70px;' class='btn btn-danger btn-sm aDel' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
					// 				$RowxMeal .= "</td>";
					// 			$RowxMeal .= "</tr>";

					// 			//Overtime
					// 			$RowxOvertime .= "<tr>";
					// 				$RowxOvertime .= "<td width='30%'>";
					// 				$RowxOvertime .= "<input type='hidden' name='ListOvertime[".$nomor.$num3."][code_group]' id='code_groupo_".$nomor."_".$num3."' class='form-control input-md' value='".$valx_vt['code_group']."'>";
					// 				$RowxOvertime .= "<input type='text' name='ListOvertime[".$nomor.$num3."][spec]' id='speco_".$nomor."_".$num3."' class='form-control input-md' readonly value='".getMP($valx_vt['code_group'],'man_power_new')."'>";
					// 				$RowxOvertime .= "</td>";
					// 				$RowxOvertime .= "<td width='13%'>";
					// 				$RowxOvertime .= "<input type='text' name='ListOvertime[".$nomor.$num3."][jml_orang]' id='jml_orango_".$nomor."_".$num3."' readonly class='form-control input-md' placeholder='Amount People'>";
					// 				$RowxOvertime .= "</td>";
					// 				$RowxOvertime .= "<td width='12%'>";
					// 				$RowxOvertime .= "<input type='text' name='ListOvertime[".$nomor.$num3."][jml_hari]' id='jml_hario_".$nomor."_".$num3."' readonly class='form-control input-md dayT_".$nomor."' placeholder='Amount (Day)'  value='".$SumTP."'>";
					// 				$RowxOvertime .= "</td>";
					// 				$RowxOvertime .= "<td width='12%'>";
					// 				$RowxOvertime .= "<input type='text' name='ListOvertime[".$nomor.$num3."][jml_jam]' id='jml_jamo_".$nomor."_".$num3."' class='form-control input-md' placeholder='Amount (Hour)'>";
					// 				$RowxOvertime .= "</td>";
					// 				$RowxOvertime .= "<td>";
					// 				$RowxOvertime .= "<input type='text' name='ListOvertime[".$nomor.$num3."][note]' id=noteo_".$nomor."_".$num3."' class='form-control input-md' placeholder='Note'>";
					// 				$RowxOvertime .= "</td>";
					// 				$RowxOvertime .= "<td align='center' width='6%'>";
					// 				$RowxOvertime .=		"<button type='button' disabled style='min-width:70px;' class='btn btn-danger btn-sm aDel' data-toggle='tooltip' data-placement='bottom' title='Delete Record'>Del</button>";
					// 				$RowxOvertime .= "</td>";
					// 			$RowxOvertime .= "</tr>";
					// 		}
					// 		//add komponent
					// 		$Rowx .= "<div id='mp_add_".$nomor."_".$no."'></div>";
					// 		$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num3' data-tanda='mp_add_' data-tanda2='new'>Add Man Power</button></div>";

					// 	$Rowx .= "</td>";
					// $Rowx .= "</tr>";
				$Rowx .= "</table>";
			$Rowx .= "</td>";
		// $Rowx .= "</tr>";

		$noNext = $no + 1;

		echo json_encode(array(
				'std_time' => $std_time,
				'code_work' => $code_work,
				'nomor'			=> $nomor,
				'rowx'			=> $Rowx,
				'row_meal'	=> $RowxMeal,
				'overtime'	=> $RowxOvertime
		));
	}

 	public function add_dropdown(){
		$num1 		= $this->uri->segment(3);
		$num2 		= $this->uri->segment(4);
		$numlast 	= $this->uri->segment(5) + 1;
		$tanda 		= $this->uri->segment(6);
		$tanda2 	= $this->uri->segment(7);

		$tanda_name = substr($tanda, 0, 2);
		if($tanda_name == 'vt'){
			$judul = "Tools Equipment";
			$table = "vehicle_tool_new";
		}
		if($tanda_name == 'he'){
			$judul = "Heavy Equipment";
			$table = "heavy_equipment_new";
		}
		if($tanda_name == 'cn'){
			$judul = "Consumable";
			$table = "con_nonmat_new";
		}
		if($tanda_name == 'mp'){
			$judul = "Man Power";
			$table = "man_power_new";
		}
		if($tanda_name == 'ap'){
			$judul = "APD";
			$table = "apd";
		}
		if($tanda_name == 'ak'){
			$judul = "Akomodasi";
			$table = "tb_view_akomodasi";
		}

		$td_plus = "";
		 //list
		 $list		= $this->db->query("SELECT * FROM $table WHERE deleted='N' ORDER BY category ASC, spec ASC")->result_array();
		 if($tanda_name == 'mp'){
			$list		= $this->db->query("SELECT a.code_group, a.spec, b.category FROM $table a LEFT JOIN man_power_category b ON a.category=b.id WHERE a.deleted='N' ORDER BY b.category ASC, a.spec ASC")->result_array();
		}
		 $unit		= $this->db->query("SELECT * FROM list WHERE origin='con_nonmat' AND category='satuan' ORDER BY urut ASC")->result_array();
		 $urut		= $this->db->query("SELECT $tanda_name FROM urut WHERE code = 'add_project' LIMIT 1")->result();
		 $urut_no 	= $urut[0]->$tanda_name;

		$Rowx = "";
		$RowxQ = "";
		$Rowx .= "<div style='margin-bottom: 6px;'>";
			// $Rowx .= "<div class='labDet ".$tanda_name."_".$num1."_".$num2."_".$numlast."'>$judul</div>";
			$Rowx .= "<div class='input-group'>";
			$Rowx .= "<select name='ListDetail[".$td_plus.$num1."][$tanda_name][0$urut_no][code_group]' class='chosen_select form-control inline-block'>";
			$Rowx .= "<option>Select An Option</option>";
				foreach($list AS $val_vtList => $valx_vtList){
					$Rowx .= "<option value='".$valx_vtList['code_group']."'>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
				}
			$Rowx .= "</select>";
			$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
			$Rowx .= "<input type='text' name='ListDetail[".$td_plus.$num1."][".$tanda_name."][0$urut_no][qty]' class='form-control input-md widCtr autoNumeric0' placeholder='Qty'>";
			$Rowx .= "<input type='text' name='ListDetail[".$td_plus.$num1."][".$tanda_name."][0$urut_no][durasi]' class='form-control input-md widCtr autoNumeric' placeholder='Durasi'>";
			
				if($tanda_name == 'cn'){
					$Rowx .= "<span class='input-group-addon'></span>";
					$Rowx .= "<select name='ListDetail[".$td_plus.$num1."][".$tanda_name."][0$urut_no][unit]' class='chosen_select form-control inline-block widCtrx'>";
					foreach($unit AS $val_unit => $valx_unit){
						$Rowx .= "<option value='".$valx_unit['category_list']."' >".strtoupper($valx_unit['view_'])."</option>";
					}
					$Rowx .= "</select>";
				}

			$Rowx .= "</div>";
		$Rowx .= "</div>";


		$this->db->where('code', 'add_project');
		$this->db->update('urut', array($tanda_name=>$urut_no+1));

		echo json_encode(array(
			 'num1' 		=> $num1,
			 'num2' 		=> $num2,
			 'numlast'	=> $numlast,
			 'tanda'		=> $tanda,
			 'rowx'			=> $Rowx,
			 'rowxqty'	=> $RowxQ
		));
	}

	public function getProject(){
		$no_ipp = $this->uri->segment(3);
		$query	 	= "SELECT * FROM ipp_header WHERE no_ipp='".$no_ipp."' LIMIT 1";
		$Q_result	= $this->db->query($query)->result();
		 echo json_encode(array(
			 'project' => strtoupper($Q_result[0]->project),
			 'location' => strtoupper($Q_result[0]->location)

		 ));
	}

	public function modalDetail(){
		$project_code = $this->uri->segment(3);
		$tanda = $this->uri->segment(4);

		$header 	= $this->db->get_where('project_header', array('project_code'=>$project_code))->result();
		$restDetail = $this->db->get_where('project_detail_header', array('project_code'=>$project_code,'deleted'=>'N'))->result_array();
		$detail_bq 	= $this->db->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'project'))->result_array();
		$detail_cus = $this->db->group_by('pekerjaan')->order_by('id','asc')->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'custom'))->result_array();

		$rMeal 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'meal'))->result_array();
		$rHouse 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'house'))->result_array();
		$rTrans 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'trans'))->result_array();
		$rEtc 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'etc'))->result_array();
		$rTesting 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'testing'))->result_array();
		$rSurvey 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'survey'))->result_array();
		$rCovid 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'covid'))->result_array();
		$rMDE 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'mde'))->result_array();

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

		// echo $qDetailVT;
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
			'covid' => $rCovid,
			'mde' => $rMDE,
			'he' => $rHE,
			'vt' => $rVT,
			'cn' => $rCN,
			'mp' => $rMP,
			'tanda' => $tanda
		);

		$this->load->view('Instalation/modalDetail', $data);
	}

	public function modalDetailPlan(){
		$project_code = $this->uri->segment(3);
		$tanda = $this->uri->segment(4);

		$detail_bq 	= $this->db->select('SUM(std_time) AS day')->get_where('project_detail_header', array('project_code'=>$project_code))->result();

		// $qDetailHE	= "SELECT a.*, MAX(a.qty) AS qty_ FROM project_detail_process a WHERE a.project_code='".$project_code."' AND a.tipe='heavy equipment' AND a.deleted='N' GROUP BY a.code_group";
		// $rHE 		= $this->db->query($qDetailHE)->result_array();

		// $qDetailVT	= "SELECT a.*, MAX(a.qty) AS qty_ FROM project_detail_process a WHERE a.project_code='".$project_code."' AND a.tipe='tools' AND a.deleted='N' GROUP BY a.code_group";
		// $rVT 		= $this->db->query($qDetailVT)->result_array();

		// $qDetailCN 	= "SELECT a.*, SUM(a.qty) AS qty_ FROM project_detail_process a WHERE a.project_code='".$project_code."' AND a.tipe='consumable' AND a.deleted='N' GROUP BY a.code_group";
		// $rCN		= $this->db->query($qDetailCN)->result_array();

		// $qDetailMP 	= "SELECT a.*, MAX(a.qty) AS qty_ FROM project_detail_process a WHERE a.project_code='".$project_code."' AND a.tipe='man power' AND a.deleted='N' GROUP BY a.code_group";
		// $rMP		= $this->db->query($qDetailMP)->result_array();

		$detail = $this->db->query("SELECT
										a.code_work,
										a.category,
										a.std_time,
										a.timeline,
										b.tipe,
										b.category as cat_tools,
										b.spec,
										b.qty
									FROM
										project_detail_header a
										LEFT JOIN project_detail_process b ON a.project_code_det = b.project_code_det 
									WHERE
										a.project_code = '".$project_code."' 
									ORDER BY
										a.id ASC, b.tipe ASC")->result_array();
		// echo $qDetailMP;
		$data = array(
			'sum_day' => $detail_bq,
			// 'he' => $rHE,
			// 'vt' => $rVT,
			// 'cn' => $rCN,
			// 'mp' => $rMP,
			'detail' => $detail,
			'tanda' => $tanda
		);

		$this->load->view('Instalation/modalDetailPlan', $data);
	}

	public function hapus(){
		$code_work = $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'deleted' 		=> 'Y',
			'deleted_by' 	=> $data_session['ORI_User']['username'],
			'deleted_date' 	=> date('Y-m-d H:i:s')
			);


		$this->db->trans_start();
		$this->db->where('project_code', $code_work);
		$this->db->update('project_header', $ArrPlant);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Delete Project data failed. Please try again later ...',
				'status'	=> 0
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Delete Project data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete Project : '.$code_work);
		}
		echo json_encode($Arr_Data);
	}

	public function print_bq(){
		$project_code	= $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$printby			= $data_session['ORI_User']['username'];
		$koneksi			= akses_server_side();

		include 'plusPrint.php';
		$data_url			= base_url();
		$Split_Beda		= explode('/',$data_url);
		$Jum_Beda			= count($Split_Beda);
		$Nama_Beda		= $Split_Beda[$Jum_Beda - 2];

		history('Print BQ Project '.$project_code);
		PrintBQ($Nama_Beda, $project_code, $koneksi, $printby);
	}

	public function print_project(){
		$project_code = $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$header 	= $this->db->get_where('project_header', array('project_code'=>$project_code))->result();
		$restDetail = $this->db->get_where('project_detail_header', array('project_code'=>$project_code,'deleted'=>'N'))->result_array();
		$detail_bq 	= $this->db->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'project'))->result_array();
		$detail_cus = $this->db->group_by('pekerjaan')->order_by('id','asc')->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'custom'))->result_array();

		$rMeal 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'meal'))->result_array();
		$rHouse 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'house'))->result_array();
		$rTrans 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'trans'))->result_array();
		$rEtc 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'etc'))->result_array();
		$rTesting 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'testing'))->result_array();
		$rSurvey 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'survey'))->result_array();
		$rCovid 	= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'covid'))->result_array();
		$rMDE 		= $this->db->get_where('project_detail_akomodasi', array('project_code'=>$project_code,'category_ak'=>'mde'))->result_array();

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

		$printby			= $data_session['ORI_User']['username'];
		// echo $qDetailVT;
		$data = array(
			'project_code'=> $project_code,
			'printby'=> $printby,
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
			'covid' => $rCovid,
			'mde' => $rMDE,
			'he' => $rHE,
			'vt' => $rVT,
			'cn' => $rCN,
			'mp' => $rMP
		);

		$this->load->view('Print/print_project', $data);
	}

	public function request_approve(){
		$id 			= $this->uri->segment(3);
		$no_ipp 		= get_name('project_header','no_ipp','project_code',$id);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$ArrUpdate = array(
			'status' => 'WAITING APPROVE ESTIMATION PROJECT',
			'aju_approved' => 'Y',
			'aju_approved_by' => $data_session['ORI_User']['username'],
			'aju_approved_date' => $dateTime
		);

		$ArrUpdate2 = array(
			'status' => 'WAITING APPROVE ESTIMATION PROJECT'
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
				'pesan'		=> 'Approve data failed. Please try again later ...',
				'status'	=> 2
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Approve data success. Thanks ...',
				'status'	=> 1
			);
			history('Request approval project instalation code project : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	public function get_cycletime($dim=null){
		$result		= $this->db->get_where('cycletime', array('diameter'=>$dim))->result();
		if(!empty($result)){
			$alert 			= "Success. Data found.";
			$cycletime 		= $result[0]->mh;
			$mp 		= $result[0]->mp;
			$ct 		= $result[0]->ct;
			$color 			= "green";
		}
		if(empty($result)){
			$alert 			= "Failed. Data not found, check master.";
			$cycletime 		= 0;
			$mp 		= 0;
			$ct 		= 0;
			$color 			= "red";
		}
		 echo json_encode(array(
			 'alert' 		=> $alert,
			 'cycletime' 	=> $cycletime,
			 'mp' 	=> $mp,
			 'ct' 	=> $ct,
			 'color' 		=> $color
		 ));
	}

	public function download_excel(){
		//membuat objek PHPExcel
		set_time_limit(0);
		ini_set('memory_limit','1024M');
		$id_inst		= $this->uri->segment(3);

		$this->load->library("PHPExcel");
		// $this->load->library("PHPExcel/Writer/Excel2007");
		$objPHPExcel	= new PHPExcel();

		$style_header = array(
			'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID
			),
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);

		$style_header2 = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'e3e3e3'),
			),
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);

		$styleArray = array(
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			  ),
			  'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			)
		  );
		$styleArray3 = array(
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
			  ),
			  'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			)
		  );
		 $styleArray4 = array(
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
			  ),
			  'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			)
		  );
	    $styleArray1 = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  ),
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'e3e3e3'),
				),
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		  );
		$styleArray2 = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  ),
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		  );

		$Arr_Bulan	= array(1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$sheet 		= $objPHPExcel->getActiveSheet();

		$qDetailBQ 	= "SELECT * FROM project_detail_w_det WHERE project_code='".$id_inst."' AND deleted='N'";
		$numx 	= $this->db->query($qDetailBQ)->num_rows();

		$Row		= 1;
		$NewRow		= $Row+1;
		$Col_Akhir	= $Cols	= getColsChar(6+$numx);
		$sheet->setCellValue('A'.$Row, 'PLAN INSTALASI '.$id_inst);
		$sheet->getStyle('A'.$Row.':'.$Col_Akhir.$NewRow)->applyFromArray($style_header2);
		$sheet->mergeCells('A'.$Row.':'.$Col_Akhir.$NewRow);

		$NewRow	= $NewRow +2;
		$NextRow= $NewRow +1;

		$sheet->setCellValue('A'.$NewRow, 'No');
		$sheet->getStyle('A'.$NewRow.':A'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('A'.$NewRow.':A'.$NextRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);

		$sheet->setCellValue('B'.$NewRow, 'Category');
		$sheet->getStyle('B'.$NewRow.':B'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('B'.$NewRow.':B'.$NextRow);
		$sheet->getColumnDimension('B')->setAutoSize(true);

		$sheet->setCellValue('C'.$NewRow, 'Name');
		$sheet->getStyle('C'.$NewRow.':C'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('C'.$NewRow.':C'.$NextRow);
		$sheet->getColumnDimension('C')->setAutoSize(true);

		$sheet->setCellValue('D'.$NewRow, 'Specification');
		$sheet->getStyle('D'.$NewRow.':D'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('D'.$NewRow.':D'.$NextRow);
		$sheet->getColumnDimension('D')->setAutoSize(true);

		$sheet->setCellValue('E'.$NewRow, 'Job Name');
		$sheet->getStyle('E'.$NewRow.':E'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('E'.$NewRow.':E'.$NextRow);
		$sheet->getColumnDimension('E')->setAutoSize(true);

		$sheet->setCellValue('F'.$NewRow, 'Time (Day)');
		$sheet->getStyle('F'.$NewRow.':F'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('F'.$NewRow.':F'.$NextRow);
		$sheet->getColumnDimension('F')->setAutoSize(true);


		$num_x = $numx + 6;
		$num = 0;
		for($a=7; $a <= $num_x; $a++ ){
			$num++;
			$row_name = getColsChar($a);
			$sheet->setCellValue($row_name.$NewRow, 'Day '.$num);
			$sheet->getStyle($row_name.$NewRow.':'.$row_name.''.$NextRow)->applyFromArray($style_header);
			$sheet->mergeCells($row_name.$NewRow.':'.$row_name.''.$NextRow);
			$sheet->getColumnDimension($row_name)->setAutoSize(false);
		}

		$qDetailVT	= "SELECT a.*, a.qty AS qty_, COUNT(b.std_time) AS std_time, c.category AS proses, b.work_process FROM project_detail_w_det_vehicle_tool a LEFT JOIN project_detail_w_header c ON a.project_code_det=c.project_code_det LEFT JOIN project_detail_w_det b ON c.project_code_det=b.project_code_det WHERE a.project_code='".$id_inst."' AND b.project_code='".$id_inst."' AND a.deleted='N' GROUP BY a.id";
		$vehicle 		= $this->db->query($qDetailVT)->result_array();

		$qDetailCN 	= "SELECT a.*, a.qty AS qty_, COUNT(b.std_time) AS std_time, c.category AS proses, b.work_process FROM project_detail_w_det_con_nonmat a LEFT JOIN project_detail_w_header c ON a.project_code_det=c.project_code_det LEFT JOIN project_detail_w_det b ON c.project_code_det=b.project_code_det WHERE a.project_code='".$id_inst."' AND b.project_code='".$id_inst."' AND a.deleted='N' GROUP BY a.id";
		$consumable	= $this->db->query($qDetailCN)->result_array();

		$qDetailMP 	= "SELECT a.*, a.qty AS qty_, COUNT(b.std_time) AS std_time, c.category AS proses, b.work_process FROM project_detail_w_det_man_power a LEFT JOIN project_detail_w_header c ON a.project_code_det=c.project_code_det LEFT JOIN project_detail_w_det b ON c.project_code_det=b.project_code_det WHERE a.project_code='".$id_inst."' AND b.project_code='".$id_inst."' AND a.deleted='N' GROUP BY a.id";
		$man_power	= $this->db->query($qDetailMP)->result_array();

		$sum_day 	= $this->db->query($qDetailBQ)->result_array();

		if($vehicle){
			$awal_row	= $NextRow;
			$no	= 0;
			foreach($vehicle as $key => $row_Cek){
				$no++;
				$awal_row++;
				$awal_col	= 0;

				$awal_col++;
				$nomorx		= $no;
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $nomorx);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$id_bqx		= "Alat Berat";
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $id_bqx);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$id_bqx		= $row_Cek['category'];
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $id_bqx);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$project	= strtolower($row_Cek['spec']);
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $project);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$id_category	= $row_Cek['proses'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $id_category);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$liner	= $row_Cek['std_time']." Day";
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $liner);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray4);

				foreach($sum_day AS $val2 => $valx2){
					$same = ($row_Cek['project_code_det'] == $valx2['project_code_det'])?$row_Cek['qty_']:'-';
					$color = ($row_Cek['project_code_det'] == $valx2['project_code_det'])?$styleArray1:$styleArray4;

					$awal_col++;
					$liner	= $same;
					$Cols		= getColsChar($awal_col);
					$sheet->setCellValue($Cols.$awal_row, $liner);
					$sheet->getStyle($Cols.$awal_row)->applyFromArray($color);
				}

			}

		}

		if($consumable){
			$awal_row	= $awal_row;
			$no	= $nomorx;
			foreach($consumable as $key => $row_Cek){
				$no++;
				$awal_row++;
				$awal_col	= 0;

				$awal_col++;
				$nomorx		= $no;
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $nomorx);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$id_bqx		= "Consumable";
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $id_bqx);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$id_bqx		= $row_Cek['category'];
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $id_bqx);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$project	= strtolower($row_Cek['spec']);
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $project);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$id_category	= $row_Cek['proses'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $id_category);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$liner	= $row_Cek['std_time']." Day";
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $liner);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray4);

				foreach($sum_day AS $val2 => $valx2){
					$same = ($row_Cek['project_code_det'] == $valx2['project_code_det'])?$row_Cek['qty_']:'-';
					$color = ($row_Cek['project_code_det'] == $valx2['project_code_det'])?$styleArray1:$styleArray4;

					$awal_col++;
					$liner	= $same;
					$Cols		= getColsChar($awal_col);
					$sheet->setCellValue($Cols.$awal_row, $liner);
					$sheet->getStyle($Cols.$awal_row)->applyFromArray($color);
				}

			}

		}

		if($man_power){
			$awal_row	= $awal_row;
			$no	= $nomorx;
			foreach($man_power as $key => $row_Cek){
				$no++;
				$awal_row++;
				$awal_col	= 0;

				$awal_col++;
				$nomorx		= $no;
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $nomorx);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$id_bqx		= "Man Power";
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $id_bqx);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$id_bqx		= $row_Cek['category'];
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $id_bqx);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$project	= strtolower($row_Cek['spec']);
				$Cols		= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $project);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$id_category	= $row_Cek['proses'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $id_category);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$liner	= $row_Cek['std_time']." Day";
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $liner);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray4);

				foreach($sum_day AS $val2 => $valx2){
					$same = ($row_Cek['project_code_det'] == $valx2['project_code_det'])?$row_Cek['qty_']:'-';
					$color = ($row_Cek['project_code_det'] == $valx2['project_code_det'])?$styleArray1:$styleArray4;

					$awal_col++;
					$liner	= $same;
					$Cols		= getColsChar($awal_col);
					$sheet->setCellValue($Cols.$awal_row, $liner);
					$sheet->getStyle($Cols.$awal_row)->applyFromArray($color);
				}

			}

		}


		$sheet->setTitle('Excel Plan Instalasi');
		//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
		$objWriter		= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		//sesuaikan headernya
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="Plan Instalasi '.$id_inst.' '.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

	//===============================================================================================================
	//=====================================APPROVE===================================================================
	//===============================================================================================================

	public function approve_project(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_Group			= $this->master_model->getArray('groups',array(),'id','name');
		$data = array(
			'title'			=> 'Indeks Of Approved Instalation',
			'action'		=> 'approve',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Approve Instalation');
		$this->load->view('Instalation/approve',$data);
	}

	public function data_side_approve(){
		$this->instalation_model->get_json_approve();
	}

	public function approve(){
		$id = $this->uri->segment(3);
		$no_ipp 		= get_name('project_header','no_ipp','project_code',$id);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$ArrUpdate2 = array(
			'status' => 'WAITING COSTING PROJECT'
		);

		$ArrUpdate = array(
			'status' => 'WAITING COSTING PROJECT',
			'approved' => 'Y',
			'approved_by' => $data_session['ORI_User']['username'],
			'approved_date' => $dateTime
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
				'pesan'		=> 'Approve data failed. Please try again later ...',
				'status'	=> 2
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Approve data success. Thanks ...',
				'status'	=> 1
			);
			history('Approve project instalation (next costing project) code project : '.$id);
		}

		echo json_encode($Arr_Kembali);
	}

	public function dialog_reject(){
		if($this->input->post()){
			$id = $this->uri->segment(3);
			$no_ipp 		= get_name('project_header','no_ipp','project_code',$id);
			$data = $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$reason = strtolower($data['reason_approved']);

			$ArrUpdate = array(
				'status' => 'WAITING ESTIMATION PROJECT',
				'aju_approved' => 'N',
				'aju_approved_by' => $data_session['ORI_User']['username'],
				'aju_approved_date' => $dateTime,
				'reason_approved' => $reason
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
					'pesan'		=> 'Approve data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> 'Approve data success. Thanks ...',
					'status'	=> 1
				);
				history('Reject project instalation code project : '.$id);
			}

			echo json_encode($Arr_Kembali);
		}
		else{
			$id			= $this->uri->segment(3);
			$data = array(
				'id'	=> $id
			);
			$this->load->view('Instalation/dialog_reject',$data);
		}
	}

	public function modalAdd(){
		$data = $this->input->post();
		$tipe = $data['tipe'];
		$project_code = $data['project_code'];

		if($tipe == 'safety'){
			$list_safety = $this->db->get_where('vehicle_tool_new', array('deleted_date'=>NULL, 'id_category'=>'6'))->result_array();
			$count_MP = $this->db->select('SUM(qty) AS jumlah_mp')->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>'man power'))->result();
			$ArrDetail = array();
			foreach ($list_safety as $val => $valx) {
				$get_check = $this->db->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>$tipe,'code_group'=>$valx['code_group']))->result();
				$ArrDetail[$val]['project_code'] 	= $project_code;
				$ArrDetail[$val]['code_group'] 		= $valx['code_group'];
				$ArrDetail[$val]['category'] 		= $valx['category'];
				$ArrDetail[$val]['spec'] 			= $valx['spec'];
				$ArrDetail[$val]['id_unit'] 		= $valx['unit'];
				$ArrDetail[$val]['unit'] 			= get_name('unit','unit','id',$valx['unit']);
				$ArrDetail[$val]['qty'] 			= (!empty($get_check))?$get_check[0]->qty:$count_MP[0]->jumlah_mp;
				$ArrDetail[$val]['tipe'] 			= $tipe;
				$ArrDetail[$val]['rate'] 			= 0;
				$ArrDetail[$val]['jml_hari'] 		= (!empty($get_check))?$get_check[0]->jml_hari:0;
			}

			$this->db->trans_start();
				$this->db->delete('project_detail_process', array('tipe'=>$tipe,'project_code'=>$project_code));
				if(!empty($ArrDetail)){
					$this->db->insert_batch('project_detail_process', $ArrDetail);
				}
			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			}
			else{
				$this->db->trans_commit();
			}
		}

		// $satuan = $this->db->get_where('unit',array('deleted'=>'N'))->result_array();
		
		$data_result = $this->db->get_where('project_detail_process', array('project_code'=>$project_code,'tipe'=>$tipe))->result_array();
		$arrayRequest= array();
		$arrayDataCheck= array();
		foreach ($data_result as $key => $value) {

			// $Unit = "";
			// foreach ($satuan as $key2 => $value2) {
			// 	$sel = (strtolower($value2['id']) == number_format($value['jml_hari']))?'selected':'';
			// 	$Unit .= "<option value='".$value2['id']."' $sel>".strtoupper($value2['unit'])."</option>";
			// }

			$arrayRequest[$key]['id'] = $value['code_group'];
			$arrayRequest[$key]['qty'] = $value['qty'];
			$arrayRequest[$key]['material'] = strtoupper($value['category']." - ".$value['spec']);
			$arrayRequest[$key]['tipe'] = $tipe;
			if($tipe != 'man power'){
				$arrayRequest[$key]['durasi'] = $value['id_unit'];
			}
			else{
				$arrayRequest[$key]['durasi'] = $value['jml_hari'];
			}

			$arrayDataCheck[] = $value['code_group'];
		}

		// print_r($arrayRequest);
		// print_r($arrayDataCheck);
		$satuan = $this->db->order_by('urut','asc')->get_where('unit',array('deleted'=>'N'))->result_array();
		$satuanHTML= array();
		foreach ($satuan as $key => $value) {
			$satuanHTML[$key]['id'] = $value['id'];
			$satuanHTML[$key]['unit'] = strtoupper($value['unit']);
		}

		$data = array(
			'tipe' => $tipe,
			'satuan' => $satuan,
			'satuanHTML' => $satuanHTML,
			'data_result' => $data_result,
			'arrayRequest' => $arrayRequest,
			'arrayDataCheck' => $arrayDataCheck,
			'project_code' => $project_code
		);
		$this->load->view('Instalation/modalAdd', $data);
	}

	public function get_data_json_modal_add(){
		$requestData	= $_REQUEST;
		$fetch			= $this->query_data_json_modal_add(
			$requestData['tipe'],
			$requestData['search']['value'],
			$requestData['order'][0]['column'],
			$requestData['order'][0]['dir'],
			$requestData['start'],
			$requestData['length']
		);
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		$urut1  = 1;
        $urut2  = 0;

		$satuan = $this->db->get_where('unit',array('deleted'=>'N'))->result_array();
		$Unit = "";
		foreach ($satuan as $key => $value) {
			$sel = (strtolower($value['unit']) == 'pcs')?'selected':'';
			$Unit .= "<option value='".$value['id']."' $sel>".strtoupper($value['unit'])."</option>";
		}
		foreach($query->result_array() as $row)
		{
			$total_data     = $totalData;
            $start_dari     = $requestData['start'];
            $asc_desc       = $requestData['order'][0]['dir'];
            if($asc_desc == 'asc')
            {
                $nomor = $urut1 + $start_dari;
            }
            if($asc_desc == 'desc')
            {
                $nomor = ($total_data - $start_dari) - $urut2;
            }

			$readonly = 'readonly';
			if($requestData['tipe'] == 'man power'){
				$readonly = '';
			}

			$nestedData 	= array();
			$nestedData[]	= "<div align='center'>".$nomor."
								<input type='hidden' class='id' name='detailx[".$nomor."][id]' value='".$row['code_group']."'>
								<input type='hidden' class='material' name='detailx[".$nomor."][material]' value='".strtoupper($row['ctg']." - ".$row['spec'])."'>
								<input type='hidden' class='tipe' value='".$requestData['tipe']."'>
								<input type='hidden' class='durasi' value='".$row['unit']."'>
								</div>";
			$nestedData[]	= "<div align='left'>".strtoupper($row['ctg']." - ".$row['spec'])."</div>";
			$nestedData[]	= "<div align='left'><input type='text' style='width:100%' name='detailx[".$nomor."][qty]' data-no='".$nomor."'class='qty form-control input-sm text-center autoNumeric0'></div>";
			
			if($requestData['tipe'] == 'man power'){
				$nestedData[]	= "<div align='left'><input type='text' style='width:100%' name='detailx[".$nomor."][durasi]' data-no='".$nomor."'class='durasi form-control input-sm text-center autoNumeric' ".$readonly."><script type='text/javascript'>$('.autoNumeric').autoNumeric();$('.autoNumeric0').autoNumeric('init', {mDec: '0', aPad: false});</script></div>";
			}
			// if($requestData['tipe'] == 'consumable'){
			// 	$nestedData[]	= "<div align='left'><select name='detailx[".$nomor."][durasi]' class='durasi form-control input-sm chosen-select'>".$Unit."</select><script type='text/javascript'>$('.chosen-select').chosen({width:'100%'});</script></div>";
			// }
			
			$nestedData[]	= "<div align='center'><button type='button' class='btn btn-primary btn-sm pindahkan' title='Pindahkan'><i class='fa fa-location-arrow'></i></button></div>";

			$data[] = $nestedData;
            $urut1++;
            $urut2++;
		}

		$json_data = array(
			"draw"            	=> intval( $requestData['draw'] ),
			"recordsTotal"    	=> intval( $totalData ),
			"recordsFiltered" 	=> intval( $totalFiltered ),
			"data"            	=> $data
		);

		echo json_encode($json_data);
	}

	public function query_data_json_modal_add($tipe, $like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){
		
		$table = 'man_power_new';
		$table_join = 'man_power_category';
		$field = 'category';
		$where = '';
		if($tipe == 'consumable'){
			$table = 'con_nonmat_new';
			$table_join = 'con_nonmat_category';
			$field = 'category_code';
			$where = '';
		}
		if($tipe == 'tools'){
			$table = 'vehicle_tool_new';
			$table_join = 'vehicle_tool_category';
			$field = 'id_category';
			$where = "AND id_category != '6' ";
		}
		if($tipe == 'safety'){
			$table = 'vehicle_tool_new';
			$table_join = 'vehicle_tool_category';
			$field = 'id_category';
			$where = "AND id_category = '6' ";
		}
		
		$sql = "
				SELECT
					(@row:=@row+1) AS nomor,
					b.*,
					c.category AS ctg
				FROM
					".$table." b
					LEFT JOIN $table_join c ON b.$field = c.id,
					(SELECT @row:=0) r
				WHERE b.deleted_date IS NULL ".$where."
				AND(
					b.spec LIKE '%".$this->db->escape_like_str($like_value)."%'
					OR c.category LIKE '%".$this->db->escape_like_str($like_value)."%'
				)
			";
		// echo $sql; exit;

		$data['totalData'] 		= $this->db->query($sql)->num_rows();
		$data['totalFiltered'] 	= $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'category',
			2 => 'spec'
		);

		$sql .= " ORDER BY c.category, b.spec, ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function save_add(){
		$Arr_Kembali	= array();
		$data			= $this->input->post();
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		// print_r($data);
		// exit;

		$project_code	= $data['project_code'];
		$tipe			= $data['tipex'];

		$detail			= $data['detail'];
		$area = get_name('project_header','region','project_code',$project_code);
		$ArrDetail = array();
		$ArrMeal = array();
		foreach($detail AS $val => $valx){

			if($tipe == 'consumable'){
				$get_master 	= $this->db->get_where('con_nonmat_new', array('code_group'=>$valx['id']))->result();
				$get_category 	= $this->db->get_where('con_nonmat_category', array('id'=>$get_master[0]->category_code))->result();
				$category 		= (!empty($get_category))?$get_category[0]->category:'not found';
			}
			if($tipe == 'tools' OR $tipe == 'safety'){
				$get_master 	= $this->db->get_where('vehicle_tool_new', array('code_group'=>$valx['id']))->result();
				$get_category 	= $this->db->get_where('vehicle_tool_category', array('id'=>$get_master[0]->id_category))->result();
				$category 		= (!empty($get_category))?$get_category[0]->category:'not found';
			}
			if($tipe == 'man power'){
				$get_master 	= $this->db->get_where('man_power_new', array('code_group'=>$valx['id']))->result();
				$get_category 	= $this->db->get_where('man_power_category', array('id'=>$get_master[0]->category))->result();
				$category 		= (!empty($get_category))?$get_category[0]->category:'not found';
			}
			
			$tot_hari = (!empty($valx['durasi']))?str_replace(',','',$valx['durasi']):0;
			$tot_qty = str_replace(',','',$valx['qty']);

			$ArrDetail[$val]['project_code'] 	= $project_code;
			$ArrDetail[$val]['code_group'] 		= $valx['id'];
			$ArrDetail[$val]['category'] 		= $category;
			$ArrDetail[$val]['spec'] 			= $get_master[0]->spec;
			$ArrDetail[$val]['qty'] 			= $tot_qty;
			$ArrDetail[$val]['tipe'] 			= $tipe;
			$ArrDetail[$val]['jml_hari'] 		= $tot_hari;
			if($tipe != 'man power'){
				$id_unit = (!empty($valx['durasi']))?number_format(str_replace(',','',$valx['durasi'])):6;
				$name_unit = strtolower(get_name('unit','unit','id',$id_unit));
				$get_rate = $this->db->get_where('price_ref', array('code_group'=>$valx['id'],'LOWER(unit_material)'=>$name_unit,'sts_price'=>'N','deleted'=>'N'))->result();
				$rate = (!empty($get_rate))?$get_rate[0]->rate:0;
				$ArrDetail[$val]['unit'] 		= $name_unit;
				$ArrDetail[$val]['id_unit'] 	= $id_unit;
				$ArrDetail[$val]['rate'] 		= $rate;
				$ArrDetail[$val]['rate_unit'] 	= $rate * $tot_qty;
				$ArrDetail[$val]['total_rate'] 	= $rate * $tot_qty;

			}

			if($tipe == 'man power'){
				$get_price_ref 	= $this->db->get_where('price_ref', array('code_group'=>$valx['id'], 'deleted'=>'N', 'sts_price'=>'N', 'region'=>'all region'))->result();
				$rate	 		= (!empty($get_price_ref))?$get_price_ref[0]->rate / 22 : 0;
				$rate_ot 		= (!empty($get_price_ref))?$get_price_ref[0]->rate_ot : 0;
				$rate_us 		= (!empty($get_price_ref))?$get_price_ref[0]->rate_us : 0;
				$rate_um 		= (!empty($get_price_ref))?$get_price_ref[0]->rate_um : 0;

				$cal_tot_mp 	= $tot_qty * $tot_hari * $rate;
				$cal_tot_ot 	= $tot_qty * $tot_hari * 4 * $rate_ot;
				$cal_tot_us 	= $tot_qty * $tot_hari * $rate_us;
				$cal_tot_um 	= $tot_qty * $tot_hari * $rate_um;

				$cal_tot 		= $cal_tot_mp + $cal_tot_ot + $cal_tot_us + $cal_tot_um;

				$ArrDetail[$val]['jml_jam'] 	= 4;
				$ArrDetail[$val]['unit'] 		= 'month';
				$ArrDetail[$val]['rate'] 		= $rate;
				$ArrDetail[$val]['rate_ot'] 	= $rate_ot;
				$ArrDetail[$val]['rate_us'] 	= $rate_us;
				$ArrDetail[$val]['rate_um'] 	= $rate_um;
				$ArrDetail[$val]['rate_unit'] 	= $cal_tot_mp;
				$ArrDetail[$val]['total_rate'] 	= $cal_tot;

				$ArrMeal[$val]['project_code']	= $project_code;
				$ArrMeal[$val]['category_ak'] 	= 'meal';
				$ArrMeal[$val]['code_group'] 	= $valx['id'];
				$ArrMeal[$val]['category'] 		= $category;
				$ArrMeal[$val]['spec'] 			= $get_master[0]->spec;
				$ArrMeal[$val]['rate'] 			= rate($valx['id'], 'day', 'man power', "all region");
				$ArrMeal[$val]['area'] 			= $area;
				$ArrMeal[$val]['jml_orang'] 	= str_replace(',','',$valx['qty']);
				$ArrMeal[$val]['jml_hari'] 		= str_replace(',','',$valx['durasi']);
			}
		}

		// print_r($ArrDetail);
		// print_r($ArrMeal);
		// exit;


		$this->db->trans_start();
			$this->db->delete('project_detail_process', array('tipe'=>$tipe,'project_code'=>$project_code));
			if(!empty($ArrDetail)){
				$this->db->insert_batch('project_detail_process', $ArrDetail);
			}
			if(!empty($ArrMeal)){
				$this->db->delete('project_detail_akomodasi', array('category_ak'=>'meal','project_code'=>$project_code));
				$this->db->insert_batch('project_detail_akomodasi', $ArrMeal);
			}
		$this->db->trans_complete();


		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Process data failed. Please try again later ...',
				'status'	=> 2,
				'kode'		=> $project_code
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Process data success. Thanks ...',
				'status'	=> 1,
				'kode'		=> $project_code
			);
			history('Save add instalation code '.$project_code);
		}

		echo json_encode($Arr_Kembali);
		
	}

	public function view_ipp(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		// echo $Arr_Akses['create']; exit;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$db2 = $this->load->database('costing', TRUE);
		//customer
		$dataCust		= "SELECT id_customer, nm_customer FROM customer WHERE id_customer <> 'C100-1903000' ORDER BY nm_customer ASC";
		$restCust		= $db2->query($dataCust)->result_array();
		
		$ipp 			= $this->uri->segment(3);
		$tanda 			= $this->uri->segment(4);
		$header 		= $this->db->get_where('ipp_header', array('no_ipp'=>$ipp))->result();

		$data = array(
			'title'			=> 'Identification Of Customer Requests',
			'action'		=> 'add',
			'ipp'			=> $ipp,
			'tanda'			=> $tanda,
			'header'		=> $header,
			'cust'			=> $restCust
		);
		$this->load->view('Sales/add2',$data);
	}

	public function get_add(){
		$id 	= $this->uri->segment(3);
		$no 	= 0;
		$d_Header = "";
			$d_Header .= "<tr class='header_".$id."'>";
				$d_Header .= "<td align='left'>";
					$d_Header .= "<input type='text' name='Detail[".$id."][pekerjaan]' class='form-control input-sm' placeholder='Pekerjaan'>";
				$d_Header .= "</td>";
				$d_Header .= "<td colspan='4'></td>";
				$d_Header .= "<td align='center'>";
				$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delPart' title='Delete Part'><i class='fa fa-close'></i></button>";
				$d_Header .= "</td>";
			$d_Header .= "</tr>";

		//add nya
		$d_Header .= "<tr id='add_".$id."_".$no."' class='header_".$id."'>";
			$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-primary addSubPart' title='Add Detail'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Detail</button></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		//add part
		$d_Header .= "<tr id='add_".$id."'>";
			$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-warning addPart' title='Add'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Pekerjaan</button></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		 echo json_encode(array(
				'header'			=> $d_Header,
		 ));
	}

	public function get_add_sub(){
		$id 	= $this->uri->segment(3);
    	$no 	= $this->uri->segment(4);
		// echo $qListResin; exit;
		$d_Header = "";
			$d_Header .= "<tr class='header_".$id."'>";
				$d_Header .= "<td align='left' style='vertical-align:middle; padding-left: 30px;'>";
					$d_Header .= "<input type='text' name='Detail[".$id."][detail][".$no."][pekerjaan_detail]' class='form-control input-sm' placeholder='Pekerjaan' >";
				$d_Header .= "</td>";
				$d_Header .= "<td align='left'>";
					$d_Header .= "<input type='text' name='Detail[".$id."][detail][".$no."][qty]' class='form-control input-sm text-center autoNumeric0' placeholder='Qty' >";
				$d_Header .= "</td>";
				$d_Header .= "<td align='left'>";
					$d_Header .= "<input type='text' name='Detail[".$id."][detail][".$no."][satuan]' class='form-control input-sm text-center' placeholder='Satuan''>";
				$d_Header .= "</td>";
				$d_Header .= "<td align='left'>";
					$d_Header .= "<input type='text' name='Detail[".$id."][detail][".$no."][mp]' class='form-control input-sm text-center autoNumeric0' placeholder='Man Power'>";
				$d_Header .= "</td>";
				$d_Header .= "<td align='left'>";
					$d_Header .= "<input type='text' name='Detail[".$id."][detail][".$no."][jumlah_hari]' class='form-control input-sm text-center autoNumeric' placeholder='Jumlah Hari'>";
				$d_Header .= "</td>";
				$d_Header .= "<td align='center'>";
					$d_Header .= "&nbsp;<button type='button' class='btn btn-sm btn-danger delSubPart' title='Delete Part'><i class='fa fa-close'></i></button>";
				$d_Header .= "</td>";
			$d_Header .= "</tr>";

		//add nya
		$d_Header .= "<tr id='add_".$id."_".$no."' class='header_".$id."'>";
			$d_Header .= "<td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-sm btn-primary addSubPart' title='Add Detail'><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Detail</button></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
			$d_Header .= "<td align='center'></td>";
		$d_Header .= "</tr>";

		 echo json_encode(array(
				'header'			=> $d_Header,
		 ));
	}

	public function getTujuan(){
		$data1 		= $this->input->post('data1');

		$restSup	= api_get_mde_tujuan($data1);

		$option	= "<option value='0'>Select An Destination</option>";
		foreach($restSup AS $val => $valx){
			$option .= "<option value='".$valx['tujuan']."'>".strtoupper($valx['tujuan'])."</option>";
		}

		$ArrJson	= array(
			'option' => $option
		);
		echo json_encode($ArrJson);
	}

	public function getTruck(){
		$data1 		= $this->input->post('data1');
		$data2 		= $this->input->post('data2');

		$restSup	= api_get_mde_kendaraan($data1, $data2);

		$option	= "<option value='0'>Select An Truck</option>";
		foreach($restSup AS $val => $valx){
			$option .= "<option value='".$valx['id_truck']."'>".strtoupper($valx['nama_truck'])."</option>";
		}

		$ArrJson	= array(
			'option' => $option
		);
		echo json_encode($ArrJson);
	}

	public function download_tmp_1(){
		//membuat objek PHPExcel
		$project_code = $this->uri->segment(3);

		set_time_limit(0);
		ini_set('memory_limit','1024M');

		$this->load->library("PHPExcel");
		// $this->load->library("PHPExcel/Writer/Excel2007");
		$objPHPExcel	= new PHPExcel();
		
		$style_header = array(
			'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'e0e0e0'),
			),
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);

		$style_header2 = array(	
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'e0e0e0'),
			),
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);

		$styleArray = array(					  
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			  ),
			  'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			)
		  );
		$styleArray3 = array(					  
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
			  ),
			  'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			)
		  );  
		 $styleArray4 = array(					  
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
			  ),
			  'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			)
		  );  
	    $styleArray1 = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  ),
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		  );
		$styleArray2 = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  ),
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		  );
		 
		$Arr_Bulan	= array(1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec'); 
		$sheet 		= $objPHPExcel->getActiveSheet();
		
		$Row		= 1;
		$NewRow		= $Row;
		// $Col_Akhir	= $Cols	= getColsChar(20);
		// $sheet->setCellValue('A'.$Row, 'TEMPLETE UPLOAD FRP TANKI');
		// $sheet->getStyle('A'.$Row.':'.$Col_Akhir.$NewRow)->applyFromArray($style_header2);
		// $sheet->mergeCells('A'.$Row.':'.$Col_Akhir.$NewRow);
		
		// $NewRow	= $NewRow +2;
		$NextRow= $NewRow;
		$NextRow3= $NewRow+2;
		$NextRow2= $NewRow+1;
		
		$sheet->setCellValue('A'.$NewRow, 'Category');
		$sheet->getStyle('A'.$NewRow.':A'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('A'.$NewRow.':A'.$NewRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);
		
		$sheet->setCellValue('B'.$NewRow, 'Code');
		$sheet->getStyle('B'.$NewRow.':B'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('B'.$NewRow.':B'.$NewRow);
        $sheet->getColumnDimension('B')->setWidth(16);
        
        $sheet->setCellValue('C'.$NewRow, 'Category Item');
		$sheet->getStyle('C'.$NewRow.':C'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('C'.$NewRow.':C'.$NewRow);
        $sheet->getColumnDimension('C')->setWidth(16);
        
        $sheet->setCellValue('D'.$NewRow, 'Spec');
		$sheet->getStyle('D'.$NewRow.':D'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('D'.$NewRow.':D'.$NewRow);
        $sheet->getColumnDimension('D')->setWidth(16);

		$sheet->setCellValue('E'.$NewRow, 'Qty');
		$sheet->getStyle('E'.$NewRow.':E'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('E'.$NewRow.':E'.$NewRow);
        $sheet->getColumnDimension('E')->setWidth(16);

		$sheet->setCellValue('F'.$NewRow, 'Jml Orang');
		$sheet->getStyle('F'.$NewRow.':F'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('F'.$NewRow.':F'.$NewRow);
        $sheet->getColumnDimension('F')->setWidth(16);

		$sheet->setCellValue('G'.$NewRow, 'Jml Hari');
		$sheet->getStyle('G'.$NewRow.':G'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('G'.$NewRow.':G'.$NewRow);
        $sheet->getColumnDimension('G')->setWidth(16);

		$sheet->setCellValue('H'.$NewRow, 'Note');
		$sheet->getStyle('H'.$NewRow.':H'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('H'.$NewRow.':H'.$NewRow);
        $sheet->getColumnDimension('H')->setWidth(16);

		$sheet->setCellValue('I'.$NewRow, 'Area');
		$sheet->getStyle('I'.$NewRow.':I'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('I'.$NewRow.':I'.$NewRow);
        $sheet->getColumnDimension('I')->setWidth(16);

		$sheet->setCellValue('J'.$NewRow, 'Tujuan');
		$sheet->getStyle('J'.$NewRow.':J'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('J'.$NewRow.':J'.$NewRow);
        $sheet->getColumnDimension('J')->setWidth(16);

		$sheet->setCellValue('K'.$NewRow, 'Kode Truck');
		$sheet->getStyle('K'.$NewRow.':K'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('K'.$NewRow.':K'.$NewRow);
        $sheet->getColumnDimension('K')->setWidth(16);

		$sheet->setCellValue('L'.$NewRow, 'Origin');
		$sheet->getStyle('L'.$NewRow.':L'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('L'.$NewRow.':L'.$NewRow);
        $sheet->getColumnDimension('L')->setWidth(16);

		$sheet->setCellValue('M'.$NewRow, 'Round-Trip');
		$sheet->getStyle('M'.$NewRow.':M'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('M'.$NewRow.':M'.$NewRow);
        $sheet->getColumnDimension('M')->setWidth(16);
        
		$house = $this->db->select('*')->get_where('akomodasi_new', array('id_category'=> '2','deleted'=>'N'))->result_array();
		$etc = $this->db->select('*')->get_where('akomodasi_new', array('id_category'=> '1','deleted'=>'N'))->result_array();
		$trans = $this->db->select('*')->get_where('list', array('category'=> 'transportasi','category_ !='=>'item cost'))->result_array();
		$mde = $this->db->select('*')->get_where('akomodasi_new', array('id_category'=> '7','deleted'=>'N'))->result_array();
		$survey = $this->db->select('*')->get_where('akomodasi_new', array('id_category'=> '5','deleted'=>'N'))->result_array();
		$covid = $this->db->select('*')->get_where('akomodasi_new', array('id_category'=> '6','deleted'=>'N'))->result_array();

		// print_r($check_temp); exit;
		//datannya
		$awal_row	= $NextRow;
		$a = 0;
		foreach($house AS $val => $valx){
			$a++;
			$awal_row++;
			$awal_col	= 0;

			$awal_col++;
			$id_category		= 'house';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $id_category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$code_group		= $valx['code_group'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $code_group);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$category		= $valx['category'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= $valx['spec'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$qty		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $qty);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$orang		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $orang);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$hari		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $hari);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$note		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $note);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$area		= 'month';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $area);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$tujuan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $tujuan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$kendaraan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $kendaraan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$asal		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $asal);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$pulang_pergi		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $pulang_pergi);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);
		}

		for($z=1; $z <= 3; $z++){
			$a++;
			$awal_row++;
			$awal_col	= 0;

			$awal_col++;
			$id_category		= 'testing';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $id_category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$code_group		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $code_group);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$category		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$spec		= 'testing '.$z;
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$qty		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $qty);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$orang		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $orang);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$hari		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $hari);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$note		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $note);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$area		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $area);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$tujuan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $tujuan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$kendaraan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $kendaraan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$asal		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $asal);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$pulang_pergi		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $pulang_pergi);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);
		}

		foreach($etc AS $val => $valx){
			$a++;
			$awal_row++;
			$awal_col	= 0;

			$awal_col++;
			$id_category		= 'etc';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $id_category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$code_group		= $valx['code_group'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $code_group);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$category		= $valx['category'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= $valx['spec'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$qty		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $qty);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$orang		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $orang);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$hari		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $hari);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$note		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $note);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$area		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $area);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$tujuan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $tujuan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$kendaraan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $kendaraan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$asal		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $asal);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$pulang_pergi		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $pulang_pergi);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);
		}

		foreach($trans AS $val => $valx){
			$a++;
			$awal_row++;
			$awal_col	= 0;

			$awal_col++;
			$id_category		= 'trans';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $id_category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$code_group		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $code_group);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$category		= $valx['category_'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= $valx['category_list'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$qty		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $qty);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$orang		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $orang);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$hari		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $hari);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$note		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $note);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$area		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $area);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$tujuan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $tujuan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$kendaraan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $kendaraan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$asal		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $asal);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$pulang_pergi		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $pulang_pergi);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);
		}

		foreach($mde AS $val => $valx){
			$a++;
			$awal_row++;
			$awal_col	= 0;

			$awal_col++;
			$id_category		= 'mde';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $id_category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$code_group		= $valx['code_group'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $code_group);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$category		= $valx['category'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= $valx['spec'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$qty		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $qty);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$orang		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $orang);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$hari		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $hari);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$note		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $note);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$area		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $area);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$tujuan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $tujuan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$kendaraan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $kendaraan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$asal		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $asal);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$pulang_pergi		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $pulang_pergi);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);
		}

		foreach($survey AS $val => $valx){
			$a++;
			$awal_row++;
			$awal_col	= 0;

			$awal_col++;
			$id_category		= 'survey';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $id_category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$code_group		= $valx['code_group'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $code_group);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$category		= $valx['category'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= $valx['spec'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$qty		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $qty);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$orang		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $orang);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$hari		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $hari);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$note		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $note);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$area		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $area);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$tujuan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $tujuan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$kendaraan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $kendaraan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$asal		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $asal);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$pulang_pergi		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $pulang_pergi);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);
		}

		foreach($covid AS $val => $valx){
			$a++;
			$awal_row++;
			$awal_col	= 0;

			$awal_col++;
			$id_category		= 'covid';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $id_category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$code_group		= $valx['code_group'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $code_group);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$category		= $valx['category'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= $valx['spec'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$qty		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $qty);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$orang		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $orang);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$hari		= '1';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $hari);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$note		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $note);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$area		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $area);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$tujuan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $tujuan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$kendaraan		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $kendaraan);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$asal		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $asal);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);

			$awal_col++;
			$pulang_pergi		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $pulang_pergi);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($style_header);
		}
		

		
		$sheet->setTitle('UPLOAD 1');
		//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
		$objWriter		= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		//sesuaikan headernya 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="UPLOAD Akomodasi, Testing, Etc, MD Equipment, Survey, Covid '.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

	public function download_tmp_2(){
		//membuat objek PHPExcel
		$project_code = $this->uri->segment(3);

		set_time_limit(0);
		ini_set('memory_limit','1024M');

		$this->load->library("PHPExcel");
		// $this->load->library("PHPExcel/Writer/Excel2007");
		$objPHPExcel	= new PHPExcel();
		
		$style_header = array(
			'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'e0e0e0'),
			),
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);

		$style_header2 = array(	
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'e0e0e0'),
			),
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);

		$styleArray = array(					  
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			  ),
			  'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			)
		  );
		$styleArray3 = array(					  
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
			  ),
			  'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			)
		  );  
		 $styleArray4 = array(					  
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
			  ),
			  'borders' => array(
				'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN,
					  'color' => array('rgb'=>'000000')
				  )
			)
		  );  
	    $styleArray1 = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  ),
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		  );
		$styleArray2 = array(
			  'borders' => array(
				  'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
				  )
			  ),
			  'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		  );
		 
		$Arr_Bulan	= array(1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Oct','Nov','Dec'); 
		$sheet 		= $objPHPExcel->getActiveSheet();
		
		$Row		= 1;
		$NewRow		= $Row;
		// $Col_Akhir	= $Cols	= getColsChar(20);
		// $sheet->setCellValue('A'.$Row, 'TEMPLETE UPLOAD FRP TANKI');
		// $sheet->getStyle('A'.$Row.':'.$Col_Akhir.$NewRow)->applyFromArray($style_header2);
		// $sheet->mergeCells('A'.$Row.':'.$Col_Akhir.$NewRow);
		
		// $NewRow	= $NewRow +2;
		$NextRow= $NewRow;
		$NextRow3= $NewRow+2;
		$NextRow2= $NewRow+1;
		
		$sheet->setCellValue('A'.$NewRow, 'Category');
		$sheet->getStyle('A'.$NewRow.':A'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('A'.$NewRow.':A'.$NewRow);
		$sheet->getColumnDimension('A')->setAutoSize(true);
		
		$sheet->setCellValue('B'.$NewRow, 'Code');
		$sheet->getStyle('B'.$NewRow.':B'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('B'.$NewRow.':B'.$NewRow);
        $sheet->getColumnDimension('B')->setWidth(16);
        
        $sheet->setCellValue('C'.$NewRow, 'Category Item');
		$sheet->getStyle('C'.$NewRow.':C'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('C'.$NewRow.':C'.$NewRow);
        $sheet->getColumnDimension('C')->setWidth(16);
        
        $sheet->setCellValue('D'.$NewRow, 'Spec');
		$sheet->getStyle('D'.$NewRow.':D'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('D'.$NewRow.':D'.$NewRow);
        $sheet->getColumnDimension('D')->setWidth(16);

		$sheet->setCellValue('E'.$NewRow, 'Qty');
		$sheet->getStyle('E'.$NewRow.':E'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('E'.$NewRow.':E'.$NewRow);
        $sheet->getColumnDimension('E')->setWidth(16);

		$sheet->setCellValue('F'.$NewRow, 'Durasi');
		$sheet->getStyle('F'.$NewRow.':F'.$NewRow)->applyFromArray($style_header);
		$sheet->mergeCells('F'.$NewRow.':F'.$NewRow);
        $sheet->getColumnDimension('F')->setWidth(16);
        
		$mp 	= $this->db->order_by('category')->order_by('spec')->get_where('man_power_new', array('deleted'=>'N'))->result_array();
		$cn 	= $this->db->order_by('category')->order_by('spec')->get_where('con_nonmat_new', array('deleted'=>'N'))->result_array();
		$tools 	= $this->db->order_by('category')->order_by('spec')->get_where('vehicle_tool_new', array('deleted'=>'N','id_category !='=>'6'))->result_array();

		// print_r($check_temp); exit;
		//datannya
		$awal_row	= $NextRow;
		$a = 0;
		foreach($mp AS $val => $valx){
			$a++;
			$awal_row++;
			$awal_col	= 0;

			$awal_col++;
			$id_category		= 'man power';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $id_category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$code_group		= $valx['code_group'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $code_group);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$category		= get_name('man_power_category','category','id',$valx['category']);
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= $valx['spec'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);
		}

		foreach($cn AS $val => $valx){
			$a++;
			$awal_row++;
			$awal_col	= 0;

			$awal_col++;
			$id_category		= 'consumable';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $id_category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$code_group		= $valx['code_group'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $code_group);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$category		= $valx['category'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= $valx['spec'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);
		}

		foreach($tools AS $val => $valx){
			$a++;
			$awal_row++;
			$awal_col	= 0;

			$awal_col++;
			$id_category		= 'tools';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $id_category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$code_group		= $valx['code_group'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $code_group);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$category		= $valx['category'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $category);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= $valx['spec'];
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

			$awal_col++;
			$spec		= '';
			$Cols		= getColsChar($awal_col);
			$sheet->setCellValue($Cols.$awal_row, $spec);
			$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);
		}
		
		$sheet->setTitle('UPLOAD 2');
		//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
		$objWriter		= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		//sesuaikan headernya 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//ubah nama file saat diunduh
		header('Content-Disposition: attachment;filename="UPLOAD ManPower, Consumable, Tools '.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

	public function upload_temp_1(){
		$data = $this->input->post();

		$project_code 	= $data['project_code'];
		$region_code 	= $data['region_code'];

		//Upload Data
		set_time_limit(0);
        ini_set('memory_limit','2048M');
		$Arr_Detail = array();
		if($_FILES['file_1']['name']){
			$exts   = getExtension($_FILES['file_1']['name']);
			if(!in_array($exts,array(1=>'xls','xlsx')))
			{
				$Arr_Kembali		= array(
					'status'		=> 3,
					'pesan'			=> 'Invalid file type, Please Upload the Excel format ...'
				);
			}
			else{
				
				$fileName = $_FILES['file_1']['name'];
				$this->load->library(array('PHPExcel'));
				$config['upload_path'] = './assets/file/'; 
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'xls|xlsx';
				$config['max_size'] = 10000;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				// echo $fileName; exit;

				if (!$this->upload->do_upload('file_1')) {
					$error = array('error' => $this->upload->display_errors());
					$Arr_Kembali		= array(
						'status'		=> 3,
						'pesan'			=> $error['error']
					);
				}
				else{
					// echo 'success!';
					$media = $this->upload->data();
					$inputFileName = './assets/file/'.$media['file_name'];
					
					$data_session	= $this->session->userdata;
					$Create_By      = $data_session['ORI_User']['username'];
					$Create_Date    = date('Y-m-d H:i:s');
						
					try{
						$inputFileType  = PHPExcel_IOFactory::identify($inputFileName);
						$objReader      = PHPExcel_IOFactory::createReader($inputFileType); 
						$objReader->setReadDataOnly(true);                               
						$objPHPExcel    = $objReader->load($inputFileName);
							
					}catch(Exception $e){
						die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());                               
					}

					$sheet = $objPHPExcel->getSheet(0);
					$highestRow     = $sheet->getHighestRow();
					$highestColumn = $sheet->getHighestColumn();
					$Error      = 0;
					$Arr_Keys   = array();
					$Loop       = 0;
					$Total      = 0;
					$Message    = "";
					$Urut       = 0;
					
					
					$intL 		= 0;
					$intError 	= 0;
					$pesan 		= '';
					$status		= '';
					$SUM_MP = 0;
					for ($row = 2; $row <= $highestRow; $row++){ 
						$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL,TRUE,FALSE);
						$Urut++;

						$Arr_Detail[$Urut]['project_code']  = $project_code;

						$tipe								= (isset($rowData[0][0]) && $rowData[0][0])?$rowData[0][0]:'';
						$Arr_Detail[$Urut]['category_ak']  	= trim($tipe);

						$code_group							= (isset($rowData[0][1]) && $rowData[0][1])?$rowData[0][1]:NULL;
						$Arr_Detail[$Urut]['code_group']  	= trim($code_group);

						$category							= (isset($rowData[0][2]) && $rowData[0][2])?$rowData[0][2]:NULL;
						$Arr_Detail[$Urut]['category']  	= trim($category);

						$spec								= (isset($rowData[0][3]) && $rowData[0][3])?$rowData[0][3]:NULL;
						$Arr_Detail[$Urut]['spec']  		= $spec;

						$qty								= (isset($rowData[0][4]) && $rowData[0][4])?$rowData[0][4]:NULL;
						$Arr_Detail[$Urut]['qty']  			= $qty;

						$jml_orang							= (isset($rowData[0][5]) && $rowData[0][5])?$rowData[0][5]:NULL;
						$Arr_Detail[$Urut]['jml_orang']  	= $jml_orang;

						$jml_hari							= (isset($rowData[0][6]) && $rowData[0][6])?$rowData[0][6]:NULL;
						$Arr_Detail[$Urut]['jml_hari']  	= $jml_hari;

						$note								= (isset($rowData[0][7]) && $rowData[0][7])?$rowData[0][7]:NULL;
						$Arr_Detail[$Urut]['note']  		= $note;

						$area								= (isset($rowData[0][8]) && $rowData[0][8])?$rowData[0][8]:NULL;
						$Arr_Detail[$Urut]['area']  		= $area;

						$tujuan								= (isset($rowData[0][9]) && $rowData[0][9])?$rowData[0][9]:NULL;
						$Arr_Detail[$Urut]['tujuan']  		= $tujuan;

						$truck								= (isset($rowData[0][10]) && $rowData[0][10])?$rowData[0][10]:NULL;
						$Arr_Detail[$Urut]['truck']  		= $truck;

						$asal								= (isset($rowData[0][11]) && $rowData[0][11])?$rowData[0][11]:NULL;
						$Arr_Detail[$Urut]['asal']  		= $asal;

						$pulang_pergi						= (isset($rowData[0][12]) && $rowData[0][12])?$rowData[0][12]:NULL;
						$Arr_Detail[$Urut]['pulang_pergi']  = $pulang_pergi;


					}
					// print_r($Arr_Detail);
					// exit;
				}
			}
		}

		$ArrMerge = $Arr_Detail;

		// print_r($ArrMerge);
		// exit;

		$this->db->trans_start();
			$this->db->where('project_code', $project_code);
			$this->db->where('category_ak <>', 'meal');
			$this->db->delete('project_detail_akomodasi');

			if(!empty($ArrMerge)){
				$this->db->insert_batch('project_detail_akomodasi', $ArrMerge);
			}
			
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=>'Process data failed. Please try again later ...',
				'status'	=> 2,
				'project_code'	=> $project_code
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=>'Process data success. Thanks ...',
				'status'	=> 1,
				'project_code'	=> $project_code
			);
			history('Upload Instalasi 1 : '.$project_code);
		}

		echo json_encode($Arr_Kembali);
	}

	public function upload_temp_2(){
		$data = $this->input->post();

		$project_code 	= $data['project_code'];
		$region_code 	= $data['region_code'];

		//Upload Data
		set_time_limit(0);
        ini_set('memory_limit','2048M');
		$Arr_Detail = array();
		$Arr_Meal = array();
		if($_FILES['file_2']['name']){
			$exts   = getExtension($_FILES['file_2']['name']);
			if(!in_array($exts,array(1=>'xls','xlsx')))
			{
				$Arr_Kembali		= array(
					'status'		=> 3,
					'pesan'			=> 'Invalid file type, Please Upload the Excel format ...'
				);
			}
			else{
				
				$fileName = $_FILES['file_2']['name'];
				$this->load->library(array('PHPExcel'));
				$config['upload_path'] = './assets/file/'; 
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'xls|xlsx';
				$config['max_size'] = 10000;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				// echo $fileName; exit;

				if (!$this->upload->do_upload('file_2')) {
					$error = array('error' => $this->upload->display_errors());
					$Arr_Kembali		= array(
						'status'		=> 3,
						'pesan'			=> $error['error']
					);
				}
				else{
					// echo 'success!';
					$media = $this->upload->data();
					$inputFileName = './assets/file/'.$media['file_name'];
					
					$data_session	= $this->session->userdata;
					$Create_By      = $data_session['ORI_User']['username'];
					$Create_Date    = date('Y-m-d H:i:s');
						
					try{
						$inputFileType  = PHPExcel_IOFactory::identify($inputFileName);
						$objReader      = PHPExcel_IOFactory::createReader($inputFileType); 
						$objReader->setReadDataOnly(true);                               
						$objPHPExcel    = $objReader->load($inputFileName);
							
					}catch(Exception $e){
						die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());                               
					}

					$sheet = $objPHPExcel->getSheet(0);
					$highestRow     = $sheet->getHighestRow();
					$highestColumn = $sheet->getHighestColumn();
					$Error      = 0;
					$Arr_Keys   = array();
					$Loop       = 0;
					$Total      = 0;
					$Message    = "";
					$Urut       = 0;
					
					
					$intL 		= 0;
					$intError 	= 0;
					$pesan 		= '';
					$status		= '';
					$SUM_MP = 0;
					for ($row = 2; $row <= $highestRow; $row++){ 
						$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL,TRUE,FALSE);
						$Urut++;

						$Arr_Detail[$Urut]['project_code']  = $project_code;

						$tipe								= (isset($rowData[0][0]) && $rowData[0][0])?$rowData[0][0]:'';
						$Arr_Detail[$Urut]['tipe']  		= trim($tipe);

						$code_group							= (isset($rowData[0][1]) && $rowData[0][1])?$rowData[0][1]:'';
						$Arr_Detail[$Urut]['code_group']  	= trim($code_group);

						$category							= (isset($rowData[0][2]) && $rowData[0][2])?$rowData[0][2]:'';
						$Arr_Detail[$Urut]['category']  	= trim($category);

						$spec								= (isset($rowData[0][3]) && $rowData[0][3])?$rowData[0][3]:'';
						$Arr_Detail[$Urut]['spec']  		= $spec;

						$qty								= (isset($rowData[0][4]) && $rowData[0][4])?$rowData[0][4]:0;
						$Arr_Detail[$Urut]['qty']  			= $qty;

						$jml_hari							= (isset($rowData[0][5]) && $rowData[0][5])?$rowData[0][5]:0;
						$Arr_Detail[$Urut]['jml_hari']  	= $jml_hari;

						$Arr_Detail[$Urut]['id_unit']  		= NULL;
						$Arr_Detail[$Urut]['unit']  		= 'month';
						$Arr_Detail[$Urut]['jml_jam'] 		= 4;
						if($tipe == 'tools'){
							$id_unit = get_name('vehicle_tool_new','unit','code_group',$code_group);
							$nm_unit = get_name('unit','unit','id',$id_unit);
							$Arr_Detail[$Urut]['id_unit']  		= $id_unit;
							$Arr_Detail[$Urut]['unit']  		= strtolower($nm_unit);
							$Arr_Detail[$Urut]['jml_jam'] 		= 1;
						}
						if($tipe == 'consumable'){
							$id_unit = get_name('con_nonmat_new','unit','code_group',$code_group);
							$nm_unit = get_name('unit','unit','id',$id_unit);
							$Arr_Detail[$Urut]['id_unit']  		= $id_unit;
							$Arr_Detail[$Urut]['unit']  		= strtolower($nm_unit);
							$Arr_Detail[$Urut]['jml_jam'] 		= 1;
						}

						$Arr_Detail[$Urut]['area']  		= $region_code;

						if($tipe == 'man power'){
						$SUM_MP += $qty;
						}

						//UANG MAKAKAN
						if($tipe == 'man power'){
							$Arr_Meal[$Urut]['project_code']  = $project_code;
							$Arr_Meal[$Urut]['category_ak']  = 'meal';
							$Arr_Meal[$Urut]['code_group']  = $code_group;
							$Arr_Meal[$Urut]['category']  = $category;
							$Arr_Meal[$Urut]['spec']  = $spec;
							$Arr_Meal[$Urut]['area']  = $region_code;
							$Arr_Meal[$Urut]['jml_orang']  = $qty;
							$Arr_Meal[$Urut]['jml_hari']  = $jml_hari;
						}


					}
					// print_r($Arr_Detail);
					// exit;
				}
			}
		}
		
		$safety = $this->db->order_by('category')->order_by('spec')->get_where('vehicle_tool_new', array('deleted'=>'N','id_category'=>'6'))->result_array();
		$ArrSafety = [];
		foreach($safety AS $valx => $value){
			$nm_unit = get_name('unit','unit','id',$value['unit']);

			$ArrSafety[$valx]['project_code'] 	= $project_code;
			$ArrSafety[$valx]['tipe'] 			= 'safety';
			$ArrSafety[$valx]['code_group'] 	= $value['code_group'];
			$ArrSafety[$valx]['category'] 		= $value['category'];
			$ArrSafety[$valx]['spec'] 			= $value['spec'];
			$ArrSafety[$valx]['qty'] 			= $SUM_MP;
			$ArrSafety[$valx]['jml_hari'] 		= 1;
			$ArrSafety[$valx]['jml_jam'] 		= 1;
			$ArrSafety[$valx]['id_unit'] 		= $value['unit'];
			$ArrSafety[$valx]['unit'] 			= $nm_unit;
			$ArrSafety[$valx]['area'] 			= $region_code;
		}

		$ArrMerge = array_merge($Arr_Detail, $ArrSafety);

		// print_r($ArrMerge);
		// exit;

		$this->db->trans_start();
			$this->db->where('project_code', $project_code);
			$this->db->where('tipe <>', 'heavy equipment');
			$this->db->delete('project_detail_process');

			if(!empty($ArrMerge)){
				$this->db->insert_batch('project_detail_process', $ArrMerge);
			}

			$this->db->where('project_code', $project_code);
			$this->db->where('category_ak', 'meal');
			$this->db->delete('project_detail_akomodasi');

			if(!empty($Arr_Meal)){
				$this->db->insert_batch('project_detail_akomodasi', $Arr_Meal);
			}
			
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=>'Process data failed. Please try again later ...',
				'status'	=> 2,
				'project_code'	=> $project_code
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=>'Process data success. Thanks ...',
				'status'	=> 1,
				'project_code'	=> $project_code
			);
			history('Upload Instalasi 2 : '.$project_code);
		}

		echo json_encode($Arr_Kembali);
	}

	public function kosongkan_data_1(){
		$project_code = $this->input->post('project_code');
		$data_session	= $this->session->userdata;
		
		// exit;
		$this->db->trans_start();
			$this->db->where('project_code', $project_code);
			$this->db->where('category_ak <>', 'meal');
			$this->db->delete('project_detail_akomodasi');
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Failed process data. Please try again later ...',
				'status'	=> 0,
				'project_code'	=> $project_code
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Sucess process data. Thanks ...',
				'status'	=> 0,
				'project_code'	=> $project_code
			);
			history('Clear data upload instalasi 1 : '.$project_code);
		}
		echo json_encode($Arr_Data);
	}

	public function kosongkan_data_2(){
		$project_code = $this->input->post('project_code');
		$data_session	= $this->session->userdata;
		
		// exit;
		$this->db->trans_start();
			$this->db->where('project_code', $project_code);
			$this->db->where('category_ak', 'meal');
			$this->db->delete('project_detail_akomodasi');

			$this->db->where('project_code', $project_code);
			$this->db->where('tipe <>', 'heavy equipment');
			$this->db->delete('project_detail_process');
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Failed process data. Please try again later ...',
				'status'	=> 0,
				'project_code'	=> $project_code
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Sucess process data. Thanks ...',
				'status'	=> 0,
				'project_code'	=> $project_code
			);
			history('Clear data upload instalasi 2 : '.$project_code);
		}
		echo json_encode($Arr_Data);
	}

	

}
