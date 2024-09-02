<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Price extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');

		// Your own constructor code
		if(!$this->session->userdata('isORIlogin')){
			redirect('login');
		}
	}

	//==========================================================================================================================
	//================================================CONSUMABLE================================================================
	//==========================================================================================================================

	public function index(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_Group			= $this->master_model->getArray('groups',array(),'id','name');

		$data = array(
			'title'			=> 'Indeks Of Consumable',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View price reference consumable');
		$this->load->view('Price/index',$data);
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['material_name']))."</div>";
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['unit_material']))."</div>";
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['kurs']))."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['rate'])."</div>";
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

	public function queryDataJSON($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
			SELECT
				a.id,
				a.code_group,
				b.material_name,
				b.category,
				b.spec,
				b.brand,
				a.unit_material,
				a.kurs,
				a.rate
			FROM
				price_ref a LEFT JOIN con_nonmat_new b
					ON a.code_group=b.code_group
		   WHERE 1=1 AND a.category = 'consumable' AND a.sts_price='N' AND a.deleted='N' AND (
				b.material_name LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR b.spec LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR b.brand LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.unit_material LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.kurs LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.rate LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'category',
			2 => 'spec',
			3 => 'material_name',
			4 => 'unit_material',
			5 => 'kurs',
			6 => 'rate'
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
			$db2 = $this->load->database('costing', TRUE);
			// print_r($data);
			// exit;
			$id			= strtolower($data['id']);
			$tanda_edit		= strtolower($data['tanda_edit']);
			$code_group		= $data['code_group'];
			$kurs		= $data['kurs'];
			$unit_material= strtolower(trim($data['unit_material']));
			$rate		= str_replace(',', '', $data['rate']);

			$ArrHeader = array(
				'category' => 'consumable',
				'code_group' => $code_group,
				'unit_material' => $unit_material,
				'kurs' => $kurs,
				'region' => 'all region',
				'rate' => $rate,
				'updated_by' => $data_session['ORI_User']['username'],
				'updated_date' => $dateTime
			);

			$ArrUpdate = array(
				'sts_price' => 'Y',
			);

			if(empty($tanda_edit)){
				$Hist = "Add ";
			}

			if(!empty($tanda_edit)){
				$Hist = "Update ";

				$qHist		= "SELECT * FROM price_ref WHERE code_group='".$code_group."' AND unit_material='".$unit_material."'";
				$restHist	= $this->db->query($qHist)->result();

				$ArrHist = array(
					'category' 			=> $restHist[0]->category,
					'code_group' 		=> $restHist[0]->code_group,
					'unit_material' => $restHist[0]->unit_material,
					'kurs'					=> $restHist[0]->kurs,
					'region' 				=> $restHist[0]->region,
					'rate' 					=> $restHist[0]->rate,
					'sts_price' 		=> $restHist[0]->sts_price,
					'updated_by' 		=> $restHist[0]->updated_by,
					'updated_date' 	=> $restHist[0]->updated_date,
					'hist_by' 			=> $data_session['ORI_User']['username'],
					'hist_date' 		=> date('Y-m-d H:i:s')
				);
			}

			$this->db->trans_start();
				if(empty($tanda_edit)){
					$this->db->insert('price_ref', $ArrHeader);

					$this->db->where('code_group', $code_group);
					$this->db->where('unit_material', $unit_material);
					$this->db->update('con_nonmat_new_konversi', $ArrUpdate);
				}

				if(!empty($tanda_edit)){
					$this->db->insert('hist_price_ref', $ArrHist);

					$this->db->where('id', $id);
					$this->db->update('price_ref', $ArrHeader);

					$this->db->where('code_group', $code_group);
					$this->db->where('unit_material', $unit_material);
					$this->db->update('con_nonmat_new_konversi', $ArrUpdate);
				}
			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> $Hist.'data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> $Hist.'data success. Thanks ...',
					'status'	=> 1
				);
				history($Hist.'Consumable '.$code_group.', id = '.$id);
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

			$id = $this->uri->segment(3);

			$tanda1 = 'Add';
			if(!empty($id)){
				$tanda1 = 'Edit';
			}

			$qConsumable 	= "	SELECT b.* FROM con_nonmat_new b WHERE b.deleted = 'N' ORDER BY b.category, b.material_name, b.spec";
			$restConsumable = $this->db->query($qConsumable)->result_array();

			$qCurrency		= "SELECT * FROM currency ORDER BY mata_uang ASC, negara ASC";
			$restCurrency = $this->db->query($qCurrency)->result_array();

			$qHeader 		= "SELECT a.*, b.spec, b.material_name, b.brand, b.category AS cty FROM price_ref a LEFT JOIN con_nonmat_new b ON a.code_group=b.code_group WHERE a.id='".$id."'";
			$restHeader = $this->db->query($qHeader)->result();

			$restUnit = array();
			if(!empty($restHeader)){
				$qUnit		= "SELECT * FROM con_nonmat_new_konversi WHERE code_group = '".$restHeader[0]->code_group."' ";
				$restUnit = $this->db->query($qUnit)->result_array();
			}
			$data = array(
				'title'			=> $tanda1.' Price Reference',
				'action'		=> 'add',
				'consumable'=> $restConsumable,
				'currency'	=> $restCurrency,
				'header'		=> $restHeader,
				'unit'		=> $restUnit
			);
			$this->load->view('Price/add',$data);
		}
	}

	public function hapus(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$qGet = "SELECT unit_material, code_group FROM price_ref WHERE id='".$id."' LIMIT 1";
		$restGet = $this->db->query($qGet)->result();

		$ArrUpdate = array(
			'deleted' => 'Y',
			'deleted_by' => $data_session['ORI_User']['username'],
			'deleted_date' => $dateTime
		);

		$ArrUpdate2 = array(
			'sts_price' => 'N',
			'updated_by' => $data_session['ORI_User']['username'],
			'updated_date' => $dateTime
		);

		$this->db->trans_start();
				$this->db->where('id', $id);
				$this->db->update('price_ref', $ArrUpdate);

				$this->db->where('code_group', $restGet[0]->code_group);
				$this->db->where('unit_material', $restGet[0]->unit_material);
				$this->db->update('con_nonmat_new_konversi', $ArrUpdate2);

		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Delete data failed. Please try again later ...',
				'status'	=> 2
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Delete data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete Price Reference '.$restGet[0]->code_group.' / '.$restGet[0]->unit_material.', id = '.$id);
		}

		echo json_encode($Arr_Kembali);


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

	//==========================================================================================================================
	//================================================VEHICLE TOOLS=============================================================
	//==========================================================================================================================

	public function index_vt(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_Group			= $this->master_model->getArray('groups',array(),'id','name');

		$data = array(
			'title'			=> 'Indeks Of Tools Equipment',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View price reference tools equipment');
		$this->load->view('Price/index_vt',$data);
	}

	public function getDataJSON_vt(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->queryDataJSON_vt(
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
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['unit']))."</div>";
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['kurs']))."</div>";
			// $nestedData[]	= "<div align='right'>".number_format($row['jawa'])."</div>";
			// $nestedData[]	= "<div align='right'>".number_format($row['sumatra'])."</div>";
			// $nestedData[]	= "<div align='right'>".number_format($row['kalimantan'])."</div>";
			// $nestedData[]	= "<div align='right'>".number_format($row['sulawesi'])."</div>";
			// $nestedData[]	= "<div align='right'>".number_format($row['indonesia_timur'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['rate'])."</div>";
					$updX	= "";
					$delX	= "";
					if($Arr_Akses['update']=='1'){
						$updX	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/add_vt/'.$row['id'])."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
					}
					if($Arr_Akses['delete']=='1'){
						$delX	= "<button class='btn btn-sm btn-danger deleted' title='Permanent Delete' data-code_group='".$row['code_group']."' data-unit='".$row['unit']."'><i class='fa fa-trash'></i></button>";
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

	public function queryDataJSON_vt($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
		select
			a.code_group AS code_group,
			b.category AS category,
			b.spec AS spec,
			a.unit_material AS unit,
			a.kurs AS kurs,
			a.rate,
			a.id,
			a.updated_by AS updated_by,
			a.updated_date AS updated_date
		from
			price_ref a LEFT JOIN vehicle_tool_new b ON a.code_group=b.code_group
		where
			a.category='vehicle tool' AND a.deleted = 'N'AND (
				b.category LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR b.spec LIKE '%".$this->db->escape_like_str($like_value)."%'
	      )
		group by
			a.code_group,
			a.unit_material
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'category',
			2 => 'spec',
			3 => 'unit',
			4 => 'kurs'
		);

		$sql .= " ORDER BY  ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function add_vt(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');
			$db2 = $this->load->database('costing', TRUE);
			// print_r($data);
			// exit;
			$id			= strtolower($data['id']);
			$tanda_edit		= strtolower($data['tanda_edit']);
			$code_group		= $data['code_group'];
			$kurs		= $data['kurs'];
			$unit_material= strtolower(trim($data['unit_material']));
			$rate		= str_replace(',', '', $data['rate']);
			// $DetailData		= $data['DetailData'];

			// $ArrInsert = array();
			// $nomor = 0;
			// foreach($DetailData AS $val => $valx){
			// 	$nomor++;
			// 	$ArrInsert[$val]['code_group'] = $code_group;
			// 	$ArrInsert[$val]['category'] = 'vehicle tool';
			// 	$ArrInsert[$val]['unit_material'] = $unit_material;
			// 	$ArrInsert[$val]['kurs'] = $kurs;
			// 	$ArrInsert[$val]['region'] = $valx['region'];
			// 	$ArrInsert[$val]['rate'] = str_replace(',','',$valx['rate']);
			// 	$ArrInsert[$val]['updated_by'] = $data_session['ORI_User']['username'];
			// 	$ArrInsert[$val]['updated_date'] = $dateTime;
			// }

			$ArrHeader = array(
				'category' => 'vehicle tool',
				'code_group' => $code_group,
				'unit_material' => $unit_material,
				'kurs' => $kurs,
				'region' => 'all region',
				'rate' => $rate,
				'updated_by' => $data_session['ORI_User']['username'],
				'updated_date' => $dateTime
			);

			$ArrUpdate = array(
				'sts_price' => 'Y',
			);

			if(empty($tanda_edit)){
				$Hist = "Add ";
			}

			if(!empty($tanda_edit)){
				$Hist = "Update ";

				// $ArrInsert = array();
				// $nomor = 0;
				// foreach($DetailData AS $val => $valx){
				// 	$nomor++;
				// 	$ArrInsert[$val]['id'] = $valx['id'];
				// 	$ArrInsert[$val]['code_group'] = $code_group;
				// 	$ArrInsert[$val]['category'] = 'vehicle tool';
				// 	$ArrInsert[$val]['unit_material'] = $unit_material;
				// 	$ArrInsert[$val]['kurs'] = $kurs;
				// 	$ArrInsert[$val]['region'] = $valx['region'];
				// 	$ArrInsert[$val]['rate'] = str_replace(',','',$valx['rate']);
				// 	$ArrInsert[$val]['updated_by'] = $data_session['ORI_User']['username'];
				// 	$ArrInsert[$val]['updated_date'] = $dateTime;
				// }

				$ArrHeader = array(
					'category' => 'vehicle tool',
					'code_group' => $code_group,
					'unit_material' => $unit_material,
					'kurs' => $kurs,
					'region' => 'all region',
					'rate' => $rate,
					'updated_by' => $data_session['ORI_User']['username'],
					'updated_date' => $dateTime
				);

				$qHist		= "SELECT * FROM price_ref WHERE code_group='".$code_group."' AND unit_material='".$unit_material."'";
				$restHist	= $this->db->query($qHist)->result();

				// $ArrHist = array();
				// foreach($restHist AS $val => $valx){
				// 	$ArrHist[$val]['category'] 		= $valx['category'];
				// 	$ArrHist[$val]['code_group'] 		= $valx['code_group'];
				// 	$ArrHist[$val]['unit_material'] 			= $valx['unit_material'];
				// 	$ArrHist[$val]['kurs']		= $valx['kurs'];
				// 	$ArrHist[$val]['region'] 	= $valx['region'];
				// 	$ArrHist[$val]['rate'] 	= $valx['rate'];
				// 	$ArrHist[$val]['sts_price'] 			= $valx['sts_price'];
				// 	$ArrHist[$val]['updated_by'] 	= $valx['updated_by'];
				// 	$ArrHist[$val]['updated_date'] 	= $valx['updated_date'];
				// 	$ArrHist[$val]['hist_by'] 		= $data_session['ORI_User']['username'];
				// 	$ArrHist[$val]['hist_date'] 	= date('Y-m-d H:i:s');
				// }

				$ArrHist2 = array(
					'category' 			=> $restHist[0]->category,
					'code_group' 		=> $restHist[0]->code_group,
					'unit_material' => $restHist[0]->unit_material,
					'kurs'					=> $restHist[0]->kurs,
					'region' 				=> $restHist[0]->region,
					'rate' 					=> $restHist[0]->rate,
					'sts_price' 		=> $restHist[0]->sts_price,
					'updated_by' 		=> $restHist[0]->updated_by,
					'updated_date' 	=> $restHist[0]->updated_date,
					'hist_by' 			=> $data_session['ORI_User']['username'],
					'hist_date' 		=> date('Y-m-d H:i:s')
				);
			}
			// print_r($ArrInsert); exit;
			$this->db->trans_start();
				if(empty($tanda_edit)){
					// $this->db->insert_batch('price_ref', $ArrInsert);
					$this->db->insert('price_ref', $ArrHeader);

					// $this->db->where('code_group', $code_group);
					// $this->db->update('vehicle_tool_new', $ArrUpdate);
				}

				if(!empty($tanda_edit)){
					$this->db->insert('hist_price_ref', $ArrHist2);
					// $this->db->update_batch('price_ref', $ArrInsert,'id');

					$this->db->where('id', $id);
					$this->db->update('price_ref', $ArrHeader);
				}
			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> $Hist.'data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> $Hist.'data success. Thanks ...',
					'status'	=> 1
				);
				history($Hist.'Vehicle Tools '.$code_group.', id = '.$id);
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

			

			$qConsumable 		= "	SELECT * FROM vehicle_tool_new WHERE
														deleted = 'N'
														AND sts_price = 'N'
													ORDER BY
														category ASC, spec ASC";
			$restConsumable = $this->db->query($qConsumable)->result_array();

			$qCurrency		= "SELECT * FROM currency ORDER BY mata_uang ASC, negara ASC";
			$restCurrency = $this->db->query($qCurrency)->result_array();

			$qHeader 		= "SELECT a.*, b.spec, b.category AS cty FROM price_ref a LEFT JOIN vehicle_tool_new b ON a.code_group=b.code_group WHERE a.id='".$code_group."'";
			$restHeader = $this->db->query($qHeader)->result();

		

			$data = array(
				'title'			=> 'Price Reference',
				'action'		=> 'add',
				'vehicle_tool'=> $restConsumable,
				'currency'	=> $restCurrency,
				'header'		=> $restHeader,
			);
			$this->load->view('Price/add_vt',$data);
		}
	}

	public function hapus_vt(){
		$code_group = $this->uri->segment(3);
		$unit = $this->uri->segment(4);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$qGet = "SELECT unit_material, code_group FROM price_ref WHERE code_group='".$code_group."' AND unit_material='".$unit."' LIMIT 1";
		$restGet = $this->db->query($qGet)->result();

		$ArrUpdate = array(
			'deleted' => 'Y',
			'deleted_by' => $data_session['ORI_User']['username'],
			'deleted_date' => $dateTime
		);

		$this->db->trans_start();
				$this->db->where('code_group', $code_group);
				$this->db->where('unit_material', $unit);
				$this->db->update('price_ref', $ArrUpdate);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Delete data failed. Please try again later ...',
				'status'	=> 2
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Delete data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete Price Reference '.$restGet[0]->code_group.' / '.$restGet[0]->unit_material.', id = '.$code_group);
		}

		echo json_encode($Arr_Kembali);


	}

	//==========================================================================================================================
	//================================================MAN POWER================================================================
	//==========================================================================================================================

	public function index_mp(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_Group			= $this->master_model->getArray('groups',array(),'id','name');

		$data = array(
			'title'			=> 'Indeks Of Man Power',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View price reference man power');
		$this->load->view('Price/index_mp',$data);
	}

	public function getDataJSON_mp(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->queryDataJSON_mp(
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['nm_category']))."</div>";
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['spec']))."</div>";
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['unit_material']))."</div>";
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['kurs']))."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['rate'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['rate_ot'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['rate_us'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['rate_um'])."</div>";
					$updX	= "";
					$delX	= "";
					if($Arr_Akses['update']=='1'){
						$updX	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/add_mp/'.$row['id'])."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
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

	public function queryDataJSON_mp($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
			SELECT
				a.id,
				a.code_group,
				b.category,
				c.category as nm_category,
				b.spec,
				b.note,
				a.unit_material,
				a.kurs,
				a.rate,
				a.rate_ot,
				a.rate_um,
				a.rate_us
			FROM
				price_ref a LEFT JOIN man_power_new b
					ON a.code_group=b.code_group
					LEFT JOIN man_power_category c on b.category = c.id
		   WHERE 1=1 AND a.category = 'man power' AND a.sts_price='N' AND a.deleted='N' AND (
				b.category LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR b.spec LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR b.note LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.unit_material LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.kurs LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.rate LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'category',
			2 => 'spec',
			3 => 'note',
			4 => 'unit_material',
			5 => 'kurs',
			6 => 'rate',
			7 => 'rate_ot',
			8 => 'rate_us',
			9 => 'rate_um'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function add_mp(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');
			$db2 = $this->load->database('costing', TRUE);
			// print_r($data);
			// exit;
			$id			= strtolower($data['id']);
			$tanda_edit		= strtolower($data['tanda_edit']);
			$code_group		= $data['code_group'];
			$kurs		= $data['kurs'];
			$unit_material= strtolower(trim($data['unit_material']));
			$rate		= str_replace(',', '', $data['rate']);
			$rate_ot		= str_replace(',', '', $data['rate_ot']);
			$rate_us		= str_replace(',', '', $data['rate_us']);
			$rate_um		= str_replace(',', '', $data['rate_um']);

			$ArrHeader = array(
				'category' => 'man power',
				'code_group' => $code_group,
				'unit_material' => $unit_material,
				'kurs' => $kurs,
				'region' => 'all region',
				'rate' => $rate,
				'rate_ot' => $rate_ot,
				'rate_us' => $rate_us,
				'rate_um' => $rate_um,
				'updated_by' => $data_session['ORI_User']['username'],
				'updated_date' => $dateTime
			);

			if(empty($tanda_edit)){
				$Hist = "Add ";
			}

			if(!empty($tanda_edit)){
				$Hist = "Update ";

				$qHist		= "SELECT * FROM price_ref WHERE code_group='".$code_group."' AND unit_material='".$unit_material."'";
				$restHist	= $this->db->query($qHist)->result();

				$ArrHist = array(
					'category' 			=> $restHist[0]->category,
					'code_group' 		=> $restHist[0]->code_group,
					'unit_material' => $restHist[0]->unit_material,
					'kurs'					=> $restHist[0]->kurs,
					'region' 				=> $restHist[0]->region,
					'rate' 					=> $restHist[0]->rate,
					'rate_ot' 				=> $restHist[0]->rate_ot,
					'rate_us' 				=> $restHist[0]->rate_us,
					'rate_um' 				=> $restHist[0]->rate_um,
					'sts_price' 		=> $restHist[0]->sts_price,
					'updated_by' 		=> $restHist[0]->updated_by,
					'updated_date' 	=> $restHist[0]->updated_date,
					'hist_by' 			=> $data_session['ORI_User']['username'],
					'hist_date' 		=> date('Y-m-d H:i:s')
				);
			}

			$this->db->trans_start();
				if(empty($tanda_edit)){
					$this->db->insert('price_ref', $ArrHeader);
				}

				if(!empty($tanda_edit)){
					$this->db->insert('hist_price_ref', $ArrHist);

					$this->db->where('id', $id);
					$this->db->update('price_ref', $ArrHeader);
				}
			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> $Hist.' data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> $Hist.' data success. Thanks ...',
					'status'	=> 1
				);
				history($Hist.'Man Power '.$code_group.', id = '.$id);
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

			$id = $this->uri->segment(3);

			$tanda1 = 'Add';
			if(!empty($id)){
				$tanda1 = 'Edit';
			}

			$qConsumable 		= "	SELECT * FROM man_power_new WHERE
														deleted = 'N'
														AND sts_price = 'N'
													ORDER BY
														category ASC, spec ASC";
			$restConsumable = $this->db->query($qConsumable)->result_array();

			$qCurrency		= "SELECT * FROM currency ORDER BY mata_uang ASC, negara ASC";
			$restCurrency = $this->db->query($qCurrency)->result_array();

			$qHeader 		= "SELECT a.*, b.spec, b.note, b.category AS cty FROM price_ref a LEFT JOIN man_power_new b ON a.code_group=b.code_group WHERE a.id='".$id."'";
			$restHeader = $this->db->query($qHeader)->result();

			$restUnit = array();
			if(!empty($restHeader)){
				$ListBQipp		= $this->db->query("SELECT unit_material FROM price_ref WHERE code_group='".$restHeader[0]->code_group."' AND deleted='N' ")->result_array();
				$dtListArray = array();
				foreach($ListBQipp AS $val => $valx){
					$dtListArray[$val] = $valx['unit_material'];
				}
				$dtImplode	= "('".implode("','", $dtListArray)."')";

				$qUnit	= "SELECT a.* FROM list a WHERE a.origin='vehicle_tool' AND a.category='unit' AND category_list NOT IN ".$dtImplode." ORDER BY a.urut ASC";
		  	$restUnit = $this->db->query($qUnit)->result_array();
			}
			$data = array(
				'title'			=> $tanda1.' Price Reference',
				'action'		=> 'add',
				'consumable'=> $restConsumable,
				'currency'	=> $restCurrency,
				'header'		=> $restHeader,
				'unit'		=> $restUnit
			);
			$this->load->view('Price/add_mp',$data);
		}
	}

	public function hapus_mp(){
		$id = $this->uri->segment(3);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$qGet = "SELECT unit_material, code_group FROM price_ref WHERE id='".$id."' LIMIT 1";
		$restGet = $this->db->query($qGet)->result();

		$ArrUpdate = array(
			'deleted' => 'Y',
			'deleted_by' => $data_session['ORI_User']['username'],
			'deleted_date' => $dateTime
		);

		$ArrUpdate2 = array(
			'sts_price' => 'N',
			'updated_by' => $data_session['ORI_User']['username'],
			'updated_date' => $dateTime
		);

		$this->db->trans_start();
				$this->db->where('id', $id);
				$this->db->update('price_ref', $ArrUpdate);

				$this->db->where('code_group', $restGet[0]->code_group);
				$this->db->where('unit_material', $restGet[0]->unit_material);
				$this->db->update('con_nonmat_new_konversi', $ArrUpdate2);

		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Delete data failed. Please try again later ...',
				'status'	=> 2
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Delete data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete Price Reference '.$restGet[0]->code_group.' / '.$restGet[0]->unit_material.', id = '.$id);
		}

		echo json_encode($Arr_Kembali);


	}

	//==========================================================================================================================
	//================================================AKOMODASI=============================================================
	//==========================================================================================================================

	public function index_ak(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_Group			= $this->master_model->getArray('groups',array(),'id','name');

		$data = array(
			'title'			=> 'Indeks Of Akomodasi',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View price reference akomodasi');
		$this->load->view('Price/index_ak',$data);
	}

	public function getDataJSON_ak(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->queryDataJSON_ak(
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['unit']))."</div>";
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['kurs']))."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['jawa'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['sumatra'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['kalimantan'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['sulawesi'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['indonesia_timur'])."</div>";
					$updX	= "";
					$delX	= "";
					if($Arr_Akses['update']=='1'){
						$updX	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/add_ak/'.$row['code_group'].'/'.$row['unit'])."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
					}
					if($Arr_Akses['delete']=='1'){
						$delX	= "<button class='btn btn-sm btn-danger deleted' title='Permanent Delete' data-code_group='".$row['code_group']."' data-unit='".$row['unit']."'><i class='fa fa-trash'></i></button>";
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

	public function queryDataJSON_ak($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
		select
			a.code_group AS code_group,
			b.category AS category,
			b.spec AS spec,
			a.unit_material AS unit,
			a.kurs AS kurs,
			(select b.rate from price_ref b where b.region = 'jawa' and b.code_group = a.code_group and b.unit_material = a.unit_material and b.deleted='N' limit 1) AS jawa,
			(select b.rate from price_ref b where b.region = 'sumatra' and b.code_group = a.code_group and b.unit_material = a.unit_material and b.deleted='N' limit 1) AS sumatra,
			(select b.rate from price_ref b where b.region = 'kalimantan' and b.code_group = a.code_group and b.unit_material = a.unit_material and b.deleted='N' limit 1) AS kalimantan,
			(select b.rate from price_ref b where b.region = 'sulawesi' and b.code_group = a.code_group and b.unit_material = a.unit_material and b.deleted='N' limit 1) AS sulawesi,
			(select b.rate from price_ref b where b.region = 'indonesia timur' and b.code_group = a.code_group and b.unit_material = a.unit_material and b.deleted='N' limit 1) AS indonesia_timur,
			a.updated_by AS updated_by,
			a.updated_date AS updated_date
		from
			price_ref a LEFT JOIN akomodasi_new b ON a.code_group=b.code_group
		where
			a.category='akomodasi' AND a.deleted = 'N'AND (
				b.category LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR b.spec LIKE '%".$this->db->escape_like_str($like_value)."%'
	      )
		group by
			a.code_group,
			a.unit_material
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'category',
			2 => 'spec',
			3 => 'unit',
			4 => 'kurs'
		);

		$sql .= " ORDER BY  ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function add_ak(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');
			$db2 = $this->load->database('costing', TRUE);
			// print_r($data);
			// exit;
			$id			= strtolower($data['id']);
			$tanda_edit		= strtolower($data['tanda_edit']);
			$code_group		= $data['code_group'];
			$kurs		= $data['kurs'];
			$unit_material= strtolower(trim($data['unit_material']));
			$DetailData		= $data['DetailData'];

			$ArrInsert = array();
			$nomor = 0;
			foreach($DetailData AS $val => $valx){
				$nomor++;
				$ArrInsert[$val]['code_group'] = $code_group;
				$ArrInsert[$val]['category'] = 'akomodasi';
				$ArrInsert[$val]['unit_material'] = $unit_material;
				$ArrInsert[$val]['kurs'] = $kurs;
				$ArrInsert[$val]['region'] = $valx['region'];
				$ArrInsert[$val]['rate'] = str_replace(',','',$valx['rate']);
				$ArrInsert[$val]['updated_by'] = $data_session['ORI_User']['username'];
				$ArrInsert[$val]['updated_date'] = $dateTime;

			}

			$ArrUpdate = array(
				'sts_price' => 'Y',
			);

			if(empty($tanda_edit)){
				$Hist = "Add ";
			}

			if(!empty($tanda_edit)){
				$Hist = "Update ";

				$ArrInsert = array();
				$nomor = 0;
				foreach($DetailData AS $val => $valx){
					$nomor++;
					$ArrInsert[$val]['id'] = $valx['id'];
					$ArrInsert[$val]['code_group'] = $code_group;
					$ArrInsert[$val]['category'] = 'akomodasi';
					$ArrInsert[$val]['unit_material'] = $unit_material;
					$ArrInsert[$val]['kurs'] = $kurs;
					$ArrInsert[$val]['region'] = $valx['region'];
					$ArrInsert[$val]['rate'] = str_replace(',','',$valx['rate']);
					$ArrInsert[$val]['updated_by'] = $data_session['ORI_User']['username'];
					$ArrInsert[$val]['updated_date'] = $dateTime;
				}

				$qHist		= "SELECT * FROM price_ref WHERE code_group='".$code_group."' AND unit_material='".$unit_material."'";
				$restHist	= $this->db->query($qHist)->result_array();

				$ArrHist = array();
					foreach($restHist AS $val => $valx){
						$ArrHist[$val]['category'] 		= $valx['category'];
						$ArrHist[$val]['code_group'] 		= $valx['code_group'];
						$ArrHist[$val]['unit_material'] 			= $valx['unit_material'];
						$ArrHist[$val]['kurs']		= $valx['kurs'];
						$ArrHist[$val]['region'] 	= $valx['region'];
						$ArrHist[$val]['rate'] 	= $valx['rate'];
						$ArrHist[$val]['sts_price'] 			= $valx['sts_price'];
						$ArrHist[$val]['updated_by'] 	= $valx['updated_by'];
						$ArrHist[$val]['updated_date'] 	= $valx['updated_date'];
						$ArrHist[$val]['hist_by'] 		= $data_session['ORI_User']['username'];
						$ArrHist[$val]['hist_date'] 	= date('Y-m-d H:i:s');
				}
			}
			// print_r($ArrInsert); exit;
			$this->db->trans_start();
				if(empty($tanda_edit)){
					$this->db->insert_batch('price_ref', $ArrInsert);

					// $this->db->where('code_group', $code_group);
					// $this->db->update('vehicle_tool_new', $ArrUpdate);
				}

				if(!empty($tanda_edit)){
					$this->db->insert_batch('hist_price_ref', $ArrHist);
					$this->db->update_batch('price_ref', $ArrInsert,'id');

					// $this->db->where('code_group', $code_group);
					// $this->db->update('vehicle_tool_new', $ArrUpdate);
				}
			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> $Hist.' data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> $Hist.' data success. Thanks ...',
					'status'	=> 1
				);
				history($Hist.'Akomodasi '.$code_group.', id = '.$id);
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
			$unit = $this->uri->segment(4);

			$qRegion	= "SELECT * FROM region ORDER BY urut ASC";
			$tanda1 = 'Add';
			if(!empty($code_group)){
				$qRegion	= "	SELECT
												a.region_code,
												a.region,
												b.rate,
												c.category,
												c.spec,
												b.id
											FROM
												region a
												LEFT JOIN price_ref b ON a.region=b.region
												LEFT JOIN akomodasi_new c ON b.code_group=c.code_group
											WHERE
												b.code_group='".$code_group."'
												AND b.unit_material='".$unit."'
											ORDER BY
												a.urut ASC";
				$tanda1 = 'Edit';
			}
			// echo $qRegion;exit;
			$restRegion	= $this->db->query($qRegion)->result_array();

			$qConsumable 		= "	SELECT * FROM akomodasi_new WHERE
														deleted = 'N'
														AND sts_price = 'N'
													ORDER BY
														category ASC, spec ASC";
			$restConsumable = $this->db->query($qConsumable)->result_array();

			$qCurrency		= "SELECT * FROM currency ORDER BY mata_uang ASC, negara ASC";
			$restCurrency = $this->db->query($qCurrency)->result_array();

			$qHeader 		= "SELECT a.*, b.spec, b.category AS cty FROM price_ref a LEFT JOIN akomodasi_new b ON a.code_group=b.code_group WHERE a.code_group='".$code_group."' AND a.unit_material='".$unit."'";
			$restHeader = $this->db->query($qHeader)->result();

			$restUnit = array();
			if(!empty($restHeader)){
				$ListBQipp		= $this->db->query("SELECT unit_material FROM price_ref WHERE code_group='".$code_group."' AND deleted='N' ")->result_array();
				$dtListArray = array();
				foreach($ListBQipp AS $val => $valx){
					$dtListArray[$val] = $valx['unit_material'];
				}
				$dtImplode	= "('".implode("','", $dtListArray)."')";

				$qUnit	= "SELECT a.* FROM list a WHERE a.origin='vehicle_tool' AND a.category='unit' AND category_list NOT IN ".$dtImplode." ORDER BY a.urut ASC";
		  	$restUnit = $this->db->query($qUnit)->result_array();
			}

			$data = array(
				'title'			=> $tanda1.' Price Reference',
				'action'		=> 'add',
				'vehicle_tool'=> $restConsumable,
				'currency'	=> $restCurrency,
				'header'		=> $restHeader,
				'region'		=> $restRegion,
				'unit'		=> $restUnit
			);
			$this->load->view('Price/add_ak',$data);
		}
	}

	public function hapus_ak(){
		$code_group = $this->uri->segment(3);
		$unit = $this->uri->segment(4);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$qGet = "SELECT unit_material, code_group FROM price_ref WHERE code_group='".$code_group."' AND unit_material='".$unit."' LIMIT 1";
		$restGet = $this->db->query($qGet)->result();

		$ArrUpdate = array(
			'deleted' => 'Y',
			'deleted_by' => $data_session['ORI_User']['username'],
			'deleted_date' => $dateTime
		);

		$this->db->trans_start();
				$this->db->where('code_group', $code_group);
				$this->db->where('unit_material', $unit);
				$this->db->update('price_ref', $ArrUpdate);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Delete data failed. Please try again later ...',
				'status'	=> 2
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Delete data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete Price Reference '.$restGet[0]->code_group.' / '.$restGet[0]->unit_material.', id = '.$code_group);
		}

		echo json_encode($Arr_Kembali);


	}

	//==========================================================================================================================
	//==============================================HEAVY EQUIPMENT=============================================================
	//==========================================================================================================================

	public function index_he(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_Group			= $this->master_model->getArray('groups',array(),'id','name');

		$data = array(
			'title'			=> 'Indeks Of Heavy Equipment',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View price reference heavy equipment');
		$this->load->view('Price/index_he',$data);
	}

	public function getDataJSON_he(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->queryDataJSON_he(
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
			$nestedData[]	= "<div align='left'>".strtoupper(strtolower($row['unit']))."</div>";
			$nestedData[]	= "<div align='center'>".strtoupper(strtolower($row['kurs']))."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['jawa'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['sumatra'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['kalimantan'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['sulawesi'])."</div>";
			$nestedData[]	= "<div align='right'>".number_format($row['indonesia_timur'])."</div>";
					$updX	= "";
					$delX	= "";
					if($Arr_Akses['update']=='1'){
						$updX	= "&nbsp;<a href='".site_url($this->uri->segment(1).'/add_he/'.$row['code_group'].'/'.$row['unit'])."' class='btn btn-sm btn-primary' title='Edit Data' data-role='qtip'><i class='fa fa-edit'></i></a>";
					}
					if($Arr_Akses['delete']=='1'){
						$delX	= "<button class='btn btn-sm btn-danger deleted' title='Permanent Delete' data-code_group='".$row['code_group']."' data-unit='".$row['unit']."'><i class='fa fa-trash'></i></button>";
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

	public function queryDataJSON_he($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){

		$sql = "
		select
			a.code_group AS code_group,
			b.category AS category,
			b.spec AS spec,
			a.unit_material AS unit,
			a.kurs AS kurs,
			(select b.rate from price_ref b where b.region = 'jawa' and b.code_group = a.code_group and b.unit_material = a.unit_material and b.deleted='N' limit 1) AS jawa,
			(select b.rate from price_ref b where b.region = 'sumatra' and b.code_group = a.code_group and b.unit_material = a.unit_material and b.deleted='N' limit 1) AS sumatra,
			(select b.rate from price_ref b where b.region = 'kalimantan' and b.code_group = a.code_group and b.unit_material = a.unit_material and b.deleted='N' limit 1) AS kalimantan,
			(select b.rate from price_ref b where b.region = 'sulawesi' and b.code_group = a.code_group and b.unit_material = a.unit_material and b.deleted='N' limit 1) AS sulawesi,
			(select b.rate from price_ref b where b.region = 'indonesia timur' and b.code_group = a.code_group and b.unit_material = a.unit_material and b.deleted='N' limit 1) AS indonesia_timur,
			a.updated_by AS updated_by,
			a.updated_date AS updated_date
		from
			price_ref a LEFT JOIN heavy_equipment_new b ON a.code_group=b.code_group
		where
			a.category='heavy equipment' AND a.deleted = 'N'AND (
				b.category LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR b.spec LIKE '%".$this->db->escape_like_str($like_value)."%'
	      )
		group by
			a.code_group,
			a.unit_material
		";
		// echo $sql; exit;

		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'nomor',
			1 => 'category',
			2 => 'spec',
			3 => 'unit',
			4 => 'kurs'
		);

		$sql .= " ORDER BY  ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function add_he(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');
			$db2 = $this->load->database('costing', TRUE);
			// print_r($data);
			// exit;
			$id			= strtolower($data['id']);
			$tanda_edit		= strtolower($data['tanda_edit']);
			$code_group		= $data['code_group'];
			$kurs		= $data['kurs'];
			$unit_material= strtolower(trim($data['unit_material']));
			$DetailData		= $data['DetailData'];

			$ArrInsert = array();
			$nomor = 0;
			foreach($DetailData AS $val => $valx){
				$nomor++;
				$ArrInsert[$val]['code_group'] = $code_group;
				$ArrInsert[$val]['category'] = 'heavy equipment';
				$ArrInsert[$val]['unit_material'] = $unit_material;
				$ArrInsert[$val]['kurs'] = $kurs;
				$ArrInsert[$val]['region'] = $valx['region'];
				$ArrInsert[$val]['rate'] = str_replace(',','',$valx['rate']);
				$ArrInsert[$val]['updated_by'] = $data_session['ORI_User']['username'];
				$ArrInsert[$val]['updated_date'] = $dateTime;

			}

			$ArrUpdate = array(
				'sts_price' => 'Y',
			);

			if(empty($tanda_edit)){
				$Hist = "Add ";
			}

			if(!empty($tanda_edit)){
				$Hist = "Update ";

				$ArrInsert = array();
				$nomor = 0;
				foreach($DetailData AS $val => $valx){
					$nomor++;
					$ArrInsert[$val]['id'] = $valx['id'];
					$ArrInsert[$val]['code_group'] = $code_group;
					$ArrInsert[$val]['category'] = 'vehicle tool';
					$ArrInsert[$val]['unit_material'] = $unit_material;
					$ArrInsert[$val]['kurs'] = $kurs;
					$ArrInsert[$val]['region'] = $valx['region'];
					$ArrInsert[$val]['rate'] = str_replace(',','',$valx['rate']);
					$ArrInsert[$val]['updated_by'] = $data_session['ORI_User']['username'];
					$ArrInsert[$val]['updated_date'] = $dateTime;
				}

				$qHist		= "SELECT * FROM price_ref WHERE code_group='".$code_group."' AND unit_material='".$unit_material."'";
				$restHist	= $this->db->query($qHist)->result_array();

				$ArrHist = array();
					foreach($restHist AS $val => $valx){
						$ArrHist[$val]['category'] 		= $valx['category'];
						$ArrHist[$val]['code_group'] 		= $valx['code_group'];
						$ArrHist[$val]['unit_material'] 			= $valx['unit_material'];
						$ArrHist[$val]['kurs']		= $valx['kurs'];
						$ArrHist[$val]['region'] 	= $valx['region'];
						$ArrHist[$val]['rate'] 	= $valx['rate'];
						$ArrHist[$val]['sts_price'] 			= $valx['sts_price'];
						$ArrHist[$val]['updated_by'] 	= $valx['updated_by'];
						$ArrHist[$val]['updated_date'] 	= $valx['updated_date'];
						$ArrHist[$val]['hist_by'] 		= $data_session['ORI_User']['username'];
						$ArrHist[$val]['hist_date'] 	= date('Y-m-d H:i:s');
				}
			}
			// print_r($ArrInsert); exit;
			$this->db->trans_start();
				if(empty($tanda_edit)){
					$this->db->insert_batch('price_ref', $ArrInsert);

					// $this->db->where('code_group', $code_group);
					// $this->db->update('vehicle_tool_new', $ArrUpdate);
				}

				if(!empty($tanda_edit)){
					$this->db->insert_batch('hist_price_ref', $ArrHist);
					$this->db->update_batch('price_ref', $ArrInsert,'id');

					// $this->db->where('code_group', $code_group);
					// $this->db->update('vehicle_tool_new', $ArrUpdate);
				}
			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$Arr_Kembali	= array(
					'pesan'		=> $Hist.'data failed. Please try again later ...',
					'status'	=> 2
				);
			}
			else{
				$this->db->trans_commit();
				$Arr_Kembali	= array(
					'pesan'		=> $Hist.'data success. Thanks ...',
					'status'	=> 1
				);
				history($Hist.'Vehicle Tools '.$code_group.', id = '.$id);
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
			$unit = $this->uri->segment(4);

			$qRegion	= "SELECT * FROM region ORDER BY urut ASC";
			$tanda1 = 'Add';
			if(!empty($code_group)){
				$qRegion	= "	SELECT
												a.region_code,
												a.region,
												b.rate,
												c.category,
												c.spec,
												b.id
											FROM
												region a
												LEFT JOIN price_ref b ON a.region=b.region
												LEFT JOIN heavy_equipment_new c ON b.code_group=c.code_group
											WHERE
												b.code_group='".$code_group."'
												AND b.unit_material='".$unit."'
											ORDER BY
												a.urut ASC";
				$tanda1 = 'Edit';
			}
			// echo $qRegion;exit;
			$restRegion	= $this->db->query($qRegion)->result_array();

			$qConsumable 		= "	SELECT * FROM heavy_equipment_new WHERE
														deleted = 'N'
														AND sts_price = 'N'
													ORDER BY
														category ASC, spec ASC";
			$restConsumable = $this->db->query($qConsumable)->result_array();

			$qCurrency		= "SELECT * FROM currency ORDER BY mata_uang ASC, negara ASC";
			$restCurrency = $this->db->query($qCurrency)->result_array();

			$qHeader 		= "SELECT a.*, b.spec, b.category AS cty FROM price_ref a LEFT JOIN heavy_equipment_new b ON a.code_group=b.code_group WHERE a.code_group='".$code_group."' AND a.unit_material='".$unit."'";
			$restHeader = $this->db->query($qHeader)->result();

			$restUnit = array();
			if(!empty($restHeader)){
				$ListBQipp		= $this->db->query("SELECT unit_material FROM price_ref WHERE code_group='".$code_group."' AND deleted='N' ")->result_array();
				$dtListArray = array();
				foreach($ListBQipp AS $val => $valx){
					$dtListArray[$val] = $valx['unit_material'];
				}
				$dtImplode	= "('".implode("','", $dtListArray)."')";

				$qUnit	= "SELECT a.* FROM list a WHERE a.origin='vehicle_tool' AND a.category='unit' AND category_list NOT IN ".$dtImplode." ORDER BY a.urut ASC";
		  	$restUnit = $this->db->query($qUnit)->result_array();
			}

			$data = array(
				'title'			=> $tanda1.' Price Reference',
				'action'		=> 'add',
				'vehicle_tool'=> $restConsumable,
				'currency'	=> $restCurrency,
				'header'		=> $restHeader,
				'region'		=> $restRegion,
				'unit'		=> $restUnit
			);
			$this->load->view('Price/add_he',$data);
		}
	}

	public function hapus_he(){
		$code_group = $this->uri->segment(3);
		$unit = $this->uri->segment(4);
		$data_session	= $this->session->userdata;
		$dateTime		= date('Y-m-d H:i:s');

		$qGet = "SELECT unit_material, code_group FROM price_ref WHERE code_group='".$code_group."' AND unit_material='".$unit."' LIMIT 1";
		$restGet = $this->db->query($qGet)->result();

		$ArrUpdate = array(
			'deleted' => 'Y',
			'deleted_by' => $data_session['ORI_User']['username'],
			'deleted_date' => $dateTime
		);

		$this->db->trans_start();
				$this->db->where('code_group', $code_group);
				$this->db->where('unit_material', $unit);
				$this->db->update('price_ref', $ArrUpdate);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Kembali	= array(
				'pesan'		=> 'Delete data failed. Please try again later ...',
				'status'	=> 2
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Kembali	= array(
				'pesan'		=> 'Delete data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete Price Reference '.$restGet[0]->code_group.' / '.$restGet[0]->unit_material.', id = '.$code_group);
		}

		echo json_encode($Arr_Kembali);


	}





	//list supplier
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

	public function get_detail(){
		 	$code_group = $this->uri->segment(3);
			$code = substr($code_group, 0,2);
			// echo $code;
			if($code == 'VT'){
				$table = "vehicle_tool_new";

				$ListBQipp		= $this->db->query("SELECT unit_material FROM price_ref WHERE code_group='".$code_group."' AND deleted='N' ")->result_array();
				$dtListArray = array();
				foreach($ListBQipp AS $val => $valx){
					$dtListArray[$val] = $valx['unit_material'];
				}
				$dtImplode	= "('".implode("','", $dtListArray)."')";

				$qList	= "SELECT a.* FROM list a WHERE a.origin='vehicle_tool' AND a.category='unit' AND category_list NOT IN ".$dtImplode." ORDER BY a.urut ASC";
				$list		= $this->db->query($qList)->result();

				$option 	= "";
				foreach($list as $row)	{
						$option .= "<option value='".$row->category_list."'>".strtoupper($row->category_list)."</option>";
				}
			}

			if($code == 'HE'){
				$table = "heavy_equipment_new";

				$ListBQipp		= $this->db->query("SELECT unit_material FROM price_ref WHERE code_group='".$code_group."' AND deleted='N' ")->result_array();
				$dtListArray = array();
				foreach($ListBQipp AS $val => $valx){
					$dtListArray[$val] = $valx['unit_material'];
				}
				$dtImplode	= "('".implode("','", $dtListArray)."')";

				$qList	= "SELECT a.* FROM list a WHERE a.origin='vehicle_tool' AND a.category='unit' AND category_list NOT IN ".$dtImplode." ORDER BY a.urut ASC";
				$list		= $this->db->query($qList)->result();

				$option 	= "";
				foreach($list as $row)	{
						$option .= "<option value='".$row->category_list."'>".strtoupper($row->category_list)."</option>";
				}
			}

			if($code == 'AK'){
				$table = "akomodasi_new";

				$ListBQipp		= $this->db->query("SELECT unit_material FROM price_ref WHERE code_group='".$code_group."' AND deleted='N' ")->result_array();
				$dtListArray = array();
				foreach($ListBQipp AS $val => $valx){
					$dtListArray[$val] = $valx['unit_material'];
				}
				$dtImplode	= "('".implode("','", $dtListArray)."')";

				$qList	= "SELECT a.* FROM list a WHERE a.origin='vehicle_tool' AND a.category='unit' AND category_list NOT IN ".$dtImplode." ORDER BY a.urut ASC";
				$list		= $this->db->query($qList)->result();

				$option 	= "";
				foreach($list as $row)	{
						$option .= "<option value='".$row->category_list."'>".strtoupper($row->category_list)."</option>";
				}
			}

			if($code == 'MP'){
				$table = "man_power_new";

				$ListBQipp		= $this->db->query("SELECT unit_material FROM price_ref WHERE code_group='".$code_group."' AND deleted='N' ")->result_array();
				$dtListArray = array();
				foreach($ListBQipp AS $val => $valx){
					$dtListArray[$val] = $valx['unit_material'];
				}
				$dtImplode	= "('".implode("','", $dtListArray)."')";

				$qList	= "SELECT a.* FROM list a WHERE a.origin='vehicle_tool' AND a.category='unit' AND category_list NOT IN ".$dtImplode." ORDER BY a.urut ASC";
				$list		= $this->db->query($qList)->result();

				$option 	= "";
				foreach($list as $row)	{
						$option .= "<option value='".$row->category_list."'>".strtoupper($row->category_list)."</option>";
				}
			}


			if($code == 'CN'){
				$table = "con_nonmat_new";

				$qList	= "SELECT unit_material FROM con_nonmat_new_konversi WHERE code_group='".$code_group."' AND sts_price='N' AND deleted='N' GROUP BY unit_material ORDER BY unit_material ASC ";
				$list		= $this->db->query($qList)->result();

				$option 	= "";
				foreach($list as $row)	{
						$option .= "<option value='".$row->unit_material."'>".strtoupper($row->unit_material)."</option>";
				}
			}

			// exit;
			$data	= $this->db->limit(1)->get_where($table, array('code_group'=>$code_group))->result();

			$brand = (!empty($data[0]->brand))?strtoupper($data[0]->brand):'';
			if($code == 'MP'){
				$brand = (!empty($data[0]->note))?strtoupper($data[0]->note):'';
			}

			$unit = (!empty($data[0]->unit))?strtoupper($data[0]->unit):'';
			if($code == 'CN' OR $code == 'VT'){
				$unit = (!empty($data[0]->unit))?get_name('unit','unit','id',$data[0]->unit):'';
			}

			$material_name = '';
			if($code == 'CN'){
				$material_name = (!empty($data[0]->material_name))?$data[0]->material_name:'';
			}


	 		echo json_encode(array(
	 			'spec' => strtoupper($data[0]->spec),
				'brand' => $brand,
				'unit' => $unit,
				'material_name' => $material_name,
				'option' => $option
	 		));
    }


}
