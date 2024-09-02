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
		$result         = $this->db->order_by('category','asc')->get_where('work_header', array('status'=>'N'))->result_array();
		$data = array(
			'title'			=> 'Indeks Of Process List',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'result'		=> $result,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Process List');
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
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['total_time']))."</div>";
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
			$tipe		= $data['tipe'];
			$total_time		= str_replace(',','',$data['total_time']);
			// $ListDetail		= $data['ListDetail'];
			if(!empty($data['Listvehicle_tool'])){
				$Listvehicle_tool	= $data['Listvehicle_tool'];
			}
			if(!empty($data['Listcon_nonmat'])){
				$Listcon_nonmat		= $data['Listcon_nonmat'];
			}
			if(!empty($data['Listman_power'])){
				$Listman_power		= $data['Listman_power'];
			}
			if(!empty($data['List_equipment'])){
				$List_equipment	= $data['List_equipment'];
			}
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
				'total_time' => $total_time,
				'tipe' => $tipe,
				'created_by' => $data_session['ORI_User']['username'],
				'created_date' => $dateTime
			);

			//detail
			$ArrInsert = array();
			$ArrInsert1 = array();
			$ArrInsert3 = array();
			$ArrInsert5 = array();

			$ArrInsert2New = array();
			$ArrInsert1New = array();
			$ArrInsert3New = array();
			$ArrInsert5New = array();
			$nomor = 0;

			$numWPlusx = $code_work.'-001';
			//tools
			$no1=0;
			if(!empty($data['Listvehicle_tool'])){
				foreach($Listvehicle_tool AS $val_apd => $valx_apd){
					$no1++;
					$numPlus1 = sprintf('%03s',$no1);
					$numWPlus1 = $numWPlusx.'-'.$numPlus1;
					$restData = $this->db->query("SELECT category, spec FROM vehicle_tool_new WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();

					$ArrInsert1[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus1;
					$ArrInsert1[$val_apd.$nomor]['code_work_detail']	= $numWPlusx;
					$ArrInsert1[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert1[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert1[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert1[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;

					$ArrInsert1New[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus1;
					$ArrInsert1New[$val_apd.$nomor]['code_work_detail']		= $numWPlusx;
					$ArrInsert1New[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert1New[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert1New[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert1New[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;
					$ArrInsert1New[$val_apd.$nomor]['tipe'] 				= 'tools';
				}
			}

			//equipment
			$no1=0;
			if(!empty($data['List_equipment'])){
				foreach($List_equipment AS $val_apd => $valx_apd){
					$no1++;
					$numPlus1 = sprintf('%03s',$no1);
					$numWPlus1 = $numWPlusx.'-'.$numPlus1;
					$restData = $this->db->query("SELECT category, spec FROM heavy_equipment_new WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();

					$ArrInsert2New[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus1;
					$ArrInsert2New[$val_apd.$nomor]['code_work_detail']		= $numWPlusx;
					$ArrInsert2New[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert2New[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert2New[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert2New[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;
					$ArrInsert2New[$val_apd.$nomor]['tipe'] 				= 'heavy equipment';
				}
			}

			//consumable and apd
			$no3=0;
			if(!empty($data['Listcon_nonmat'])){
				foreach($Listcon_nonmat AS $val_apd => $valx_apd){
					$no3++;
					$numPlus3 = sprintf('%03s',$no3);
					$numWPlus3 = $numWPlusx.'-'.$numPlus3;

					$restData = $this->db->query("SELECT category, spec FROM con_nonmat_new WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert3[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus3;
					$ArrInsert3[$val_apd.$nomor]['code_work_detail']	= $numWPlusx;
					$ArrInsert3[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert3[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert3[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert3[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;

					$ArrInsert3New[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus3;
					$ArrInsert3New[$val_apd.$nomor]['code_work_detail']	= $numWPlusx;
					$ArrInsert3New[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert3New[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert3New[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert3New[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;
					$ArrInsert3New[$val_apd.$nomor]['tipe'] 				= 'consumable';
				}
			}

			//man power
			$no5=0;
			if(!empty($data['Listman_power'])){
				foreach($Listman_power AS $val_apd => $valx_apd){
					$no5++;
					$numPlus5 = sprintf('%03s',$no5);
					$numWPlus5 = $numWPlusx.'-'.$numPlus5;

					$restData = $this->db->query("SELECT category, spec FROM man_power_new WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert5[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus5;
					$ArrInsert5[$val_apd.$nomor]['code_work_detail']	= $numWPlusx;
					$ArrInsert5[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert5[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert5[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert5[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;

					$ArrInsert5New[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus5;
					$ArrInsert5New[$val_apd.$nomor]['code_work_detail']		= $numWPlusx;
					$ArrInsert5New[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert5New[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert5New[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert5New[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;
					$ArrInsert5New[$val_apd.$nomor]['tipe'] 				= 'man power';
				}
			}

			// print_r($ArrHeader);
			// print_r($ArrInsert);
			// print_r($ArrInsert1);
			// print_r($ArrInsert3);
			// print_r($ArrInsert5);
			// exit;


			$this->db->trans_start();
				$this->db->insert('work_header', $ArrHeader);

				//NEW SAVE
				if(!empty($ArrInsert2New)){
					$this->db->insert_batch('work_detail_detail', $ArrInsert2New);
				}
				if(!empty($ArrInsert1New)){
					$this->db->insert_batch('work_detail_detail', $ArrInsert1New);
				}
				if(!empty($ArrInsert3New)){
					$this->db->insert_batch('work_detail_detail', $ArrInsert3New);
				}
				if(!empty($ArrInsert5New)){
					$this->db->insert_batch('work_detail_detail', $ArrInsert5New);
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

			$data = array(
				'title'			=> 'Add Process List',
				'action'		=> 'add'
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
			$category		= strtolower($data['category']);
			$tipe			= $data['tipe'];
			$total_time		= str_replace(',','',$data['total_time']);
			// $ListDetail		= $data['ListDetail'];
			if(!empty($data['Listvehicle_tool'])){
				$Listvehicle_tool	= $data['Listvehicle_tool'];
			}
			if(!empty($data['Listcon_nonmat'])){
				$Listcon_nonmat		= $data['Listcon_nonmat'];
			}
			if(!empty($data['Listman_power'])){
				$Listman_power		= $data['Listman_power'];
			}

			if(!empty($data['List_equipment'])){
				$List_equipment	= $data['List_equipment'];
			}

			$code_work		= $data['code_work'];
			//header
			$ArrHeader = array(
				'code_work' => $code_work,
				'category' => $category,
				'total_time' => $total_time,
				'tipe' => $tipe,
				'updated_by' => $data_session['ORI_User']['username'],
				'updated_date' => $dateTime
			);

			//detail
			$ArrInsert = array();
			$ArrInsert1 = array();
			$ArrInsert3 = array();
			$ArrInsert5 = array();

			$ArrInsert2New = array();
			$ArrInsert1New = array();
			$ArrInsert3New = array();
			$ArrInsert5New = array();
			$nomor = 0;

			$numWPlusx = $code_work.'-001';
			//tools
			$no1=0;
			if(!empty($data['Listvehicle_tool'])){
				foreach($Listvehicle_tool AS $val_apd => $valx_apd){
					$no1++;
					$numPlus1 = sprintf('%03s',$no1);
					$numWPlus1 = $numWPlusx.'-'.$numPlus1;
					$restData = $this->db->query("SELECT category, spec FROM vehicle_tool_new WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();

					$ArrInsert1[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus1;
					$ArrInsert1[$val_apd.$nomor]['code_work_detail']	= $numWPlusx;
					$ArrInsert1[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert1[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert1[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert1[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;

					$ArrInsert1New[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus1;
					$ArrInsert1New[$val_apd.$nomor]['code_work_detail']		= $numWPlusx;
					$ArrInsert1New[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert1New[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert1New[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert1New[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;
					$ArrInsert1New[$val_apd.$nomor]['tipe'] 				= 'tools';
				}
			}

			//equipment
			$no1=0;
			if(!empty($data['List_equipment'])){
				foreach($List_equipment AS $val_apd => $valx_apd){
					$no1++;
					$numPlus1 = sprintf('%03s',$no1);
					$numWPlus1 = $numWPlusx.'-'.$numPlus1;
					$restData = $this->db->query("SELECT category, spec FROM heavy_equipment_new WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();

					$ArrInsert2New[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus1;
					$ArrInsert2New[$val_apd.$nomor]['code_work_detail']		= $numWPlusx;
					$ArrInsert2New[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert2New[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert2New[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert2New[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;
					$ArrInsert2New[$val_apd.$nomor]['tipe'] 				= 'heavy equipment';
				}
			}

			//consumable and apd
			$no3=0;
			if(!empty($data['Listcon_nonmat'])){
				foreach($Listcon_nonmat AS $val_apd => $valx_apd){
					$no3++;
					$numPlus3 = sprintf('%03s',$no3);
					$numWPlus3 = $numWPlusx.'-'.$numPlus3;

					$restData = $this->db->query("SELECT category, spec FROM con_nonmat_new WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert3[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus3;
					$ArrInsert3[$val_apd.$nomor]['code_work_detail']	= $numWPlusx;
					$ArrInsert3[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert3[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert3[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert3[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;

					$ArrInsert3New[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus3;
					$ArrInsert3New[$val_apd.$nomor]['code_work_detail']	= $numWPlusx;
					$ArrInsert3New[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert3New[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert3New[$val_apd.$nomor]['category'] 	= $restData[0]->category;
					$ArrInsert3New[$val_apd.$nomor]['spec'] 		= $restData[0]->spec;
					$ArrInsert3New[$val_apd.$nomor]['tipe'] 				= 'consumable';
				}
			}

			//man power
			$no5=0;
			if(!empty($data['Listman_power'])){
				foreach($Listman_power AS $val_apd => $valx_apd){
					$no5++;
					$numPlus5 = sprintf('%03s',$no5);
					$numWPlus5 = $numWPlusx.'-'.$numPlus5;

					$restData = $this->db->query("SELECT category, spec FROM man_power_new WHERE code_group='".$valx_apd."' LIMIT 1 ")->result();
					$ArrInsert5[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus5;
					$ArrInsert5[$val_apd.$nomor]['code_work_detail']	= $numWPlusx;
					$ArrInsert5[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert5[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert5[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert5[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;

					$ArrInsert5New[$val_apd.$nomor]['code_work_detail_d']	= $numWPlus5;
					$ArrInsert5New[$val_apd.$nomor]['code_work_detail']		= $numWPlusx;
					$ArrInsert5New[$val_apd.$nomor]['code_work'] 			= $code_work;
					$ArrInsert5New[$val_apd.$nomor]['code_group'] 			= $valx_apd;
					$ArrInsert5New[$val_apd.$nomor]['category'] 			= $restData[0]->category;
					$ArrInsert5New[$val_apd.$nomor]['spec'] 				= $restData[0]->spec;
					$ArrInsert5New[$val_apd.$nomor]['tipe'] 				= 'man power';
				}
			}

			// echo $category."<br>";
			// print_r($ArrHeader);
			// print_r($ArrInsert);
			// print_r($ArrInsert1);
			// print_r($ArrInsert3);
			// print_r($ArrInsert5);
			// exit;


			$this->db->trans_start();
				$this->db->where('code_work', $code_work);
				$this->db->update('work_header', $ArrHeader);
				//NEW SAVE
				$this->db->delete('work_detail_detail', array('code_work' => $code_work));
				if(!empty($ArrInsert2New)){
					$this->db->insert_batch('work_detail_detail', $ArrInsert2New);
				}
				if(!empty($ArrInsert1New)){
					$this->db->insert_batch('work_detail_detail', $ArrInsert1New);
				}
				if(!empty($ArrInsert3New)){
					$this->db->insert_batch('work_detail_detail', $ArrInsert3New);
				}
				if(!empty($ArrInsert5New)){
					$this->db->insert_batch('work_detail_detail', $ArrInsert5New);
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

			$restHeader 	= $this->db->get_where('work_header', array('code_work'=>$code_work))->result();
			$restDetail 	= $this->db->get_where('work_detail', array('code_work'=>$code_work,'deleted'=>'N'))->result_array();

			$consumable		= $this->db->query("SELECT * FROM con_nonmat_new WHERE deleted='N' ORDER BY category ASC, spec ASC")->result();
			$man_power		= $this->db->query("SELECT * FROM man_power_new WHERE deleted='N' ORDER BY category ASC, spec ASC")->result();
			$vehicle		= $this->db->query("SELECT * FROM vehicle_tool_new WHERE deleted='N' ORDER BY category ASC, spec ASC")->result();
			$heavy_equip	= $this->db->query("SELECT * FROM heavy_equipment_new WHERE deleted='N' ORDER BY category ASC, spec ASC")->result();

			$restDet1 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'tools','deleted'=>'N'))->result_array();
			$restDet2 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'consumable','deleted'=>'N'))->result_array();
			$restDet3 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'man power','deleted'=>'N'))->result_array();
			$restDet4 = $this->db->get_where('work_detail_detail', array('code_work'=>$code_work,'tipe'=>'heavy equipment','deleted'=>'N'))->result_array();

			$ArrData1 = array();
			foreach($restDet1 as $vaS => $vaA){
				 $ArrData1[] = $vaA['code_group'];
			}
			$ArrData1 = implode("," ,$ArrData1);
			$ArrData1x = explode("," ,$ArrData1);

			$ArrData2 = array();
			foreach($restDet2 as $vaS => $vaA){
				 $ArrData2[] = $vaA['code_group'];
			}
			$ArrData2 = implode("," ,$ArrData2);
			$ArrData2x = explode("," ,$ArrData2);

			$ArrData3 = array();
			foreach($restDet3 as $vaS => $vaA){
				 $ArrData3[] = $vaA['code_group'];
			}
			$ArrData3 = implode("," ,$ArrData3);
			$ArrData3x = explode("," ,$ArrData3);

			$ArrData4 = array();
			foreach($restDet4 as $vaS => $vaA){
				 $ArrData4[] = $vaA['code_group'];
			}
			$ArrData4 = implode("," ,$ArrData4);
			$ArrData4x = explode("," ,$ArrData4);

			$data = array(
				'title'			=> 'Edit Process List',
				'action'		=> 'add',
				'header'		=> $restHeader,
				'detail'		=> $restDetail,

				'consumable'	=> $consumable,
				'man_power'		=> $man_power,
				'vehicle'		=> $vehicle,
				'heavy_equip'	=> $heavy_equip,

				'consumablex'	=> $ArrData2x,
				'man_powerx'	=> $ArrData3x,
				'vehiclex'		=> $ArrData1x,
				'heavyx'		=> $ArrData4x
			);
			$this->load->view('Work/edit',$data);
		}
	}

	public function modalDetail(){
		$code_work 			= $this->uri->segment(3);
		$header 			= $this->db->get_where('work_header', array('code_work'=>$code_work))->result();
		$heavy_equipment 	= $this->db->order_by('category','ASC')->get_where('work_detail_detail', array('code_work'=>$code_work, 'deleted'=>'N', 'tipe'=>'heavy equipment'))->result_array();
		$con_nonmat 		= $this->db->order_by('category','ASC')->get_where('work_detail_detail', array('code_work'=>$code_work, 'deleted'=>'N', 'tipe'=>'consumable'))->result_array();
		$man_power 			= $this->db->order_by('category','ASC')->get_where('work_detail_detail', array('code_work'=>$code_work, 'deleted'=>'N', 'tipe'=>'man power'))->result_array();
		$vehicle_tool 		= $this->db->order_by('category','ASC')->get_where('work_detail_detail', array('code_work'=>$code_work, 'deleted'=>'N', 'tipe'=>'tools'))->result_array();

		$data = array(
			'restHeader'		=> $header,
			'con_nonmat'		=> $con_nonmat,
			'man_power'			=> $man_power,
			'heavy_equipment'	=> $heavy_equipment,
			'vehicle_tool'		=> $vehicle_tool
		);
		$this->load->view('Work/modalDetail', $data);
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
		$code_work 		= $this->uri->segment(3);
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

	public function list_con_nonmat(){
		$query	 	= "SELECT * FROM con_nonmat_new WHERE deleted='N' ORDER BY category ASC, spec ASC";
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
		$query	 	= "SELECT * FROM man_power_new WHERE deleted='N' ORDER BY category ASC, spec ASC";
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
	   	$query	 	= "SELECT * FROM vehicle_tool_new WHERE deleted='N' ORDER BY category ASC, spec ASC";
	  	$Q_result	= $this->db->query($query)->result();
	  	$option 	= "";
	  	foreach($Q_result as $row)	{
		   $option .= "<option value='".$row->code_group."'>".strtoupper($row->category." - ".$row->spec)."</option>";
	   	}
		echo json_encode(array(
			'option' => $option
		));
   }

	public function list_heavy_equipment(){
		$Q_result	= $this->db->order_by('category','ASC')->order_by('spec','ASC')->get_where('heavy_equipment_new', array('deleted'=>'N'))->result();
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
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'deleted' 		=> 'Y',
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
				'pesan'		=>'Delete data failed. Please try again later ...',
				'status'	=> 0,
				'code_work'	=> $code_work
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Delete data success. Thanks ...',
				'status'	=> 1,
				'code_work'	=> $code_work
			);
			history('Delete Work : '.$code_work);
		}
		echo json_encode($Arr_Data);
	}

}
