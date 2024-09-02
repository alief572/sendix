<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		$db2 = $this->load->database('costing', TRUE);

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
			'title'			=> 'Indeks Of Quotation',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data quotation');
		$this->load->view('Quotation/index',$data);
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
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['no_ipp']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['nm_customer']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['project']))."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['rate_budget'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['nilai_penawaran'])."</div>";
					$update		= "";
					$release	= "";
					$print		= "";
					$reject		= "";

					
					if($Arr_Akses['update']=='1'){
						$reject		= "&nbsp;<button type='button' class='btn btn-sm btn-danger reject' title='Reject' data-project_code='".$row['project_code']."'><i class='fa fa-reply'></i></button>";
						$update	= "&nbsp;<a href='".site_url($this->uri->segment(1)).'/edit/'.$row['project_code']."' class='btn btn-sm btn-success' title='Edit' data-role='qtip'><i class='fa fa-edit'></i></a>";
					}
					// 	if($Arr_Akses['delete']=='1'){
					// 		$delete	= "&nbsp;<button class='btn btn-sm btn-danger delete' title='Delete data' data-no_ipp='".$row['no_ipp']."'><i class='fa fa-trash'></i></button>";
					// 	}
					// 	if($Arr_Akses['update']=='1'){
					// 		$release	= "&nbsp;<button class='btn btn-sm btn-success release' title='Release' data-no_ipp='".$row['no_ipp']."'><i class='fa fa-check'></i></button>";
					// 	}
					
					if($Arr_Akses['download']=='1'){
						$print	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/print_quotation/'.$row['project_code'])."' class='btn btn-sm btn-info' target='_blank' title='Print IPP' data-role='qtip'><i class='fa fa-print'></i></a>";
					}
			$nestedData[]	= "<div align='left'>
									".$update."
									".$print."
									".$reject."
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
             (@row:=@row+1) AS nomor,
				a.*,
				b.project,
				b.nm_customer
			FROM
                project_header a
				LEFT JOIN ipp_header b ON a.no_ipp=b.no_ipp,
				(SELECT @row:=0) r
		    WHERE 1=1 AND a.deleted='N' AND (a.status = 'PROCESS QUOTATION') AND (
				b.project LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.no_ipp LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR b.nm_customer LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'no_ipp',
			2 => 'project',
			3 => 'nm_customer',
			4 => 'rate_budget'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function edit(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');
		
			$project_code 	= $data['project_code'];
			$reff_no		= $data['reff_no'];
			$subject		= $data['subject'];
			$tgl_quo		= $data['tgl_quo'];
			$project_name_quo 	= strtolower($data['project_name']);
			$location_quo 		= strtolower($data['location']);
			$nilai_penawaran 	= str_replace(',','',$data['nilai_penawaran']);
			
			$include_check	= (!empty($data['check_include']))?json_encode($data['check_include']):'';
			$exclude_check	= (!empty($data['check_exclude']))?json_encode($data['check_exclude']):'';
			$include_text	= (!empty($data['text_include']))?json_encode($data['text_include']):'';
			$exclude_text	= (!empty($data['text_exclude']))?json_encode($data['text_exclude']):'';

			$ArrUpdateBQ = [];
			if(!empty($data['ListDetailBq'])){
			$detail_bq		= $data['ListDetailBq'];
				foreach ($detail_bq as $key => $value) {
					$ArrUpdateBQ[$key]['id'] 			= $value['id'];
					$ArrUpdateBQ[$key]['desc'] 			= $value['desc'];
					$ArrUpdateBQ[$key]['harga_dia'] 	= str_replace(',','',$value['harga_dia']);
					$ArrUpdateBQ[$key]['total_harga'] 	= str_replace(',','',$value['total_harga']);
				}
			}

			$ArrDetail = [];
			$delete_WhereIn = [];
			if(!empty($data['ListDetail'])){
			$detail	= $data['ListDetail'];
				foreach ($detail as $key => $value) {
					$ArrDetail[$key]['id'] 				= $value['id'];
					$ArrDetail[$key]['desc'] 			= $value['desc'];
					$ArrDetail[$key]['satuan'] 			= $value['satuan'];
					$ArrDetail[$key]['qty'] 			= str_replace(',','',$value['qty']);
					$ArrDetail[$key]['harga_satuan'] 	= str_replace(',','',$value['harga_satuan']);
					$ArrDetail[$key]['total_harga'] 	= str_replace(',','',$value['total_harga']);
					$ArrDetail[$key]['created_by'] 		= $data_session['ORI_User']['username'];
					$ArrDetail[$key]['created_date'] 	= $dateTime;

					$delete_WhereIn[] = $value['id'];
				}
			}

			$ArrDetailAdd = [];
			if(!empty($data['ListDetailAdd'])){
			$detail_add	= $data['ListDetailAdd'];
				foreach ($detail_add as $key => $value) {
					$ArrDetailAdd[$key]['project_code'] = $project_code;
					$ArrDetailAdd[$key]['desc'] 		= $value['desc'];
					$ArrDetailAdd[$key]['satuan'] 		= $value['satuan'];
					$ArrDetailAdd[$key]['qty'] 			= str_replace(',','',$value['qty']);
					$ArrDetailAdd[$key]['harga_satuan'] = str_replace(',','',$value['harga_satuan']);
					$ArrDetailAdd[$key]['total_harga'] 	= str_replace(',','',$value['total_harga']);
					$ArrDetailAdd[$key]['created_by'] 	= $data_session['ORI_User']['username'];
					$ArrDetailAdd[$key]['created_date'] = $dateTime;
				}
			}

			$ArrUpdateHeader = [
				'nilai_penawaran' => $nilai_penawaran,
				'project_name_quo' => $project_name_quo,
				'location_quo' => $location_quo,
				'reff_no' => $reff_no,
				'subject' => $subject,
				'tgl_quo' => $tgl_quo,
				'include_check' => $include_check,
				'exclude_check' => $exclude_check,
				'include_text' => $include_text,
				'exclude_text' => $exclude_text
			];

			// print_r($ArrUpdateHeader);
			// print_r($ArrUpdateBQ);
			// exit;

			$this->db->trans_start();
				$this->db->where('project_code', $project_code);
				$this->db->update('project_header', $ArrUpdateHeader);

				if(!empty($delete_WhereIn)){
					$this->db->where_not_in('id', $delete_WhereIn);
					$this->db->delete('project_detail_quo');
				}

				if(!empty($ArrUpdateBQ)){
					$this->db->update_batch('project_detail_bq', $ArrUpdateBQ, 'id');
				}
				if(!empty($ArrDetail)){
					$this->db->update_batch('project_detail_quo', $ArrDetail, 'id');
				}
				if(!empty($ArrDetailAdd)){
					$this->db->insert_batch('project_detail_quo', $ArrDetailAdd);
				}
				
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
				history('Edit quotation '.$project_code);
			}
			echo json_encode($Arr_Kembali);
		}
		else{
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			
			if($Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('quotation'));
			}
			
			$project_code 	= $this->uri->segment(3);
			$detail 		= $this->db->get_where('project_header', array('project_code'=>$project_code))->result();
			$detail_bq 		= $this->db->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'project'))->result_array();
			$detail_quo 	= $this->db->get_where('project_detail_quo', array('project_code'=>$project_code))->result_array();

			$list_include 	= $this->db->get_where('include_exclude', array('category'=>'include','deleted_date'=>NULL))->result_array();
			$list_exclude 	= $this->db->get_where('include_exclude', array('category'=>'exclude','deleted_date'=>NULL))->result_array();

			$data = array(
				'title'			=> 'Edit Quotation',
				'action'		=> 'edit',
				'project_code'	=> $project_code,
				'detail_bq'		=> $detail_bq,
				'detail_quo'	=> $detail_quo,
				'list_include'	=> $list_include,
				'list_exclude'	=> $list_exclude,
				'header'		=> $detail
			);
			$this->load->view('Quotation/edit',$data);
		}
	}

	public function print_quotation(){
		$project_code = $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$detail 		= $this->db->get_where('project_header', array('project_code'=>$project_code))->result();
		$detail_bq 		= $this->db->get_where('project_detail_bq', array('project_code'=>$project_code,'category'=>'project'))->result_array();
		$detail_quo 	= $this->db->get_where('project_detail_quo', array('project_code'=>$project_code))->result_array();

		$list_include 	= $this->db->get_where('include_exclude', array('category'=>'include','deleted_date'=>NULL))->result_array();
		$list_exclude 	= $this->db->get_where('include_exclude', array('category'=>'exclude','deleted_date'=>NULL))->result_array();

		$data = array(
			'project_code'	=> $project_code,
			'detail_bq'		=> $detail_bq,
			'detail_quo'	=> $detail_quo,
			'list_include'	=> $list_include,
			'list_exclude'	=> $list_exclude,
			'header'		=> $detail
		);

		$this->load->view('Print/print_quotation', $data);
	}

	public function dialog_reject(){
		if($this->input->post()){
			$data 			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');
			
			$project_code 	= $data['project_code'];
			$no_ipp 		= get_name('project_header','no_ipp','project_code',$project_code);
			$status = $data['status'];
			$reason = strtolower($data['reason']);

			if($status == 'N'){
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
			}

			if($status == 'X'){
				$ArrUpdate = array(
					'status' => 'WAITING COSTING PROJECT'
				);

				$ArrUpdate2 = array(
					'status' => 'WAITING COSTING PROJECT'
				);
			}

			$this->db->trans_start();
				$this->db->where('project_code', $project_code);
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
				history('Reject quotation code project : '.$project_code);
			}
			echo json_encode($Arr_Kembali);
		}
		else{
			$id			= $this->uri->segment(3);
			$data = array(
				'id'	=> $id
			);
			$this->load->view('Quotation/dialog_reject',$data);
		}
	}

}
