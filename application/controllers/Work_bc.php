<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Work extends CI_Controller {

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
			'title'			=> 'Indeks Of Work',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Work');
		$this->load->view('Work/index',$data);
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['category']))."</div>";
			$nestedData[]	= "<div align='left'>".$row['created_by']."</div>";
			$nestedData[]	= "<div align='left'>".date('d-m-Y H:i:s', strtotime($row['created_date']))."</div>";
					$updX	= "";
					$delX	= "";
					if($Arr_Akses['update']=='1'){
						$updX	= "&nbsp;<a href='".site_url($this->uri->segment(1)).'/edit/'.$row['code_work']."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
					}
					if($Arr_Akses['delete']=='1'){
						$delX	= "&nbsp;<button class='btn btn-sm btn-danger' id='deletePlant' title='Delete work data' data-code_work='".$row['code_work']."'><i class='fa fa-trash'></i></button>";
					}
			$nestedData[]	= "<div align='center'>
									<button type='button' class='btn btn-sm btn-warning' id='detailWork' title='Detail Work' data-code_work='".$row['code_work']."'><i class='fa fa-eye'></i></button>
									".$updX."
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
				work_header
		    WHERE 1=1 AND status='N' AND (
				category LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'category'
		);

		$sql .= " ORDER BY code_work DESC, ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function add(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');
			// print_r($data);
			$category		= strtolower($data['category']);
			$ListDetail		= $data['ListDetail'];
			$Ym				= date('ym');
			//pengurutan kode
			$srcMtr			= "SELECT MAX(code_work) as maxP FROM work_header WHERE code_work LIKE 'W".$Ym."%' ";
			$numrowMtr		= $this->db->query($srcMtr)->num_rows();
			$resultMtr		= $this->db->query($srcMtr)->result_array();
			$angkaUrut2		= $resultMtr[0]['maxP'];
			$urutan2		= (int)substr($angkaUrut2, 5, 4);
			$urutan2++;
			$urut2			= sprintf('%04s',$urutan2);
			$code_work		= "W".$Ym.$urut2;

			//header
			$ArrHeader = array(
				'code_work' => $code_work,
				'category' => $category,
				'created_by' => $data_session['ORI_User']['username'],
				'created_date' => $dateTime
			);

			//detail
			$ArrInsert = array();
			$ArrInsert1 = array();
			$ArrInsert2 = array();
			$ArrInsert3 = array();
			$ArrInsert4 = array();
			$ArrInsert5 = array();
			$nomor = 0;
			foreach($ListDetail AS $val => $valx){
				$nomor++;
				$numPlus = sprintf('%03s',$nomor);
				$numWPlus = $code_work.'-'.$numPlus;

				$ArrInsert[$val]['code_work_detail'] 	= $numWPlus;
				$ArrInsert[$val]['code_work'] 			= $code_work;
				$ArrInsert[$val]['work_process'] 		= $valx['work_process'];
				$ArrInsert[$val]['information'] 		= $valx['information'];

				$no1=0;
				if(!empty($valx['vehicle_tool'])){
				foreach($valx['vehicle_tool'] AS $val_apd => $valx_apd){
					$no1++;
					$numPlus1 = sprintf('%03s',$no1);
					$numWPlus1 = $numWPlus.'-'.$numPlus1;
					$restData = $this->db->query("SELECT category, spec FROM view_vehicle_tool WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();

					$ArrInsert1[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus1;
					$ArrInsert1[$val_apd.$nomor]['code_work_detail']	= $numWPlus;
					$ArrInsert1[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert1[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert1[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert1[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;
				}
				}
				$no2=0;
				if(!empty($valx['apd'])){
				foreach($valx['apd'] AS $val_apd => $valx_apd){
					$no2++;
					$numPlus2 = sprintf('%03s',$no2);
					$numWPlus2 = $numWPlus.'-'.$numPlus2;

					$restData = $this->db->query("SELECT category, spec FROM view_apd WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert2[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus2;
					$ArrInsert2[$val_apd.$nomor]['code_work_detail']	= $numWPlus;
					$ArrInsert2[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert2[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert2[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert2[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;
				}
				}
				$no3=0;
				if(!empty($valx['con_nonmat'])){
				foreach($valx['con_nonmat'] AS $val_apd => $valx_apd){
					$no3++;
					$numPlus3 = sprintf('%03s',$no3);
					$numWPlus3 = $numWPlus.'-'.$numPlus3;

					$restData = $this->db->query("SELECT category, spec FROM view_con_nonmat WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert3[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus3;
					$ArrInsert3[$val_apd.$nomor]['code_work_detail']	= $numWPlus;
					$ArrInsert3[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert3[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert3[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert3[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;
				}
				}
				$no4=0;
				if(!empty($valx['akomodasi'])){
				foreach($valx['akomodasi'] AS $val_apd => $valx_apd){
					$no4++;
					$numPlus4 = sprintf('%03s',$no4);
					$numWPlus4 = $numWPlus.'-'.$numPlus4;

					$restData = $this->db->query("SELECT category, spec FROM view_akomodasi WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert4[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus4;
					$ArrInsert4[$val_apd.$nomor]['code_work_detail']	= $numWPlus;
					$ArrInsert4[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert4[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert4[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert4[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;
				}
				}
				$no5=0;
				if(!empty($valx['man_power'])){
				foreach($valx['man_power'] AS $val_apd => $valx_apd){
					$no5++;
					$numPlus5 = sprintf('%03s',$no5);
					$numWPlus5 = $numWPlus.'-'.$numPlus5;

					$restData = $this->db->query("SELECT category, spec FROM view_man_power WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert5[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus5;
					$ArrInsert5[$val_apd.$nomor]['code_work_detail']	= $numWPlus;
					$ArrInsert5[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert5[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert5[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert5[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;
				}
				}

			}

			// echo $category."<br>";
			// print_r($ArrHeader);
			// print_r($ArrInsert);
			// print_r($ArrInsert1);
			// print_r($ArrInsert2);
			// print_r($ArrInsert3);
			// print_r($ArrInsert4);
			// print_r($ArrInsert5);
			//
			// exit;


			$this->db->trans_start();
				$this->db->insert('work_header', $ArrHeader);
				$this->db->insert_batch('work_detail', $ArrInsert);
				if(!empty($ArrInsert1)){
				$this->db->insert_batch('work_detail_vehicle_tool', $ArrInsert1);
				}
				if(!empty($ArrInsert2)){
				$this->db->insert_batch('work_detail_apd', $ArrInsert2);
				}
				if(!empty($ArrInsert3)){
				$this->db->insert_batch('work_detail_con_nonmat', $ArrInsert3);
				}
				if(!empty($ArrInsert4)){
				$this->db->insert_batch('work_detail_akomodasi', $ArrInsert4);
				}
				if(!empty($ArrInsert5)){
				$this->db->insert_batch('work_detail_man_power', $ArrInsert5);
				}
			$this->db->trans_complete();


			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> 'Insert work data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> 'Insert work data success. Thanks ...',
					'status'	=> 1
				);
				history('Insert master work code '.$code_work);
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

			$uri3 = str_replace('sp4zi',' ',$this->uri->segment(3));
			$uri4 = str_replace('sp4zi',' ',$this->uri->segment(4));


			$qRegion	= "SELECT * FROM region ORDER BY urut ASC";
			$tanda1 = 'Add';
			if(!empty($uri3)){
				$qRegion	= "SELECT a.region_code, a.region, b.rate, b.category, b.spec, b.id FROM region a LEFT JOIN man_power b ON a.region=b.region WHERE b.category='".$uri3."' AND b.spec='".$uri4."' ORDER BY a.urut ASC";
				$tanda1 = 'Edit';
			}

			// echo $qRegion;
			$restRegion	= $this->db->query($qRegion)->result_array();

			$qCateMP	= "SELECT * FROM apd_category ORDER BY category ASC";
			$restCateMP	= $this->db->query($qCateMP)->result_array();

			$data = array(
				'title'			=> $tanda1.' Work',
				'action'		=> 'add',
				'region'		=> $restRegion,
				'cateMP'		=> $restCateMP
			);
			$this->load->view('Work/add',$data);
		}
	}

	public function edit(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data					= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime			= date('Y-m-d H:i:s');
			// print_r($data);
			$category			= strtolower($data['category']);
			$ListDetail		= $data['ListDetail'];
			$code_work		= $data['code_work'];
			//header
			$ArrHeader = array(
				'code_work' => $code_work,
				'category' => $category,
				'updated_by' => $data_session['ORI_User']['username'],
				'updated_date' => $dateTime
			);

			//detail
			$ArrInsert = array();
			$ArrInsert1 = array();
			$ArrInsert2 = array();
			$ArrInsert3 = array();
			$ArrInsert4 = array();
			$ArrInsert5 = array();
			$nomor = 0;
			foreach($ListDetail AS $val => $valx){
				$nomor++;
				$numPlus = sprintf('%03s',$nomor);
				$numWPlus = $code_work.'-'.$numPlus;

				$ArrInsert[$val]['code_work_detail'] 	= $numWPlus;
				$ArrInsert[$val]['code_work'] 			= $code_work;
				$ArrInsert[$val]['work_process'] 		= $valx['work_process'];
				$ArrInsert[$val]['information'] 		= $valx['information'];

				$no1=0;
				if(!empty($valx['vehicle_tool'])){
				foreach($valx['vehicle_tool'] AS $val_apd => $valx_apd){
					$no1++;
					$numPlus1 = sprintf('%03s',$no1);
					$numWPlus1 = $numWPlus.'-'.$numPlus1;

					$restData = $this->db->query("SELECT category, spec FROM view_vehicle_tool WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert1[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus1;
					$ArrInsert1[$val_apd.$nomor]['code_work_detail']	= $numWPlus;
					$ArrInsert1[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert1[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert1[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert1[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;
				}
				}
				$no2=0;
				if(!empty($valx['apd'])){
				foreach($valx['apd'] AS $val_apd => $valx_apd){
					$no2++;
					$numPlus2 = sprintf('%03s',$no2);
					$numWPlus2 = $numWPlus.'-'.$numPlus2;

				$restData = $this->db->query("SELECT category, spec FROM view_apd WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert2[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus2;
					$ArrInsert2[$val_apd.$nomor]['code_work_detail']	= $numWPlus;
					$ArrInsert2[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert2[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert2[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert2[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;
				}
				}
				$no3=0;
				if(!empty($valx['con_nonmat'])){
				foreach($valx['con_nonmat'] AS $val_apd => $valx_apd){
					$no3++;
					$numPlus3 = sprintf('%03s',$no3);
					$numWPlus3 = $numWPlus.'-'.$numPlus3;

					$restData = $this->db->query("SELECT category, spec FROM view_con_nonmat WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert3[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus3;
					$ArrInsert3[$val_apd.$nomor]['code_work_detail']	= $numWPlus;
					$ArrInsert3[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert3[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert3[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert3[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;
				}
				}
				$no4=0;
				if(!empty($valx['akomodasi'])){
				foreach($valx['akomodasi'] AS $val_apd => $valx_apd){
					$no4++;
					$numPlus4 = sprintf('%03s',$no4);
					$numWPlus4 = $numWPlus.'-'.$numPlus4;

					$restData = $this->db->query("SELECT category, spec FROM view_akomodasi WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert4[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus4;
					$ArrInsert4[$val_apd.$nomor]['code_work_detail']	= $numWPlus;
					$ArrInsert4[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert4[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert4[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert4[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;
				}
				}
				$no5=0;
				if(!empty($valx['man_power'])){
				foreach($valx['man_power'] AS $val_apd => $valx_apd){
					$no5++;
					$numPlus5 = sprintf('%03s',$no5);
					$numWPlus5 = $numWPlus.'-'.$numPlus5;

					$restData = $this->db->query("SELECT category, spec FROM view_man_power WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert5[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus5;
					$ArrInsert5[$val_apd.$nomor]['code_work_detail']	= $numWPlus;
					$ArrInsert5[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert5[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert5[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert5[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;
				}
				}

			}

			// echo $category."<br>";
			// print_r($ArrHeader);
			// print_r($ArrInsert);
			// print_r($ArrInsert1);
			// print_r($ArrInsert2);
			// print_r($ArrInsert3);
			// print_r($ArrInsert4);
			// print_r($ArrInsert5);
			// exit;


			$this->db->trans_start();
				$this->db->where('code_work', $code_work);
				$this->db->update('work_header', $ArrHeader);

				$this->db->delete('work_detail', array('code_work' => $code_work));
				$this->db->delete('work_detail_vehicle_tool', array('code_work' => $code_work));
				$this->db->delete('work_detail_apd', array('code_work' => $code_work));
				$this->db->delete('work_detail_con_nonmat', array('code_work' => $code_work));
				$this->db->delete('work_detail_akomodasi', array('code_work' => $code_work));
				$this->db->delete('work_detail_man_power', array('code_work' => $code_work));

				if(!empty($ArrInsert)){
				$this->db->insert_batch('work_detail', $ArrInsert);
				}
				if(!empty($ArrInsert1)){
				$this->db->insert_batch('work_detail_vehicle_tool', $ArrInsert1);
				}
				if(!empty($ArrInsert2)){
				$this->db->insert_batch('work_detail_apd', $ArrInsert2);
				}
				if(!empty($ArrInsert3)){
				$this->db->insert_batch('work_detail_con_nonmat', $ArrInsert3);
				}
				if(!empty($ArrInsert4)){
				$this->db->insert_batch('work_detail_akomodasi', $ArrInsert4);
				}
				if(!empty($ArrInsert5)){
				$this->db->insert_batch('work_detail_man_power', $ArrInsert5);
				}
			$this->db->trans_complete();


			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> 'Update work data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> 'Update work data success. Thanks ...',
					'status'	=> 1
				);
				history('Update master work code '.$code_work);
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

			$code_work = $this->uri->segment(3);

			$qHeader = "SELECT * FROM work_header WHERE code_work='".$code_work."'";
			$qDetail = "SELECT * FROM work_detail WHERE code_work='".$code_work."' AND deleted='N'";

			$restHeader = $this->db->query($qHeader)->result();
			$restDetail = $this->db->query($qDetail)->result_array();

			$akomodasi	= $this->db->query("SELECT * FROM view_akomodasi ORDER BY category ASC, spec ASC")->result();
			$apd				= $this->db->query("SELECT * FROM view_apd ORDER BY category ASC, spec ASC")->result();
			$consumable	= $this->db->query("SELECT * FROM view_con_nonmat ORDER BY category ASC, spec ASC")->result();
			$man_power	= $this->db->query("SELECT * FROM view_man_power ORDER BY category ASC, spec ASC")->result();
			$vehicle		= $this->db->query("SELECT * FROM view_vehicle_tool ORDER BY category ASC, spec ASC")->result();

			$data = array(
				'title'			=> 'Edit Work',
				'action'		=> 'add',
				'header'		=> $restHeader,
				'detail'		=> $restDetail,
				'akomodasi'		=> $akomodasi,
				'apd'			=> $apd,
				'consumable'	=> $consumable,
				'man_power'		=> $man_power,
				'vehicle'		=> $vehicle
			);
			$this->load->view('Work/edit',$data);
		}
	}

	public function modalDetail(){
		$this->load->view('Work/modalDetail');
	}

	public function add_category(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$add_category	= strtolower($data['add_category']);
			$information	= strtolower($data['information']);
			$tanda_category	= strtolower($data['tanda_category']);
			$tanda_spec		= strtolower($data['tanda_spec']);

			// echo $tanda_category;
			// exit;
			//Pencarian data yang sudah ada
			$ValueProduct	= "SELECT * FROM apd_category WHERE category='".$add_category."' ";
			$NumProduct		= $this->db->query($ValueProduct)->num_rows();

			if($NumProduct > 0){
				$Arr_Kembali		= array(
					'status'		=> 3,
					'pesan'			=> 'Category Apd sudah digunakan. Input catgeory lain ...'
				);
			}
			else{
				$ArrInsert = array(
					'category' => $add_category,
					'information' => $information,
					'created_by' => $data_session['ORI_User']['username'],
					'created_date' => $dateTime
				);

				$this->db->trans_start();
				$this->db->insert('apd_category', $ArrInsert);
				$this->db->trans_complete();


				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$Arr_Kembali	= array(
						'pesan'		=> 'Add Category data failed. Please try again later ...',
						'status'	=> 2,
						'tanda_category'	=> $tanda_category,
						'tanda_spec'		=> $tanda_spec
					);
				}
				else{
					$this->db->trans_commit();
					$Arr_Kembali	= array(
						'pesan'		=> 'Add Category data success. Thanks ...',
						'status'	=> 1,
						'tanda_category'	=> $tanda_category,
						'tanda_spec'		=> $tanda_spec
					);
					history('Add Category '.$add_category);
				}
			}

			echo json_encode($Arr_Kembali);
		}
	}

	public function hapus(){
		$code_work = $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'status' 		=> 'Y',
			'deleted_by' 	=> $data_session['ORI_User']['username'],
			'deleted_date' 	=> date('Y-m-d H:i:s')
			);


		$this->db->trans_start();
		$this->db->where('code_work', $code_work);
		$this->db->update('work_header', $ArrPlant);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Delete Apd data failed. Please try again later ...',
				'status'	=> 0
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Delete Apd data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete Work : '.$code_work);
		}
		echo json_encode($Arr_Data);
	}

	public function ExcelMasterDownload(){
		//membuat objek PHPExcel
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
				'color' => array('rgb'=>'f2f2f2'),
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
				'color' => array('rgb'=>'59c3f7'),
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
		$NewRow		= $Row+1;
		$Col_Akhir	= $Cols	= getColsChar(8);
		$sheet->setCellValue('A'.$Row, 'MASTER APD');
		$sheet->getStyle('A'.$Row.':'.$Col_Akhir.$NewRow)->applyFromArray($style_header2);
		$sheet->mergeCells('A'.$Row.':'.$Col_Akhir.$NewRow);

		$NewRow	= $NewRow +2;
		$NextRow= $NewRow +1;

		$sheet->setCellValue('A'.$NewRow, 'No');
		$sheet->getStyle('A'.$NewRow.':A'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('A'.$NewRow.':A'.$NextRow);
		$sheet->getColumnDimension('A')->setWidth(5);

		$sheet->setCellValue('B'.$NewRow, 'Category');
		$sheet->getStyle('B'.$NewRow.':B'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('B'.$NewRow.':B'.$NextRow);
		$sheet->getColumnDimension('B')->setWidth(20);

		$sheet->setCellValue('C'.$NewRow, 'Spesification');
		$sheet->getStyle('C'.$NewRow.':C'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('C'.$NewRow.':C'.$NextRow);
		$sheet->getColumnDimension('C')->setWidth(20);

		$sheet->setCellValue('D'.$NewRow, 'Java');
		$sheet->getStyle('D'.$NewRow.':D'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('D'.$NewRow.':D'.$NextRow);
		$sheet->getColumnDimension('D')->setWidth(20);

		$sheet->setCellValue('E'.$NewRow, 'Sumatra');
		$sheet->getStyle('E'.$NewRow.':E'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('E'.$NewRow.':E'.$NextRow);
		$sheet->getColumnDimension('E')->setWidth(20);

		$sheet->setCellValue('F'.$NewRow, 'Kalimantan');
		$sheet->getStyle('F'.$NewRow.':F'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('F'.$NewRow.':F'.$NextRow);
		$sheet->getColumnDimension('F')->setWidth(20);

		$sheet->setCellValue('G'.$NewRow, 'Sulawesi');
		$sheet->getStyle('G'.$NewRow.':G'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('G'.$NewRow.':G'.$NextRow);
		$sheet->getColumnDimension('G')->setWidth(20);

		$sheet->setCellValue('H'.$NewRow, 'East Indonesia');
		$sheet->getStyle('H'.$NewRow.':H'.$NextRow)->applyFromArray($style_header);
		$sheet->mergeCells('H'.$NewRow.':H'.$NextRow);
		$sheet->getColumnDimension('H')->setWidth(20);

		$qManPower	= "SELECT * FROM view_apd";
		$row		= $this->db->query($qManPower)->result_array();

		if($row){
			$awal_row	= $NextRow;
			$no=0;
			foreach($row as $key => $row_Cek){
				$no++;
				$awal_row++;
				$awal_col	= 0;

				$awal_col++;
				$nomor	= $no;
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $nomor);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$category	= $row_Cek['category'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $category);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$spec	= $row_Cek['spec'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $spec);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray3);

				$awal_col++;
				$rate	= $row_Cek['jawa'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $rate);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray4);

				$awal_col++;
				$rate	= $row_Cek['sumatra'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $rate);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray4);

				$awal_col++;
				$rate	= $row_Cek['kalimantan'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $rate);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray4);

				$awal_col++;
				$rate	= $row_Cek['sulawesi'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $rate);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray4);

				$awal_col++;
				$rate	= $row_Cek['indonesia_timur'];
				$Cols			= getColsChar($awal_col);
				$sheet->setCellValue($Cols.$awal_row, $rate);
				$sheet->getStyle($Cols.$awal_row)->applyFromArray($styleArray4);


			}
		}

		$sheet->setTitle('Apd Master');
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
		header('Content-Disposition: attachment;filename="master_apd_'.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

	public function list_akomodasi(){
	   	$query	 	= "SELECT * FROM view_akomodasi ORDER BY category ASC, spec ASC";
	  	$Q_result	= $this->db->query($query)->result();
	  	$option 	= "";
	  	foreach($Q_result as $row)	{
		   $option .= "<option value='".$row->code_group."'>".strtoupper($row->category." - ".$row->spec)."</option>";
	   	}
		echo json_encode(array(
			'option' => $option
		));
   }

	public function list_apd(){
		$query	 	= "SELECT * FROM view_apd ORDER BY category ASC, spec ASC";
		$Q_result	= $this->db->query($query)->result();
		$option 	= "";
		foreach($Q_result as $row)	{
		   $option .= "<option value='".$row->code_group."'>".strtoupper($row->category." - ".$row->spec)."</option>";
		}
		echo json_encode(array(
			'option' => $option
		));
	}

	public function list_con_nonmat(){
		$query	 	= "SELECT * FROM view_con_nonmat ORDER BY category ASC, spec ASC";
		$Q_result	= $this->db->query($query)->result();
		$option 	= "";
		foreach($Q_result as $row)	{
		   $option .= "<option value='".$row->code_group."'>".strtoupper($row->category." - ".$row->spec)."</option>";
		}
		echo json_encode(array(
			'option' => $option
		));
	}

	public function list_man_power(){
		$query	 	= "SELECT * FROM view_man_power ORDER BY category ASC, spec ASC";
		$Q_result	= $this->db->query($query)->result();
		$option 	= "";
		foreach($Q_result as $row)	{
		   $option .= "<option value='".$row->code_group."'>".strtoupper($row->category." - ".$row->spec)."</option>";
		}
		echo json_encode(array(
			'option' => $option
		));
	}

	public function list_vehicle_tool(){
	   	$query	 	= "SELECT * FROM view_vehicle_tool ORDER BY category ASC, spec ASC";
	  	$Q_result	= $this->db->query($query)->result();
	  	$option 	= "";
	  	foreach($Q_result as $row)	{
		   $option .= "<option value='".$row->code_group."'>".strtoupper($row->category." - ".$row->spec)."</option>";
	   	}
		echo json_encode(array(
			'option' => $option
		));
   }

	public function delete_detail(){
		$code_work			= $this->uri->segment(3);
		$tanda				= $this->uri->segment(4);
		$id_work_process 	= $this->uri->segment(5);
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'deleted' 		=> 'Y',
			'deleted_by' 	=> $data_session['ORI_User']['username'],
			'deleted_date' 	=> date('Y-m-d H:i:s')
			);
		// $qHist		= "SELECT * FROM apd WHERE category='".$uri3."' AND spec='".$uri4."' ";
		// $restHist	= $this->db->query($qHist)->result_array();

		// $ArrHist = array();
		// foreach($restHist AS $val => $valx){
			// $ArrHist[$val]['category'] 		= $valx['category'];
			// $ArrHist[$val]['spec'] 			= $valx['spec'];
			// $ArrHist[$val]['region']		= $valx['region'];
			// $ArrHist[$val]['created_by'] 	= $valx['created_by'];
			// $ArrHist[$val]['created_date'] 	= $valx['created_date'];
			// $ArrHist[$val]['rate'] 			= $valx['rate'];
			// $ArrHist[$val]['updated_by'] 	= $valx['updated_by'];
			// $ArrHist[$val]['updated_date'] 	= $valx['updated_date'];
			// $ArrHist[$val]['hist_by'] 		= $data_session['ORI_User']['username'];
			// $ArrHist[$val]['hist_date'] 	= date('Y-m-d H:i:s');

		// }
		if($tanda == 'det'){
			$table = "work_detail";
		}
		if($tanda == 'ako'){
			$table = "work_detail_akomodasi";
		}
		if($tanda == 'apd'){
			$table = "work_detail_apd";
		}
		if($tanda == 'con'){
			$table = "work_detail_con_nonmat";
		}
		if($tanda == 'mp'){
			$table = "work_detail_man_power";
		}
		if($tanda == 'vt'){
			$table = "work_detail_vehicle_tool";
		}

		$this->db->trans_start();
		$this->db->where('id', $id_work_process);
		$this->db->update($table, $ArrPlant);
		// $this->db->insert_batch('hist_apd', $ArrHist);
		// $this->db->delete('Apd', array('category' => $uri3, 'spec' => $uri4));
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Delete Apd data failed. Please try again later ...',
				'status'	=> 0,
				'code_work'	=> $code_work
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Delete Apd data success. Thanks ...',
				'status'	=> 1,
				'code_work'	=> $code_work
			);
			history('Delete Work : '.$code_work.' / '.$tanda.' / '.$id_work_process);
		}
		echo json_encode($Arr_Data);
	}

}
