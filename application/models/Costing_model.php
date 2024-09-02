<?php

class Costing_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		// Your own constructor code
	}

	public function get_json_costing(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->get_query_json_costing(
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['no_ipp']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['project_name']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['location']))."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['rate'])."</div>";
			$nestedData[]	= "<div align='center'><span class='badge bg-blue'>".number_format($row['rev_cost'])."</span></div>";
					$edit	= "";
					$approve	= "";
					if($Arr_Akses['update']=='1'){
						$edit	= "<a href='".site_url($this->uri->segment(1)).'/edit/'.$row['project_code']."' class='btn btn-sm btn-primary' title='Edit COGS' data-role='qtip'><i class='fa fa-edit'></i></a>";
					}
					if($Arr_Akses['approve']=='1'){
						$approve	= "<button class='btn btn-sm btn-danger reject' title='Back To Engineering' data-project_code='".$row['project_code']."'><i class='fa fa-reply'></i></button>";
					}
					// <button type='button' class='btn btn-sm btn-warning detail' title='Detail Work' data-tanda='1' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
			$nestedData[]	= "<div align='left'>
									<button type='button' class='btn btn-sm btn-warning detail2' title='Detail Costing' data-tanda='2' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
									".$edit."
									".$approve."
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

	public function get_query_json_costing($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
			SELECT
				*
			FROM
				project_header
				WHERE status = 'WAITING COSTING PROJECT' AND (
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

	public function get_json_budget(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->get_query_json_budget(
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['no_ipp']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['project_name']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['location']))."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['rate'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['rate_budget'])."</div>";
			$nestedData[]	= "<div align='center'><span class='badge bg-blue'>".number_format($row['rev_cost'])."</span></div>";
					$edit		= "";
					$delete		= "";
					$print		= "";
					$approve	= "";
						if($Arr_Akses['update']=='1'){
							$edit	= "<a href='".site_url($this->uri->segment(1)).'/edit_budget/'.$row['project_code']."' class='btn btn-sm btn-primary' title='Edit Selling Price' data-role='qtip'><i class='fa fa-edit'></i></a>";
						}
						if($Arr_Akses['approve']=='1'){
							$approve	= "<button class='btn btn-sm btn-success approve' title='Approve Selling Price' data-project_code='".$row['project_code']."'><i class='fa fa-check'></i></button>";
						}
						
						// <button type='button' class='btn btn-sm btn-warning detail' title='Detail Work' data-tanda='1' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
			$nestedData[]	= "<div align='left'>
									<button type='button' class='btn btn-sm btn-warning detail2' title='Detail Costing' data-tanda='2' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>

									".$edit."
									".$print."
									".$approve."
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

	public function get_query_json_budget($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
			SELECT
				*
			FROM
				project_header
			WHERE status = 'WAITING COSTING PROJECT' AND (
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
			$class = getColor(getStatus($row['project_code']));
			$nestedData[]	= "<div align='left'><span class='badge' style='background-color:".$class."'>".$row['status']."</span></div>";

			$approve = "";
			$reject = "";

			if($Arr_Akses['approve']=='1'){
				$approve	= "&nbsp;<button type='button' class='btn btn-sm btn-primary approve' title='Approved' data-project_code='".$row['project_code']."'><i class='fa fa-check'></i></button>";
				$reject		= "&nbsp;<button type='button' class='btn btn-sm btn-danger reject' title='Reject' data-project_code='".$row['project_code']."'><i class='fa fa-reply'></i></button>";
			}

			$nestedData[]	= "<div align='left'>
												<button type='button' class='btn btn-sm btn-warning detail' title='Detail Work' data-tanda='1' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
												<button type='button' class='btn btn-sm btn-info detail2' title='Detail Work' data-tanda='2' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
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
				a.*,
				b.status
			FROM
				cost_project_header a
					LEFT JOIN project_header b ON a.project_code = b.project_code,
				(SELECT @row:=0) r
		    WHERE 1=1 AND a.deleted='N' AND b.status='WAITING APPROVE COSTING PROJECT' AND (
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

	public function get_json_approve_budget(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData		= $_REQUEST;
		$fetch					= $this->get_query_json_approve_budget(
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
			$nestedData[]	= "<div align='center'>".strtolower($row['aju_approved_budget_by'])."</div>";
			$nestedData[]	= "<div align='center'>".date('d-m-Y H:i:s', strtotime($row['aju_approved_budget_date']))."</div>";
			$class = getColor(getStatus($row['project_code']));
			$nestedData[]	= "<div align='left'><span class='badge' style='background-color:".$class."'>".$row['status']."</span></div>";

			$approve = "";
			$reject = "";

			if($Arr_Akses['approve']=='1'){
				$approve	= "&nbsp;<button type='button' class='btn btn-sm btn-primary approve' title='Approved' data-project_code='".$row['project_code']."'><i class='fa fa-check'></i></button>";
				$reject		= "&nbsp;<button type='button' class='btn btn-sm btn-danger reject' title='Reject' data-project_code='".$row['project_code']."'><i class='fa fa-reply'></i></button>";
			}

			$nestedData[]	= "<div align='left'>
												<button type='button' class='btn btn-sm btn-warning detail' title='Detail Work' data-tanda='1' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
												<button type='button' class='btn btn-sm btn-info detail2' title='Detail Work' data-tanda='2' data-project_code='".$row['project_code']."'><i class='fa fa-eye'></i></button>
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

	public function get_query_json_approve_budget($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
			SELECT
				(@row:=@row+1) AS nomor,
				a.*,
				b.status
			FROM
				cost_project_header a
					LEFT JOIN project_header b ON a.project_code = b.project_code,
				(SELECT @row:=0) r
		    WHERE 1=1 AND a.deleted='N' AND b.status='WAITING APPROVE CHECK BUDGET PROJECT' AND (
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
