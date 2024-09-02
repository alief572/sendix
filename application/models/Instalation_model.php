<?php

class Instalation_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		// Your own constructor code
	}

	public function get_json_instalation(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData		= $_REQUEST;
		$fetch					= $this->get_query_json_instalation(
			$requestData['search']['value'],
			$requestData['order'][0]['column'],
			$requestData['order'][0]['dir'],
			$requestData['start'],
			$requestData['length']
		);
		$totalData			= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query					= $fetch['query'];

		$data	= array();
		$urut1  = 1;
		$urut2  = 0;
		foreach($query->result_array() as $row){
			$total_data     = $totalData;
			$start_dari     = $requestData['start'];
			$asc_desc       = $requestData['order'][0]['dir'];
			if($asc_desc == 'asc'){
				$nomor = $urut1 + $start_dari;
			}
			if($asc_desc == 'desc'){
				$nomor = ($total_data - $start_dari) - $urut2;
			}

			$nestedData 	= array();
			$nestedData[]	= "<div align='center'>".$nomor."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['no_ipp']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['project_name']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['region']))."</div>";
			$last_create = (!empty($row['updated_by']))?$row['updated_by']:$row['created_by'];
			$nestedData[]	= "<div align='left'>".strtolower($last_create)."</div>";

			$last_date = (!empty($row['updated_date']))?$row['updated_date']:$row['created_date'];
			$nestedData[]	= "<div align='center'>".date('d-M-Y H:i:s', strtotime($last_date))."</div>";
			$nestedData[]	= "<div align='left'>".strtolower($row['reason_approved'])."</div>";
			$class = getColor(getStatus($row['project_code']));
			$nestedData[]	= "<div align='left'><span class='badge' style='background-color:".$class."'>".getStatus($row['project_code'])."</span></div>";

			$edit	= "";
			$delete	= "";
			$print	= "";
			$approve = "";
			$download = "";
			if(getStatus($row['project_code']) == 'WAITING ESTIMATION PROJECT'){
				if($Arr_Akses['update']=='1'){
					$edit = "&nbsp;<a href='".site_url($this->uri->segment(1)).'/edit/'.$row['project_code']."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
				}
				if($Arr_Akses['approve']=='1'){
					$approve = "&nbsp;<button type='button' class='btn btn-sm btn-success approve' title='Request Approval' data-project_code='".$row['project_code']."'><i class='fa fa-check'></i></button>";
				}
				if($Arr_Akses['delete']=='1'){
					$delete	= "&nbsp;<button type='button' class='btn btn-sm btn-danger delete' title='Delete work data' data-project_code='".$row['project_code']."'><i class='fa fa-trash'></i></button>";
				}
			}
			if($Arr_Akses['download']=='1'){
				$print	= "<a href='".site_url($this->uri->segment(1).'/print_project/'.$row['project_code'])."' class='btn btn-sm btn-info' target='_blank' title='Print Project' data-role='qtip'><i class='fa fa-print'></i></a>";
				// $download	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/download_excel/'.$row['project_code'])."' class='btn btn-sm btn-warning' target='_blank' title='Print Project' data-role='qtip'><i class='fa fa-file-excel-o'></i></a>";
			}
			$nestedData[]	= "	<div align='left'>
									<button type='button' class='btn btn-sm btn-warning detail' title='Detail Work' data-tanda='1' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
									&nbsp;<button type='button' class='btn btn-sm btn-info detail2' title='Detail Kebutuhan' data-tanda='2' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
									&nbsp;<button type='button' class='btn btn-sm bg-purple detail3' title='Plan Instalasi' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
									".$edit."
									".$print."
									".$approve."
									".$download."
									".$delete."
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

	public function get_query_json_instalation($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

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
			1 => 'no_ipp',
			2 => 'project_name'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function get_json_approve(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData		= $_REQUEST;
		$fetch					= $this->get_query_json_approve(
			$requestData['search']['value'],
			$requestData['order'][0]['column'],
			$requestData['order'][0]['dir'],
			$requestData['start'],
			$requestData['length']
		);
		$totalData			= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query					= $fetch['query'];

		$data	= array();
		$urut1  = 1;
		$urut2  = 0;
		foreach($query->result_array() as $row){
			$total_data     = $totalData;
			$start_dari     = $requestData['start'];
			$asc_desc       = $requestData['order'][0]['dir'];
			if($asc_desc == 'asc'){
				$nomor = $urut1 + $start_dari;
			}
			if($asc_desc == 'desc'){
				$nomor = ($total_data - $start_dari) - $urut2;
			}

			$nestedData 	= array();
			$nestedData[]	= "<div align='center'>".$nomor."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['no_ipp']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['project_code']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['project_name']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['region']))."</div>";
			$nestedData[]	= "<div align='center'>".strtolower($row['aju_approved_by'])."</div>";
			$nestedData[]	= "<div align='center'>".date('d-m-Y H:i:s', strtotime($row['aju_approved_date']))."</div>";
			
			$approve = "";
			$reject = "";

			if($Arr_Akses['approve']=='1'){
				$approve	= "<button type='button' class='btn btn-sm btn-primary approve' title='Approved' data-project_code='".$row['project_code']."'><i class='fa fa-check'></i></button>";
				$reject		= "<button type='button' class='btn btn-sm btn-danger reject' title='Reject' data-project_code='".$row['project_code']."'><i class='fa fa-reply'></i></button>";
			}

			$nestedData[]	= "<div align='center'>
												<button type='button' class='btn btn-sm btn-warning detail' title='Detail Work' data-tanda='1' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
												<button type='button' class='btn btn-sm btn-info detail2' title='Detail Work' data-tanda='2' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
												<button type='button' class='btn btn-sm btn-success detail3' title='Plan Instalasi' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
												".$approve."
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

	public function get_query_json_approve($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
			SELECT
				(@row:=@row+1) AS nomor,
				a.*
			FROM
				project_header a,
				(SELECT @row:=0) r
		    WHERE 1=1 AND a.deleted='N' AND a.status='WAITING APPROVE ESTIMATION PROJECT' AND (
				a.project_name LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.location LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'no_ipp',
			2 => 'project_code',
			3 => 'project_name',
			4 => 'region',
			5 => 'no_ipp'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}


}
