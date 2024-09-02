<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * @author Harboens
 * @copyright Copyright (c) 2022
 *
 * This is controller for Request Payment
 */

$status = array();

class Request_payment extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->load->model('master_model');
		$this->load->model('All_model');
		$this->load->model('Jurnal_model');
		$this->load->model('Request_payment_model');
		$this->load->database();
        if(!$this->session->userdata('isORIlogin')){
			redirect('login');
		}
  		$this->status = array("0" => "Baru", "1" => "Disetujui", "2" => "Selesai");
  }

	public function list_return(){
		$controller			= 'request_payment/index';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$get_Data			= $this->db->query("SELECT a.id as ids,a.no_doc,c.nama_karyawan nama,a.tgl_doc,a.informasi as keperluan, 'expense' as tipe,a.jumlah,null as tanggal,a.no_doc as id, bank_id, accnumber, accname FROM " . DBERP . ".tr_expense a left join " . DBACC . ".coa_master as b on a.coa=b.no_perkiraan
		left join user_emp c on a.nama=c.id WHERE a.status=1 and a.jumlah<=0")->result();
		$menu_akses			= $this->master_model->getMenu();
		$data = array(
			'title'			=> 'Pengembalian Expense',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Pengembalian Expense');
		$this->load->view('Request_payment/list_return',$data);
	}
	public function index(){
		$controller			= 'request_payment/index';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$get_Data			= $this->Request_payment_model->GetListDataRequest();
		$menu_akses			= $this->master_model->getMenu();

		$data = array(
			'title'			=> 'Index Of Request Payment',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Request Payment');
		$this->load->view('Request_payment/index',$data);
	}
	public function save_request()
	{
		$data_session	= $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['id_user'];
		$status	= $this->input->post("status");
		$this->db->trans_begin();
		if (!empty($status)) {
			$no_request=time().'-'.mt_rand();
			foreach ($status as $val) {
				$tipe = $this->input->post("tipe_" . $val);
				$no_doc = $this->input->post("no_doc_" . $val);
				$data =  array(
					'no_request' => $no_request,
					'no_doc' => $no_doc,
					'nama' => $this->input->post("nama_" . $val),
					'tgl_doc' => $this->input->post("tgl_doc_" . $val),
					'tanggal' => $this->input->post("tanggal_" . $val),
					'keperluan' => $this->input->post("keperluan_" . $val),
					'tipe' => $tipe,
					'jumlah' => $this->input->post("jumlah_" . $val),
					'ids' => $this->input->post("ids_" . $val),
					'status' => 0,
					'bank_id' => $this->input->post("bank_id_" . $val),
					'accnumber' => $this->input->post("accnumber_" . $val),
					'accname' => $this->input->post("accname_" . $val),
					'created_by' => $UserName,
					'created_on' => date("Y-m-d h:i:s"),
				);
				$idreq = $this->All_model->dataSave(DBERP . '.request_payment', $data);
				if ($tipe == 'transportasi') {
					$this->All_model->dataUpdate(DBERP . '.tr_transport_req', array('status' => 2), array('no_doc' => $no_doc));
				}
				if ($tipe == 'kasbon') {
					$this->All_model->dataUpdate(DBERP . '.tr_kasbon', array('status' => 2), array('no_doc' => $no_doc));
				}
				if ($tipe == 'expense') {
					$this->All_model->dataUpdate(DBERP . '.tr_expense', array('status' => 2), array('no_doc' => $no_doc));
				}
				if ($tipe == 'nonpo') {
					$this->All_model->dataUpdate(DBERP . '.tr_non_po_header', array('status' => 4), array('no_doc' => $no_doc));
				}
				if ($tipe == 'periodik') {
					$this->All_model->dataUpdate(DBERP . '.tr_pengajuan_rutin_detail', array('id_payment' => $idreq), array('no_doc' => $no_doc, 'id' => $this->input->post("ids_" . $val)));
				}
			}
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result = false;
		} else {
			$this->db->trans_commit();
			$result = true;
		}
		$param = array(
			'save' => $result
		);
		echo json_encode($param);
	}
	function print_req($id){
		$data_request = $this->db->query("select * from request_payment where no_request='".$id."'")->result();
		$data = array(
			'data_request'	=> $data_request,
		);

		$this->load->view('Request_payment/request_print',$data);
	}
	public function list_approve()
	{
		$controller			= 'request_payment/list_approve';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$get_Data			= $this->Request_payment_model->GetListDataApproval('status!=2');
		$menu_akses			= $this->master_model->getMenu();

		$data = array(
			'title'			=> 'Index Of Request Payment Approval',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Request Payment');
		$this->load->view('Request_payment/list_approve',$data);
	}
	public function approval_payment($type = null, $id = null) {
		$controller			= 'request_payment/list_approve';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$menu_akses			= $this->master_model->getMenu();
		$type 	= $_GET['type'];
		$id 	= $_GET['id'];
		/* Expense */
		if (isset($type) && $type == 'expense') {
			$data 			= $this->db->get_where('tr_expense', ['id' => $id])->row();
			$data_detail	= $this->db->get_where('tr_expense_detail', ['no_doc' => $data->no_doc])->result();
		}

		/* Kasbon */
		if (isset($type) && $type == 'kasbon') {
			$data 			= $this->db->get_where('tr_kasbon', ['id' => $id])->row();
			$data_detail	= $this->db->get_where('tr_kasbon', ['no_doc' => $data->no_doc])->result();
		}

		/* Transportasi */
		if (isset($type) && $type == 'transportasi') {
			$data 			= $this->db->get_where('tr_transport_req', ['id' => $id])->row();
			$data_detail	= $this->db->get_where('tr_transport', ['no_req' => $data->no_doc])->result();
		}

		/* NON PO */
		if (isset($type) && $type == 'nonpo') {
			$data 			= $this->db->get_where('tr_non_po_header', ['id' => $id])->row();
			$data_detail	= $this->db->get_where('tr_non_po_detail', ['no_doc' => $data->no_doc])->result();
		}

		/* Periodik/Rutin */
		if (isset($type) && $type == 'periodik') {
			$data 			= $this->db->get_where('tr_pengajuan_rutin_detail', ['id' => $id])->row();
			$data_detail	= $this->db->get_where('tr_pengajuan_rutin_detail', ['no_doc' => $data->no_doc])->result();
		}
		$data = array(
			'title'			=> 'Approval Payment',
			'action'		=> 'index',
			'type'		 	=> $type,
			'header'	 	=> $data,
			'details' 		=> $data_detail,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Request Payment');
		$this->load->view('Request_payment/detail_approve',$data);
	}
	private function _getIdPayment($date)
	{
		$count 		= 1;
//		$m 			= date_format(date_create($date), 'm');
		$y 			= date_format(date_create($date), 'Y');

		$sql 		= "SELECT count(id) as max_id FROM payment_approve where YEAR(tgl_doc) = '$y'";
		$max_id 	= $this->db->query($sql)->row()->max_id;

		if ($max_id > 0) {
			$max_id = (int)$max_id;
			$count 	= $max_id + 1;
		}
		$new_id  	= 'PAY' .$y. str_pad($count, 5, '0', STR_PAD_LEFT);
		return  $new_id;
	}

	private function _getIdDetail($payment_id)
	{
		$count 		= 1;
		$sql 		= "SELECT MAX(RIGHT(id,2)) as max_id FROM payment_approve_details where payment_id = '$payment_id'";
		$max_id 	= $this->db->query($sql)->row()->max_id;

		if ($max_id > 0) {
			$count 	= $max_id + 1;
		}

		// $new_id  	= 'PAY' . date('ym') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
		return  $count;
	}

	public function save_approval()
	{
		$data_session		= $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['id_user'];

		$Data		= $this->input->post();
		$header 	= $this->db->get_where('request_payment', ['ids' => $Data['id'],'tipe' => $Data['tipe']])->row_array();
		$Id 		= $this->_getIdPayment($Data['date']);

		// $detail =
		$ArrDetail 			= [];
		$idDetail 			= $this->_getIdDetail($Id);

		$n = 0;
		foreach ($Data['item'] as $detail) {
			$n++;
			$idDetail++;
			if ($Data['tipe'] == 'expense') {
				$dtl 				= $this->db->get_where('tr_expense_detail', ['id' => $detail['id']])->row();
				$ArrDetail[] 		= [
					'id' 			=> $Id . "-" . str_pad($idDetail, 2, '0', STR_PAD_LEFT),
					'payment_id' 	=> $Id,
					'no_doc' 		=> $dtl->no_doc,
					'tgl_doc' 		=> $dtl->tanggal,
					'deskripsi' 	=> $dtl->deskripsi,
					'qty' 			=> $dtl->qty,
					'harga' 		=> $dtl->harga,
					'total' 		=> $dtl->total_harga,
					'keterangan' 	=> $dtl->keterangan,
					'doc_file' 		=> $dtl->doc_file,
					'coa' 			=> $dtl->coa,
					'created_by' 	=> $UserName,
					'created_on' 	=> date("Y-m-d h:i:s"),
				];
				$updateExpense[] = [
					'id' 			=> $dtl->id,
					'status' 		=> '1',
					'modified_by' 	=> $UserName,
					'modified_on' 	=> date("Y-m-d h:i:s"),
				];
				$Harga[] 		= $dtl->total_harga;
			}

			if ($Data['tipe'] == 'kasbon') {
				$dtl 				= $this->db->get_where('tr_kasbon', ['id' => $detail['id']])->row();

				$ArrDetail[] 		= [
					'id' 			=> $Id . "-" . str_pad($idDetail, 2, '0', STR_PAD_LEFT),
					'payment_id' 	=> $Id,
					'no_doc' 		=> $dtl->no_doc,
					'tgl_doc' 		=> $dtl->tgl_doc,
					'deskripsi' 	=> $dtl->keperluan,
					'qty' 			=> '1',
					'harga' 		=> $dtl->jumlah_kasbon,
					'total' 		=> $dtl->jumlah_kasbon,
					'keterangan' 	=> $dtl->keperluan,
					'doc_file' 		=> $dtl->doc_file,
					'coa' 			=> $dtl->coa,
					'created_by' 	=> $UserName,
					'created_on' 	=> date("Y-m-d h:i:s"),
				];
				$updateDetail[] = [
					'id' 			=> $dtl->id,
					'status' 		=> '3',
					'modified_by' 	=> $UserName,
					'modified_on' 	=> date("Y-m-d h:i:s"),
				];
				$Harga[] 		= $dtl->jumlah_kasbon;
			}

			if ($Data['tipe'] == 'transportasi') {
				$dtl 				= $this->db->get_where('tr_transport', ['id' => $detail['id']])->row();
				$ArrDetail[] 		= [
					'id' 			=> $Id . "-" . str_pad($idDetail, 2, '0', STR_PAD_LEFT),
					'payment_id' 	=> $Id,
					'no_doc' 		=> $dtl->no_req,
					'tgl_doc' 		=> $dtl->tgl_doc,
					'deskripsi' 	=> $dtl->keperluan,
					'qty' 			=> '1',
					'harga' 		=> $dtl->jumlah_kasbon,
					'total' 		=> $dtl->jumlah_kasbon,
					'keterangan' 	=> $dtl->keperluan,
					'doc_file' 		=> $dtl->doc_file,
					'coa' 			=> null,
					'created_by' 	=> $UserName,
					'created_on' 	=> date("Y-m-d h:i:s"),
				];
				$updateDetail[] = [
					'id' 			=> $dtl->id,
					'status' 		=> '2',
					'modified_by' 	=> $UserName,
					'modified_on' 	=> date("Y-m-d h:i:s"),
				];
				$Harga[] 		= $dtl->jumlah_kasbon;
			}

			if ($Data['tipe'] == 'nonpo') {
				$dtl 				= $this->db->get_where('tr_non_po_detail', ['id' => $detail['id']])->row();

				$ArrDetail[] 		= [
					'id' 			=> $Id . "-" . str_pad($idDetail, 2, '0', STR_PAD_LEFT),
					'payment_id' 	=> $Id,
					'no_doc' 		=> $dtl->no_doc,
					'tgl_doc' 		=> $dtl->tgl_pr,
					'deskripsi' 	=> $dtl->deskripsi,
					'qty' 			=> '1',
					'harga' 		=> $dtl->nilai_satuan_request,
					'total' 		=> $dtl->total_request,
					'keterangan' 	=> $dtl->keterangan,
					// 'doc_file' 		=> $dtl->doc_file,
					'coa' 			=> null,
					'created_by' 	=> $UserName,
					'created_on' 	=> date("Y-m-d h:i:s"),
				];

				$updateDetail[] = [
					'id' 			=> $dtl->id,
					'status' 		=> '1',
					'modified_by' 	=> $UserName,
					'modified_on' 	=> date("Y-m-d h:i:s"),
				];
				$Harga[] 		= $dtl->total_request;
			}

			if ($Data['tipe'] == 'periodik') {
				$dtl 				= $this->db->get_where('tr_pengajuan_rutin_detail', ['id' => $detail['id']])->row();

				$ArrDetail[] 		= [
					'id' 			=> $Id . "-" . str_pad($idDetail, 2, '0', STR_PAD_LEFT),
					'payment_id' 	=> $Id,
					'no_doc' 		=> $dtl->no_doc,
					'tgl_doc' 		=> $dtl->tanggal,
					'deskripsi' 	=> $dtl->keterangan,
					'qty' 			=> '1',
					'harga' 		=> $dtl->nilai,
					'total' 		=> $dtl->nilai,
					'keterangan' 	=> $dtl->keterangan,
					'doc_file' 		=> $dtl->doc_file,
					'coa' 			=> $dtl->coa,
					'created_by' 	=> $UserName,
					'created_on' 	=> date("Y-m-d h:i:s"),
				];

				$updateDetail[] = [
					'id' 			=> $dtl->id,
					'status' 		=> '1',
					'modified_by' 	=> $UserName,
					'modified_on' 	=> date("Y-m-d h:i:s"),
				];
				$Harga[] 		= $dtl->nilai;
			}
		}


		$header['jumlah'] 	= array_sum($Harga);
		$header['status'] 	= '1';

		$this->db->trans_rollback();
		$this->db->trans_begin();

		if (($header)) {
			$header['id'] = $Id;
			$header['approved_by'] = $UserName;
			$header['approved_on'] = date("Y-m-d h:i:s");
			$exist_data = $this->db->get_where('payment_approve', ['ids' => $Data['id'],'tipe' => $Data['tipe']])->num_rows();
			if ($exist_data == '0') {
				$this->db->insert(DBERP . '.payment_approve', $header);
			}
		}

		/* Details */
		if ($ArrDetail) {
			if ($Data['tipe'] == 'expense') {
				$this->db->insert_batch(DBERP . '.payment_approve_details', $ArrDetail);
				$this->db->update_batch(DBERP . '.tr_expense_detail', $updateExpense, 'id');

				// Update request_payment
				$countData 		= $this->db->get_where('tr_expense_detail', ['no_doc' => $header['no_doc']])->num_rows();
				$actualPayment 	= $this->db->get_where('tr_expense_detail', ['no_doc' => $header['no_doc'], 'status >=' => '1'])->num_rows();

				if ($countData > $actualPayment) {
					$this->db->update('request_payment', ['status' => '1'], ['ids' => $Data['id']]);
				} elseif (($countData == $actualPayment)) {
					$this->db->update('request_payment', ['status' => '2'], ['ids' => $Data['id']]);
				}
			}

			if ($Data['tipe'] == 'kasbon') {
				$this->db->insert_batch(DBERP . '.payment_approve_details', $ArrDetail);
				$this->db->update_batch(DBERP . '.tr_kasbon', $updateDetail, 'id');

				// Update request_payment
				$countData 		= $this->db->get_where('tr_kasbon', ['no_doc' => $header['no_doc']])->num_rows();
				$actualPayment 	= $this->db->get_where('tr_kasbon', ['no_doc' => $header['no_doc'], 'status >=' => '3'])->num_rows();

				if ($countData > $actualPayment) {
					$this->db->update('request_payment', ['status' => '1'], ['ids' => $Data['id']]);
				} elseif (($countData == $actualPayment)) {
					$this->db->update('request_payment', ['status' => '2'], ['ids' => $Data['id']]);
				}
			}

			if ($Data['tipe'] == 'transportasi') {
				$this->db->insert_batch(DBERP . '.payment_approve_details', $ArrDetail);
				$this->db->update_batch(DBERP . '.tr_transport', $updateDetail, 'id');

				// Update request_payment
				$countData 		= $this->db->get_where('tr_transport', ['no_doc' => $header['no_doc']])->num_rows();
				$actualPayment 	= $this->db->get_where('tr_transport', ['no_doc' => $header['no_doc'], 'status >=' => '2'])->num_rows();

				if ($countData > $actualPayment) {
					$this->db->update('request_payment', ['status' => '1'], ['ids' => $Data['id']]);
				} elseif (($countData == $actualPayment)) {
					$this->db->update('request_payment', ['status' => '2'], ['ids' => $Data['id']]);
				}
			}

			if ($Data['tipe'] == 'nonpo') {
				$this->db->insert_batch(DBERP . '.payment_approve_details', $ArrDetail);
				$this->db->update_batch(DBERP . '.tr_non_po_detail', $updateDetail, 'id');

				// Update request_payment
				$countData 		= $this->db->get_where('tr_non_po_detail', ['no_doc' => $header['no_doc']])->num_rows();
				$actualPayment 	= $this->db->get_where('tr_non_po_detail', ['no_doc' => $header['no_doc'], 'status >=' => '1'])->num_rows();

				if ($countData > $actualPayment) {
					$this->db->update('request_payment', ['status' => '1'], ['ids' => $Data['id']]);
				} elseif (($countData == $actualPayment)) {
					$this->db->update('request_payment', ['status' => '2'], ['ids' => $Data['id']]);
				}
			}

			if ($Data['tipe'] == 'periodik') {
				$this->db->insert_batch(DBERP . '.payment_approve_details', $ArrDetail);
				$this->db->update_batch(DBERP . '.tr_pengajuan_rutin_detail', $updateDetail, 'id');

				// Update request_payment
				$countData 		= $this->db->get_where('tr_pengajuan_rutin_detail', ['no_doc' => $header['no_doc']])->num_rows();
				$actualPayment 	= $this->db->get_where('tr_pengajuan_rutin_detail', ['no_doc' => $header['no_doc'], 'status >=' => '1'])->num_rows();

				if ($countData > $actualPayment) {
					$this->db->update('request_payment', ['status' => '1'], ['ids' => $Data['id']]);
				} elseif (($countData == $actualPayment)) {
					$this->db->update('request_payment', ['status' => '2'], ['ids' => $Data['id']]);
				}
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result = false;
		} else {
			$this->db->trans_commit();
			$result = true;
		}
		$param = array(
			'save' => $result
		);

		echo json_encode($param);
	}
	public function save_reject() {
		$result=false;
        $id		= $this->input->post("id");
        $tipe	= $this->input->post("tipe");
        $no_doc	= $this->input->post("no_doc");
		$reason	= $this->input->post("reason");
		$data_session = $this->session->userdata;
		$UserName = $data_session['ORI_User']['username'];
        if($id!="") {
			$this->db->trans_begin();
			$table='';
			if($tipe=='expense') $table='tr_expense';
			if($tipe=='kasbon') $table='tr_kasbon';
			if($tipe=='transportasi') $table='tr_transport_req';
			$data = array(
						'status'=>9,
						'st_reject'=>$reason,
						'approved_by'=> $UserName,
						'approved_on'=>date("Y-m-d h:i:s"),
					);
			$results=$this->All_model->dataUpdate($table,$data,array('id'=>$id));
			$this->All_model->dataDelete('request_payment',array('no_doc'=>$no_doc,'ids'=>$id));
			if(is_numeric($results)) {
                $result	= TRUE;
            } else {
                $result = FALSE;
            }
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
			history('Reject data '.$tipe.' : '.$id);
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}
	public function list_payment()
	{
		$controller			= 'request_payment/list_payment';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$menu_akses	= $this->master_model->getMenu();
		$data_coa = $this->All_model->GetCoaCombo('5'," a.no_perkiraan like '1101%'");
		$results = $this->Request_payment_model->GetListDataPayment('a.status!=2');

		$data = array(
			'title'			=> 'Index Of Payment',
			'action'		=> 'index',
			'data_coa'		=> $data_coa,
			'results'		=> $results,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Payment');
		$this->load->view('Request_payment/list_payment',$data);
	}
	public function list_payment_after()
	{
		$controller			= 'request_payment/list_payment_after';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$menu_akses	= $this->master_model->getMenu();
		$data_coa = $this->All_model->GetCoaCombo();
		$results = $this->Request_payment_model->GetListDataPayment('a.status=2');

		$data = array(
			'title'			=> 'Index Of Payment',
			'action'		=> 'index',
			'data_coa'		=> $data_coa,
			'results'		=> $results,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Payment');
		$this->load->view('Request_payment/list_payment_after',$data);
	}
	public function save_payment()
	{
		$bank_coa		= $this->input->post("bank_coa");
		$keterangan		= $this->input->post("keterangan");
		$bank_nilai		= $this->input->post("bank_nilai");
		$bank_admin		= $this->input->post("bank_admin");
		$status			= $this->input->post("status");
		$no_doc			= $this->input->post("no_doc");
		$keperluan		= $this->input->post("keperluan");
		$tipe			= $this->input->post("tipe");
		$nama			= $this->input->post("nama");
		$ids			= $this->input->post("ids");

		$bank_id		= $this->input->post("bank_id");
		$accnumber		= $this->input->post("accnumber");
		$accname		= $this->input->post("accname");

		$tanggal		= $this->input->post("tanggal");	
		
		$this->db->trans_begin();
		$jenis_jurnal = 'BUK030';
		$payment_date = date("Y-m-d");
		$det_Jurnaltes1 = array();
		$ix = 0;
		$config['upload_path'] = './assets/expense/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|pdf|doc|docx|jfif';
		$config['remove_spaces'] = TRUE;
		$config['encrypt_name'] = TRUE;

		$data_session			= $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['id_user'];

		if (!empty($status)) {
			foreach ($status as $keys => $val) {
				if ($bank_nilai[$keys] <> 0) {
					$filenames = "";
					if (!empty($_FILES['doc_file_' . $val]['name'])) {
						$_FILES['file']['name'] = $_FILES['doc_file_' . $val]['name'];
						$_FILES['file']['type'] = $_FILES['doc_file_' . $val]['type'];
						$_FILES['file']['tmp_name'] = $_FILES['doc_file_' . $val]['tmp_name'];
						$_FILES['file']['error'] = $_FILES['doc_file_' . $val]['error'];
						$_FILES['file']['size'] = $_FILES['doc_file_' . $val]['size'];
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						if ($this->upload->do_upload('file')) {
							$uploadData = $this->upload->data();
							$filenames = $uploadData['file_name'];
						}
					}
					$ix++;
					$nomor_jurnal = $jenis_jurnal . date("ymd") . rand(1000, 9999) . $ix;
					$data =  array(
						'bank_id'	 => $bank_id[$keys],
						'accnumber'	 => $accnumber[$keys],
						'accname'	 => $accname[$keys],
						'keterangan' => $keterangan[$keys],
						'bank_nilai' => $bank_nilai[$keys],
						'bank_admin' => $bank_admin[$keys],
						'bank_coa' => $bank_coa,
						'doc_file' => $filenames,
						'status' => 2,
						'pay_by' => $UserName,
						'pay_on' => date("Y-m-d h:i:s"),
					);

					$this->All_model->dataUpdate(DBERP . '.payment_approve', $data, array('id' => $val));

					if ($tipe[$keys] == 'transportasi') {
//						$rec = $this->db->query("select * from ".DBACC.".master_oto_jurnal_detail where kode_master_jurnal='". $jenis_jurnal."' and menu='".$tipe[$keys]."'")->row();
						$rec = $this->db->query("select coa from ".DBERP.".tr_transport_req where no_doc='".$no_doc[$keys]."'")->row();
						$det_Jurnaltes1[] = array(
							'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $rec->coa, 'keterangan' => $keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' => $bank_nilai[$keys], 'kredit' => 0, 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
						);
						if ($bank_admin[$keys] > 0) {
							$rec = $this->db->query("select * from " . DBACC . ".master_oto_jurnal_detail where kode_master_jurnal='" . $jenis_jurnal . "' and menu='admin'")->row();
							$det_Jurnaltes1[] = array(
								'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $rec->no_perkiraan, 'keterangan' => $keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' =>  $bank_admin[$keys], 'kredit' => 0, 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
							);
						}
					}
					if ($tipe[$keys] == 'kasbon') {
						$rec = $this->db->query("select * from " . DBACC . ".master_oto_jurnal_detail where kode_master_jurnal='" . $jenis_jurnal . "' and menu='" . $tipe[$keys] . "'")->row();
						$det_Jurnaltes1[] = array(
							'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $rec->no_perkiraan, 'keterangan' => $keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' => $bank_nilai[$keys], 'kredit' => 0, 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
						);
						if ($bank_admin[$keys] > 0) {
							$rec = $this->db->query("select * from " . DBACC . ".master_oto_jurnal_detail where kode_master_jurnal='" . $jenis_jurnal . "' and menu='admin'")->row();
							$det_Jurnaltes1[] = array(
								'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $rec->no_perkiraan, 'keterangan' => $keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' =>  $bank_admin[$keys], 'kredit' => 0, 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
							);
						}
					}

					if ($tipe[$keys] == 'expense') {
						$ketpetty='';
						$recpc = $this->db->query("select * from " . DBERP . ".tr_expense where no_doc='" . $no_doc[$keys] . "'")->row();
						$ketpetty=$recpc->pettycash.' ';
						$rec = $this->db->query("select * from " . DBERP . ".tr_expense_detail where no_doc='" . $no_doc[$keys] . "' and status = '1'")->result();
						// $rec = $this->db->get_where('payment_approve_details', ['payment_id' => $val])->result();
						$this->db->update('tr_expense_detail', ['status' => '2'], ['no_doc' => $no_doc[$keys], 'status' => '1']);
						foreach ($rec as $record) {
							if ($record->id_kasbon != '') {
								$det_Jurnaltes1[] = array(
									'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $record->coa, 'keterangan' => $ketpetty.$keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' => 0, 'kredit' => $record->kasbon, 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
								);
							} else {
								$det_Jurnaltes1[] = array(
									'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $record->coa, 'keterangan' => $ketpetty.$keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' => $record->expense, 'kredit' => 0, 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
								);
							}
						}
						if($recpc->no_so !=""){
							$datadeferred = array(
								'no_so'        	=> $recpc->no_so,
								'tanggal'     	=> $payment_date,
								'no_reff'     	=> $recpc->no_doc,
								'tipe'		 	=> 'expense',
								'qty'	 		=> 1,
								'amount' 		=> $recpc->expense,
								'id_material'	=> "",
								'nm_material'	=> "",
								'keterangan'	=> $ketpetty,
								'kode_trans'	=> $recpc->no_doc
							);
							$this->db->insert(DBERP . '.tr_deferred', $datadeferred);
						}

						if ($bank_admin[$keys] > 0) {
							$rec = $this->db->query("select * from " . DBACC . ".master_oto_jurnal_detail where kode_master_jurnal='" . $jenis_jurnal . "' and menu='admin'")->row();
							$det_Jurnaltes1[] = array(
								'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $rec->no_perkiraan, 'keterangan' => $keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' =>  $bank_admin[$keys], 'kredit' => 0, 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
							);
						}

						if($recpc->add_ppn_nilai > 0){
							//ppn coa
							$det_Jurnaltes1[] = array(
								'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $recpc->add_ppn_coa, 'keterangan' => $ketpetty, 'no_request' => $recpc->no_doc, 'debet' =>  $recpc->add_ppn_nilai, 'kredit' =>0, 'no_reff' =>  $recpc->no_doc, 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
							);
						}
						if($recpc->add_pph_nilai > 0){
							//pph coa
							$det_Jurnaltes1[] = array(
								'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $recpc->add_pph_coa, 'keterangan' => $ketpetty, 'no_request' => $recpc->no_doc, 'debet' =>  0, 'kredit' =>$recpc->add_pph_nilai, 'no_reff' =>  $recpc->no_doc, 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
							);
						}
					}

					if ($tipe[$keys] == 'nonpo') {
						$rec = $this->db->query("select * from " . DBACC . ".master_oto_jurnal_detail where kode_master_jurnal='" . $jenis_jurnal . "' and menu='" . $tipe[$keys] . "'")->row();
						$det_Jurnaltes1[] = array(
							'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $rec->no_perkiraan, 'keterangan' => $keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' => $bank_nilai[$keys], 'kredit' => 0, 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
						);
						if ($bank_admin[$keys] > 0) {
							$rec = $this->db->query("select * from " . DBACC . ".master_oto_jurnal_detail where kode_master_jurnal='" . $jenis_jurnal . "' and menu='admin'")->row();
							$det_Jurnaltes1[] = array(
								'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $rec->no_perkiraan, 'keterangan' => $keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' =>  $bank_admin[$keys], 'kredit' => 0, 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
							);
						}
					}

					if ($tipe[$keys] == 'periodik') {
						$rec = $this->db->query("select coa from " . DBERP . ".tr_pengajuan_rutin_detail where id='" . $ids[$keys] . "' and no_doc='" . $no_doc[$keys] . "'")->row();
						$det_Jurnaltes1[] = array(
							'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $rec->coa, 'keterangan' => $keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' => $bank_nilai[$keys], 'kredit' => 0, 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
						);
						if ($bank_admin[$keys] > 0) {
							$rec = $this->db->query("select * from " . DBACC . ".master_oto_jurnal_detail where kode_master_jurnal='" . $jenis_jurnal . "' and menu='admin'")->row();
							$det_Jurnaltes1[] = array(
								'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $rec->no_perkiraan, 'keterangan' => $keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' =>  $bank_admin[$keys], 'kredit' => 0, 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
							);
						}
					}


					//bank coa
					$det_Jurnaltes1[] = array(
						'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $bank_coa, 'keterangan' => $keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' => ($bank_nilai[$keys] < 0 ? ($bank_nilai[$keys] * -1) : 0), 'kredit' => ($bank_nilai[$keys] >= 0 ? $bank_nilai[$keys] : 0), 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
					);
					if ($bank_admin[$keys] > 0) {
						$rec = $this->db->query("select * from " . DBACC . ".master_oto_jurnal_detail where kode_master_jurnal='" . $jenis_jurnal . "' and menu='admin'")->row();
						$det_Jurnaltes1[] = array(
							'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUK', 'no_perkiraan' => $bank_coa, 'keterangan' => $keterangan[$keys], 'no_request' => $no_doc[$keys], 'debet' => 0, 'kredit' => $bank_admin[$keys], 'no_reff' =>  $no_doc[$keys], 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $nama[$keys], 'stspos' => '1'
						);
					}
				}
			}
			$this->db->insert_batch(DBERP . '.jurnaltras', $det_Jurnaltes1);
		}
		//autojurnal
		$dataJURNAL = $this->db->query("select * from " . DBERP . ".jurnaltras where nomor='" . $nomor_jurnal . "' order by kredit,debet,no_perkiraan")->result();
		$Bln		= substr($payment_date, 5, 2);
		$Thn		= substr($payment_date, 0, 4);
		$Nomor_JV	= $this->Jurnal_model->get_no_buk('101');
		$total	= 0;
		foreach($dataJURNAL as $keys){
			$datadetail = array(
				'tipe'        	=> 'BUK',
				'nomor'       	=> $Nomor_JV,
				'tanggal'     	=> $payment_date,
				'no_reff'     	=> $keys->no_reff,
				'no_perkiraan'	=> $keys->no_perkiraan,
				'keterangan' 	=> $keys->keterangan,
				'debet' 		=> $keys->debet,
				'kredit' 		=> $keys->kredit
			);
			$this->db->insert(DBACC . '.jurnal', $datadetail);
			$total=($total+$keys->debet);
		}
		$keterangan	= 'Payment';
		$dataJVhead = array(
			'nomor' 	    	=> $Nomor_JV,
			'tgl'	         	=> $payment_date,
			'jml'	            => $total,
			'kdcab'				=> '101',
			'jenis_reff'	    => 'BUK',
			'no_reff' 		    => '',
			'jenis_ap'			=> 'V',
			'note'				=> $keterangan,
			'user_id'			=> $UserName,
			'ho_valid'			=> '',
			'batal'			    => '0'
		);
		$this->db->insert(DBACC . '.japh', $dataJVhead);
		$Qry_Update_Cabang_acc	 = "UPDATE " . DBACC . ".pastibisa_tb_cabang SET nobuk=nobuk + 1 WHERE nocab='101'";
		$this->db->query($Qry_Update_Cabang_acc);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$result = false;
		} else {
			$this->db->trans_commit();
			$result = true;
		}
		$param = array(
			'save' => $result
		);
		echo json_encode($param);
	}

	public function payment_jurnal_list()
	{
		$controller			= 'request_payment/payment_jurnal_list';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$menu_akses	= $this->master_model->getMenu();
		$results = $this->Request_payment_model->GetListDataJurnal("BUK030");

		$data = array(
			'title'			=> 'Index Of Jurnal',
			'action'		=> 'index',
			'results'		=> $results,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Payment Jurnal');
		$this->load->view('Request_payment/list_jurnal',$data);
	}
	public function view_jurnal($id)
	{
		$controller			= 'request_payment/payment_jurnal_list';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['update'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_session			= $this->session->userdata;
		$data = $this->db->query("select * from " . DBERP . ".jurnaltras where nomor='" . $id . "' order by kredit,debet,no_perkiraan")->result();
		$data_coa = $this->All_model->GetCoaCombo();

		$data = array(
			'title'			=> 'Submit Jurnal',
			'action'		=> 'index',
			'data'	    	=> $data,
			'datacoa'	    => $data_coa,
			'status'	    => 'view',
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Request_payment/form_jurnal',$data);
	}
	public function edit_jurnal($id)
	{
		$controller			= 'request_payment/payment_jurnal_list';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['update'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_session			= $this->session->userdata;
		$data = $this->db->query("select * from " . DBERP . ".jurnaltras where nomor='" . $id . "' order by kredit,debet,no_perkiraan")->result();
		$data_coa = $this->All_model->GetCoaCombo();

		$data = array(
			'title'			=> 'Submit Jurnal',
			'action'		=> 'index',
			'data'	    	=> $data,
			'datacoa'	    => $data_coa,
			'status'	    => 'edit',
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Request_payment/form_jurnal',$data);
	}
	public function jurnal_save()
	{
		$id = $this->input->post("id");
		$no_perkiraan = $this->input->post("no_perkiraan");
		$keterangan = $this->input->post("keterangan");
		$debet = $this->input->post("debet");
		$kredit = $this->input->post("kredit");

		$tanggal		= $this->input->post('tanggal');
		$tipe			= $this->input->post('tipe');
		$no_reff        = $this->input->post('no_reff');
		$no_request		= $this->input->post('no_request');
		$jenis_jurnal	= $this->input->post('jenis_jurnal');
		$nocust         = $this->input->post('nocust');
		$total			= 0;
		$total_po		= $this->input->post('total_po');
//		$tanggal		= date("Y-m-d");
		$Bln 			= substr($tanggal, 5, 2);
		$Thn 			= substr($tanggal, 0, 4);
		$Nomor_JV = $this->Jurnal_model->get_no_buk('101');
		$session = $this->session->userdata('app_session');

		$data_session			= $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['id_user'];

		$this->db->trans_begin();
		for ($i = 0; $i < count($id); $i++) {
			$dataheader =  array(
				'stspos' => "1",
				'no_perkiraan' => $no_perkiraan[$i],
				'keterangan' => $keterangan[$i],
				'debet' => $debet[$i],
				'kredit' => $kredit[$i]
			);
			$total = ($total + $debet[$i]);
			$this->All_model->DataUpdate(DBERP . '.jurnaltras', $dataheader, array('id' => $id[$i]));

			$datadetail = array(
				'tipe'        	=> $tipe,
				'nomor'       	=> $Nomor_JV,
				'tanggal'     	=> $tanggal,
				'no_reff'     	=> $no_reff,
				'no_perkiraan'	=> $no_perkiraan[$i],
				'keterangan' 	=> $keterangan[$i],
				'debet' 		=> $debet[$i],
				'kredit' 		=> $kredit[$i]
			);
			$this->db->insert(DBACC . '.jurnal', $datadetail);
		}

		$keterangan	= 'Payment';
		$dataJVhead = array(
			'nomor' 	    	=> $Nomor_JV,
			'tgl'	         	=> $tanggal,
			'jml'	            => $total,
			'kdcab'				=> '101',
			'jenis_reff'	    => 'BUK',
			'no_reff' 		    => $no_reff,
			'jenis_ap'			=> 'V',
			'note'				=> $keterangan,
			'user_id'			=> $UserName,
			'ho_valid'			=> '',
			'batal'			    => '0'
		);
		$this->db->insert(DBACC . '.japh', $dataJVhead);
		$Qry_Update_Cabang_acc	 = "UPDATE " . DBACC . ".pastibisa_tb_cabang SET nobuk=nobuk + 1 WHERE nocab='101'";
		$this->db->query($Qry_Update_Cabang_acc);

		$this->db->trans_complete();
		if ($this->db->trans_status()) {
			$this->db->trans_commit();
			$result         = TRUE;
		} else {
			$this->db->trans_rollback();
			$result = FALSE;
		}
		$param = array(
			'save' => $result
		);
		echo json_encode($param);
	}
}