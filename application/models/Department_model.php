<?php
class Department_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_list_order($table, $order_by){
		$query = $this->db->query("SELECT * FROM $table ORDER BY $order_by ASC")->result_array();
		return $query;
	}

	public function get_list_where_order($table, $field_where, $value_where, $order_by){
		$query = $this->db->query("SELECT * FROM $table WHERE $field_where = '".$value_where."' ORDER BY $order_by ASC")->result_array();
		return $query;
	}
	
	//==========================================================================================================================
	//======================================================SERVER SIDE=========================================================
	//==========================================================================================================================

	//PROCESS
	public function get_json_process(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->get_query_json_process(
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['nm_process']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['keterangan']))."</div>";

			$last_create = (!empty($row['updated_by']))?$row['updated_by']:$row['created_by'];
			$nestedData[]	= "<div align='center'>".strtolower($last_create)."</div>";

			$last_date = (!empty($row['updated_date']))?$row['updated_date']:$row['created_date'];
			$nestedData[]	= "<div align='center'>".date('d-m-Y', strtotime($last_date))."</div>";

                $detail		= "";
                $edit		= "";
                $delete		= "";

                if($Arr_Akses['delete']=='1'){
                    $delete	= "&nbsp;<button type='button' class='btn btn-sm btn-danger delete' title='Delete data' data-code='".$row['code_process']."'><i class='fa fa-trash'></i></button>";
                }

                if($Arr_Akses['update']=='1'){
                    $edit	= "&nbsp;<button type='button' class='btn btn-sm btn-primary edit' data-code='".$row['code_process']."' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></button>";
                }
                $nestedData[]	= "<div align='left'>
                                    ".$edit."
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

	public function get_query_json_process($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
            SELECT
                (@row:=@row+1) AS nomor,
				a.*
			FROM
                process a,
                (SELECT @row:=0) r 
            WHERE 
                1=1 AND a.deleted='N' AND (
                a.code_process LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.nm_process LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.keterangan LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData']  = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'nm_process',
			2 => 'keterangan'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
    }
    
    //COSTCENTER
    public function get_json_costcenter(){
		$controller			= ucfirst(strtolower($this->uri->segment(1))).'/costcenter';
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->get_query_json_costcenter(
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

            $s1 = ($row['shift1'] == 'Y')?'blue':'red';
            $s2 = ($row['shift2'] == 'Y')?'blue':'red';
            $s3 = ($row['shift3'] == 'Y')?'blue':'red';

            $sx1 = ($row['shift1'] == 'Y')?'Yes':'No';
            $sx2 = ($row['shift2'] == 'Y')?'Yes':'No';
            $sx3 = ($row['shift3'] == 'Y')?'Yes':'No';

			$nestedData 	= array();
			$nestedData[]	= "<div align='center'>".$nomor."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['nm_costcenter']))."</div>";
            $nestedData[]	= "<div align='center'><span class='badge bg-".$s1."'>".strtoupper(strtolower($row['mp_1']))."</span></div>";
            $nestedData[]	= "<div align='center'><span class='badge bg-".$s2."'>".strtoupper(strtolower($row['mp_2']))."</span></div>";
            $nestedData[]	= "<div align='center'><span class='badge bg-".$s3."'>".strtoupper(strtolower($row['mp_3']))."</span></div>";
            $nestedData[]	= "<div align='center'><span class='badge bg-".$s1."'>".strtoupper(strtolower($sx1))."</span></div>";
            $nestedData[]	= "<div align='center'><span class='badge bg-".$s2."'>".strtoupper(strtolower($sx2))."</span></div>";
            $nestedData[]	= "<div align='center'><span class='badge bg-".$s3."'>".strtoupper(strtolower($sx3))."</span></div>";


			$last_create = (!empty($row['updated_by']))?$row['updated_by']:$row['created_by'];
			$nestedData[]	= "<div align='center'>".strtolower($last_create)."</div>";

			$last_date = (!empty($row['updated_date']))?$row['updated_date']:$row['created_date'];
			$nestedData[]	= "<div align='center'>".date('d-m-Y', strtotime($last_date))."</div>";

                $detail		= "";
                $edit		= "";
                $delete		= "";

                if($Arr_Akses['delete']=='1'){
                    $delete	= "&nbsp;<button type='button' class='btn btn-sm btn-danger delete' title='Delete data' data-code='".$row['id_costcenter']."'><i class='fa fa-trash'></i></button>";
                }

                if($Arr_Akses['update']=='1'){
                    $edit	= "&nbsp;<button type='button' class='btn btn-sm btn-primary edit' data-code='".$row['id_costcenter']."' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></button>";
                }
                $nestedData[]	= "<div align='left'>
                                    ".$edit."
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

	public function get_query_json_costcenter($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
            SELECT
                (@row:=@row+1) AS nomor,
				a.*
			FROM
                costcenter a,
                (SELECT @row:=0) r 
            WHERE 
                1=1 AND a.deleted='N' AND (
                a.nm_costcenter LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.created_by LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.updated_by LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData']  = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'nm_costcenter',
            2 => 'mp_1',
            3 => 'mp_2',
            4 => 'mp_3',
            5 => 'shift1',
            6 => 'shift2',
            7 => 'shift3'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	//SHIFT
    public function get_json_shift(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->get_query_json_shift(
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['day']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['nm_type']))."</div>";
			$nestedData[]	= "<div align='center'>".date('H:i',strtotime($row['start_work']))."</div>";
			$nestedData[]	= "<div align='center'>".date('H:i',strtotime($row['done_work']))."</div>";

			$nestedData[]	= "<div align='center'>".date('H:i',strtotime($row['start_break_1']))."</div>";
			$nestedData[]	= "<div align='center'>".date('H:i',strtotime($row['done_break_1']))."</div>";
			$nestedData[]	= "<div align='center'>".date('H:i',strtotime($row['start_break_2']))."</div>";
			$nestedData[]	= "<div align='center'>".date('H:i',strtotime($row['done_break_2']))."</div>";
			$nestedData[]	= "<div align='center'>".date('H:i',strtotime($row['start_break_3']))."</div>";
			$nestedData[]	= "<div align='center'>".date('H:i',strtotime($row['done_break_3']))."</div>";

			$last_create = (!empty($row['updated_by']))?$row['updated_by']:$row['created_by'];
			$nestedData[]	= "<div align='center'>".strtolower($last_create)."</div>";

			$last_date = (!empty($row['updated_date']))?$row['updated_date']:$row['created_date'];
			$nestedData[]	= "<div align='center'>".date('d-m-Y', strtotime($last_date))."</div>";

                $detail		= "";
                $edit		= "";
                $delete		= "";

                if($Arr_Akses['delete']=='1'){
                    $delete	= "&nbsp;<button type='button' class='btn btn-sm btn-danger delete' title='Delete data' data-code='".$row['id_shift']."'><i class='fa fa-trash'></i></button>";
                }

                if($Arr_Akses['update']=='1'){
                    $edit	= "&nbsp;<button type='button' class='btn btn-sm btn-primary edit' data-code='".$row['id_shift']."' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></button>";
                }
                $nestedData[]	= "<div align='left'>
                                    ".$edit."
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

	public function get_query_json_shift($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
            SELECT
                (@row:=@row+1) AS nomor,
				a.*
			FROM
                shift a,
                (SELECT @row:=0) r 
            WHERE 
                1=1 AND a.deleted='N' AND (
                a.nm_shift LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.created_by LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.updated_by LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.day LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData']  = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'day',
            2 => 'nm_type',
            3 => 'start_work',
            4 => 'done_work'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	//DEPARTMENT
	public function get_json_department(){
		$controller			= ucfirst(strtolower($this->uri->segment(1))).'/department';
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->get_query_json_department(
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['nm_dept']))."</div>";
			$value = "Active";
			$color = "bg-green";
			if($row['status'] == 'N'){
				$value = "Not Active";
				$color = "bg-red";
			}
			$nestedData[]	= "<div align='center'><span class='badge ".$color." '>".$value."</span></div>";

			$last_create = (!empty($row['updated_by']))?$row['updated_by']:$row['created_by'];
			$nestedData[]	= "<div align='center'>".strtolower($last_create)."</div>";

			$last_date = (!empty($row['updated_date']))?$row['updated_date']:$row['created_date'];
			$nestedData[]	= "<div align='center'>".date('d-m-Y', strtotime($last_date))."</div>";

                $detail		= "";
                $edit		= "";
                $delete		= "";

                if($Arr_Akses['delete']=='1'){
                    $delete	= "&nbsp;<button type='button' class='btn btn-sm btn-danger delete' title='Delete data' data-code='".$row['id']."'><i class='fa fa-trash'></i></button>";
                }

                if($Arr_Akses['update']=='1'){
                    $edit	= "&nbsp;<button type='button' class='btn btn-sm btn-primary edit' data-code='".$row['id']."' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></button>";
                }
                $nestedData[]	= "<div align='left'>
                                    ".$edit."
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

	public function get_query_json_department($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
            SELECT
                (@row:=@row+1) AS nomor,
				a.*
			FROM
                department a,
                (SELECT @row:=0) r 
            WHERE 
                1=1 AND a.deleted='N' AND (
                a.nm_dept LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.created_by LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.updated_by LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData']  = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'nm_dept'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
    }

}
