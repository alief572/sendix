<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

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
			'title'			=> 'Indeks Of Identification Of Customer Requests',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data IPP Tanki');
		$this->load->view('Sales/index',$data);
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
			$created = (is_null($row['modified_by']))?$row['created_by']:$row['modified_by'];
			$nestedData[]	= "<div align='center'>".$created."</div>";
			$modif = (is_null($row['modified_date']))?$row['created_date']:$row['modified_date'];
			$nestedData[]	= "<div align='center'>".date('d-M-Y', strtotime($modif))."</div>";
			$class = getColor($row['status']);
			$nestedData[]	= "<div align='left'><span class='badge' style='background-color:".$class."'>".$row['status']."</span></div>";
					
					$detail	= "<a href='".site_url($this->uri->segment(1)).'/view_ipp/'.$row['no_ipp']."/detail' class='btn btn-sm btn-warning' title='Detail' data-role='qtip'><i class='fa fa-eye'></i></a>";
					$update	= "";
					$release	= "";
					$delete	= "";
					$print	= "";

					if($row['status'] == 'WAITING RELEASE IPP'){
						if($Arr_Akses['update']=='1'){
							$update	= "&nbsp;<a href='".site_url($this->uri->segment(1)).'/add2/'.$row['no_ipp']."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
						}
						if($Arr_Akses['delete']=='1'){
							$delete	= "&nbsp;<button class='btn btn-sm btn-danger delete' title='Delete data' data-no_ipp='".$row['no_ipp']."'><i class='fa fa-trash'></i></button>";
						}
						if($Arr_Akses['update']=='1'){
							$release	= "&nbsp;<button class='btn btn-sm btn-success release' title='Release' data-no_ipp='".$row['no_ipp']."'><i class='fa fa-check'></i></button>";
						}
					}
					if($Arr_Akses['download']=='1'){
						$print	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/print_ipp/'.$row['no_ipp'])."' class='btn btn-sm btn-info' target='_blank' title='Print IPP' data-role='qtip'><i class='fa fa-print'></i></a>";
					}
			$nestedData[]	= "<div align='left'>
									".$detail."
									".$update."
									".$release."
									".$print."
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

	public function queryDataJSON($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
			SELECT
				*
			FROM
				ipp_header
		    WHERE 1=1 AND deleted='N' AND (
				project LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR no_ipp LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'no_ipp',
			2 => 'project'
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
			$db2 = $this->load->database('costing', TRUE);
			// print_r($data);
			// exit;
			$no_ipp 			= $data['no_ipp'];
			$id_customer	= $data['id_customer'];
			$project			= strtolower($data['project']);
			$validity			= strtolower($data['validity']);
			$ref_cust			= strtolower($data['ref_cust']);
			$location			= strtolower($data['location']);
			$app					= $data['app'];

			$Detail			= $data['Detail'];

			$TandaI			= (!empty($no_ipp))?'Update':'Insert';


			$Y					= date('y');
			$dtCust = $db2->query("SELECT * FROM customer WHERE id_customer = '".$id_customer."' LIMIT 1")->result();

			//pengurutan kode
			$LocInt				= ($dtCust[0]->country_code == 'IDN' OR $dtCust[0]->country_code == 'Sele')?'L':'E';
			$qIPP					= "SELECT MAX(no_ipp) as maxP FROM ipp_header WHERE no_ipp LIKE 'IPPS".$Y."%' ";
			$numrowIPP		= $this->db->query($qIPP)->num_rows();
			$resultIPP		= $this->db->query($qIPP)->result_array();
			$angkaUrut2		= $resultIPP[0]['maxP'];
			$urutan2			= (int)substr($angkaUrut2, 6, 3);
			$urutan2++;
			$urut2				= sprintf('%03s',$urutan2);
			$IdIPP				= "IPPS".$Y.$urut2.$LocInt;

			$ipp			= (!empty($no_ipp))?$no_ipp:$IdIPP;

			// echo $IdIPP; exit;
			//project_header
			$ArrHeader = array(
				'no_ipp' 			=> $ipp,
				'id_customer' => $id_customer,
				'nm_customer' => $dtCust[0]->nm_customer,
				'project' 		=> $project,
				'location' 		=> $location,
				'app' 				=> $app,
				'ref_cust' 		=> $ref_cust,
				'validity' 		=> $validity,
				'created_by' 		=> $data_session['ORI_User']['username'],
				'created_date' 	=> date('Y-m-d H:i:s')
			);

			if(!empty($data['Detail'])){
				$no = 0;
				$ArrDetail = array();
				foreach($Detail AS $val => $valx){ $no++;
					$ArrDetail[$val]['no_ipp'] 				= $ipp;
					$ArrDetail[$val]['category'] 			= $valx['category'];
					$ArrDetail[$val]['category_list'] = strtolower($valx['category_list']);
					$ArrDetail[$val]['req'] 					= $valx['req'];
					$ArrDetail[$val]['spec'] 					= strtolower($valx['spec']);
					$ArrDetail[$val]['urut'] 					= $no;
					$ArrDetail[$val]['updated_by'] 		= $data_session['ORI_User']['username'];
					$ArrDetail[$val]['updated_date'] 	= date('Y-m-d H:i:s');
				}
			}

			// print_r($ArrHeader);
			// print_r($ArrDetail);
			// exit;

			$this->db->trans_start();
				if(!empty($no_ipp)){
					$this->db->where('no_ipp', $no_ipp);
					$this->db->update('ipp_header', $ArrHeader);

					$this->db->delete('ipp_detail', array('no_ipp' => $no_ipp));
				}
				else{
					$this->db->insert('ipp_header', $ArrHeader);
				}

				$this->db->insert_batch('ipp_detail', $ArrDetail);
			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> $TandaI.' IPP Instalasi data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> $TandaI.' IPP Instalasi data success. Thanks ...',
					'status'	=> 1
				);
				history($TandaI.' IPP Instalasi '.$ipp);
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
			$db2 = $this->load->database('costing', TRUE);
			//customer
			$dataCust		= "SELECT id_customer, nm_customer FROM customer WHERE id_customer <> 'C100-1903000' ORDER BY nm_customer ASC";
			$restCust		= $db2->query($dataCust)->result_array();
			//app
			$qApp				= "SELECT * FROM list WHERE flag='N' AND category = 'app' ORDER BY urut ASC";
			$app				= $this->db->query($qApp)->result_array();
			//material
			$qMaterial	= "SELECT * FROM list WHERE flag='N' AND category = 'material' ORDER BY urut ASC";
			$material		= $this->db->query($qMaterial)->result_array();
			//akomodasi
			$qAkomodasi	= "SELECT * FROM list WHERE flag='N' AND category = 'akomodasi' ORDER BY urut ASC";
			$akomodasi	= $this->db->query($qAkomodasi)->result_array();
			//product
			$qProduct		= "SELECT * FROM list WHERE flag='N' AND category = 'product' ORDER BY urut ASC";
			$product		= $this->db->query($qProduct)->result_array();
			//fasilitas
			$qFasilitas	= "SELECT * FROM list WHERE flag='N' AND category = 'fasilitas' ORDER BY urut ASC";
			$fasilitas	= $this->db->query($qFasilitas)->result_array();
			//pekerja
			$qPekerja		= "SELECT * FROM list WHERE flag='N' AND category = 'pekerja' ORDER BY urut ASC";
			$pekerja		= $this->db->query($qPekerja)->result_array();
			//req
			$qReq				= "SELECT * FROM list WHERE flag='N' AND category = 'req' ORDER BY urut ASC";
			$req				= $this->db->query($qReq)->result_array();
			//add
			$qAdd				= "SELECT * FROM list WHERE flag='N' AND category = 'add' ORDER BY urut ASC";
			$add				= $this->db->query($qAdd)->result_array();

			$ipp 				= $this->uri->segment(3);
			$qHeader 		= "SELECT * FROM ipp_header WHERE no_ipp='".$ipp."'";
			$header 		= $this->db->query($qHeader)->result();

			if(!empty($ipp)){
				//material
				$qMaterial	= "SELECT a.*, b.category_ FROM ipp_detail a LEFT JOIN view_list b ON a.category=b.category WHERE a.no_ipp='".$ipp."' AND a.category = 'material' ORDER BY a.urut ASC";
				$material		= $this->db->query($qMaterial)->result_array();
				//akomodasi
				$qAkomodasi	= "SELECT a.*, b.category_ FROM ipp_detail a LEFT JOIN view_list b ON a.category=b.category WHERE a.no_ipp='".$ipp."' AND a.category = 'akomodasi' ORDER BY a.urut ASC";
				$akomodasi	= $this->db->query($qAkomodasi)->result_array();
				//product
				$qProduct		= "SELECT a.*, b.category_ FROM ipp_detail a LEFT JOIN view_list b ON a.category=b.category WHERE a.no_ipp='".$ipp."' AND a.category = 'product' ORDER BY a.urut ASC";
				$product		= $this->db->query($qProduct)->result_array();
				//fasilitas
				$qFasilitas	= "SELECT a.*, b.category_ FROM ipp_detail a LEFT JOIN view_list b ON a.category=b.category WHERE a.no_ipp='".$ipp."' AND a.category = 'fasilitas' ORDER BY a.urut ASC";
				$fasilitas	= $this->db->query($qFasilitas)->result_array();
				//pekerja
				$qPekerja		= "SELECT a.*, b.category_ FROM ipp_detail a LEFT JOIN view_list b ON a.category=b.category WHERE a.no_ipp='".$ipp."' AND a.category = 'pekerja' ORDER BY a.urut ASC";
				$pekerja		= $this->db->query($qPekerja)->result_array();
				//add
				$qAdd				= "SELECT a.*, b.category_ FROM ipp_detail a LEFT JOIN view_list b ON a.category=b.category WHERE a.no_ipp='".$ipp."' AND a.category = 'add' ORDER BY a.urut ASC";
				$add				= $this->db->query($qAdd)->result_array();
			}
			// echo $qMaterial;
			$data = array(
				'title'			=> 'Identification Of Customer Requests',
				'action'		=> 'add',
				'ipp'				=> $ipp,
				'header'		=> $header,
				'cust'			=> $restCust,
				'app'				=> $app,
				'material' 	=> $material,
				'akomodasi' => $akomodasi,
				'product' 	=> $product,
				'fasilitas' => $fasilitas,
				'pekerja' 	=> $pekerja,
				'req' 			=> $req,
				'add' 			=> $add
			);
			$this->load->view('Sales/add',$data);
		}
	}

	public function add2(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');
			$db2 = $this->load->database('costing', TRUE);
			// print_r($data);
			// exit;
			$no_ipp 		= $data['no_ipp'];
			$id_customer	= $data['id_customer'];

			$TandaI			= (!empty($no_ipp))?'Update':'Insert';

			$Y				= date('y');
			$dtCust = $db2->query("SELECT * FROM customer WHERE id_customer = '".$id_customer."' LIMIT 1")->result();

			//pengurutan kode
			$LocInt			= ($dtCust[0]->country_code == 'IDN' OR $dtCust[0]->country_code == 'Sele')?'L':'E';
			$qIPP			= "SELECT MAX(no_ipp) as maxP FROM ipp_header WHERE no_ipp LIKE 'IPPS".$Y."%' ";
			$numrowIPP		= $this->db->query($qIPP)->num_rows();
			$resultIPP		= $this->db->query($qIPP)->result_array();
			$angkaUrut2		= $resultIPP[0]['maxP'];
			$urutan2		= (int)substr($angkaUrut2, 6, 3);
			$urutan2++;
			$urut2			= sprintf('%03s',$urutan2);
			$IdIPP			= "IPPS".$Y.$urut2.$LocInt;

			$ipp			= (!empty($no_ipp))?$no_ipp:$IdIPP;

			//project_header
			$ArrHeader = array(
				'no_ipp' 			=> $ipp,
				'id_customer' 		=> $id_customer,
				'nm_customer' 		=> $dtCust[0]->nm_customer,
				'project' 			=> $data['project'],
				'no_proposal' 		=> $data['no_proposal'],
				'note' 				=> $data['note'],
				'ref_cust' 			=> $data['ref_cust'],
				'ruang_lingkup' 	=> $data['ruang_lingkup'],
				'harga_per_pcs' 	=> $data['harga_per_pcs'],
				'jumlah' 			=> $data['jumlah'],
				'kapan' 			=> $data['kapan'],
				'alamat' 			=> $data['alamat'],
				'syarat_cust' 		=> $data['syarat_cust'],
				'validity' 			=> $data['validity'],
				'payment' 			=> $data['payment'],
				
				'nm_product' 		=> (!empty($data['nm_product']))?json_encode($data['nm_product']):'',
				'unit' 				=> (!empty($data['unit']))?$data['unit']:'',
				'inspeksi' 			=> (!empty($data['inspeksi']))?$data['inspeksi']:'',
				// 'informasi' 		=> (!empty($data['informasi']))?$data['informasi']:'',
				'keb_joint' 		=> (!empty($data['keb_joint']))?$data['keb_joint']:'',
				'app' 				=> (!empty($data['app']))?json_encode($data['app']):'',
				'test' 				=> (!empty($data['test']))?$data['test']:'',
				'sertifikat' 		=> (!empty($data['sertifikat']))?$data['sertifikat']:'',
				'syarat' 			=> (!empty($data['syarat']))?$data['syarat']:'',
				'alat_berat' 		=> (!empty($data['alat_berat']))?$data['alat_berat']:'',
				'scaffolding' 		=> (!empty($data['scaffolding']))?$data['scaffolding']:'',
				'electricity' 		=> (!empty($data['electricity']))?$data['electricity']:'',

				'informasi' 		=> (!empty($data['informasi']))?json_encode($data['informasi']):'',
				'jenis_test' 		=> (!empty($data['jenis_test']))?json_encode($data['jenis_test']):'',
				'jenis_sertifikat' 	=> (!empty($data['jenis_sertifikat']))?json_encode($data['jenis_sertifikat']):'',

				'created_by' 		=> $data_session['ORI_User']['username'],
				'created_date' 		=> date('Y-m-d H:i:s')
			);

			// print_r($ArrHeader);
			// exit;

			$this->db->trans_start();
				if(!empty($no_ipp)){
					$this->db->where('no_ipp', $no_ipp);
					$this->db->update('ipp_header', $ArrHeader);
				}
				else{
					$this->db->insert('ipp_header', $ArrHeader);
				}
			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> $TandaI.' IPP Instalasi data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> $TandaI.' IPP Instalasi data success. Thanks ...',
					'status'	=> 1
				);
				history($TandaI.' IPP Instalasi '.$ipp);
			}

			echo json_encode($Arr_Kembali);
		}
		else{
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			// echo $Arr_Akses['create']; exit;
			if($Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('users'));
			}
			$db2 = $this->load->database('costing', TRUE);
			//customer
			$dataCust		= "SELECT id_customer, nm_customer FROM customer WHERE id_customer <> 'C100-1903000' ORDER BY nm_customer ASC";
			$restCust		= $db2->query($dataCust)->result_array();
			
			$ipp 			= $this->uri->segment(3);
			$tanda 			= $this->uri->segment(4);
			$header 		= $this->db->get_where('ipp_header', array('no_ipp'=>$ipp))->result();

			$data = array(
				'title'			=> 'Identification Of Customer Requests',
				'action'		=> 'add',
				'ipp'			=> $ipp,
				'tanda'			=> $tanda,
				'header'		=> $header,
				'cust'			=> $restCust
			);
			$this->load->view('Sales/add2',$data);
		}
	}

	public function modalDetail(){
		$ipp 				= $this->uri->segment(3);
		$qHeader 		= "SELECT * FROM ipp_header WHERE no_ipp='".$ipp."'";
		$header 		= $this->db->query($qHeader)->result();

		$qDetail 		= "SELECT a.*, b.category_ FROM ipp_detail a LEFT JOIN view_list b ON a.category=b.category WHERE a.no_ipp='".$ipp."'";
		$detail 		= $this->db->query($qDetail)->result_array();

		$qHelp 		= "SELECT id FROM ipp_detail a LEFT JOIN view_list b ON a.category=b.category WHERE a.no_ipp='".$ipp."' GROUP BY a.category ORDER BY id ASC";
		$help 		= $this->db->query($qHelp)->result_array();

		$ArrData2 = array();
		foreach($help as $vaS => $vaA){
			 $ArrData2[] = $vaA['id'];
		}
		$ArrData2 = implode("," ,$ArrData2);
		$ArrData2x = explode("," ,$ArrData2);

		$data = array(
			'header'		=> $header,
			'detail'		=> $detail,
			'help'			=> $help,
			'list'			=> $ArrData2x
		);

		$this->load->view('Sales/modalDetail', $data);
	}

	public function hapus(){
		$no_ipp = $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'deleted' 		=> 'Y',
			'deleted_by' 	=> $data_session['ORI_User']['username'],
			'deleted_date' 	=> date('Y-m-d H:i:s')
			);


		$this->db->trans_start();
		$this->db->where('no_ipp', $no_ipp);
		$this->db->update('ipp_header', $ArrPlant);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Delete IPP data failed. Please try again later ...',
				'status'	=> 0
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Delete IPP data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete IPP Instalation : '.$no_ipp);
		}
		echo json_encode($Arr_Data);
	}

	public function print_ipp(){
		$no_ipp	= $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$printby			= $data_session['ORI_User']['username'];
		$koneksi			= akses_server_side();

		include 'plusPrint.php';
		$data_url			= base_url();
		$Split_Beda		= explode('/',$data_url);
		$Jum_Beda			= count($Split_Beda);
		$Nama_Beda		= $Split_Beda[$Jum_Beda - 2];

		history('Print IPP Instalation '.$no_ipp);
		PrintIPP($Nama_Beda, $no_ipp, $koneksi, $printby);
	}

	public function release(){
		$no_ipp = $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'status' 		=> 'WAITING ESTIMATION PROJECT',
			'sts_app' 		=> 'Y',
			'app_by' 	=> $data_session['ORI_User']['username'],
			'app_date' 	=> date('Y-m-d H:i:s')
			);


		$this->db->trans_start();
		$this->db->where('no_ipp', $no_ipp);
		$this->db->update('ipp_header', $ArrPlant);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Failed process data. Please try again later ...',
				'status'	=> 0
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Sucess process data. Thanks ...',
				'status'	=> 1
			);
			history('Release IPP Instlasi : '.$no_ipp);
		}
		echo json_encode($Arr_Data);
	}


	public function confirm(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_Group		= $this->master_model->getArray('groups',array(),'id','name');
		$result 		= $this->db->get_where('ipp_header', array('sts_app'=>'Y','sts_confirm'=>'N'))->result_array();
		$data = array(
			'title'			=> 'Confirmation IPP',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'result'		=> $result,
			'akses_menu'	=> $Arr_Akses
		);
		history('View confirm ipp');
		$this->load->view('Sales/confirm',$data);
	}

	public function confirm_ipp(){
		$no_ipp 		= $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'sts_confirm' 	=> 'Y',
			'confirm_by' 	=> $data_session['ORI_User']['username'],
			'confirm_date' 	=> date('Y-m-d H:i:s')
		);

		$Ym = date('ym');
		$Y = date('y');
		//pengurutan kode
		$srcMtr			= "SELECT MAX(project_code) as maxP FROM project_header WHERE project_code LIKE 'P".$Y."%' ";
		$numrowMtr		= $this->db->query($srcMtr)->num_rows();
		$resultMtr		= $this->db->query($srcMtr)->result_array();
		$angkaUrut2		= $resultMtr[0]['maxP'];
		$urutan2		= (int)substr($angkaUrut2, 5, 4);
		$urutan2++;
		$urut2			= sprintf('%04s',$urutan2);
		$project_code	= "P".$Ym.$urut2;

		//project_header
		$ArrHeader = array(
			'project_code' 	=> $project_code,
			'no_ipp' 		=> $no_ipp,
			'project_name' 	=> get_name('ipp_header','project','no_ipp', $no_ipp),
			'created_by' 	=> $data_session['ORI_User']['username'],
			'created_date' 	=> date('Y-m-d H:i:s')
		);

		$list_kend 	= $this->db->get_where('akomodasi_new', array('id_category'=>'2','deleted_date'=>NULL))->result_array();
		$ArrHouse = array();
		foreach ($list_kend as $val => $value) {
			$ArrHouse[$val]['project_code'] = $project_code;
			$ArrHouse[$val]['category_ak'] 	= 'house';
			$ArrHouse[$val]['code_group'] 	= $value['code_group'];
			$ArrHouse[$val]['category'] 	= $value['category'];
			$ArrHouse[$val]['spec'] 		= $value['spec'];
		}

		$this->db->trans_start();
			$this->db->insert('project_header', $ArrHeader);
			if(!empty($ArrHouse)){
			$this->db->insert_batch('project_detail_akomodasi', $ArrHouse);
			}

			$this->db->where('no_ipp', $no_ipp);
			$this->db->update('ipp_header', $ArrPlant);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Failed process data. Please try again later ...',
				'status'	=> 0
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Sucess process data. Thanks ...',
				'status'	=> 1
			);
			history('Confirm ipp instalasi : '.$no_ipp);
		}
		echo json_encode($Arr_Data);
	}

	public function reject_ipp(){
		$no_ipp 		= $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'status'	=> 'WAITING RELEASE IPP',
			'sts_app' 	=> 'N',
			'app_by' 	=> $data_session['ORI_User']['username'],
			'app_date' 	=> date('Y-m-d H:i:s')
		);

		$this->db->trans_start();
			$this->db->where('no_ipp', $no_ipp);
			$this->db->update('ipp_header', $ArrPlant);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Failed process data. Please try again later ...',
				'status'	=> 0
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Sucess process data. Thanks ...',
				'status'	=> 1
			);
			history('Reject ipp instalasi : '.$no_ipp);
		}
		echo json_encode($Arr_Data);
	}

	public function view_ipp(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		// echo $Arr_Akses['create']; exit;
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$db2 = $this->load->database('costing', TRUE);
		//customer
		$dataCust		= "SELECT id_customer, nm_customer FROM customer WHERE id_customer <> 'C100-1903000' ORDER BY nm_customer ASC";
		$restCust		= $db2->query($dataCust)->result_array();
		
		$ipp 			= $this->uri->segment(3);
		$tanda 			= $this->uri->segment(4);
		$header 		= $this->db->get_where('ipp_header', array('no_ipp'=>$ipp))->result();

		$data = array(
			'title'			=> 'Identification Of Customer Requests',
			'action'		=> 'add',
			'ipp'			=> $ipp,
			'tanda'			=> $tanda,
			'header'		=> $header,
			'cust'			=> $restCust
		);
		$this->load->view('Sales/add2',$data);
	}

}
