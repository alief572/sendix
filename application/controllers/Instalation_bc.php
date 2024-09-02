<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instalation extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');

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

	public function getDataJSON(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->queryDataJSON(
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

			$nestedData 	= array();
			$nestedData[]	= "<div align='center'>".$nomor."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['project_code']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['project_name']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['region']))."</div>";
			$nestedData[]	= "<div align='left'>".$row['created_by']."</div>";
			$nestedData[]	= "<div align='left'>".date('d-m-Y H:i:s', strtotime($row['created_date']))."</div>";
					$updX	= "";
					$delX	= "";
					$PrintX	= "";
					if($Arr_Akses['update']=='1'){
						$updX	= "&nbsp;<a href='".site_url($this->uri->segment(1)).'/edit/'.$row['project_code']."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
					}
					if($Arr_Akses['delete']=='1'){
						$delX	= "&nbsp;<button class='btn btn-sm btn-danger delete' title='Delete work data' data-project_code='".$row['project_code']."'><i class='fa fa-trash'></i></button>";
					}
					if($Arr_Akses['download']=='1'){
						$PrintX	= "<a href='".site_url($this->uri->segment(1).'/print_bq/'.$row['project_code'])."' class='btn btn-sm btn-success' target='_blank' title='Print Project' data-role='qtip'><i class='fa fa-print'></i></a>";
					}
			$nestedData[]	= "<div align='center'>
									<button type='button' class='btn btn-sm btn-warning detail' title='Detail Work' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
									".$updX."
									".$PrintX."
									".$delX."
									</div>";
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

	public function queryDataJSON($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
			SELECT
				*
			FROM
				project_header
		    WHERE 1=1 AND deleted='N' AND (
				project_name LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR location LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'project_code',
			2 => 'project_name'
		);

		$sql .= " ORDER BY created_date DESC, ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function add(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data					= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime			= date('Y-m-d H:i:s');

			// print_r($data);
			// exit;

			$project_name	= strtolower($data['project_name']);
			$region_code	= strtolower($data['region_code']);
			$location			= strtolower($data['location']);
			$total_time		= strtolower($data['total_time']);
			$ListDetail		= $data['ListDetail'];
			$ListDetailBq	= $data['ListDetailBq'];
			$Ym						= date('ym');
			//pengurutan kode
			$srcMtr				= "SELECT MAX(project_code) as maxP FROM project_header WHERE project_code LIKE 'P".$Ym."%' ";
			$numrowMtr		= $this->db->query($srcMtr)->num_rows();
			$resultMtr		= $this->db->query($srcMtr)->result_array();
			$angkaUrut2		= $resultMtr[0]['maxP'];
			$urutan2			= (int)substr($angkaUrut2, 5, 4);
			$urutan2++;
			$urut2				= sprintf('%04s',$urutan2);
			$project_code		= "P".$Ym.$urut2;
			$region = str_replace('_',' ',$region_code);

			//project_header
			$ArrHeader = array(
				'project_code' 	=> $project_code,
				'project_name' 	=> $project_name,
				'region_code' 	=> $region_code,
				'region' 				=> str_replace('_',' ',$region_code),
				'location' 			=> $location,
				'total_time' 		=> $total_time,
				'created_by' 		=> $data_session['ORI_User']['username'],
				'created_date' 	=> $dateTime
			);
			//project_detail_bq
			$ArrHeaderDetail = array();
			foreach($ListDetailBq AS $val => $valx){
				$ArrHeaderDetail[$val]['project_code'] 	= $project_code;
				$ArrHeaderDetail[$val]['satuan_code'] 	= $valx['satuan_code'];
				$ArrHeaderDetail[$val]['qty'] 					= $valx['qty'];
			}
			// print_r($ArrHeader);
			// print_r($ArrHeaderDetail);
			// exit;
			//detail
			$ArrIntHead = array();
			$ArrInsert 	= array();
			$ArrInsert1 = array();
			$ArrInsert2 = array();
			$ArrInsert3 = array();
			$ArrInsert4 = array();
			$ArrInsert5 = array();
			$nomor = 0;
			foreach($ListDetail AS $val => $valx){
				$nomor++;
				$numPlus = sprintf('%03s',$nomor);
				$project_code_det = $project_code.'-'.$numPlus;
				$NmWork = $this->db->query("SELECT category FROM work_header WHERE code_work='".$valx['code_work']."' AND status='N' ")->result();
				// project_detail_w_header
				$ArrIntHead[$val]['project_code'] 			= $project_code;
				$ArrIntHead[$val]['project_code_det'] 	= $project_code_det;
				$ArrIntHead[$val]['code_work'] 					= $valx['code_work'];
				$ArrIntHead[$val]['category'] 					= $NmWork[0]->category;

				// project_detail_w_det
				$nox=0;
				$noxx=0;
				foreach($valx['work_process'] AS $swat => $valwp){
					$nox++;
					$numPlusx = sprintf('%03s',$nox);
					$code_work_detail = $valx['code_work'].'-'.$numPlusx;
					$ArrInsert[$nomor.$nox]['project_code'] 			= $project_code;
					$ArrInsert[$nomor.$nox]['project_code_det'] 	= $project_code_det;
					$ArrInsert[$nomor.$nox]['code_work_detail'] 	= $code_work_detail;
					$ArrInsert[$nomor.$nox]['code_work'] 					= $valx['code_work'];
					$ArrInsert[$nomor.$nox]['work_process'] 			= $valwp['work_process'];
					$ArrInsert[$nomor.$nox]['std_time'] 					= $valwp['std_time'];

					// project_detail_w_det_vehicle_tool
					$no1=0;
					$no1x=0;
					if(!empty($valwp['vt'])){
						foreach($valwp['vt'] AS $valvt){
							$no1++;
							$numPlus1 					= sprintf('%03s',$no1);
							$code_work_detail_d = $valx['code_work'].'-'.$numPlus.'-'.$numPlus1;
							$restData = $this->db->query("SELECT category, spec FROM view_vehicle_tool WHERE code_group='".$valvt."' LIMIT 1 ")->result();
							$ArrInsert1[$nomor.$nox.$no1]['project_code'] 				= $project_code;
							$ArrInsert1[$nomor.$nox.$no1]['project_code_det'] 		= $project_code_det;
							$ArrInsert1[$nomor.$nox.$no1]['code_work_detail_d'] 	= $code_work_detail_d;
							$ArrInsert1[$nomor.$nox.$no1]['code_work_detail'] 		= $code_work_detail;
							$ArrInsert1[$nomor.$nox.$no1]['code_work'] 						= $valx['code_work'];
							$ArrInsert1[$nomor.$nox.$no1]['code_group'] 					= $valvt;
							$ArrInsert1[$nomor.$nox.$no1]['category'] 						= $restData[0]->category;
							$ArrInsert1[$nomor.$nox.$no1]['spec'] 								= $restData[0]->spec;
							$ArrInsert1[$nomor.$nox.$no1]['rate'] 								= rate($valvt, $region, 'vehicle_tool');
						}
						foreach($valwp['vtqty'] AS $valvtxqty){
							$no1x++;
							$ArrInsert1[$nomor.$nox.$no1x]['qty'] 								= $valvtxqty;
						}
					}

					// project_detail_w_det_con_nonmat
					$no2=0;
					$no2x=0;
					if(!empty($valwp['cn'])){
						foreach($valwp['cn'] AS $valcn){
							$no2++;
							$numPlus2 					= sprintf('%03s',$no2);
							$code_work_detail_d = $valx['code_work'].'-'.$numPlus.'-'.$numPlus2;
							$restData = $this->db->query("SELECT category, spec FROM view_con_nonmat WHERE code_group='".$valcn."' LIMIT 1 ")->result();
							$ArrInsert2[$nomor.$nox.$no2]['project_code'] 				= $project_code;
							$ArrInsert2[$nomor.$nox.$no2]['project_code_det'] 		= $project_code_det;
							$ArrInsert2[$nomor.$nox.$no2]['code_work_detail_d'] 	= $code_work_detail_d;
							$ArrInsert2[$nomor.$nox.$no2]['code_work_detail'] 		= $code_work_detail;
							$ArrInsert2[$nomor.$nox.$no2]['code_work'] 					= $valx['code_work'];
							$ArrInsert2[$nomor.$nox.$no2]['code_group'] 		= $valcn;
							$ArrInsert2[$nomor.$nox.$no2]['category'] 						= $restData[0]->category;
							$ArrInsert2[$nomor.$nox.$no2]['spec'] 								= $restData[0]->spec;
							$ArrInsert2[$nomor.$nox.$no2]['rate'] 								= rate($valcn, $region, 'con_nonmat');
						}
						foreach($valwp['cnqty'] AS $valcnxqty){
							$no2x++;
							$ArrInsert2[$nomor.$nox.$no2x]['qty'] 								= $valcnxqty;
						}
					}

					// project_detail_w_det_man_power
					$no3=0;
					$no3x=0;
					if(!empty($valwp['mp'])){
						foreach($valwp['mp'] AS $valmp){
							$no3++;
							$numPlus3 					= sprintf('%03s',$no3);
							$code_work_detail_d = $valx['code_work'].'-'.$numPlus.'-'.$numPlus3;
							$restData = $this->db->query("SELECT category, spec FROM view_man_power WHERE code_group='".$valmp."' LIMIT 1 ")->result();
							$ArrInsert3[$nomor.$nox.$no3]['project_code'] 				= $project_code;
							$ArrInsert3[$nomor.$nox.$no3]['project_code_det'] 		= $project_code_det;
							$ArrInsert3[$nomor.$nox.$no3]['code_work_detail_d'] 	= $code_work_detail_d;
							$ArrInsert3[$nomor.$nox.$no3]['code_work_detail'] 		= $code_work_detail;
							$ArrInsert3[$nomor.$nox.$no3]['code_work'] 					= $valx['code_work'];
							$ArrInsert3[$nomor.$nox.$no3]['code_group'] 		= $valmp;
							$ArrInsert3[$nomor.$nox.$no3]['category'] 						= $restData[0]->category;
							$ArrInsert3[$nomor.$nox.$no3]['spec'] 								= $restData[0]->spec;
							$ArrInsert3[$nomor.$nox.$no3]['rate'] 								= rate($valmp, $region, 'man_power');
						}
						foreach($valwp['mpqty'] AS $valmpxqty){
							$no3x++;
							$ArrInsert3[$nomor.$nox.$no3x]['qty'] 								= $valmpxqty;
						}
					}

					// project_detail_w_det_apd
					$no4=0;
					$no4x=0;
					if(!empty($valwp['ap'])){
						foreach($valwp['ap'] AS $valap){
							$no4++;
							$numPlus4 					= sprintf('%03s',$no4);
							$code_work_detail_d = $valx['code_work'].'-'.$numPlus.'-'.$numPlus4;
							$restData = $this->db->query("SELECT category, spec FROM view_apd WHERE code_group='".$valap."' LIMIT 1 ")->result();
							$ArrInsert4[$nomor.$nox.$no4]['project_code'] 				= $project_code;
							$ArrInsert4[$nomor.$nox.$no4]['project_code_det'] 		= $project_code_det;
							$ArrInsert4[$nomor.$nox.$no4]['code_work_detail_d'] 	= $code_work_detail_d;
							$ArrInsert4[$nomor.$nox.$no4]['code_work_detail'] 		= $code_work_detail;
							$ArrInsert4[$nomor.$nox.$no4]['code_work'] 					= $valx['code_work'];
							$ArrInsert4[$nomor.$nox.$no4]['code_group'] 		= $valap;
							$ArrInsert4[$nomor.$nox.$no4]['category'] 						= $restData[0]->category;
							$ArrInsert4[$nomor.$nox.$no4]['spec'] 								= $restData[0]->spec;
							$ArrInsert4[$nomor.$nox.$no4]['rate'] 								= rate($valap, $region, 'apd');
						}
						foreach($valwp['apqty'] AS $valapxqty){
							$no4x++;
							$ArrInsert4[$nomor.$nox.$no4x]['qty'] 								= $valapxqty;
						}
					}

					// project_detail_w_det_akomodasi
					$no5=0;
					$no5x=0;
					if(!empty($valwp['ak'])){
						foreach($valwp['ak'] AS $valak){
							$no5++;
							$numPlus5 					= sprintf('%03s',$no5);
							$code_work_detail_d = $valx['code_work'].'-'.$numPlus.'-'.$numPlus5;
							$restData = $this->db->query("SELECT category, spec FROM view_akomodasi WHERE code_group='".$valak."' LIMIT 1 ")->result();
							$ArrInsert5[$nomor.$nox.$no5]['project_code'] 				= $project_code;
							$ArrInsert5[$nomor.$nox.$no5]['project_code_det'] 		= $project_code_det;
							$ArrInsert5[$nomor.$nox.$no5]['code_work_detail_d'] 	= $code_work_detail_d;
							$ArrInsert5[$nomor.$nox.$no5]['code_work_detail'] 		= $code_work_detail;
							$ArrInsert5[$nomor.$nox.$no5]['code_group'] 		= $valak;
							$ArrInsert5[$nomor.$nox.$no5]['code_work'] 					= $valx['code_work'];
							$ArrInsert5[$nomor.$nox.$no5]['category'] 						= $restData[0]->category;
							$ArrInsert5[$nomor.$nox.$no5]['spec'] 								= $restData[0]->spec;
							$ArrInsert5[$nomor.$nox.$no5]['rate'] 								= rate($valak, $region, 'akomodasi');
						}
						foreach($valwp['akqty'] AS $valakxqty){
							$no5x++;
							$ArrInsert5[$nomor.$nox.$no5x]['qty'] 								= $valakxqty;
						}
					}

				}
			}

			// print_r($ArrHeader);
			// print_r($ArrHeaderDetail);
			// print_r($ArrIntHead);
			// print_r($ArrInsert);
			// print_r($ArrInsert1);
			// print_r($ArrInsert2);
			// print_r($ArrInsert3);
			// print_r($ArrInsert4);
			// print_r($ArrInsert5);
			// exit;


			$this->db->trans_start();
				$this->db->insert('project_header', $ArrHeader);
				$this->db->insert_batch('project_detail_bq', $ArrHeaderDetail);
				$this->db->insert_batch('project_detail_w_header', $ArrIntHead);
				$this->db->insert_batch('project_detail_w_det', $ArrInsert);
				if(!empty($ArrInsert1)){
				$this->db->insert_batch('project_detail_w_det_vehicle_tool', $ArrInsert1);
				}
				if(!empty($ArrInsert2)){
				$this->db->insert_batch('project_detail_w_det_con_nonmat', $ArrInsert2);
				}
				if(!empty($ArrInsert3)){
				$this->db->insert_batch('project_detail_w_det_man_power', $ArrInsert3);
				}
				if(!empty($ArrInsert4)){
				$this->db->insert_batch('project_detail_w_det_apd', $ArrInsert4);
				}
				if(!empty($ArrInsert5)){
				$this->db->insert_batch('project_detail_w_det_akomodasi', $ArrInsert5);
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

			$data = array(
				'title'			=> 'Add Instalasi Project',
				'action'		=> 'add',
				'region'		=> $restRegion
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
			$location			= strtolower($data['location']);
			$total_time		= strtolower($data['total_time']);
			$ListDetail		= $data['ListDetail'];
			$ListDetailBq	= $data['ListDetailBq'];
			$Ym						= date('ym');
			$region = str_replace('_',' ',$region_code);

			//project_header
			$ArrHeader = array(
				'project_code' 	=> $project_code,
				'project_name' 	=> $project_name,
				'region_code' 	=> $region_code,
				'region' 				=> str_replace('_',' ',$region_code),
				'location' 			=> $location,
				'total_time' 		=> $total_time,
				'updated_by' 		=> $data_session['ORI_User']['username'],
				'updated_date' 	=> $dateTime
			);
			//project_detail_bq
			$ArrHeaderDetail = array();
			foreach($ListDetailBq AS $val => $valx){
				$ArrHeaderDetail[$val]['project_code'] 	= $project_code;
				$ArrHeaderDetail[$val]['satuan_code'] 	= $valx['satuan_code'];
				$ArrHeaderDetail[$val]['qty'] 					= $valx['qty'];
			}
			// print_r($ArrHeader);
			// print_r($ArrHeaderDetail);
			// exit;
			//detail
			$ArrIntHead = array();
			$ArrInsert 	= array();
			$ArrInsert1 = array();
			$ArrInsert2 = array();
			$ArrInsert3 = array();
			$ArrInsert4 = array();
			$ArrInsert5 = array();
			$nomor = 0;
			foreach($ListDetail AS $val => $valx){
				$nomor++;
				$numPlus = sprintf('%03s',$nomor);
				$project_code_det = $project_code.'-'.$numPlus;
				$NmWork = $this->db->query("SELECT category FROM work_header WHERE code_work='".$valx['code_work']."' AND status='N' ")->result();
				// project_detail_w_header
				$ArrIntHead[$val]['project_code'] 			= $project_code;
				$ArrIntHead[$val]['project_code_det'] 	= $project_code_det;
				$ArrIntHead[$val]['code_work'] 					= $valx['code_work'];
				$ArrIntHead[$val]['category'] 					= $NmWork[0]->category;

				// project_detail_w_det
				$nox=0;
				$noxx=0;
				foreach($valx['work_process'] AS $swat => $valwp){
					$nox++;
					$numPlusx = sprintf('%03s',$nox);
					$code_work_detail = $valx['code_work'].'-'.$numPlusx;
					$ArrInsert[$nomor.$nox]['project_code'] 			= $project_code;
					$ArrInsert[$nomor.$nox]['project_code_det'] 	= $project_code_det;
					$ArrInsert[$nomor.$nox]['code_work_detail'] 	= $code_work_detail;
					$ArrInsert[$nomor.$nox]['code_work'] 					= $valx['code_work'];
					$ArrInsert[$nomor.$nox]['work_process'] 			= $valwp['work_process'];
					$ArrInsert[$nomor.$nox]['std_time'] 					= $valwp['std_time'];

					// project_detail_w_det_vehicle_tool
					$no1=0;
					$no1x=0;
					if(!empty($valwp['vt'])){
						foreach($valwp['vt'] AS $valvt){
							$no1++;
							$numPlus1 					= sprintf('%03s',$no1);
							$code_work_detail_d = $valx['code_work'].'-'.$numPlus.'-'.$numPlus1;
							$restData = $this->db->query("SELECT category, spec FROM view_vehicle_tool WHERE code_group='".$valvt."' LIMIT 1 ")->result();
							$ArrInsert1[$nomor.$nox.$no1]['project_code'] 				= $project_code;
							$ArrInsert1[$nomor.$nox.$no1]['project_code_det'] 		= $project_code_det;
							$ArrInsert1[$nomor.$nox.$no1]['code_work_detail_d'] 	= $code_work_detail_d;
							$ArrInsert1[$nomor.$nox.$no1]['code_work_detail'] 		= $code_work_detail;
							$ArrInsert1[$nomor.$nox.$no1]['code_work'] 						= $valx['code_work'];
							$ArrInsert1[$nomor.$nox.$no1]['code_group'] 					= $valvt;
							$ArrInsert1[$nomor.$nox.$no1]['category'] 						= $restData[0]->category;
							$ArrInsert1[$nomor.$nox.$no1]['spec'] 								= $restData[0]->spec;
							$ArrInsert1[$nomor.$nox.$no1]['rate'] 								= rate($valvt, $region, 'vehicle_tool');
						}
						foreach($valwp['vtqty'] AS $valvtxqty){
							$no1x++;
							$ArrInsert1[$nomor.$nox.$no1x]['qty'] 								= $valvtxqty;
						}
					}

					// project_detail_w_det_con_nonmat
					$no2=0;
					$no2x=0;
					if(!empty($valwp['cn'])){
						foreach($valwp['cn'] AS $valcn){
							$no2++;
							$numPlus2 					= sprintf('%03s',$no2);
							$code_work_detail_d = $valx['code_work'].'-'.$numPlus.'-'.$numPlus2;
							$restData = $this->db->query("SELECT category, spec FROM view_con_nonmat WHERE code_group='".$valcn."' LIMIT 1 ")->result();
							$ArrInsert2[$nomor.$nox.$no2]['project_code'] 				= $project_code;
							$ArrInsert2[$nomor.$nox.$no2]['project_code_det'] 		= $project_code_det;
							$ArrInsert2[$nomor.$nox.$no2]['code_work_detail_d'] 	= $code_work_detail_d;
							$ArrInsert2[$nomor.$nox.$no2]['code_work_detail'] 		= $code_work_detail;
							$ArrInsert2[$nomor.$nox.$no2]['code_work'] 					= $valx['code_work'];
							$ArrInsert2[$nomor.$nox.$no2]['code_group'] 		= $valcn;
							$ArrInsert2[$nomor.$nox.$no2]['category'] 						= $restData[0]->category;
							$ArrInsert2[$nomor.$nox.$no2]['spec'] 								= $restData[0]->spec;
							$ArrInsert2[$nomor.$nox.$no2]['rate'] 								= rate($valcn, $region, 'con_nonmat');
						}
						foreach($valwp['cnqty'] AS $valcnxqty){
							$no2x++;
							$ArrInsert2[$nomor.$nox.$no2x]['qty'] 								= $valcnxqty;
						}
					}

					// project_detail_w_det_man_power
					$no3=0;
					$no3x=0;
					if(!empty($valwp['mp'])){
						foreach($valwp['mp'] AS $valmp){
							$no3++;
							$numPlus3 					= sprintf('%03s',$no3);
							$code_work_detail_d = $valx['code_work'].'-'.$numPlus.'-'.$numPlus3;
							$restData = $this->db->query("SELECT category, spec FROM view_man_power WHERE code_group='".$valmp."' LIMIT 1 ")->result();
							$ArrInsert3[$nomor.$nox.$no3]['project_code'] 				= $project_code;
							$ArrInsert3[$nomor.$nox.$no3]['project_code_det'] 		= $project_code_det;
							$ArrInsert3[$nomor.$nox.$no3]['code_work_detail_d'] 	= $code_work_detail_d;
							$ArrInsert3[$nomor.$nox.$no3]['code_work_detail'] 		= $code_work_detail;
							$ArrInsert3[$nomor.$nox.$no3]['code_work'] 					= $valx['code_work'];
							$ArrInsert3[$nomor.$nox.$no3]['code_group'] 		= $valmp;
							$ArrInsert3[$nomor.$nox.$no3]['category'] 						= $restData[0]->category;
							$ArrInsert3[$nomor.$nox.$no3]['spec'] 								= $restData[0]->spec;
							$ArrInsert3[$nomor.$nox.$no3]['rate'] 								= rate($valmp, $region, 'man_power');
						}
						foreach($valwp['mpqty'] AS $valmpxqty){
							$no3x++;
							$ArrInsert3[$nomor.$nox.$no3x]['qty'] 								= $valmpxqty;
						}
					}

					// project_detail_w_det_apd
					$no4=0;
					$no4x=0;
					if(!empty($valwp['ap'])){
						foreach($valwp['ap'] AS $valap){
							$no4++;
							$numPlus4 					= sprintf('%03s',$no4);
							$code_work_detail_d = $valx['code_work'].'-'.$numPlus.'-'.$numPlus4;
							$restData = $this->db->query("SELECT category, spec FROM view_apd WHERE code_group='".$valap."' LIMIT 1 ")->result();
							$ArrInsert4[$nomor.$nox.$no4]['project_code'] 				= $project_code;
							$ArrInsert4[$nomor.$nox.$no4]['project_code_det'] 		= $project_code_det;
							$ArrInsert4[$nomor.$nox.$no4]['code_work_detail_d'] 	= $code_work_detail_d;
							$ArrInsert4[$nomor.$nox.$no4]['code_work_detail'] 		= $code_work_detail;
							$ArrInsert4[$nomor.$nox.$no4]['code_work'] 					= $valx['code_work'];
							$ArrInsert4[$nomor.$nox.$no4]['code_group'] 		= $valap;
							$ArrInsert4[$nomor.$nox.$no4]['category'] 						= $restData[0]->category;
							$ArrInsert4[$nomor.$nox.$no4]['spec'] 								= $restData[0]->spec;
							$ArrInsert4[$nomor.$nox.$no4]['rate'] 								= rate($valap, $region, 'apd');
						}
						foreach($valwp['apqty'] AS $valapxqty){
							$no4x++;
							$ArrInsert4[$nomor.$nox.$no4x]['qty'] 								= $valapxqty;
						}
					}

					// project_detail_w_det_akomodasi
					$no5=0;
					$no5x=0;
					if(!empty($valwp['ak'])){
						foreach($valwp['ak'] AS $valak){
							$no5++;
							$numPlus5 					= sprintf('%03s',$no5);
							$code_work_detail_d = $valx['code_work'].'-'.$numPlus.'-'.$numPlus5;
							$restData = $this->db->query("SELECT category, spec FROM view_akomodasi WHERE code_group='".$valak."' LIMIT 1 ")->result();
							$ArrInsert5[$nomor.$nox.$no5]['project_code'] 				= $project_code;
							$ArrInsert5[$nomor.$nox.$no5]['project_code_det'] 		= $project_code_det;
							$ArrInsert5[$nomor.$nox.$no5]['code_work_detail_d'] 	= $code_work_detail_d;
							$ArrInsert5[$nomor.$nox.$no5]['code_work_detail'] 		= $code_work_detail;
							$ArrInsert5[$nomor.$nox.$no5]['code_group'] 		= $valak;
							$ArrInsert5[$nomor.$nox.$no5]['code_work'] 					= $valx['code_work'];
							$ArrInsert5[$nomor.$nox.$no5]['category'] 						= $restData[0]->category;
							$ArrInsert5[$nomor.$nox.$no5]['spec'] 								= $restData[0]->spec;
							$ArrInsert5[$nomor.$nox.$no5]['rate'] 								= rate($valak, $region, 'akomodasi');
						}
						foreach($valwp['akqty'] AS $valakxqty){
							$no5x++;
							$ArrInsert5[$nomor.$nox.$no5x]['qty'] 								= $valakxqty;
						}
					}

				}
			}

			// print_r($ArrHeader);
			// print_r($ArrHeaderDetail);
			// print_r($ArrIntHead);
			// print_r($ArrInsert);
			// print_r($ArrInsert1);
			// print_r($ArrInsert2);
			// print_r($ArrInsert3);
			// print_r($ArrInsert4);
			// print_r($ArrInsert5);
			// exit;


			$this->db->trans_start();

				$this->db->delete('project_detail_bq', array('project_code' => $project_code));
				$this->db->delete('project_detail_w_header', array('project_code' => $project_code));
				$this->db->delete('project_detail_w_det', array('project_code' => $project_code));
				$this->db->delete('project_detail_w_det_vehicle_tool', array('project_code' => $project_code));
				$this->db->delete('project_detail_w_det_con_nonmat', array('project_code' => $project_code));
				$this->db->delete('project_detail_w_det_man_power', array('project_code' => $project_code));
				$this->db->delete('project_detail_w_det_apd', array('project_code' => $project_code));
				$this->db->delete('project_detail_w_det_akomodasi', array('project_code' => $project_code));


				$this->db->where('project_code', $project_code);
				$this->db->update('project_header', $ArrHeader);

				$this->db->insert_batch('project_detail_bq', $ArrHeaderDetail);
				$this->db->insert_batch('project_detail_w_header', $ArrIntHead);
				$this->db->insert_batch('project_detail_w_det', $ArrInsert);
				if(!empty($ArrInsert1)){
				$this->db->insert_batch('project_detail_w_det_vehicle_tool', $ArrInsert1);
				}
				if(!empty($ArrInsert2)){
				$this->db->insert_batch('project_detail_w_det_con_nonmat', $ArrInsert2);
				}
				if(!empty($ArrInsert3)){
				$this->db->insert_batch('project_detail_w_det_man_power', $ArrInsert3);
				}
				if(!empty($ArrInsert4)){
				$this->db->insert_batch('project_detail_w_det_apd', $ArrInsert4);
				}
				if(!empty($ArrInsert5)){
				$this->db->insert_batch('project_detail_w_det_akomodasi', $ArrInsert5);
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

			$qHeader 		= "SELECT * FROM project_header WHERE project_code='".$project_code."'";
			$qDetail 		= "SELECT * FROM project_detail_w_header WHERE project_code='".$project_code."' AND deleted='N'";
			$qDetailBQ 	= "SELECT * FROM project_detail_bq WHERE project_code='".$project_code."' AND deleted='N'";

			$restHeader = $this->db->query($qHeader)->result();
			$restDetail = $this->db->query($qDetail)->result_array();
			$restDetBQ 	= $this->db->query($qDetailBQ)->result_array();

			//list region
			$qRegion		= "SELECT * FROM region ORDER BY urut ASC";
			$restRegion	= $this->db->query($qRegion)->result_array();

			//list satuan
			$qSatuan		= "SELECT * FROM satuan_bq_project";
			$restSatuan	= $this->db->query($qSatuan)->result_array();

			//list work
			$qWork		= "SELECT * FROM work_header ORDER BY category ASC";
			$restWork	= $this->db->query($qWork)->result_array();

			$data = array(
				'title'			=> 'Edit Instalasi Project',
				'action'		=> 'edit',
				'region'		=> $restRegion,
				'satuan'		=> $restSatuan,
				'work'			=> $restWork,
				'header'		=> $restHeader,
				'detail'		=> $restDetail,
				'detail_bq'	=> $restDetBQ

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

	 public function list_work(){
 	   	$query	 	= "SELECT * FROM work_header ORDER BY category ASC";
 	  	$Q_result	= $this->db->query($query)->result();
 	  	$option 	= "<option value='0'>Select an Option</option>";
 	  	foreach($Q_result as $row)	{
 		   $option .= "<option value='".$row->code_work."'>".ucwords(strtolower($row->category))."</option>";
 	   	}
 		echo json_encode(array(
 			'option' => $option
 		));
  }

	// public function list_work_det(){
	// 		$code_work 	= $this->uri->segment(3);
	// 		$nomor 			= $this->uri->segment(4);
	//
	// 	 	$query	 		= "SELECT * FROM work_detail WHERE code_work='".$code_work."' AND deleted='N'";
	// 	 	$numRow			= $this->db->query($query)->num_rows();
	//
	//  echo json_encode(array(
	// 	 	'loop' 		=> $numRow,
	// 		'code_work' => $code_work,
	// 	 	'nomor'			=> $nomor
	//  ));

	public function list_work_det(){
			$code_work 	= $this->uri->segment(3);
			$nomor 			= $this->uri->segment(4);

			//list
			$akomodasi	= $this->db->query("SELECT * FROM view_akomodasi ORDER BY category ASC, spec ASC")->result_array();
			$apd				= $this->db->query("SELECT * FROM view_apd ORDER BY category ASC, spec ASC")->result_array();
			$consumable	= $this->db->query("SELECT * FROM view_con_nonmat ORDER BY category ASC, spec ASC")->result_array();
			$man_power	= $this->db->query("SELECT * FROM view_man_power ORDER BY category ASC, spec ASC")->result_array();
			$vehicle		= $this->db->query("SELECT * FROM view_vehicle_tool ORDER BY category ASC, spec ASC")->result_array();

		 	$query	 		= "SELECT * FROM work_detail WHERE code_work='".$code_work."' AND deleted='N'";
		 	$numRow			= $this->db->query($query)->num_rows();
			$restQuery	= $this->db->query($query)->result_array();
			$no = 0;
			$Rowx = "";
			foreach($restQuery AS $val => $valx){
				$no++;
				//vehicle and Tools
				$restVT = $this->db->query("SELECT * FROM work_detail_vehicle_tool WHERE code_work_detail='".$valx['code_work_detail']."' AND deleted='N' ORDER BY id ASC ")->result_array();
				$restCN = $this->db->query("SELECT * FROM work_detail_con_nonmat WHERE code_work_detail='".$valx['code_work_detail']."' AND deleted='N' ORDER BY id ASC ")->result_array();
				$restMP = $this->db->query("SELECT * FROM work_detail_man_power WHERE code_work_detail='".$valx['code_work_detail']."' AND deleted='N' ORDER BY id ASC ")->result_array();
				$restAP = $this->db->query("SELECT * FROM work_detail_apd WHERE code_work_detail='".$valx['code_work_detail']."' AND deleted='N' ORDER BY id ASC ")->result_array();
				$restAK = $this->db->query("SELECT * FROM work_detail_akomodasi WHERE code_work_detail='".$valx['code_work_detail']."' AND deleted='N' ORDER BY id ASC ")->result_array();


				$Rowx .= "<tr id='tr_".$nomor."_".$no."'>";
				$Rowx .= "<td width='40%'>";
				$Rowx .= "<div class='labDet'>Work Process</div>";
				$Rowx .= "<div class='input-group'>";
				$Rowx .= "<input type='text' name='ListDetail[".$nomor."][work_process][".$no."][work_process]' id='work_process_".$nomor."_".$no."' class='form-control input-md' value='".$valx['work_process']."'>";
				$Rowx .= "<span class='input-group-addon cldelete aDelP'><i class='fa fa-close'></i></span>";
				$Rowx .= "</div>";
				$Rowx .= "<div class='labDet'>Standart Time</div>";
				$Rowx .= "<input type='text' name='ListDetail[".$nomor."][work_process][".$no."][std_time]' id='std_time_".$nomor."_".$no."' class='form-control input-md' placeholder='Standart Time'>";

				$Rowx .= "</td>";
				// Vehicle and Tools
				$Rowx .= "<td width='60%'>";
					$num1 = 0;
					foreach($restVT AS $val_vt => $valx_vt){
						$num1++;
						$Rowx .= "<div>";
						$Rowx .= "<div class='labDet vt_".$nomor."_".$no."_".$num1."'>Vehicles and Tools</div>";
						$Rowx .= "<div class='input-group'>";
						$Rowx .= "<select name='ListDetail[".$nomor."][work_process][".$no."][vt][]' class='form-control input-md vt_".$nomor."_".$no."_".$num1."' width='100%'>";
						foreach($vehicle AS $val_vtList => $valx_vtList){
							$sel1 = ($valx_vtList['code_group'] == $valx_vt['code_group'])?'selected':'';
							$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
						}
						$Rowx .= "</select>";
						$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
						$Rowx .= "<input type='text' name='ListDetail[".$nomor."][work_process][".$no."][vtqty][]' class='form-control widCtr' placeholder='Qty'>";
						$Rowx .= "</div>";
						$Rowx .= "</div>";
					}
					//add komponent
					$Rowx .= "<div id='vt_add_".$nomor."_".$no."'></div>";
					$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num1' data-tanda='vt_add_'>Add Vehicles and Tools</button></div>";

					//Consumable
					$num2 = 0;
					foreach($restCN AS $val_cn => $valx_cn){
						$num2++;
						$Rowx .= "<div>";
						$Rowx .= "<div class='labDet cn_".$nomor."_".$no."_".$num2."'>Consumable</div>";
						$Rowx .= "<div class='input-group'>";
						$Rowx .= "<select name='ListDetail[".$nomor."][work_process][".$no."][cn][]' class='form-control input-md cn_".$nomor."_".$no."_".$num2."''>";
						foreach($consumable AS $val_vtList => $valx_vtList){
							$sel2 = ($valx_vtList['code_group'] == $valx_cn['code_group'])?'selected':'';
							$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel2>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
						}
						$Rowx .= "</select>";
						$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
						$Rowx .= "<input type='text' name='ListDetail[".$nomor."][work_process][".$no."][cnqty][]' class='form-control widCtr' placeholder='Qty'>";
						$Rowx .= "</div>";
						$Rowx .= "</div>";
					}
					//add komponent
					$Rowx .= "<div id='cn_add_".$nomor."_".$no."'></div>";
					$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num1' data-tanda='cn_add_'>Add Consumable</button></div>";


					//Man Power
					$num3 = 0;
					foreach($restMP AS $val_vt => $valx_vt){
						$num3++;
						$Rowx .= "<div>";
						$Rowx .= "<div class='labDet mp_".$nomor."_".$no."_".$num3."''>Man Power</div>";
						$Rowx .= "<div class='input-group'>";
						$Rowx .= "<select name='ListDetail[".$nomor."][work_process][".$no."][mp][]' class='form-control input-md mp_".$nomor."_".$no."_".$num3."''>";
						foreach($man_power AS $val_vtList => $valx_vtList){
							$sel1 = ($valx_vtList['code_group'] == $valx_vt['code_group'])?'selected':'';
							$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
						}
						$Rowx .= "</select>";
						$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
						$Rowx .= "<input type='text' name='ListDetail[".$nomor."][work_process][".$no."][mpqty][]' class='form-control widCtr' placeholder='Qty'>";
						$Rowx .= "</div>";
						$Rowx .= "</div>";
					}
					//add komponent
					$Rowx .= "<div id='mp_add_".$nomor."_".$no."'></div>";
					$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num1' data-tanda='mp_add_'>Add Man Power</button></div>";


					//Apd
					$num4 = 0;
					foreach($restAP AS $val_vt => $valx_vt){
						$num4++;
						$Rowx .= "<div>";
						$Rowx .= "<div class='labDet ap_".$nomor."_".$no."_".$num4."''>APD</div>";
						$Rowx .= "<div class='input-group'>";
						$Rowx .= "<select name='ListDetail[".$nomor."][work_process][".$no."][ap][]' class='form-control input-md ap_".$nomor."_".$no."_".$num4."''>";
						foreach($apd AS $val_vtList => $valx_vtList){
							$sel1 = ($valx_vtList['code_group'] == $valx_vt['code_group'])?'selected':'';
							$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
						}
						$Rowx .= "</select>";
						$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
						$Rowx .= "<input type='text' name='ListDetail[".$nomor."][work_process][".$no."][apqty][]' class='form-control widCtr' placeholder='Qty'>";
						$Rowx .= "</div>";
						$Rowx .= "</div>";
					}
					//add komponent
					$Rowx .= "<div id='ap_add_".$nomor."_".$no."'></div>";
					$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num1' data-tanda='ap_add_'>Add APD</button></div>";


					//akomodasi
					$num5 = 0;
					foreach($restAK AS $val_vt => $valx_vt){
						$num5++;
							$Rowx .= "<div>";
							$Rowx .= "<div class='labDet ak_".$nomor."_".$no."_".$num5."''>Akomodasi</div>";
							$Rowx .= "<div class='input-group'>";
							$Rowx .= "<select name='ListDetail[".$nomor."][work_process][".$no."][ak][]' class='form-control input-md ak_".$nomor."_".$no."_".$num5."''>";
							foreach($akomodasi AS $val_vtList => $valx_vtList){
								$sel1 = ($valx_vtList['code_group'] == $valx_vt['code_group'])?'selected':'';
								$Rowx .= "<option value='".$valx_vtList['code_group']."' $sel1>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
							}
							$Rowx .= "</select>";
							$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
							$Rowx .= "<input type='text' name='ListDetail[".$nomor."][work_process][".$no."][akqty][]' class='form-control widCtr' placeholder='Qty'>";
						$Rowx .= "</div>";
						$Rowx .= "</div>";
					}
					//add komponent
					$Rowx .= "<div id='ak_add_".$nomor."_".$no."'></div>";
					$Rowx .= "<div><button type='button' class='btn btn-success btn-sm aAdd' data-num1='$nomor' data-num2='$no' data-numlast='$num1' data-tanda='ak_add_'>Add Akomodasi</button></div>";


				$Rowx .= "</td>";
				$Rowx .= "</tr>";
			}
			$noNext = $no + 1;
			// $Rowx .= "<tr id='tr_".$nomor."_".$noNext."'>";
			// 	$Rowx .= "<td>";
			// 	$Rowx .= "</td>";
			// 	$Rowx .= "<td>";
			// 	$Rowx .= "</td>";
			// $Rowx .= "</tr>";

	 echo json_encode(array(
		 	'loop' 			=> $numRow,
			'code_work' => $code_work,
		 	'nomor'			=> $nomor,
			'rowx'			=> $Rowx
	 ));
 }

 public function add_dropdown(){
			$num1 		= $this->uri->segment(3);
			$num2 		= $this->uri->segment(4);
			$numlast 	= $this->uri->segment(5) + 1;
			$tanda 		= $this->uri->segment(6);

			$tanda_name = substr($tanda, 0, 2);
			if($tanda_name == 'vt'){
				$judul = "Vehicles and Tools";
				$table = "vehicle_tool";
			}
			if($tanda_name == 'cn'){
				$judul = "Consumable";
				$table = "con_nonmat";
			}
			if($tanda_name == 'mp'){
				$judul = "Man Power";
				$table = "man_power";
			}
			if($tanda_name == 'ap'){
				$judul = "APD";
				$table = "apd";
			}
			if($tanda_name == 'ak'){
				$judul = "Akomodasi";
				$table = "akomodasi";
			}
			// echo $num1.'/'.$num2.'/'.$numlast.'/'.$tanda;
			// exit;

		 //list
		 $list		= $this->db->query("SELECT * FROM view_$table ORDER BY category ASC, spec ASC")->result_array();

		$Rowx = "";
		$RowxQ = "";
		$Rowx .= "<div>";
		$Rowx .= "<div class='labDet ".$tanda_name."_".$num1."_".$num2."_".$numlast."'>$judul</div>";
		$Rowx .= "<div class='input-group'>";
		$Rowx .= "<select name='ListDetail[".$num1."][work_process][".$num2."][$tanda_name][]' class='form-control input-md chosen_select' width='100%'>";
		$Rowx .= "<option>Select An Option</option>";
			foreach($list AS $val_vtList => $valx_vtList){
				$Rowx .= "<option value='".$valx_vtList['code_group']."'>".strtoupper($valx_vtList['category']." - ".$valx_vtList['spec'])."</option>";
			}
		$Rowx .= "</select>";
		$Rowx .= "<span class='input-group-addon cldelete aDel'><i class='fa fa-close'></i></span>";
		$Rowx .= "<input type='text' name='ListDetail[".$num1."][work_process][".$num2."][".$tanda_name."qty][]' class='form-control input-md widCtr' placeholder='Qty'>";
		$Rowx .= "</div>";
		$Rowx .= "</div>";

	echo json_encode(array(
		 'num1' 		=> $num1,
		 'num2' 		=> $num2,
		 'numlast'	=> $numlast,
		 'tanda'		=> $tanda,
		 'rowx'			=> $Rowx,
		 'rowxqty'	=> $RowxQ
	));
}

public function modalDetail(){
	$this->load->view('Instalation/modalDetail');
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
}
