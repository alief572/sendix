<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Man_power extends CI_Controller {

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
		$getBy				= "SELECT updated_by, updated_date FROM tb_view_man_power ORDER BY updated_date DESC LIMIT 1";
		$restgetBy			= $this->db->query($getBy)->result();
		$category			= $this->db->order_by('category','ASC')->get_where('man_power_category', array('deleted_date'=>NULL))->result_array();

		$data = array(
			'title'			=> 'Indeks Of Man Power',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses,
			'category'		=> $category,
			'get_by'		=> $restgetBy
		);
		history('View Data Man Power');
		$this->load->view('Man_power/index',$data);
	}

	public function data_side_man_power(){
		$this->serverside_model->get_json_man_power();
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['spec']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['note']))."</div>";
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

	public function queryDataJSON($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
			SELECT
				*
			FROM
				man_power_new
		    WHERE 1=1 AND (
				category LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR spec LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR note LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'category',
			2 => 'spec',
			3 => 'note'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
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

			$category			= strtolower($data['category']);
			$spec			= str_replace(["'", '"'], ' inchi', strtolower(trim($data['spec'])));
			$tanda_edit		= strtolower($data['tanda_edit']);
			$note					= strtolower($data['note']);
			$code_group		= $data['code_group'];

			//Pencarian data yang sudah ada
			$ValueProduct	= "SELECT * FROM man_power_new WHERE category='".$category."' AND spec='".$spec."' ";
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
					$srcMtr			= "SELECT MAX(code_group) as maxP FROM man_power_new WHERE code_group LIKE 'MP%' ";
					$numrowMtr		= $this->db->query($srcMtr)->num_rows();
					$resultMtr		= $this->db->query($srcMtr)->result_array();
					$angkaUrut2		= $resultMtr[0]['maxP'];
					$urutan2		= (int)substr($angkaUrut2, 2, 5);
					$urutan2++;
					$urut2			= sprintf('%05s',$urutan2);
					$code_group		= "MP".$urut2;

					$ArrInsert = array(
						'code_group' => $code_group,
						'category' => $category,
						'spec' => $spec,
						'note' => $note,
						'created_by' => $data_session['ORI_User']['username'],
						'created_date' => $dateTime
					);

					// echo "<pre>"; print_r($ArrInsert);
					// exit;

					$this->db->trans_start();
					$this->db->insert('man_power_new', $ArrInsert);
					$this->db->trans_complete();
				}

				//edit
				if(!empty($tanda_edit)){
					$Hist = 'Edit ';

					$ArrInsert = array(
						'code_group' => $code_group,
						'category' => $category,
						'spec' => $spec,
						'note' => $note,
						'created_by' => $data_session['ORI_User']['username'],
						'created_date' => $dateTime
					);

					// echo "<pre>"; print_r($ArrInsert);
					// exit;

					$this->db->trans_start();
						$this->db->where('code_group', $code_group);
						$this->db->update('man_power_new', $ArrInsert);
					$this->db->trans_complete();
				}

				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$Arr_Kembali	= array(
						'pesan'		=> $Hist.'Man Power data failed. Please try again later ...',
						'status'	=> 2
					);
				}
				else{
					$this->db->trans_commit();
					$Arr_Kembali	= array(
						'pesan'		=> $Hist.'Man Power data success. Thanks ...',
						'status'	=> 1
					);
					history($Hist.'Man Power '.$category.' / '.$spec);
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
				$qRegion	= "SELECT * FROM man_power_new WHERE code_group='".$uri3."' LIMIT 1";
				$tanda1 = 'Edit';
			}

			// echo $qRegion;
			$restRegion	= $this->db->query($qRegion)->result_array();

			$qCateMP	= "SELECT * FROM man_power_category WHERE deleted_date IS NULL ORDER BY category ASC";
			$restCateMP	= $this->db->query($qCateMP)->result_array();

			$data = array(
				'title'			=> $tanda1.' Man Power',
				'action'		=> 'add',
				'region'		=> $restRegion,
				'cateMP'		=> $restCateMP
			);
			$this->load->view('Man_power/add',$data);
		}
	}

	public function add_category(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$add_category	= strtolower($data['add_category']);
			$information	= strtolower($data['information']);
			$code_group	= strtolower($data['code_group']);

			// echo $tanda_category;
			// exit;
			//Pencarian data yang sudah ada
			$ValueProduct	= "SELECT * FROM man_power_category WHERE category='".$add_category."' ";
			$NumProduct		= $this->db->query($ValueProduct)->num_rows();

			if($NumProduct > 0){
				$Arr_Kembali		= array(
					'status'		=> 3,
					'pesan'			=> 'Category man power sudah digunakan. Input catgeory lain ...'
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
				$this->db->insert('man_power_category', $ArrInsert);
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

	function hapus(){
		$code_group = $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'deleted' 		=> 'Y',
			'deleted_by' 	=> $data_session['ORI_User']['username'],
			'deleted_date' 	=> date('Y-m-d H:i:s')
			);

		$this->db->trans_start();
			$this->db->where('code_group', $code_group);
            $this->db->update('man_power_new', $ArrPlant);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Delete man power data failed. Please try again later ...',
				'status'	=> 0
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Delete man power data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete man power category : '.$code_group);
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
		$sheet->setCellValue('A'.$Row, 'MASTER MAN POWER');
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

		$qManPower	= "SELECT * FROM view_man_power";
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

		$sheet->setTitle('Man Power Master');
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
		header('Content-Disposition: attachment;filename="master_man_power_'.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

	public function edit_category(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$category		= strtolower(trim($data['category']));
			$information	= strtolower(trim($data['information']));
			$id				= $data['id'];

			//Pencarian data yang sudah ada
			$ValueProduct	= "SELECT * FROM man_power_category WHERE category='".$category."' AND id != '".$id."'";
			$NumProduct		= $this->db->query($ValueProduct)->num_rows();

			if($NumProduct > 0){
				$Arr_Kembali		= array(
					'status'		=> 3,
					'pesan'			=> 'Category sudah digunakan. Input spesifikasi lain ...'
				);
			}
			else{
				$ArrInsert = array(
					'category' => $category,
					'information' => $information,
					'created_by' => $data_session['ORI_User']['username'],
					'created_date' => $dateTime
				);

				// echo "<pre>"; print_r($ArrInsert);
				// exit;

				$this->db->trans_start();
					$this->db->where('id', $id);
					$this->db->update('man_power_category', $ArrInsert);
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
					history('Edit category man power '.$id);
				}
			}

			echo json_encode($Arr_Kembali);
		}
		else{
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if($Arr_Akses['create'] !='1'){
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('man_power'));
			}

			$id 		= $this->uri->segment(3);
			$qRegion	= "SELECT * FROM man_power_category WHERE id='".$id."' LIMIT 1";
			$restRegion	= $this->db->query($qRegion)->result_array();

			$data = array(
				'title'			=> 'Edit Category',
				'action'		=> 'add',
				'header'		=> $restRegion
			);
			$this->load->view('Man_power/edit_category',$data);
		}
	}

	function hapus_category(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;

		$ArrPlant		= array(
			'deleted_by' 	=> $data_session['ORI_User']['username'],
			'deleted_date' 	=> date('Y-m-d H:i:s')
		);

		$this->db->trans_start();
			$this->db->where('id', $id);
            $this->db->update('man_power_category', $ArrPlant);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Process data failed. Please try again later ...',
				'status'	=> 0
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Process data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete man power category : '.$id);
		}
		echo json_encode($Arr_Data);
	}

}
