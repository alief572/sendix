<?php

class Serverside_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		// Your own constructor code
	}

  //===============================================================================================================================
  //========================================TOOLS EQUIPMENT==========================================================================
  //===============================================================================================================================

  public function get_json_vehicle_tool(){
    $controller		= ucfirst(strtolower($this->uri->segment(1)));
    $Arr_Akses		= getAcccesmenu($controller);
    $requestData	= $_REQUEST;
    $fetch		  	= $this->get_query_json_vehicle_tool(
      $requestData['search']['value'],
      $requestData['order'][0]['column'],
      $requestData['order'][0]['dir'],
      $requestData['start'],
      $requestData['length']
    );
    $totalData		  = $fetch['totalData'];
    $totalFiltered	= $fetch['totalFiltered'];
    $query			    = $fetch['query'];

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
      $nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['category']))."</div>";
      $nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['spec']))."</div>";
      $nestedData[]	= "<div align='center'>".strtoupper(get_name('unit','unit','id',$row['unit']))."</div>";

      $last_create  = (!empty($row['updated_by']))?$row['updated_by']:$row['created_by'];
      $nestedData[]	= "<div align='center'>".strtolower($last_create)."</div>";

      $last_date = (!empty($row['updated_date']))?$row['updated_date']:$row['created_date'];
      $nestedData[]	= "<div align='center'>".date('d-M-Y H:i:s', strtotime($last_date))."</div>";
      $updX	= "";
      $delX	= "";
      $cd = substr($row['code_group'], 0,2);
      if($cd <> 'AS'){
        if($Arr_Akses['update']=='1'){
          $updX	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/add/'.$row['code_group'])."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
        }
        if($Arr_Akses['delete']=='1'){
          $delX	= "<button class='btn btn-sm btn-danger deleted' title='Permanent Delete' data-code_group='".$row['code_group']."'><i class='fa fa-trash'></i></button>";
        }
      }
      $nestedData[]	= "<div align='center'>
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

  public function get_query_json_vehicle_tool($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

    $sql = "
            SELECT
            (@row:=@row+1) AS nomor,
            a.*
            FROM
            vehicle_tool_new a,
            (SELECT @row:=0) r
            WHERE 1=1 AND a.deleted = 'N' AND (
            a.category LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.spec LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.created_by LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.updated_by LIKE '%".$this->db->escape_like_str($like_value)."%'
            )
            ";
    // echo $sql; exit;
    $data['totalData'] = $this->db->query($sql)->num_rows();
    $data['totalFiltered'] = $this->db->query($sql)->num_rows();
    $columns_order_by = array(
      0 => 'nomor',
      1 => 'category',
      2 => 'spec'
    );

    $sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
    $sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

    $data['query'] = $this->db->query($sql);
    return $data;
  }

  //===============================================================================================================================
  //========================================MAN POWER==============================================================================
  //===============================================================================================================================

  public function get_json_man_power(){
    $controller		= ucfirst(strtolower($this->uri->segment(1)));
    $Arr_Akses		= getAcccesmenu($controller);
    $requestData	= $_REQUEST;
    $fetch		  	= $this->get_query_json_man_power(
      $requestData['search']['value'],
      $requestData['order'][0]['column'],
      $requestData['order'][0]['dir'],
      $requestData['start'],
      $requestData['length']
    );
    $totalData		  = $fetch['totalData'];
    $totalFiltered	= $fetch['totalFiltered'];
    $query			    = $fetch['query'];

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
      $nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['ctg']))."</div>";
      $nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['spec']))."</div>";
      $nestedData[]	= "<div align='left'>".ucfirst(strtolower($row['note']))."</div>";

      $last_create  = (!empty($row['updated_by']))?$row['updated_by']:$row['created_by'];
      $nestedData[]	= "<div align='center'>".strtolower($last_create)."</div>";

      $last_date = (!empty($row['updated_date']))?$row['updated_date']:$row['created_date'];
      $nestedData[]	= "<div align='center'>".date('d-m-Y H:i:s', strtotime($last_date))."</div>";
      $updX	= "";
      $delX	= "";

      if($Arr_Akses['update']=='1'){
        $updX	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/add/'.$row['code_group'])."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
      }
      if($Arr_Akses['delete']=='1'){
        $delX	= "<button class='btn btn-sm btn-danger deleted' title='Permanent Delete' data-code_group='".$row['code_group']."'><i class='fa fa-trash'></i></button>";
      }
      $nestedData[]	= "<div align='center'>
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

  public function get_query_json_man_power($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

    $sql = "
            SELECT
            (@row:=@row+1) AS nomor,
            a.*,
            b.category AS ctg
            FROM
            man_power_new a LEFT JOIN man_power_category b ON a.category = b.id,
            (SELECT @row:=0) r
            WHERE 1=1 AND a.deleted='N' AND (
            b.category LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.spec LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.note LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.created_by LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.updated_by LIKE '%".$this->db->escape_like_str($like_value)."%'
            )
            ";
    // echo $sql; exit;
    $data['totalData'] = $this->db->query($sql)->num_rows();
    $data['totalFiltered'] = $this->db->query($sql)->num_rows();
    $columns_order_by = array(
      0 => 'nomor',
      1 => 'b.category',
      2 => 'spec',
      3 => 'note'
    );

    $sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
    $sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

    $data['query'] = $this->db->query($sql);
    return $data;
  }

  //===============================================================================================================================
  //========================================CONSUMABLE=============================================================================
  //===============================================================================================================================

  public function get_json_consumable(){
    $controller		= ucfirst(strtolower($this->uri->segment(1)));
    $Arr_Akses		= getAcccesmenu($controller);
    $requestData	= $_REQUEST;
    $fetch		  	= $this->get_query_json_consumable(
      $requestData['search']['value'],
      $requestData['order'][0]['column'],
      $requestData['order'][0]['dir'],
      $requestData['start'],
      $requestData['length']
    );
    $totalData		  = $fetch['totalData'];
    $totalFiltered	= $fetch['totalFiltered'];
    $query			    = $fetch['query'];

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
      $nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['category']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['material_name']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['general_name']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['spec']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['brand']))."</div>";
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['order_point']))."</div>";
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['lead_time']))."</div>";

      // $last_create  = (!empty($row['updated_by']))?$row['updated_by']:$row['created_by'];
      // $nestedData[]	= "<div align='center'>".strtolower($last_create)."</div>";

      // $last_date = (!empty($row['updated_date']))?$row['updated_date']:$row['created_date'];
      // $nestedData[]	= "<div align='center'>".date('d-m-Y H:i:s', strtotime($last_date))."</div>";
      $updX	= "";
      $delX	= "";

      if($Arr_Akses['update']=='1'){
        $updX	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/add_new/'.$row['code_group'])."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
      }
      if($Arr_Akses['delete']=='1'){
        $delX	= "<button class='btn btn-sm btn-danger deleted' title='Permanent Delete' data-code_group='".$row['code_group']."'><i class='fa fa-trash'></i></button>";
      }
      $nestedData[]	= "<div align='center'>
                        <button type='button' class='btn btn-sm btn-warning detail' title='Detail Data' data-code_group='".$row['code_group']."'><i class='fa fa-eye'></i></button>
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

  public function get_query_json_consumable($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

    $sql = "
            SELECT
            (@row:=@row+1) AS nomor,
            a.*
            FROM
            con_nonmat_new a,
            (SELECT @row:=0) r
            WHERE 1=1 AND a.deleted='N' AND (
            a.category LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR material_name LIKE '%".$this->db->escape_like_str($like_value)."%'
    				OR general_name LIKE '%".$this->db->escape_like_str($like_value)."%'
    				OR spec LIKE '%".$this->db->escape_like_str($like_value)."%'
    				OR brand LIKE '%".$this->db->escape_like_str($like_value)."%'
    				OR order_point LIKE '%".$this->db->escape_like_str($like_value)."%'
    				OR lead_time LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.created_by LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.updated_by LIKE '%".$this->db->escape_like_str($like_value)."%'
            )
            ";
    // echo $sql; exit;
    $data['totalData'] = $this->db->query($sql)->num_rows();
    $data['totalFiltered'] = $this->db->query($sql)->num_rows();
    $columns_order_by = array(
      0 => 'nomor',
      1 => 'category',
			2 => 'material_name',
			3 => 'general_name',
			4 => 'spec',
			5 => 'brand',
			6 => 'order_point',
			7 => 'lead_time'
    );

    $sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
    $sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

    $data['query'] = $this->db->query($sql);
    return $data;
  }

  //===============================================================================================================================
  //========================================AKOMODASI==============================================================================
  //===============================================================================================================================

  public function get_json_akomodasi(){
    $controller		= ucfirst(strtolower($this->uri->segment(1)));
    $Arr_Akses		= getAcccesmenu($controller);
    $requestData	= $_REQUEST;
    $fetch		  	= $this->get_query_json_akomodasi(
      $requestData['search']['value'],
      $requestData['order'][0]['column'],
      $requestData['order'][0]['dir'],
      $requestData['start'],
      $requestData['length']
    );
    $totalData		  = $fetch['totalData'];
    $totalFiltered	= $fetch['totalFiltered'];
    $query			    = $fetch['query'];

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
      $nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['category']))."</div>";
      $nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['spec']))."</div>";

      $last_create  = (!empty($row['updated_by']))?$row['updated_by']:$row['created_by'];
      $nestedData[]	= "<div align='center'>".strtolower($last_create)."</div>";

      $last_date = (!empty($row['updated_date']))?$row['updated_date']:$row['created_date'];
      $nestedData[]	= "<div align='center'>".date('d-m-Y H:i:s', strtotime($last_date))."</div>";
      $updX	= "";
      $delX	= "";

      if($Arr_Akses['update']=='1'){
        $updX	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/add/'.$row['code_group'])."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
      }
      if($Arr_Akses['delete']=='1'){
        $delX	= "<button class='btn btn-sm btn-danger deleted' title='Permanent Delete' data-code_group='".$row['code_group']."'><i class='fa fa-trash'></i></button>";
      }
      $nestedData[]	= "<div align='center'>
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

  public function get_query_json_akomodasi($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

    $sql = "
            SELECT
            (@row:=@row+1) AS nomor,
            a.*
            FROM
            akomodasi_new a,
            (SELECT @row:=0) r
            WHERE 1=1 AND a.deleted='N' AND (
            a.category LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.spec LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.created_by LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.updated_by LIKE '%".$this->db->escape_like_str($like_value)."%'
            )
            ";
    // echo $sql; exit;
    $data['totalData'] = $this->db->query($sql)->num_rows();
    $data['totalFiltered'] = $this->db->query($sql)->num_rows();
    $columns_order_by = array(
      0 => 'nomor',
      1 => 'category',
      2 => 'spec'
    );

    $sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
    $sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

    $data['query'] = $this->db->query($sql);
    return $data;
  }

  //===============================================================================================================================
  //===================================================HEAVY EQUIPMENT==============================================================
  //===============================================================================================================================

  public function get_json_heavy_equipment(){
    $controller		= ucfirst(strtolower($this->uri->segment(1)));
    $Arr_Akses		= getAcccesmenu($controller);
    $requestData	= $_REQUEST;
    $fetch		  	= $this->get_query_json_heavy_equipment(
      $requestData['search']['value'],
      $requestData['order'][0]['column'],
      $requestData['order'][0]['dir'],
      $requestData['start'],
      $requestData['length']
    );
    $totalData		  = $fetch['totalData'];
    $totalFiltered	= $fetch['totalFiltered'];
    $query			    = $fetch['query'];

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
      $nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['category']))."</div>";
      $nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['spec']))."</div>";

      $last_create  = (!empty($row['updated_by']))?$row['updated_by']:$row['created_by'];
      $nestedData[]	= "<div align='center'>".strtolower($last_create)."</div>";

      $last_date = (!empty($row['updated_date']))?$row['updated_date']:$row['created_date'];
      $nestedData[]	= "<div align='center'>".date('d-m-Y H:i:s', strtotime($last_date))."</div>";
      $updX	= "";
      $delX	= "";
      $cd = substr($row['code_group'], 0,2);
      if($cd <> 'AS'){
        if($Arr_Akses['update']=='1'){
          $updX	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/add/'.$row['code_group'])."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
        }
        if($Arr_Akses['delete']=='1'){
          $delX	= "<button class='btn btn-sm btn-danger deleted' title='Permanent Delete' data-code_group='".$row['code_group']."'><i class='fa fa-trash'></i></button>";
        }
      }
      $nestedData[]	= "<div align='center'>
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

  public function get_query_json_heavy_equipment($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

    $sql = "
            SELECT
              (@row:=@row+1) AS nomor,
              a.*
            FROM
              heavy_equipment_new a,
              (SELECT @row:=0) r
            WHERE 1=1 AND a.deleted = 'N' AND (
              a.category LIKE '%".$this->db->escape_like_str($like_value)."%'
              OR a.spec LIKE '%".$this->db->escape_like_str($like_value)."%'
              OR a.created_by LIKE '%".$this->db->escape_like_str($like_value)."%'
              OR a.updated_by LIKE '%".$this->db->escape_like_str($like_value)."%'
            )
            ";
    // echo $sql; exit;
    $data['totalData'] = $this->db->query($sql)->num_rows();
    $data['totalFiltered'] = $this->db->query($sql)->num_rows();
    $columns_order_by = array(
      0 => 'nomor',
      1 => 'category',
      2 => 'spec'
    );

    $sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
    $sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

    $data['query'] = $this->db->query($sql);
    return $data;
  }

}
