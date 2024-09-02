<?php

class Unit_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		// Your own constructor code
	}

  public function get_json_unit(){
    $controller		= ucfirst(strtolower($this->uri->segment(1)));
    $Arr_Akses		= getAcccesmenu($controller);
    $requestData	= $_REQUEST;
    $fetch		  	= $this->get_query_json_unit(
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
      $nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['unit']))."</div>";

      $last_create  = (!empty($row['updated_by']))?$row['updated_by']:$row['created_by'];
      $nestedData[]	= "<div align='center'>".strtolower($last_create)."</div>";

      $last_date = (!empty($row['updated_date']))?$row['updated_date']:$row['created_date'];
      $nestedData[]	= "<div align='center'>".date('d-m-Y H:i:s', strtotime($last_date))."</div>";
      $updX	= "";
      $delX	= "";
        if($Arr_Akses['update']=='1'){
          $updX	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/add/'.$row['id'])."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
        }
        if($Arr_Akses['delete']=='1'){
          $delX	= "<button class='btn btn-sm btn-danger deleted' title='Permanent Delete' data-id='".$row['id']."'><i class='fa fa-trash'></i></button>";
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

  public function get_query_json_unit($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

    $sql = "
            SELECT
            (@row:=@row+1) AS nomor,
            a.*
            FROM
            unit a,
            (SELECT @row:=0) r
            WHERE 1=1 AND a.deleted='N' AND (
            a.unit LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.created_by LIKE '%".$this->db->escape_like_str($like_value)."%'
            OR a.updated_by LIKE '%".$this->db->escape_like_str($like_value)."%'
            )
            ";
    // echo $sql; exit;
    $data['totalData'] = $this->db->query($sql)->num_rows();
    $data['totalFiltered'] = $this->db->query($sql)->num_rows();
    $columns_order_by = array(
      0 => 'nomor',
      1 => 'unit'
    );

    $sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
    $sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

    $data['query'] = $this->db->query($sql);
    return $data;
  }

}
