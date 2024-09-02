<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Con_nonmat extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		$this->load->model('serverside_model');

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
		$getBy				= "SELECT updated_by, updated_date FROM tb_view_con_nonmat ORDER BY updated_date DESC LIMIT 1";
		$restgetBy			= $this->db->query($getBy)->result();

		$data = array(
			'title'			=> 'Indeks Of Consumable',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses,
			'get_by'		=> $restgetBy
		);
		history('View Data Consumable');
		$this->load->view('Con_nonmat/index',$data);
	}

	public function data_side_consumable(){
		$this->serverside_model->get_json_consumable();
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['material_name']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['general_name']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['spec']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['brand']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['order_point']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['lead_time']))."</div>";
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

	public function queryDataJSON($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
			SELECT
				*
			FROM
				con_nonmat_new
		    WHERE 1=1 AND code_group LIKE 'CN%' AND deleted='N' AND (
				category LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR material_name LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR general_name LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR spec LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR brand LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR order_point LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR lead_time LIKE '%".$this->db->escape_like_str($like_value)."%'
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

	public function modalDetail(){
		$code_group = $this->uri->segment(3);

		$qHeader 		= "SELECT * FROM con_nonmat_new WHERE code_group='".$code_group."'";
		$qDetailKon = "SELECT * FROM con_nonmat_new_konversi WHERE code_group='".$code_group."' AND deleted='N'";
		$qDetailMat = "SELECT * FROM con_nonmat_new_add WHERE code_group='".$code_group."' AND category='material' AND deleted='N'";
		$qDetailSup = "SELECT * FROM con_nonmat_new_add WHERE code_group='".$code_group."' AND category='supplier' AND deleted='N'";

		$restHeader = $this->db->query($qHeader)->result();
		$restDetKon = $this->db->query($qDetailKon)->result_array();
		$restDetMat = $this->db->query($qDetailMat)->result_array();
		$restDetSup = $this->db->query($qDetailSup)->result_array();

		$data = array(
			'header'		=> $restHeader,
			'konversi'	=> $restDetKon,
			'material'	=> $restDetMat,
			'supplier'	=> $restDetSup
		);

		$this->load->view('Con_nonmat/modalDetail', $data);
	}

	public function add(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$category			= strtolower($data['category']);
			$spec			= str_replace(["'", '"'], ' inchi', strtolower(trim($data['spec'])));
			$tanda_edit		= strtolower($data['tanda_edit']);
			$code_group		= $data['code_group'];
			$DetailData		= $data['DetailData'];

			// echo $tanda_edit."<br>".$spec;
			// print_r($DetailData);
			// exit;

			//Pencarian data yang sudah ada
			$ValueProduct	= "SELECT * FROM con_nonmat_new WHERE category='".$category."' AND spec='".$spec."' ";
			$NumProduct		= $this->db->query($ValueProduct)->num_rows();
			if(!empty($tanda_edit)){
				$NumProduct = 0;
			}

			// echo $ValueProduct."<br>";
			// echo $NumProduct;

			if($NumProduct > 0){
				$Arr_Kembali		= array(
					'status'		=> 3,
					'pesan'			=> 'Spesifikasi product sudah digunakan. Input spesifikasi lain ...'
				);
			}
			else{
				//insert
				if(empty($tanda_edit)){
					$Hist = 'Add ';

					//pengurutan kode
					$srcMtr			= "SELECT MAX(code_group) as maxP FROM con_nonmat_new WHERE code_group LIKE 'CN%' ";
					$numrowMtr		= $this->db->query($srcMtr)->num_rows();
					$resultMtr		= $this->db->query($srcMtr)->result_array();
					$angkaUrut2		= $resultMtr[0]['maxP'];
					$urutan2		= (int)substr($angkaUrut2, 2, 5);
					$urutan2++;
					$urut2			= sprintf('%05s',$urutan2);
					$code_group		= "CN".$urut2;
					$ArrInsert = array();
					$nomor = 0;
					foreach($DetailData AS $val => $valx){
						$nomor++;
						$ArrInsert[$val]['code_group'] = $code_group;
						$ArrInsert[$val]['category'] = $category;
						$ArrInsert[$val]['spec'] = $spec;
						$ArrInsert[$val]['region'] = $valx['region'];
						$ArrInsert[$val]['rate'] = str_replace(',','',$valx['rate']);
						$ArrInsert[$val]['created_by'] = $data_session['ORI_User']['username'];
						$ArrInsert[$val]['created_date'] = $dateTime;

					}

					// echo "<pre>"; print_r($ArrInsert);
					// exit;

					$this->db->trans_start();
					$this->db->insert_batch('con_nonmat_new', $ArrInsert);
					$this->db->trans_complete();
				}

				//edit
				if(!empty($tanda_edit)){
					$Hist = 'Edit ';
					$ArrInsert = array();
					$nomor = 0;
					foreach($DetailData AS $val => $valx){
						$nomor++;
						$ArrInsert[$val]['id'] = $valx['id'];
						$ArrInsert[$val]['category'] = $category;
						$ArrInsert[$val]['spec'] = $spec;
						$ArrInsert[$val]['region'] = $valx['region'];
						$ArrInsert[$val]['rate'] = str_replace(',','',$valx['rate']);
						$ArrInsert[$val]['updated_by'] = $data_session['ORI_User']['username'];
						$ArrInsert[$val]['updated_date'] = $dateTime;

					}
					// echo "<pre>"; print_r($ArrInsert);
					// exit;

					$this->db->trans_start();
					$this->db->update_batch('con_nonmat', $ArrInsert,'id');
					$this->db->trans_complete();
				}

				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$Arr_Kembali	= array(
						'pesan'		=> $Hist.'Consumable Non Material data failed. Please try again later ...',
						'status'	=> 2
					);
				}
				else{
					$this->db->trans_commit();
					$Arr_Kembali	= array(
						'pesan'		=> $Hist.'Consumable Non Material data success. Thanks ...',
						'status'	=> 1
					);
					history($Hist.'Consumable Non Material '.$category.' / '.$spec);
				}
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

			$uri3 = $this->uri->segment(3);


			$qRegion	= "SELECT * FROM region ORDER BY urut ASC";
			$tanda1 = 'Add';
			if(!empty($uri3)){
				$qRegion	= "SELECT a.region_code, a.region, b.rate, b.category, b.spec, b.id FROM region a LEFT JOIN con_nonmat_new b ON a.region=b.region WHERE b.code_group='".$uri3."' ORDER BY a.urut ASC";
				$tanda1 = 'Edit';
			}

			// echo $qRegion;
			$restRegion	= $this->db->query($qRegion)->result_array();

			$qCateMP	= "SELECT * FROM con_nonmat_category ORDER BY category ASC";
			$restCateMP	= $this->db->query($qCateMP)->result_array();

			$data = array(
				'title'			=> $tanda1.' Consumable',
				'action'		=> 'add',
				'region'		=> $restRegion,
				'cateMP'		=> $restCateMP
			);
			$this->load->view('Con_nonmat/add',$data);
		}
	}

	public function add_new(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');
			$db2 = $this->load->database('costing', TRUE);
			// print_r($data);
			// exit;
			$category_awal= strtolower($data['category_awal']);
			$category			= strtolower($data['category']);
			$spec					= strtolower(trim($data['spec']));
			$tanda_edit		= strtolower($data['tanda_edit']);
			$code_group		= $data['code_group'];
			$material_name= strtolower(trim($data['material_name']));
			$general_name	= strtolower(trim($data['general_name']));
			$brand				= strtolower(trim($data['brand']));
			$min_order		= strtolower(trim($data['min_order']));
			$order_opt		= strtolower(trim($data['order_opt']));
			$order_point	= strtolower(trim($data['order_point']));
			$order_point_date	= strtolower(trim($data['order_point_date']));
			$safety_stock	= strtolower(trim($data['safety_stock']));
			$lead_time		= strtolower(trim($data['lead_time']));
			$max_stock		= strtolower(trim($data['max_stock']));
			$konsumsi			= strtolower(trim($data['konsumsi']));
			$note					= strtolower(trim($data['note']));
			if(!empty($data['ListKonversi'])){
				$ListKonversi	= $data['ListKonversi'];
			}
			if(!empty($data['ListMaterial'])){
				$ListMaterial	= $data['ListMaterial'];
			}
			if(!empty($data['ListSupplier'])){
				$ListSupplier	= $data['ListSupplier'];
			}

			// echo $tanda_edit."<br>".$spec;
			// print_r($DetailData);
			// exit;

			//Pencarian data yang sudah ada
			$ValueProduct	= "SELECT * FROM con_nonmat_new WHERE category='".$category."' AND spec='".$spec."' ";
			$NumProduct		= $this->db->query($ValueProduct)->num_rows();
			if(!empty($tanda_edit)){
				$NumProduct = 0;
			}

			// echo $ValueProduct."<br>";
			// echo $NumProduct;

			if($NumProduct > 0){
				$Arr_Kembali		= array(
					'status'		=> 3,
					'pesan'			=> 'Spesifikasi product sudah digunakan. Input spesifikasi lain ...'
				);
			}
			else{
				//insert
				if(empty($tanda_edit)){
					$Hist = 'Add ';

					//pengurutan kode
					$srcMtr				= "SELECT MAX(code_group) as maxP FROM con_nonmat_new WHERE code_group LIKE 'CN%' ";
					$numrowMtr		= $this->db->query($srcMtr)->num_rows();
					$resultMtr		= $this->db->query($srcMtr)->result_array();
					$angkaUrut2		= $resultMtr[0]['maxP'];
					$urutan2			= (int)substr($angkaUrut2, 2, 5);
					$urutan2++;
					$urut2				= sprintf('%05s',$urutan2);
					$code_group		= "CN".$urut2;
					//header
					$ArrInsert = array(
						'code_group' => $code_group,
						'category_awal' => $category_awal,
						'category_code' => $category,
						'category' => get_name('con_nonmat_category', 'category', 'id', $category),
						'spec' => $spec,
						'material_name' => $material_name,
						'general_name' => $general_name,
						'brand' => $brand,
						'min_order' => $min_order,
						'order_opt' => $order_opt,
						'order_point' => $order_point,
						'order_point_date' => $order_point_date,
						'safety_stock' => $safety_stock,
						'lead_time' => $lead_time,
						'max_stock' => $max_stock,
						'konsumsi' => $konsumsi,
						'note' => $note,
						'created_by' => $data_session['ORI_User']['username'],
						'created_date' => $dateTime
					);

					//konversi
					if(!empty($data['ListKonversi'])){
						$ArrKonversi = array();
						foreach($ListKonversi AS $val => $valx){
							$ArrKonversi[$val]['code_group'] = $code_group;
							$ArrKonversi[$val]['unit_material'] = $valx['unit_material'];
							$ArrKonversi[$val]['value'] = $valx['value'];
							$ArrKonversi[$val]['small_unit'] = $valx['small_unit'];
							$ArrKonversi[$val]['updated_by'] = $data_session['ORI_User']['username'];
							$ArrKonversi[$val]['updated_date'] = $dateTime;

						}
					}

					//material
					if(!empty($data['ListMaterial'])){
						$ArrMaterial = array();
						foreach($ListMaterial AS $val => $valx){
							$ArrMaterial[$val]['code_group'] = $code_group;
							$ArrMaterial[$val]['category'] = 'material';
							$ArrMaterial[$val]['value'] = $valx['material'];
							$ArrMaterial[$val]['updated_by'] = $data_session['ORI_User']['username'];
							$ArrMaterial[$val]['updated_date'] = $dateTime;

						}
					}

					//add
					if(!empty($data['ListSupplier'])){
						$ArrSupplier = array();
						$nomor = 0;
						foreach($ListSupplier AS $valx){
							$nomor++;

							$getData	 	= $db2->query("SELECT nm_supplier FROM supplier WHERE id_supplier = '".$valx."' LIMIT 1")->result();
							$ArrSupplier[$nomor]['code_group'] = $code_group;
							$ArrSupplier[$nomor]['category'] = 'supplier';
							$ArrSupplier[$nomor]['value'] = $valx;
							$ArrSupplier[$nomor]['value_2'] = $getData[0]->nm_supplier;
							$ArrSupplier[$nomor]['updated_by'] = $data_session['ORI_User']['username'];
							$ArrSupplier[$nomor]['updated_date'] = $dateTime;

						}
					}


					// echo "<pre>"; print_r($ArrInsert);
					// echo "<pre>"; print_r($ArrKonversi);
					// echo "<pre>"; print_r($ArrMaterial);
					// echo "<pre>"; print_r($ArrSupplier);
					// exit;

					$this->db->trans_start();
							$this->db->insert('con_nonmat_new', $ArrInsert);

							if(!empty($ArrKonversi)){
								$this->db->insert_batch('con_nonmat_new_konversi', $ArrKonversi);
							}
							if(!empty($ArrMaterial)){
								$this->db->insert_batch('con_nonmat_new_add', $ArrMaterial);
							}
							if(!empty($ArrSupplier)){
								$this->db->insert_batch('con_nonmat_new_add', $ArrSupplier);
							}
					$this->db->trans_complete();
				}

				//edit
				if(!empty($tanda_edit)){
					$Hist = 'Edit ';
					$ArrInsert = array(
						'code_group' => $code_group,
						'category' => $category,
						'spec' => $spec,
						'material_name' => $material_name,
						'general_name' => $general_name,
						'brand' => $brand,
						'min_order' => $min_order,
						'order_opt' => $order_opt,
						'order_point' => $order_point,
						'order_point_date' => $order_point_date,
						'safety_stock' => $safety_stock,
						'lead_time' => $lead_time,
						'max_stock' => $max_stock,
						'konsumsi' => $konsumsi,
						'note' => $note,
						'updated_by' => $data_session['ORI_User']['username'],
						'updated_date' => $dateTime
					);

					//konversi
					if(!empty($data['ListKonversi'])){
						$ArrKonversi = array();
						foreach($ListKonversi AS $val => $valx){
							$ArrKonversi[$val]['code_group'] = $code_group;
							$ArrKonversi[$val]['unit_material'] = $valx['unit_material'];
							$ArrKonversi[$val]['value'] = $valx['value'];
							$ArrKonversi[$val]['small_unit'] = $valx['small_unit'];
							$ArrKonversi[$val]['updated_by'] = $data_session['ORI_User']['username'];
							$ArrKonversi[$val]['updated_date'] = $dateTime;

						}
					}

					//material
					if(!empty($data['ListMaterial'])){
						$ArrMaterial = array();
						foreach($ListMaterial AS $val => $valx){
							$ArrMaterial[$val]['code_group'] = $code_group;
							$ArrMaterial[$val]['category'] = 'material';
							$ArrMaterial[$val]['value'] = $valx['material'];
							$ArrMaterial[$val]['updated_by'] = $data_session['ORI_User']['username'];
							$ArrMaterial[$val]['updated_date'] = $dateTime;

						}
					}

					//add
					if(!empty($data['ListSupplier'])){
						$ArrSupplier = array();
						$nomor = 0;
						foreach($ListSupplier AS $valx){
							$nomor++;

							$getData	 	= $db2->query("SELECT nm_supplier FROM supplier WHERE id_supplier = '".$valx."' LIMIT 1")->result();
							$ArrSupplier[$nomor]['code_group'] = $code_group;
							$ArrSupplier[$nomor]['category'] = 'supplier';
							$ArrSupplier[$nomor]['value'] = $valx;
							$ArrSupplier[$nomor]['value_2'] = $getData[0]->nm_supplier;
							$ArrSupplier[$nomor]['updated_by'] = $data_session['ORI_User']['username'];
							$ArrSupplier[$nomor]['updated_date'] = $dateTime;

						}
					}

					// echo "<pre>"; print_r($ArrInsert);
					// echo "<pre>"; print_r($ArrKonversi);
					// echo "<pre>"; print_r($ArrMaterial);
					// echo "<pre>"; print_r($ArrSupplier);
					// exit;

					$this->db->trans_start();
							$this->db->where('code_group', $code_group);
							$this->db->update('con_nonmat_new', $ArrInsert);

							$this->db->delete('con_nonmat_new_konversi', array('code_group' => $code_group));
							$this->db->delete('con_nonmat_new_add', array('code_group' => $code_group));

							if(!empty($ArrKonversi)){
								$this->db->insert_batch('con_nonmat_new_konversi', $ArrKonversi);
							}
							if(!empty($ArrMaterial)){
								$this->db->insert_batch('con_nonmat_new_add', $ArrMaterial);
							}
							if(!empty($ArrSupplier)){
								$this->db->insert_batch('con_nonmat_new_add', $ArrSupplier);
							}
					$this->db->trans_complete();
				}

				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$Arr_Kembali	= array(
						'pesan'		=> $Hist.'Consumable data failed. Please try again later ...',
						'status'	=> 2
					);
				}
				else{
					$this->db->trans_commit();
					$Arr_Kembali	= array(
						'pesan'		=> $Hist.'Consumable data success. Thanks ...',
						'status'	=> 1
					);
					history($Hist.'Consumable Non Material '.$code_group);
				}
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

			$code_group = $this->uri->segment(3);

			$tanda1 = 'Add';
			if(!empty($code_group)){
				$tanda1 = 'Edit';
			}

			$qHeader 		= "SELECT * FROM con_nonmat_new WHERE code_group='".$code_group."'";
			$qDetailKon = "SELECT * FROM con_nonmat_new_konversi WHERE code_group='".$code_group."' AND deleted='N'";
			$qDetailMat = "SELECT * FROM con_nonmat_new_add WHERE code_group='".$code_group."' AND category='material' AND deleted='N'";
			$qDetailSup = "SELECT * FROM con_nonmat_new_add WHERE code_group='".$code_group."' AND category='supplier' AND deleted='N'";

			$restHeader = $this->db->query($qHeader)->result();
			$restDetKon = $this->db->query($qDetailKon)->result_array();
			$restDetMat = $this->db->query($qDetailMat)->result_array();
			$restDetSup = $this->db->query($qDetailSup)->result_array();

			$query	 	= "SELECT * FROM unit WHERE deleted = 'N' ORDER BY id ASC";
 			$Q_result	= $this->db->query($query)->result_array();

			$qCateMP	= "SELECT * FROM con_nonmat_category ORDER BY category ASC";
			$restCateMP	= $this->db->query($qCateMP)->result_array();

			$qCateMPUtama			= "SELECT * FROM con_nonmat_category_awal ORDER BY category ASC";
			$restCateMPUtama	= $this->db->query($qCateMPUtama)->result_array();

			$data = array(
				'title'			=> $tanda1.' Consumable',
				'action'		=> 'add',
				'cateMP'		=> $restCateMP,
				'cateMPUtama'		=> $restCateMPUtama,
				'header'		=> $restHeader,
				'konversi'	=> $restDetKon,
				'material'	=> $restDetMat,
				'supplier'	=> $restDetSup,
				'satuan'	=> $Q_result
			);
			$this->load->view('Con_nonmat/add',$data);
		}
	}

	public function add_category(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$add_category	= strtolower($data['add_category']);
			$add_category_awal	= strtolower($data['add_category_awal']);
			$information	= strtolower($data['information']);
			$code_group	= strtolower($data['code_group']);

			// echo $tanda_category;
			// exit;
			//Pencarian data yang sudah ada
			$ValueProduct	= "SELECT * FROM con_nonmat_category WHERE category='".$add_category."' AND category_awal='".$add_category_awal."' ";
			$NumProduct		= $this->db->query($ValueProduct)->num_rows();

			if($NumProduct > 0){
				$Arr_Kembali		= array(
					'status'		=> 3,
					'pesan'			=> 'Category Consumable Non Material sudah digunakan. Input catgeory lain ...'
				);
			}
			else{
				$ArrInsert = array(
					'category' => $add_category,
					'category_awal' => $add_category_awal,
					'information' => $information,
					'created_by' => $data_session['ORI_User']['username'],
					'created_date' => $dateTime
				);

				$this->db->trans_start();
				$this->db->insert('con_nonmat_category', $ArrInsert);
				$this->db->trans_complete();


				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$Arr_Kembali	= array(
						'pesan'		=> 'Add Category data failed. Please try again later ...',
						'status'	=> 2,
						'code_group'	=> $code_group
					);
				}
				else{
					$this->db->trans_commit();
					$Arr_Kembali	= array(
						'pesan'		=> 'Add Category data success. Thanks ...',
						'status'	=> 1,
						'code_group'	=> $code_group
					);
					history('Add Category '.$add_category);
				}
			}

			echo json_encode($Arr_Kembali);
		}
	}

	public function hapus(){
		$code_group = $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'deleted' 		=> 'Y',
			'deleted_by' 	=> $data_session['ORI_User']['username'],
			'deleted_date' 	=> date('Y-m-d H:i:s')
			);

		$this->db->trans_start();
				$this->db->where('code_group', $code_group);
				$this->db->update('con_nonmat_new', $ArrPlant);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Delete Consumable Non Material data failed. Please try again later ...',
				'status'	=> 0
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Delete Consumable Non Material data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete Consumable Non Material category : '.$code_group);
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
		$sheet->setCellValue('A'.$Row, 'MASTER CONSUMABLE NON MATERIAL');
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

		$qManPower	= "SELECT * FROM view_con_nonmat";
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

		$sheet->setTitle('Consumable Non Material Master');
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
		header('Content-Disposition: attachment;filename="master_consumable_non_mataterial_'.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

	public function list_supplier(){
			$code_group = $this->uri->segment(3);
			// echo $code_group;
			$qDetailSup = "SELECT * FROM con_nonmat_new_add WHERE code_group='".$code_group."' AND category='supplier' AND deleted='N'";
			$restDetSup = $this->db->query($qDetailSup)->result_array();

			$supplierx = '';
			if(!empty($restDetSup)){
				$ArrData1 = array();
				foreach($restDetSup as $vaS => $vaA){
					 $ArrData1[] = $vaA['value'];
				}
				$ArrData1 = implode("," ,$ArrData1);
				$supplierx = explode("," ,$ArrData1);
			}

			$db2 = $this->load->database('costing', TRUE);
			$query	 	= "SELECT id_supplier, nm_supplier FROM supplier ORDER BY nm_supplier ASC";
			$Q_result	= $db2->query($query)->result();
			$option 	= "";
			foreach($Q_result as $row){
				$sel3 = '';
				if(!empty($supplierx)){
					$sel3 = (isset($supplierx) && in_array($row->id_supplier, $supplierx))?'selected':'';
				}
			 	$option .= "<option value='".$row->id_supplier."' ".$sel3.">".strtoupper($row->nm_supplier)."</option>";
			}
		echo json_encode(array(
			'option' => $option
		));
	 }

	 //list satuan

	public function list_satuan(){
 			$query	 	= "SELECT * FROM unit WHERE deleted = 'N' ORDER BY id ASC";
 			$Q_result	= $this->db->query($query)->result();
 			$option 	= "";
 			foreach($Q_result as $row)	{
 			 $option .= "<option value='".$row->unit."'>".strtoupper($row->unit)."</option>";
 			}
 		echo json_encode(array(
 			'option' => $option
 		));
 	 }

	 public function get_category(){
		 	$id = $this->uri->segment(3);
			$query	 	= "SELECT * FROM con_nonmat_category WHERE category_awal = '".$id."' ORDER BY category ASC";
			$Q_result	= $this->db->query($query)->result();
			$option 	= "<option value='0'>Select Category</option>";
			foreach($Q_result as $row)	{
			 $option .= "<option value='".$row->id."'>".strtoupper($row->category)."</option>";
			}
		echo json_encode(array(
			'option' => $option
		));
	 }

		public function get_category2(){
			$id = $this->uri->segment(3);
			$id2 = $this->uri->segment(4);
			$query	 	= "SELECT * FROM con_nonmat_category WHERE category_awal = '".$id."' ORDER BY category ASC";
			$Q_result	= $this->db->query($query)->result();
			$option 	= "<option value='0'>Select Category</option>";
			foreach($Q_result as $row)	{
				$sel = ($id2 == $row->id)?'selected':'';
				$option .= "<option value='".$row->id."' ".$sel.">".strtoupper($row->category)."</option>";
			}
			echo json_encode(array(
				'option' => $option
			));
		}

}
