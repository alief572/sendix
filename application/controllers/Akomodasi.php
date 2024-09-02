<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akomodasi extends CI_Controller {

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

		$data = array(
			'title'			=> 'Indeks Of Akomodasi',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Akomodasi');
		$this->load->view('Akomodasi/index',$data);
	}

	public function data_side_akomodasi(){
		$this->serverside_model->get_json_akomodasi();
	}

	public function add(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$id_category		= strtolower($data['category']);
			$spec			= str_replace(["'", '"'], ' inchi', strtolower(trim($data['spec'])));
			$tanda_edit		= strtolower($data['tanda_edit']);
			$code_group		= $data['code_group'];

			// echo $tanda_edit."<br>".$spec;
			// print_r($DetailData);
			// exit;

			//Pencarian data yang sudah ada
			$ValueProduct	= "SELECT * FROM akomodasi_new WHERE id_category='".$id_category."' AND spec='".$spec."' ";
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
					$srcMtr			= "SELECT MAX(code_group) as maxP FROM akomodasi_new WHERE code_group LIKE 'AK%' ";
					$numrowMtr		= $this->db->query($srcMtr)->num_rows();
					$resultMtr		= $this->db->query($srcMtr)->result_array();
					$angkaUrut2		= $resultMtr[0]['maxP'];
					$urutan2		= (int)substr($angkaUrut2, 2, 5);
					$urutan2++;
					$urut2			= sprintf('%05s',$urutan2);
					$code_group		= "AK".$urut2;

					$ArrInsert = array(
						'code_group' => $code_group,
						'id_category' => $id_category,
						'category' => get_name('akomodasi_category','category','id',$id_category),
						'spec' => $spec,
						'created_by' => $data_session['ORI_User']['username'],
						'created_date' => $dateTime
					);

					// echo "<pre>"; print_r($ArrInsert);
					// exit;

					$this->db->trans_start();
					$this->db->insert('akomodasi_new', $ArrInsert);
					$this->db->trans_complete();
				}

				//edit
				if(!empty($tanda_edit)){
					$Hist = 'Edit ';

					$ArrInsert = array(
						'code_group' => $code_group,
						'id_category' => $id_category,
						'category' => get_name('akomodasi_category','category','id',$id_category),
						'spec' => $spec,
						'created_by' => $data_session['ORI_User']['username'],
						'created_date' => $dateTime
					);

					// echo "<pre>"; print_r($ArrInsert);
					// exit;

					$this->db->trans_start();
						$this->db->where('code_group', $code_group);
						$this->db->update('akomodasi_new', $ArrInsert);
					$this->db->trans_complete();
				}

				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$Arr_Kembali	= array(
						'pesan'		=> $Hist.'Akomodasi data failed. Please try again later ...',
						'status'	=> 2
					);
				}
				else{
					$this->db->trans_commit();
					$Arr_Kembali	= array(
						'pesan'		=> $Hist.'Akomodasi data success. Thanks ...',
						'status'	=> 1
					);
					history($Hist.'Akomodasi '.$id_category.' / '.$spec);
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
				$qRegion	= "SELECT * from akomodasi_new WHERE code_group='".$uri3."' LIMIT 1";
				$tanda1 = 'Edit';
			}

			// echo $qRegion;
			$restRegion	= $this->db->query($qRegion)->result_array();

			$qCateMP	= "SELECT * FROM akomodasi_category ORDER BY category ASC";
			$restCateMP	= $this->db->query($qCateMP)->result_array();

			$data = array(
				'title'			=> $tanda1.' Akomodasi',
				'action'		=> 'add',
				'region'		=> $restRegion,
				'cateMP'		=> $restCateMP
			);
			$this->load->view('Akomodasi/add',$data);
		}
	}

	public function add_category(){
		if($this->input->post()){
			$Arr_Kembali	= array();
			$data			= $this->input->post();
			$data_session	= $this->session->userdata;
			$dateTime		= date('Y-m-d H:i:s');

			$add_category	= strtolower(trim($data['add_category']));
			$information	= strtolower(trim($data['information']));
			$code_group	= strtolower($data['code_group']);

			//Pencarian data yang sudah ada
			$ValueProduct	= "SELECT * FROM akomodasi_category WHERE category='".$add_category."' ";
			$NumProduct		= $this->db->query($ValueProduct)->num_rows();

			if($NumProduct > 0){
				$Arr_Kembali		= array(
					'status'		=> 3,
					'pesan'			=> 'Category Akomodasi sudah digunakan. Input catgeory lain ...'
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
				$this->db->insert('akomodasi_category', $ArrInsert);
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
            $this->db->update('akomodasi_new', $ArrPlant);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$Arr_Data	= array(
				'pesan'		=>'Delete data failed. Please try again later ...',
				'status'	=> 0
			);
		}
		else{
			$this->db->trans_commit();
			$Arr_Data	= array(
				'pesan'		=>'Delete data success. Thanks ...',
				'status'	=> 1
			);
			history('Delete Akomodasi category : '.$code_group);
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
		$sheet->setCellValue('A'.$Row, 'MASTER AKOMODASI');
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

		$qManPower	= "SELECT * FROM view_akomodasi";
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

		$sheet->setTitle('Akomodasi Master');
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
		header('Content-Disposition: attachment;filename="master_akomodasi_'.date('YmdHis').'.xls"');
		//unduh file
		$objWriter->save("php://output");
	}

}
