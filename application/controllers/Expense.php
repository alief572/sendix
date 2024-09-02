<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('master_model');
		$this->load->model('expense_model');
		$this->load->model('All_model');
		$this->load->model('Jurnal_model');
        $this->status=array("0"=>"Baru","1"=>"Disetujui","2"=>"Disetujui","3"=>"Selesai","9"=>"Reject");
		// Your own constructor code
		if(!$this->session->userdata('isORIlogin')){
			redirect('login');
		}
	}

	// list
    public function index() {
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_session		= $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['id_user'];
		$data = $this->expense_model->GetListData(array('nama'=>$UserName,'pettycash'=>null));

		$data = array(
			'title'			=> 'Expense Report',
			'action'		=> 'index',
			'status'	    => $this->status,
			'results'	    => $data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data expense');
		$this->load->view('Expense/expense',$data);
    }

	// create
	public function create(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['create'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_budget = $this->All_model->GetExpenseCombo();
		$data_pc = $this->All_model->GetPettyCashCombo();
		$data_user = $this->All_model->GetUserCombo();
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data_coa = $this->All_model->GetCoaCombo('5'," a.no_perkiraan like '1101%'");
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
								->where('a.id_bq <>','x')
								->get()
								->result_array();
		$combo_coa_pph=$this->All_model->Getcomboparamcoa('pph_pembelian');
		$data = array(
			'title'			=> 'Expense Report',
			'action'		=> 'index',
			'data_budget'	=> $data_budget,
			'data_coa'		=> $data_coa,
			'data_pc'	    => $data_pc,
			'data_user'	    => $data_user,
			'combodept'		=> $combodept,
			'combo_so'		=> $so_number,
			'combo_coa_pph'	=> $combo_coa_pph,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/expense_add',$data);
	}

	// edit
	public function edit($id){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['update'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_session			= $this->session->userdata;
		$data = $this->expense_model->GetDataHeader($id);
		$data_detail	= $this->expense_model->GetDataDetail($data->no_doc);
		$data_departement= $this->All_model->GetDeptCombo();
		$data_budget = $this->All_model->GetExpenseCombo();
		$data_user= $this->All_model->GetUserCombo();
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data_coa = $this->All_model->GetCoaCombo('5'," a.no_perkiraan like '1101%'");
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
								->where('a.id_bq <>','x')
								->get()
								->result_array();
		$combo_coa_pph=$this->All_model->Getcomboparamcoa('pph_pembelian');
		$data = array(
			'title'			=> 'Edit Expense Report',
			'action'		=> 'index',
			'data_coa'		=> $data_coa,
			'data_user'	    => $data_user,
			'data_budget'	=> $data_budget,
			'data_departement'	=> $data_departement,
			'data_detail'	=> $data_detail,
			'approval'	    => $data_session['ORI_User']['id_user'],
			'data'	    	=> $data,
			'stsview'	    => '',
			'combodept'		=> $combodept,
			'status'	    => $this->status,
			'combo_so'		=> $so_number,
			'combo_coa_pph'	=> $combo_coa_pph,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/expense_add',$data);
	}
	// review
	public function review($id){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		$data = $this->expense_model->GetDataHeader($id);
		$data_detail	= $this->expense_model->GetDataDetail($data->no_doc);
		$data_departement= $this->All_model->GetDeptCombo();
		$data_budget = $this->All_model->GetCoaCombo('5');
		$data_user= $this->All_model->GetUserCombo();
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data_coa = $this->All_model->GetCoaCombo('5'," a.no_perkiraan like '1101%'");
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
								->where('a.id_bq <>','x')
								->get()
								->result_array();
		$combo_coa_pph=$this->All_model->Getcomboparamcoa('pph_pembelian');
		$data = array(
			'title'			=> 'View Expense Report',
			'action'		=> 'index',
			'data_user'	    => $data_user,
			'data_coa'		=> $data_coa,
			'data_budget'	=> $data_budget,
			'data_departement'	=> $data_departement,
			'data_detail'	=> $data_detail,
			'data'	    	=> $data,
			'stsview'	    => 'review',
			'combodept'		=> $combodept,
			'status'	    => $this->status,
			'combo_so'		=> $so_number,
			'combo_coa_pph'	=> $combo_coa_pph,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/expense_review',$data);
	}
	// view
	public function view($id){
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data = $this->expense_model->GetDataHeader($id);
		$data_detail	= $this->expense_model->GetDataDetail($data->no_doc);
		$data_departement= $this->All_model->GetDeptCombo();
		$data_budget = $this->All_model->GetCoaCombo('5');
		$data_user= $this->All_model->GetUserCombo();
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data_coa = $this->All_model->GetCoaCombo('5'," a.no_perkiraan like '1101%'");
		$combo_coa_pph=$this->All_model->Getcomboparamcoa('pph_pembelian');
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
								->where('a.id_bq <>','x')
								->get()
								->result_array();
		$data = array(
			'title'			=> 'View Expense Report',
			'action'		=> 'index',
			'data_user'	    => $data_user,
			'data_coa'		=> $data_coa,
			'data_budget'	=> $data_budget,
			'data_departement'	=> $data_departement,
			'data_detail'	=> $data_detail,
			'data'	    	=> $data,
			'stsview'	    => 'view',
			'combodept'		=> $combodept,
			'status'	    => $this->status,
			'combo_so'		=> $so_number,
			'combo_coa_pph'	=> $combo_coa_pph,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/expense_add',$data);
	}
    public function list_expense_approval() {
		$controller			= 'expense/list_expense_approval';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_session			= $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['id_user'];
		$dept = $data_session['ORI_User']['department_id'];
		$data = $this->expense_model->GetListData(array("status"=>0, "departement"=>$dept));

		$data = array(
			'title'			=> 'Approval Expense Report',
			'action'		=> 'index',
			'status'	    => $this->status,
			'results'	    => $data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data expense');
		$this->load->view('Expense/index_approval',$data);
    }
	public function approval($id){
		$controller			= 'expense/list_expense_approval';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['update'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_session			= $this->session->userdata;
		$data = $this->expense_model->GetDataHeader($id);
		$data_detail	= $this->expense_model->GetDataDetail($data->no_doc);
		$data_departement= $this->All_model->GetDeptCombo();
		$data_budget = $this->All_model->GetCoaCombo('5');
		$data_user= $this->All_model->GetUserCombo();
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data_coa = $this->All_model->GetCoaCombo('5'," a.no_perkiraan like '1101%'");
		$combo_coa_pph=$this->All_model->Getcomboparamcoa('pph_pembelian');
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
                               	->where('a.id_bq <>','x')
								->get()
								->result_array();
		$data = array(
			'title'			=> 'Approval Expense Report',
			'action'		=> 'index',
			'data_user'	    => $data_user,
			'data_budget'	=> $data_budget,
			'data_departement'	=> $data_departement,
			'data_detail'	=> $data_detail,
			'approval'	    => $data_session['ORI_User']['id_user'],
			'data_coa'		=> $data_coa,
			'data'	    	=> $data,
			'stsview'	    => 'approval',
			'combodept'		=> $combodept,
			'status'	    => $this->status,
			'combo_so'		=> $so_number,
			'combo_coa_pph'	=> $combo_coa_pph,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/expense_add',$data);
	}
	// approve
	public function approve($id=''){
		$result=false;
		$data_session			= $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['username'];
        if($id!="") {
			$data = array(
						array(
							'id'=>$id,
							'status'=>1,
							'st_reject'=>'',
							'approved_by'=> $UserName,
							'approved_on'=>date("Y-m-d h:i:s")
						)
					);
			$results = $this->db->update_batch('tr_expense',$data,'id');
			if(is_numeric($results)) {
                $result         = TRUE;
            } else {
                $result = FALSE;
            }
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
			history('Approve data expense : '.$id);
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}
	// reject
	public function reject(){
		$result=false;
		$data_session = $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['username'];
        $id		= $this->input->post("id");
		$reason	= $this->input->post("reason");
        if($id!="") {
			$data = array(
						array(
							'id'=>$id,
							'status'=>9,
							'st_reject'=>$reason,
							'approved_by'=> $UserName,
							'approved_on'=>date("Y-m-d h:i:s")
						)
					);
			$results = $this->db->update_batch('tr_expense',$data,'id');
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
                $result = FALSE;
			} else {
				$this->db->trans_commit();
                $result	= TRUE;
			}
			history('Reject data expense : '.$id);
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}

	public function save(){
		$data_session	= $this->session->userdata;
        $id             = $this->input->post("id");
		$tgl_doc  		= $this->input->post("tgl_doc");
        $no_doc		    = $this->input->post("no_doc");
        $departement	= $this->input->post("departement");
        $nama			= $this->input->post("nama");
        $approval		= $this->input->post("approval");
        $informasi		= $this->input->post("informasi");
        $bank_id		= $this->input->post("bank_id");
        $accnumber		= $this->input->post("accnumber");
        $accname		= $this->input->post("accname");
        $pettycash		= $this->input->post("pettycash");
        $no_so			= $this->input->post("no_so");

        $transfer_coa_bank	= $this->input->post("transfer_coa_bank");
        $transfer_tanggal	= $this->input->post("transfer_tanggal");
        $transfer_jumlah	= $this->input->post("transfer_jumlah");
        $transferfile		= $this->input->post("transferfile");

        $add_ppn_nilai	= $this->input->post("add_ppn_nilai");
        $add_ppn_coa	= $this->input->post("add_ppn_coa");
        $add_pph_nilai	= $this->input->post("add_pph_nilai");
        $add_pph_coa	= $this->input->post("add_pph_coa");

        $coa			= $this->input->post("coa");
        $detail_id		= $this->input->post("detail_id");
        $deskripsi		= $this->input->post("deskripsi");
        $spesifikasi	= $this->input->post("spesifikasi");
        $qty			= $this->input->post("qty");
        $harga			= $this->input->post("harga");
        $kasbon			= $this->input->post("kasbon");
        $expense		= $this->input->post("expense");
        $tanggal		= $this->input->post("tanggal");
        $keterangan		= $this->input->post("keterangan");
        $filename		= $this->input->post("filename");
        $id_kasbon		= $this->input->post("id_kasbon");
        $grand_total	= $this->input->post("grand_total");
        $kasbon_max		= $this->input->post("kasbon_max");

		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['username'];

		$this->db->trans_begin();

		$config['upload_path'] = './assets/expense/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|pdf';
		$config['remove_spaces'] = TRUE;
		$config['encrypt_name'] = TRUE;
		$transfer_file=$transferfile;
		if(!empty($_FILES['transfer_file']['name'])){
			$_FILES['file']['name'] = $_FILES['transfer_file']['name'];
			$_FILES['file']['type'] = $_FILES['transfer_file']['type'];
			$_FILES['file']['tmp_name'] = $_FILES['transfer_file']['tmp_name'];
			$_FILES['file']['error'] = $_FILES['transfer_file']['error'];
			$_FILES['file']['size'] = $_FILES['transfer_file']['size'];
			$this->load->library('upload',$config);
			$this->upload->initialize($config);
			if($this->upload->do_upload('file')){
				$uploadData = $this->upload->data();
				$transfer_file = $uploadData['file_name'];
			}
		}
		if($id!="") {
			$data = array(
						'tgl_doc'=>$tgl_doc,
						'status'=>0,
						'add_ppn_nilai'=>$add_ppn_nilai,
						'add_ppn_coa'=>$add_ppn_coa,
						'add_pph_nilai'=>$add_pph_nilai,
						'add_pph_coa'=>$add_pph_coa,
//						'coa'=>$coa,
						'no_so'=>$no_so,
						'jumlah'=>$grand_total,
						'informasi'=>$informasi,
						'bank_id'=>$bank_id,
						'accnumber'=>$accnumber,
						'departement'=>$departement,
						'accname'=>$accname,
						'transfer_coa_bank'=>$transfer_coa_bank,
						'transfer_tanggal'=>$transfer_tanggal,
						'transfer_jumlah'=>$transfer_jumlah,
						'transfer_file'=>$transfer_file,
						'pettycash'=>$pettycash,
						'modified_by'=> $UserName,
						'modified_on'=>$dateTime
					);
			$result = $this->All_model->dataUpdate(DBERP.'.tr_expense',$data,array('id'=>$id));
			$data_expense_kasbon = $this->db->query("select kasbon,id_kasbon from ".DBERP.".tr_expense_detail where no_doc ='".$no_doc."' and kasbon<>0 ")->result();
			if(!empty($data_expense_kasbon)){
				foreach($data_expense_kasbon AS $record){
					if($record->kasbon<>0){
						$this->db->query("update tr_kasbon set jumlah_expense=(jumlah_expense-".$record->kasbon.") where no_doc='".$record->id_kasbon."'");
					}
				}
			}
			$this->All_model->dataDelete(DBERP.'.tr_expense_detail',array('no_doc'=>$no_doc));
			if(!empty($detail_id)){
				foreach ($detail_id as $keys => $val){
					$no_doc = $no_doc;
					if($qty[$keys]>0) {
						$config['upload_path'] = './assets/expense/';
						$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|pdf|doc|docx|jfif';
						$config['remove_spaces'] = TRUE;
						$config['encrypt_name'] = TRUE;
						$filenames=$filename[$keys];
						if(!empty($_FILES['doc_file_'.$val]['name'])){
							$_FILES['file']['name'] = $_FILES['doc_file_'.$val]['name'];
							$_FILES['file']['type'] = $_FILES['doc_file_'.$val]['type'];
							$_FILES['file']['tmp_name'] = $_FILES['doc_file_'.$val]['tmp_name'];
							$_FILES['file']['error'] = $_FILES['doc_file_'.$val]['error'];
							$_FILES['file']['size'] = $_FILES['doc_file_'.$val]['size'];
							$this->load->library('upload',$config);
							$this->upload->initialize($config);
							if($this->upload->do_upload('file')){
								$uploadData = $this->upload->data();
								$filenames = $uploadData['file_name'];
							}
						}
						if($kasbon[$keys]<>0){
							$this->db->query("update tr_kasbon set jumlah_expense=(jumlah_expense+".$kasbon[$keys].") where no_doc='".$id_kasbon[$keys]."'");
						}
						$data_detail =  array(
								'no_doc'=>$no_doc,
								'deskripsi'=>$deskripsi[$keys],
								'qty'=>$qty[$keys],
								'harga'=>$harga[$keys],
								'total_harga'=>($qty[$keys]*$harga[$keys]),
								'kasbon'=>$kasbon[$keys],
								'expense'=>$expense[$keys],
								'tanggal'=>$tanggal[$keys],
								'keterangan'=>$keterangan[$keys],
								'coa'=>$coa[$keys],
								'doc_file'=>$filenames,
								'id_kasbon'=>$id_kasbon[$keys],
								'kasbon_max'=>$kasbon_max[$keys],
								'created_by'=> $UserName,
								'created_on'=>$dateTime,
								'modified_by'=> $UserName,
								'modified_on'=>$dateTime
							);
						$this->All_model->dataSave(DBERP.'.tr_expense_detail',$data_detail);
					}
				}
			}
			$keterangan     = "SUKSES, Edit data ".$id;
			$status         = 1; $nm_hak_akses   = ""; $kode_universal = $id; $jumlah = 1;
			$sql            = $this->db->last_query();
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
        } else {
			$no_doc=$this->All_model->GetAutoGenerate('format_expense');
            $data =  array(
						'no_doc'=>$no_doc,
						'tgl_doc'=>$tgl_doc,
						'departement'=>$departement,
						'add_ppn_nilai'=>$add_ppn_nilai,
						'add_ppn_coa'=>$add_ppn_coa,
						'add_pph_nilai'=>$add_pph_nilai,
						'add_pph_coa'=>$add_pph_coa,
//						'coa'=>$coa,
						'no_so'=>$no_so,
						'nama'=>$nama,
						'informasi'=>$informasi,
						'bank_id'=>$bank_id,
						'accnumber'=>$accnumber,
						'accname'=>$accname,
						'pettycash'=>$pettycash,
						'approval'=>$approval,
						'status'=>0,
						'jumlah'=>$grand_total,
						'transfer_coa_bank'=>$transfer_coa_bank,
						'transfer_tanggal'=>$transfer_tanggal,
						'transfer_jumlah'=>$transfer_jumlah,
						'transfer_file'=>$transfer_file,
						'created_by'=> $UserName,
						'created_on'=>$dateTime,
						'modified_by'=> $UserName,
						'modified_on'=>$dateTime
					);
            $id = $this->All_model->dataSave(DBERP.'.tr_expense',$data);
			// update budget
//			$this->expense_model->Update_budget($id_type,$tgl_doc,$total,$divisi);
			if(!empty($detail_id)){
				foreach ($detail_id as $keys => $val){
					$no_doc			= $no_doc;
					if($qty[$keys]>0) {
						$config['upload_path'] = './assets/expense/';
						$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|pdf|doc|docx|jfif';
						$config['remove_spaces'] = TRUE;
						$config['encrypt_name'] = TRUE;
						$filenames="";
						if(!empty($_FILES['doc_file_'.$val]['name'])){
							$_FILES['file']['name'] = $_FILES['doc_file_'.$val]['name'];
							$_FILES['file']['type'] = $_FILES['doc_file_'.$val]['type'];
							$_FILES['file']['tmp_name'] = $_FILES['doc_file_'.$val]['tmp_name'];
							$_FILES['file']['error'] = $_FILES['doc_file_'.$val]['error'];
							$_FILES['file']['size'] = $_FILES['doc_file_'.$val]['size'];
							$this->load->library('upload',$config);
							$this->upload->initialize($config);
							if($this->upload->do_upload('file')){
								$uploadData = $this->upload->data();
								$filenames = $uploadData['file_name'];
							}
						}
						if($kasbon[$keys]<>0){
							$this->db->query("update tr_kasbon set jumlah_expense=(jumlah_expense+".$kasbon[$keys].") where no_doc='".$id_kasbon[$keys]."'");
						}
						$data_detail =  array(
								'no_doc'=>$no_doc,
								'deskripsi'=>$deskripsi[$keys],
								'qty'=>$qty[$keys],
								'harga'=>$harga[$keys],
								'total_harga'=>($qty[$keys]*$harga[$keys]),
								'kasbon'=>$kasbon[$keys],
								'kasbon_max'=>$kasbon_max[$keys],
								'expense'=>$expense[$keys],
								'tanggal'=>$tanggal[$keys],
								'keterangan'=>$keterangan[$keys],
								'doc_file'=>$filenames,
								'id_kasbon'=>$id_kasbon[$keys],
								'coa'=>$coa[$keys],
								'created_by'=> $UserName,
								'created_on'=>$dateTime
							);
						$this->All_model->dataSave(DBERP.'.tr_expense_detail',$data_detail);
					}
				}
			}
            if(is_numeric($id)) {
                $result	= TRUE;
            } else {
                $result = FALSE;
            }
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}
	public function expense_print($id){
		$response = $this->expense_model->GetDataHeader($id);
		$data_detail	= $this->expense_model->GetDataDetail($response->no_doc);
		$data = array(
			'status'		=> $this->status,
			'data_detail'	=> $data_detail,
			'data'			=> $response,
		);

		$this->load->view('Expense/expense_print',$data);
//		$show = $this->load->view('Expense/expense_print',$data);
/*
		require_once(APPPATH.'libraries/MPDF57/mpdf.php');
//		$this->load->library(array('mpdf'));
//		$mpdf=new mPDF('','','','','','','','','','');
		$mpdf=new mPDF('utf-8','A4');
		$mpdf->SetImportUse();
		$mpdf->RestartDocTemplate();
		$mpdf->AddPage('P','A4','en');
		$mpdf->WriteHTML($show);
		foreach($data_detail as $record){
			if(strpos($record->doc_file,'pdf',0)>1){
				$pagecount = $mpdf->SetSourceFile(('assets/expense/'.$record->doc_file));
				$mpdf->AddPage();
				for ($i=1; $i<=$pagecount; $i++) {
					$import_page = $mpdf->ImportPage($i);
					$mpdf->UseTemplate($import_page);
					if ($i < $pagecount) $mpdf->AddPage();
				}
			}
		}
		$mpdf->Output();
*/
	}
	public function delete($id){
		$data = $this->expense_model->GetDataHeader($id);
		$data_expense_kasbon = $this->db->query("select kasbon,id_kasbon from ".DBERP.".tr_expense_detail where no_doc ='".$data->no_doc."' and kasbon<>0 ")->result();
		if(!empty($data_expense_kasbon)){
			foreach($data_expense_kasbon AS $record){
				if($record->kasbon<>0){
					$this->db->query("update tr_kasbon set jumlah_expense=(jumlah_expense-".$record->kasbon.") where no_doc='".$record->id_kasbon."'");
				}
			}
		}
        $results=$this->All_model->dataDelete('tr_expense',array('id'=>$id));
		$this->All_model->dataDelete(DBERP.'.tr_expense_detail',array('no_doc'=>$data->no_doc));
		if(is_numeric($results)) {
			$result         = TRUE;
		} else {
			$result = FALSE;
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
        $param = array( 'delete' => $result );
        echo json_encode($param);
	}
    public function list_expense_all() {
		$controller			= 'expense/list_expense_all';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_session			= $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['id_user'];
		$data = $this->expense_model->GetListData();

		$data = array(
			'title'			=> 'View Expense Report',
			'action'		=> 'index',
			'status'	    => $this->status,
			'results'	    => $data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data expense');
		$this->load->view('Expense/expense',$data);
    }
	// list petty_cash expense/petty_cash
    public function petty_cash() {
		$controller			= 'expense/petty_cash';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_session			= $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['id_user'];
		$data = $this->expense_model->GetListData(array('nama'=>$UserName,'pettycash != '=>''));

		$data = array(
			'title'			=> 'Expense Report Petty Cash',
			'action'		=> 'index',
			'status'	    => $this->status,
			'results'	    => $data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data expense pc');
		$this->load->view('Expense/index_pc',$data);
    }

	// create petty_cash
	public function create_pc(){
		$controller			= 'expense/petty_cash';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['create'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_budget = $this->All_model->GetComboBudget('','',date('Y'));
		$data_pc = $this->All_model->GetOneTable('ms_petty_cash','','nama');
		$data_user= $this->All_model->GetUserCombo();
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
								->where('a.id_bq <>','x')
								->get()
								->result_array();
		$combo_coa_pph=$this->All_model->Getcomboparamcoa('pph_pembelian');
		$data = array(
			'title'			=> 'Expense Report',
			'action'		=> 'index',
			'data_budget'	    => $data_budget,
			'data_pc'	    => $data_pc,
			'data_user'	    => $data_user,
			'combodept'		=> $combodept,
			'combo_so'		=> $so_number,
			'combo_coa_pph'	=> $combo_coa_pph,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/form_pc',$data);

	}

	// edit petty_cash
	public function edit_pc($id){
		$controller			= 'expense/petty_cash';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data = $this->expense_model->GetDataHeader($id);
		$data_detail	= $this->expense_model->GetDataDetail($data->no_doc);
		$data_pc = $this->All_model->GetOneTable('ms_petty_cash','','nama');
		$data_budget = $this->All_model->GetComboBudget('','',date('Y'));
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
								->where('a.id_bq <>','x')
								->get()
								->result_array();
		$combo_coa_pph=$this->All_model->Getcomboparamcoa('pph_pembelian');

		$data = array(
			'title'			=> 'View Expense Report',
			'action'		=> 'index',
			'data_budget'	    => $data_budget,
			'data_pc'	=> $data_pc,
			'data_detail'	    => $data_detail,
			'data'	    	=> $data,
			'combodept'		=> $combodept,
			'stsview'	    => '',
			'status'	    => $this->status,
			'combo_so'		=> $so_number,
			'combo_coa_pph'	=> $combo_coa_pph,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/form_pc',$data);

	}

	// view petty_cash
	public function view_pc($id){
		$controller			= 'expense/petty_cash';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data = $this->expense_model->GetDataHeader($id);
		$data_detail	= $this->expense_model->GetDataDetail($data->no_doc);
		$data_pc = $this->All_model->GetOneTable('ms_petty_cash','','nama');
		$data_budget = $this->All_model->GetComboBudget('','',date('Y'));
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
								->where('a.id_bq <>','x')
								->get()
								->result_array();
		$combo_coa_pph=$this->All_model->Getcomboparamcoa('pph_pembelian');
		$data = array(
			'title'			=> 'View Expense Report',
			'action'		=> 'index',
			'data_budget'	    => $data_budget,
			'data_pc'	=> $data_pc,
			'data_detail'	    => $data_detail,
			'data'	    	=> $data,
			'stsview'	    => 'view',
			'combodept'		=> $combodept,
			'status'	    => $this->status,
			'combo_so'		=> $so_number,
			'combo_coa_pph'	=> $combo_coa_pph,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/form_pc',$data);
	}
	function getcoabudget(){
		$coa=$this->input->post("coa");
		$coabudget=str_ireplace(";","','",$coa);
		$datacombocoa="";
		$data_budget = $this->db->query("select * from ".DBACC.".coa_master where no_perkiraan in ('".$coabudget."')")->result();
		foreach($data_budget as $keys){
			$datacombocoa.="<option value='".$keys->no_perkiraan."'>".$keys->no_perkiraan." - ".$keys->nama."</option>";
		}
		echo $datacombocoa;die();
	}
	//===========================================KASBON=================================================
    public function kasbon(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)))."/kasbon";
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_session = $this->session->userdata;
		$id_user = $data_session['ORI_User']['id_user'];
		$where=array('a.nama'=>$id_user);
		$data = $this->expense_model->GetListDataKasbon($where);

		$data = array(
			'title'			=> 'Kasbon',
			'action'		=> 'index',
			'status'	    => $this->status,
			'results'	    => $data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data kasbon');
		$this->load->view('Expense/Kasbon',$data);
	}
    public function list_kasbon_all(){
		$controller			= "expense/list_kasbon_all";
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data = $this->expense_model->GetListDataKasbon();

		$data = array(
			'title'			=> 'Kasbon',
			'action'		=> 'index',
			'status'	    => $this->status,
			'results'	    => $data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data kasbon');
		$this->load->view('Expense/Kasbon',$data);
	}

    public function kasbon_add(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)))."/kasbon";
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_departement= $this->master_model->GetDeptCombo();
		$data_user= $this->All_model->GetUserCombo();
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data_coa_kasbon = $this->All_model->GetCoaMsGenerateCombo('coa_kasbon');
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
								->where('a.id_bq <>','x')
								->get()
								->result_array();
		$data = array(
			'title'			=> 'Add Kasbon',
			'action'		=> 'index',
			'data_departement'	=> $data_departement,
			'data_user'	    => $data_user,
			'combodept'		=> $combodept,
			'akses_menu'	=> $Arr_Akses,
			'data_coa_kasbon'=> $data_coa_kasbon,
			'combo_so'=> $so_number,
			'stsview'=>''
		);
		$this->load->view('Expense/kasbon_add',$data);
	}

	public function kasbon_save(){
        $id             = $this->input->post("id");
		$tgl_doc  		= $this->input->post("tgl_doc");
        $no_doc		    = $this->input->post("no_doc");
        $departement	= $this->input->post("departement");
        $nama			= $this->input->post("nama");
        $keperluan		= $this->input->post("keperluan");
        $jumlah_kasbon	= $this->input->post("jumlah_kasbon");
        $filename		= $this->input->post("filename");
        $bank_id		= $this->input->post("bank_id");
        $accnumber		= $this->input->post("accnumber");
        $accname		= $this->input->post("accname");
        $filename2		= $this->input->post("filename2");
        $project		= $this->input->post("project");
        $coa		= $this->input->post("coa");
        $no_so		= $this->input->post("no_so");

		$this->db->trans_begin();
		$config['upload_path'] = './assets/expense/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|pdf';
		$config['remove_spaces'] = TRUE;
		$config['encrypt_name'] = TRUE;
		$filenames=$filename;
		if(!empty($_FILES['doc_file']['name'])){
			$_FILES['file']['name'] = $_FILES['doc_file']['name'];
			$_FILES['file']['type'] = $_FILES['doc_file']['type'];
			$_FILES['file']['tmp_name'] = $_FILES['doc_file']['tmp_name'];
			$_FILES['file']['error'] = $_FILES['doc_file']['error'];
			$_FILES['file']['size'] = $_FILES['doc_file']['size'];
			$this->load->library('upload',$config);
			$this->upload->initialize($config);
			if($this->upload->do_upload('file')){
				$uploadData = $this->upload->data();
				$filenames = $uploadData['file_name'];
			}
		}
		$filenames2=$filename2;
		if(!empty($_FILES['doc_file_2']['name'])){
			$_FILES['file']['name'] = $_FILES['doc_file_2']['name'];
			$_FILES['file']['type'] = $_FILES['doc_file_2']['type'];
			$_FILES['file']['tmp_name'] = $_FILES['doc_file_2']['tmp_name'];
			$_FILES['file']['error'] = $_FILES['doc_file_2']['error'];
			$_FILES['file']['size'] = $_FILES['doc_file_2']['size'];
			$this->load->library('upload',$config);
			$this->upload->initialize($config);
			if($this->upload->do_upload('file')){
				$uploadData2 = $this->upload->data();
				$filenames2 = $uploadData2['file_name'];
			}
		}
		$data_session = $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['username'];

		if($id!="") {
			$data = array(
					'tgl_doc'=>$tgl_doc,
					'departement'=>$departement,
					'keperluan'=>$keperluan,
					'project'=>$project,
					'coa'=>$coa,
					'no_so'=>$no_so,
					'nama'=>$nama,
					'jumlah_kasbon'=>$jumlah_kasbon,
					'doc_file'=>$filenames,
					'doc_file_2'=>$filenames2,
					'bank_id'=>$bank_id,
					'accnumber'=>$accnumber,
					'status'=>0,
					'accname'=>$accname,
					'modified_by'=> $UserName,
					'modified_on'=>date("Y-m-d h:i:s"),
				);
			$results=$this->All_model->dataUpdate('tr_kasbon',$data,array('id'=>$id));
			if(is_numeric($results)) {
                $result         = TRUE;
            } else {
                $result = FALSE;
            }
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
			history('Edit data kasbon : '.$id);
        } else {
//			$rec = $this->db->query("select no_perkiraan from ".DBACC.".master_oto_jurnal_detail where kode_master_jurnal='BUK030' and menu='kasbon'")->row();
			$no_doc=$this->All_model->GetAutoGenerate('format_kasbon');
            $data =  array(
						'no_doc'=>$no_doc,
						'tgl_doc'=>$tgl_doc,
						'departement'=>$departement,
						'keperluan'=>$keperluan,
						'nama'=>$nama,
						'jumlah_kasbon'=>$jumlah_kasbon,
						'doc_file'=>$filenames,
						'project'=>$project,
						'status'=>0,
						'doc_file_2'=>$filenames2,
						'coa'=>$coa,
						'no_so'=>$no_so,
						'bank_id'=>$bank_id,
						'accnumber'=>$accnumber,
						'accname'=>$accname,
						'created_by'=> $UserName,
						'created_on'=>date("Y-m-d h:i:s"),
					);
            $id = $this->All_model->dataSave('tr_kasbon',$data);
            if(is_numeric($id)) {
                $result         = TRUE;
            } else {
                $result = FALSE;
            }
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
			history('Add data kasbon : '.$id);
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}

	public function kasbon_edit($id){
		$controller			= ucfirst(strtolower($this->uri->segment(1)))."/kasbon";
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['update'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data = $this->expense_model->GetDataKasbon($id);
		$data_departement= $this->All_model->GetDeptCombo();
		$data_user= $this->All_model->GetUserCombo();
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data_coa_kasbon = $this->All_model->GetCoaMsGenerateCombo('coa_kasbon');
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
								->where('a.id_bq <>','x')
								->get()
								->result_array();

		$data = array(
			'title'			=> 'Edit Kasbon',
			'action'		=> 'index',
			'data_user'	    => $data_user,
			'data_departement'	=> $data_departement,
			'combodept'		=> $combodept,
			'status'	    => $this->status,
			'data'	    	=> $data,
			'data_coa_kasbon'=> $data_coa_kasbon,
			'combo_so'=> $so_number,
			'stsview'	    => '',
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/kasbon_add',$data);
	}
	public function kasbon_app($id){
		$controller			= "expense/kasbon_fin";
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['update'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data = $this->expense_model->GetDataKasbon($id);
		$data_departement= $this->All_model->GetDeptCombo();
		$data_user= $this->All_model->GetUserCombo();
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data_coa_kasbon = $this->All_model->GetCoaMsGenerateCombo('coa_kasbon');
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
								->where('a.id_bq <>','x')
								->get()
								->result_array();
		$data = array(
			'title'			=> 'Approval Kasbon',
			'action'		=> 'index',
			'data_user'	    => $data_user,
			'data_departement'	    => $data_departement,
			'status'	    => $this->status,
			'data'	    	=> $data,
			'combodept'		=> $combodept,
			'stsview'	    => 'approve',
			'data_coa_kasbon'=> $data_coa_kasbon,
			'combo_so'=> $so_number,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/kasbon_add',$data);
	}
	public function kasbon_view($id){
		$data = $this->expense_model->GetDataKasbon($id);
		$data_departement= $this->All_model->GetDeptCombo();
		$data_user= $this->All_model->GetUserCombo();
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data_coa_kasbon = $this->All_model->GetCoaMsGenerateCombo('coa_kasbon');
		$so_number = $this->db	->select('a.so_number, a.project')
								->from('so_number a')
								->where('a.id_bq <>','x')
								->get()
								->result_array();
		$data = array(
			'title'			=> 'Detail Kasbon',
			'action'		=> 'index',
			'data_user'	    => $data_user,
			'data_departement'	=> $data_departement,
			'status'	    => $this->status,
			'combodept'		=> $combodept,
			'data'	    	=> $data,
			'data_coa_kasbon'=> $data_coa_kasbon,
			'combo_so'=> $so_number,
			'stsview'	    => 'view'
		);
		$this->load->view('Expense/kasbon_add',$data);
	}
	public function kasbon_print($id){
		$results = $this->expense_model->GetDataKasbon($id);
		$combodept	= $this->db->query("select nm_dept from department where id='".$results->departement."'")->row();
		$data = array(
			'title'			=> 'Print Kasbon',
			'stsview'		=> 'print',
			'combodept'		=> $combodept,
			'data'			=> $results
		);
		$this->load->view('Expense/kasbon_print',$data);
	}

	// kasbon approval
	public function kasbon_fin(){
		$controller			= ucfirst(strtolower($this->uri->segment(1)))."/kasbon_fin";
		$Arr_Akses			= getAcccesmenu($controller);
		$data_session = $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['username'];
		$dept = $data_session['ORI_User']['department_id'];
		$datawhere=("a.status=0 and a.departement='".$dept."'");
		$data = $this->expense_model->GetListDataKasbon($datawhere);

		$data = array(
			'title'			=> 'Kasbon Approval',
			'action'		=> 'index',
			'status'	    => $this->status,
			'results'	    => $data,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/kasbon_list_fin',$data);
	}

	public function kasbon_approve($id=''){
		$result=false;
		$data_session = $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['username'];
        if($id!="") {
			$data = array(
						'id'=>$id,
						'status'=>1,
						'st_reject'=>'',
						'approved_by'=> $UserName,
						'approved_on'=>date("Y-m-d h:i:s"),
					);
			$results=$this->All_model->dataUpdate('tr_kasbon',$data,array('id'=>$id));
			if(is_numeric($results)) {
                $result         = TRUE;
            } else {
                $result = FALSE;
            }
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
			history('Approve data kasbon : '.$id);
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}
	public function kasbon_reject(){
		$result=false;
        $id		= $this->input->post("id");
		$reason	= $this->input->post("reason");
		$data_session = $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['username'];
        if($id!="") {
			$data = array(
						'id'=>$id,
						'status'=>9,
						'st_reject'=>$reason,
						'approved_by'=> $UserName,
						'approved_on'=>date("Y-m-d h:i:s"),
					);
			$results=$this->All_model->dataUpdate('tr_kasbon',$data,array('id'=>$id));
			if(is_numeric($results)) {
                $result         = TRUE;
            } else {
                $result = FALSE;
            }
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
			history('Reject data kasbon : '.$id);
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}
	public function kasbon_delete($id){
        $results=$this->All_model->dataDelete('tr_kasbon',array('id'=>$id));
		if(is_numeric($results)) {
			$result         = TRUE;
		} else {
			$result = FALSE;
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
			history('Delete data kasbon : '.$id);
		}
        $param = array( 'delete' => $result );
        echo json_encode($param);
	}

	public function get_kasbon($nama,$departement=''){
		// $data = $this->All_model->GetOneTable('tr_kasbon',array('nama'=>$nama,'status'=>'1'),'tgl_doc');//,'departement'=>$departement
		$data = $this->db->query("select * from tr_kasbon where nama='".$nama."' and (status<>0 and status<>9 and (jumlah_kasbon-jumlah_expense)<>0 ) and departement='".$departement."' order by tgl_doc")->result();
		// and no_doc not in (select id_kasbon from tr_expense_detail where id_kasbon IS NOT NULL) 
		echo json_encode($data);
		die();
	}

	// list management transport
    public function transport_req_mgt() {
		$data_session = $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['id_user'];
		$data = $this->expense_model->GetListDataTransportRequest($UserName);
        $this->template->set('results', $data);
        $this->template->set('status', $this->status);
		$this->template->page_icon('fa fa-list');
		$this->template->title('Persetujuan Managemen Penggantian Transport');
        $this->template->render('transport_req_mgt_list');
    }

	// list finance transport
    public function transport_req_fin() {
		$controller			= 'expense/transport_req_fin';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_session			= $this->session->userdata;
		$id_user = $data_session['ORI_User']['id_user'];
		$dept = $data_session['ORI_User']['department_id'];

		$data = $this->expense_model->GetListDataTransportRequest('',array('a.status'=>'0','departement'=>$dept));
		$data = array(
			'title'			=> 'Pengecekan Finance Penggantian Transport',
			'action'		=> 'index',
			'status'	    => $this->status,
			'results'	    => $data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data Transportasi');
		$this->load->view('Expense/transport_req_fin_list',$data);

    }
	// list pengajuan transport
    public function list_transport_req_all() {
		$controller			= 'expense/list_transport_req_all';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data = $this->expense_model->GetListDataTransportRequest();
		$data = array(
			'title'			=> 'Pengecekan Finance Penggantian Transport',
			'action'		=> 'index',
			'status'	    => $this->status,
			'results'	    => $data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data Transportasi');
		$this->load->view('Expense/transport_req_fin_list',$data);		
    }

	// list pengajuan transport
    public function transport_req() {
		$controller			= 'expense/transport_req';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_session			= $this->session->userdata;
		$id_user = $data_session['ORI_User']['id_user'];

		$data = $this->expense_model->GetListDataTransportRequest($id_user);
		$data = array(
			'title'			=> 'Pengajuan Penggantian Transport',
			'action'		=> 'index',
			'status'	    => $this->status,
			'results'	    => $data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data Transportasi');
		$this->load->view('Expense/transport_req_list',$data);
    }
	// transport pengajuan create
	public function transport_req_create(){
		$controller			= 'expense/transport_req';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['create'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
		}
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data = array(
			'title'			=> 'Pengajuan Penggantian Transport',
			'action'		=> 'index',
			'combodept'		=> $combodept,
			'mod'			=> '',
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/transport_req_form',$data);
	}

	// transport req save
	public function transport_req_save(){
        $id             = $this->input->post("id");
		$tgl_doc  		= $this->input->post("tgl_doc");
        $no_doc		    = $this->input->post("no_doc");
        $departement	= $this->input->post("departement");
        $nama			= $this->input->post("nama");
		$date1  		= $this->input->post("date1");
		$date2  		= $this->input->post("date2");
        $id_transport	= $this->input->post("id_transport");
        $jumlah_expense	= $this->input->post("jumlah_expense");
        $bank_id		= $this->input->post("bank_id");
        $accnumber		= $this->input->post("accnumber");
        $accname		= $this->input->post("accname");
		$coa	  		= $this->input->post("coa");

		$data_session = $this->session->userdata;
		$UserName = $data_session['ORI_User']['id_user'];

		$this->db->trans_begin();
        if($id!="") {
			$data = array(
					'tgl_doc'=>$tgl_doc,
					'departement'=>$departement,
					'nama'=>$nama,
					'date1'=>$date1,
					'date2'=>$date2,
					'bank_id'=>$bank_id,
					'accnumber'=>$accnumber,
					'accname'=>$accname,
					'coa'=>$coa,
					'status'=>0,
					'jumlah_expense'=>($jumlah_expense),
					'modified_by'=> $UserName,
					'modified_on'=>date("Y-m-d h:i:s")
				);
			$result=$this->All_model->dataUpdate(DBERP.'.tr_transport_req',$data,array('id'=>$id));
			$result=$this->All_model->dataUpdate(DBERP.'.tr_transport',array('no_req'=>'','status'=>'0'),array('no_req'=>$no_doc));
			if(!empty($id_transport)){
				foreach ($id_transport as $keys => $val){
					$result=$this->All_model->dataUpdate(DBERP.'.tr_transport',array('no_req'=>$no_doc,'status'=>'1'),array('id'=>$val));
				}
			}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
        } else {
			$no_doc=$this->All_model->GetAutoGenerate('format_transport_req');
            $data =  array(
					'no_doc'=>$no_doc,
					'tgl_doc'=>$tgl_doc,
					'departement'=>$departement,
					'nama'=>$nama,
					'date1'=>$date1,
					'date2'=>$date2,
					'jumlah_expense'=>($jumlah_expense),
					'status'=>0,
					'bank_id'=>$bank_id,
					'accnumber'=>$accnumber,
					'accname'=>$accname,
					'created_by'=> $UserName,
					'created_on'=>date("Y-m-d h:i:s"),
				);
            $id = $this->All_model->dataSave(DBERP.'.tr_transport_req',$data);
			if(!empty($id_transport)){
				foreach ($id_transport as $keys => $val){
					$result=$this->All_model->dataUpdate(DBERP.'.tr_transport',array('no_req'=>$no_doc,'status'=>'1'),array('id'=>$val));
				}
			}
            if(is_numeric($id)) {
                $result         = TRUE;
            } else {
                $result = FALSE;
            }
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}

	// transport req edit
	public function transport_req_apfin($id){
		$this->transport_req_edit($id,'_fin');
	}

	// transport req edit
	public function transport_req_edit($id,$mod=''){
		$controller			= 'expense/transport_req';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['create'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
		}
		$data = $this->expense_model->GetDataTransportReq($id);
		$data_detail = $this->expense_model->GetDataTransportInReq($data->no_doc);
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data = array(
			'title'			=> 'Expense Report',
			'action'		=> 'index',
			'mod'			=>  $mod,
			'status'		=>$this->status,
			'combodept'		=> $combodept,
			'data_detail'	=> $data_detail,
			'data'	=> $data,
			'stsview'	=> '',
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/transport_req_form',$data);

	}
	public function transport_req_print($id){
		$results = $this->expense_model->GetDataTransportReq($id);
		$data_detail = $this->expense_model->GetDataTransportInReq($results->no_doc);
		$data = array(
			'title'			=> 'Print Transportasi Request',
			'stsview'		=> 'print',
			'data_detail'	=> $data_detail,
			'data'			=> $results
		);
		$this->load->view('Expense/transport_req_print',$data);
	}
	// transport req view
	public function transport_req_view($id,$mod=''){
		$controller			= 'expense/transport_req';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['create'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
		}
		$data = $this->expense_model->GetDataTransportReq($id);
		$data_detail = $this->expense_model->GetDataTransportInReq($data->no_doc);
		$combodept	= $this->master_model->getArray('department',array(),'id','nm_dept');
		$data = array(
			'title'			=> 'Transportasi Request',
			'action'		=> 'index',
			'mod'			=>  $mod,
			'status'		=>$this->status,
			'data_detail'	=> $data_detail,
			'data'			=> $data,
			'combodept'		=> $combodept,
			'stsview'		=> 'view',
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/transport_req_form',$data);
	}
	public function transport_req_reject(){
		$result=false;
        $id		= $this->input->post("id");
		$reason	= $this->input->post("reason");
		$data_session = $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['username'];
        if($id!="") {
			$data = array(
						'status'=>9,
						'st_reject'=>$reason,
						'management_by'=> $UserName,
						'management_on'=>date("Y-m-d h:i:s"),
					);
			$results=$this->All_model->dataUpdate('tr_transport_req',$data,array('id'=>$id));
			if(is_numeric($results)) {
                $result         = TRUE;
            } else {
                $result = FALSE;
            }
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
			history('Reject data Transport Request : '.$id);
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}

	// list transport
    public function transport() {
		$controller			= ucfirst(strtolower($this->uri->segment(1)))."/transport";
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_session			= $this->session->userdata;
		$id_user = $data_session['ORI_User']['id_user'];
		$data = $this->expense_model->GetListDataTransport($id_user);
		$dt_status=array("0"=>"Baru","1"=>"Diajukan","2"=>"Disetujui Management","3"=>"Selesai");

		$data = array(
			'title'			=> 'Input Transportasi',
			'action'		=> 'index',
			'status'	    => $dt_status,
			'results'	    => $data,
			'akses_menu'	=> $Arr_Akses
		);
		history('View data Transportasi');
		$this->load->view('Expense/transport_list',$data);
    }

	// transport create
	public function transport_create(){
		$controller			= 'expense/transport';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['create'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
		}

		$data_departement= $this->All_model->GetDeptCombo();

		$data = array(
			'title'			=> 'Transportasi',
			'action'		=> 'index',
			'data_departement'	=> $data_departement,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/transport_form',$data);
	}

	// transport save
	public function transport_save(){
        $id             = $this->input->post("id");
		$tgl_doc  		= $this->input->post("tgl_doc");
        $no_doc		    = $this->input->post("no_doc");
        $departement	= $this->input->post("departement");
        $nama			= $this->input->post("nama");
        $keperluan		= $this->input->post("keperluan");
        $rute			= $this->input->post("rute");
        $nopol			= $this->input->post("nopol");
        $km_awal		= $this->input->post("km_awal");
        $km_akhir		= $this->input->post("km_akhir");
        $bensin			= $this->input->post("bensin");
        $tol			= $this->input->post("tol");
        $parkir			= $this->input->post("parkir");
        $filename		= $this->input->post("filename");
        $lainnya		= $this->input->post("lainnya");
        $keterangan		= $this->input->post("keterangan");

		$data_session = $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['username'];

		$this->db->trans_begin();
		$config['upload_path'] = './assets/expense/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|pdf|doc|docx|jfif|';
		$config['remove_spaces'] = TRUE;
		$config['encrypt_name'] = TRUE;
		$filenames=$filename;
		if(!empty($_FILES['doc_file']['name'])){
			$_FILES['file']['name'] = $_FILES['doc_file']['name'];
			$_FILES['file']['type'] = $_FILES['doc_file']['type'];
			$_FILES['file']['tmp_name'] = $_FILES['doc_file']['tmp_name'];
			$_FILES['file']['error'] = $_FILES['doc_file']['error'];
			$_FILES['file']['size'] = $_FILES['doc_file']['size'];
			$this->load->library('upload',$config);
			$this->upload->initialize($config);
			if($this->upload->do_upload('file')){
				$uploadData = $this->upload->data();
				$filenames = $uploadData['file_name'];
			}
		}
        if($id!="") {
			$data = array(
					'tgl_doc'=>$tgl_doc,
					'departement'=>$departement,
					'keperluan'=>$keperluan,
					'nama'=>$nama,
					'rute'=>$rute,
					'km_awal'=>$km_awal,
					'km_akhir'=>$km_akhir,
					'nopol'=>$nopol,
					'bensin'=>$bensin,
					'tol'=>$tol,
					'lainnya'=>$lainnya,
					'keterangan'=>$keterangan,
					'parkir'=>$parkir,
					'jumlah_kasbon'=>($bensin+$tol+$parkir+$lainnya),
					'doc_file'=>$filenames,
					'modified_by'=> $UserName,
					'modified_on'=>date("Y-m-d h:i:s")
				);
			$result=$this->All_model->dataUpdate(DBERP.'.tr_transport',$data,array('id'=>$id));
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
        } else {
			$no_doc=$this->All_model->GetAutoGenerate('format_transport');
            $data =  array(
					'no_doc'=>$no_doc,
					'tgl_doc'=>$tgl_doc,
					'departement'=>$departement,
					'keperluan'=>$keperluan,
					'nama'=>$nama,
					'rute'=>$rute,
					'km_awal'=>$km_awal,
					'km_akhir'=>$km_akhir,
					'nopol'=>$nopol,
					'bensin'=>$bensin,
					'tol'=>$tol,
					'parkir'=>$parkir,
					'lainnya'=>$lainnya,
					'keterangan'=>$keterangan,
					'jumlah_kasbon'=>($bensin+$tol+$parkir+$lainnya),
					'doc_file'=>$filenames,
					'status'=>0,
					'created_by'=> $UserName,
					'created_on'=>date("Y-m-d h:i:s"),
				);
            $id = $this->All_model->dataSave(DBERP.'.tr_transport',$data);
            if(is_numeric($id)) {
                $result = TRUE;
            } else {
                $result = FALSE;
            }
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}

	public function get_list_req_transport($nama,$date1,$date2){
		$data	= $this->db->query("SELECT * FROM tr_transport WHERE nama='".$nama."' and tgl_doc between '".$date1."' and '".$date2."' and (no_req ='' or no_req is null) order by tgl_doc")->result();// and departement='".$departement."'$departement,
		echo json_encode($data);
		die();
	}
	public function get_transport($nama,$departement){
        $data = $this->All_model->GetOneTable('tr_transport',array('nama'=>$nama,'departement'=>$departement,'status'=>'1'),'tgl_doc');
		echo json_encode($data);
		die();
	}

	// transport edit
	public function transport_edit($id){
		$controller			= 'expense/transport';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['update'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_session			= $this->session->userdata;
		$data = $this->expense_model->GetDataTransport($id);

		$data = array(
			'title'			=> 'Edit Transportasi',
			'action'		=> 'index',
			'data'	    	=> $data,
			'stsview'	    => '',
			'status'	    => $this->status,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/transport_form',$data);
	}

	// transport view
	public function transport_view($id){
		$controller			= 'expense/transport';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['update'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_session			= $this->session->userdata;
		$data = $this->expense_model->GetDataTransport($id);

		$data = array(
			'title'			=> 'View Transportasi',
			'action'		=> 'index',
			'data'	    	=> $data,
			'stsview'	    => 'view',
			'status'	    => $this->status,
			'akses_menu'	=> $Arr_Akses
		);
		$this->load->view('Expense/transport_form',$data);

	}

	// transport fin approve
	public function transport_req_approve($id='',$status){
		$data_session			= $this->session->userdata;
		$UserName = $data_session['ORI_User']['id_user'];

		$result=false;
        if($id!="") {
			$data = array(
						'id'=>$id,
						'status'=>$status,
						'st_reject'=>'',
					);
			if($status==1){
				$data['fin_check_by']=$UserName;
				$data['fin_check_on']=date("Y-m-d h:i:s");
			}
			if($status==2){
				$data['management_by']=$UserName;
				$data['management_on']=date("Y-m-d h:i:s");
			}
			$result=$this->All_model->dataUpdate('tr_transport_req',$data,array('id'=>$id));
			$keterangan     = "SUKSES, Update data ".$id;
        }else{
			$result=false;
			$id=0;
		}
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}

	// transport approve
	public function transport_approve($id=''){
		$result=false;
        if($id!="") {
			$data = array(
						'id'=>$id,
						'status'=>1,
					);
			$result=$this->All_model->dataUpdate('tr_transport',$data,array('id'=>$id));
			$keterangan     = "SUKSES, Update data ".$id;
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}

	// transport delete
	public function transport_delete($id){
		$this->db->trans_begin();
        $result=$this->All_model->dataDelete('tr_transport',array('id'=>$id));
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
        $param = array( 'delete' => $result );
        echo json_encode($param);
	}

	// transport delete
	public function transport_req_delete($id){
		$this->db->trans_begin();
		$data = $this->expense_model->GetDataTransportReq($id);
		$this->All_model->dataUpdate(DBERP.'.tr_transport',array('status'=>0,'no_req'=>''),array('no_req'=>$data->no_doc));
        $result=$this->All_model->dataDelete('tr_transport_req',array('id'=>$id));
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}
        $param = array( 'delete' => $result );
        echo json_encode($param);
	}

	// confirm
	public function return_confirm($id=''){
		$result=false;
		$data_session	= $this->session->userdata;
		$dateTime = date('Y-m-d H:i:s');
		$UserName = $data_session['ORI_User']['username'];
        if($id!="") {
			$data = array(
						array(
							'id'=>$id,
							'status'=>2,
							'st_reject'=>'',
							'approved_by'=> $UserName,
							'approved_on'=>date("Y-m-d h:i:s")
						)
					);
			$this->db->trans_begin();
			$results = $this->db->update_batch('tr_expense',$data,'id');
			$recpc = $this->All_model->GetOneData('tr_expense',array('id'=>$id,'status'=>'2'));
			$jenis_jurnal = 'BUM';
			$payment_date = $recpc->transfer_tanggal;
			$det_Jurnaltes1 = array();
			$ix = 0;
			$ketpetty='';
			$ketpetty=$recpc->pettycash.' ';
			$this->db->update('tr_expense_detail', ['status' => '2'], ['no_doc' => $recpc->no_doc, 'status' => '1']);
			$Bln 			= substr($payment_date, 5, 2);
			$Thn 			= substr($payment_date, 0, 4);
			$Nomor_JV = $this->Jurnal_model->get_Nomor_Jurnal_BUM('101');
			$session = $this->session->userdata('app_session');
			$rec = $this->db->query("select * from " . DBERP . ".tr_expense_detail where no_doc='".$recpc->no_doc."'")->result();
			$total=0;
			$nomor_jurnal = $jenis_jurnal . date("ymd") . rand(1000, 9999) . $ix;
			foreach ($rec as $record) {
				if ($record->id_kasbon != '') {
					$det_Jurnaltes1[] = array(
						'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUM', 'no_perkiraan' => $record->coa, 'keterangan' => $ketpetty.$record->deskripsi, 'no_request' => $recpc->no_doc, 'debet' => 0, 'kredit' => $record->kasbon, 'no_reff' =>  $recpc->no_doc, 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $recpc->nama, 'stspos'=>'1'
					);
					$datadetail = array(
						'tipe'        	=> $jenis_jurnal,
						'nomor'       	=> $Nomor_JV,
						'tanggal'     	=> $payment_date,
						'no_reff'     	=> $recpc->no_doc,
						'no_perkiraan'	=> $record->coa,
						'keterangan' 	=> $ketpetty.$record->deskripsi,
						'debet' 		=> 0,
						'kredit' 		=> $record->kasbon
					);
				} else {
					//expense
					$det_Jurnaltes1[] = array(
						'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUM', 'no_perkiraan' => $record->coa, 'keterangan' => $ketpetty.$record->deskripsi, 'no_request' => $recpc->no_doc, 'debet' => $record->expense, 'kredit' => 0, 'no_reff' =>  $recpc->no_doc, 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $recpc->nama, 'stspos'=>'1'
					);
					$datadetail = array(
						'tipe'        	=> $jenis_jurnal,
						'nomor'       	=> $Nomor_JV,
						'tanggal'     	=> $payment_date,
						'no_reff'     	=> $recpc->no_doc,
						'no_perkiraan'	=> $record->coa,
						'keterangan' 	=> $ketpetty.$record->deskripsi,
						'debet' 		=> $record->expense,
						'kredit' 		=> 0
					);
					$total=$total+$record->expense;
					if($recpc->no_so !=""){
						$datadeferred = array(
							'no_so'        	=> $recpc->no_so,
							'tanggal'     	=> $payment_date,
//							'no_reff'     	=> $recpc->no_doc,
							'tipe'		 	=> 'expense',
							'qty'	 		=> 1,
							'amount' 		=> $record->expense,
							'id_material'	=> "",
							'nm_material'	=> "",
							'keterangan'	=> $ketpetty,
							'kode_trans'	=> $recpc->no_doc
						);
						$this->db->insert(DBERP . '.tr_deferred', $datadeferred);
					}

				}
				$this->db->insert(DBACC . '.jurnal', $datadetail);
			}
			if($recpc->transfer_jumlah > 0){
				//bank coa
				$det_Jurnaltes1[] = array(
					'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUM', 'no_perkiraan' => $recpc->transfer_coa_bank, 'keterangan' => $ketpetty, 'no_request' => $recpc->no_doc, 'debet' =>  $recpc->transfer_jumlah, 'kredit' =>0, 'no_reff' =>  $recpc->no_doc, 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $recpc->nama, 'stspos'=>'1'
				);
				$datadetail = array(
					'tipe'        	=> $jenis_jurnal,
					'nomor'       	=> $Nomor_JV,
					'tanggal'     	=> $payment_date,
					'no_reff'     	=> $recpc->no_doc,
					'no_perkiraan'	=> $recpc->transfer_coa_bank,
					'keterangan' 	=> $recpc->informasi,
					'debet' 		=> $recpc->transfer_jumlah,
					'kredit' 		=> 0
				);
				$this->db->insert(DBACC . '.jurnal', $datadetail);
				$total=$total+$recpc->transfer_jumlah;
			}
			if($recpc->add_ppn_nilai > 0){
				//ppn coa
				$det_Jurnaltes1[] = array(
					'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUM', 'no_perkiraan' => $recpc->add_ppn_coa, 'keterangan' => $ketpetty, 'no_request' => $recpc->no_doc, 'debet' =>  $recpc->add_ppn_nilai, 'kredit' =>0, 'no_reff' =>  $recpc->no_doc, 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $recpc->nama, 'stspos'=>'1'
				);
				$datadetail = array(
					'tipe'        	=> $jenis_jurnal,
					'nomor'       	=> $Nomor_JV,
					'tanggal'     	=> $payment_date,
					'no_reff'     	=> $recpc->no_doc,
					'no_perkiraan'	=> $recpc->add_ppn_coa,
					'keterangan' 	=> $ketpetty,
					'debet' 		=> $recpc->add_ppn_nilai,
					'kredit' 		=> 0
				);
				$this->db->insert(DBACC . '.jurnal', $datadetail);
			}
			if($recpc->add_pph_nilai > 0){
				//pph coa
				$det_Jurnaltes1[] = array(
					'nomor' => $nomor_jurnal, 'tanggal' => $payment_date, 'tipe' => 'BUM', 'no_perkiraan' => $recpc->add_pph_coa, 'keterangan' => $ketpetty, 'no_request' => $recpc->no_doc, 'debet' =>  0, 'kredit' =>$recpc->add_pph_nilai, 'no_reff' =>  $recpc->no_doc, 'jenis_jurnal' => $jenis_jurnal, 'nocust' => $recpc->nama, 'stspos'=>'1'
				);
				$datadetail = array(
					'tipe'        	=> $jenis_jurnal,
					'nomor'       	=> $Nomor_JV,
					'tanggal'     	=> $payment_date,
					'no_reff'     	=> $recpc->no_doc,
					'no_perkiraan'	=> $recpc->add_pph_coa,
					'keterangan' 	=> $ketpetty,
					'debet' 		=> 0,
					'kredit' 		=> $recpc->add_pph_nilai
				);
				$this->db->insert(DBACC . '.jurnal', $datadetail);
			}
			$this->db->insert_batch(DBERP . '.jurnaltras', $det_Jurnaltes1);
			$keterangan	= 'Penerimaan Expense';
			$dataJVhead = array(
				'nomor' 	    	=> $Nomor_JV,
				'tgl'	         	=> $payment_date,
				'jml'	            => $total,
				'kdcab'				=> '101',
				'jenis_reff'	    => 'BUM',
				'no_reff' 		    => $recpc->no_doc,
				'jenis_ar'			=> 'BUM',
				'note'				=> $keterangan,
				'terima_dari'		=> $recpc->nama,
				'user_id'			=> $UserName,
				'ho_valid'			=> '',
				'batal'			    => '0'
			);

			$this->db->insert(DBACC . '.jarh', $dataJVhead);
			$Qry_Update_Cabang_acc	 = "UPDATE " . DBACC . ".pastibisa_tb_cabang SET nobum=nobum + 1 WHERE nocab='101'";
			$this->db->query($Qry_Update_Cabang_acc);

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
			history('Approve data expense : '.$id);
        }
        $param = array(
                'save' => $result, 'id'=>$id
                );
        echo json_encode($param);
	}
	public function deffered(){
		$controller			= 'expense/deffered';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$data_Group			= $this->master_model->getArray('groups',array(),'id','name');
		$data = array(
			'title'			=> 'Index Of Deferred',
			'action'		=> 'expense/deffered',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Deferred');
		$this->load->view('Expense/index_deferred',$data);
	}	
	public function data_side_deferded(){
		$controller			= 'expense/deffered';
		$Arr_Akses			= getAcccesmenu($controller);
		$requestData	= $_REQUEST;
		$fetch			= $this->get_query_json_deferred(
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
		foreach($query->result_array() as $row) {
			$total_data     = $totalData;
            $start_dari     = $requestData['start'];
            $asc_desc       = $requestData['order'][0]['dir'];
			$nestedData 	= array();
			$nestedData[]	= "<div align='left'>".$row['no_so']."</div>";
			$nestedData[]	= "<div align='left'>".$row['kode_trans']."</div>";
			$nestedData[]	= "<div align='left'>".$row['tanggal']."</div>";
			$nestedData[]	= "<div align='left'>".$row['tipe']."</div>";
			$nestedData[]	= "<div align='left'>".$row['keterangan']."</div>";
			$nestedData[]	= "<div align='left'>".$row['qty']."</div>";
			$nestedData[]	= "<div align='left'>".$row['amount']."</div>";
			$data[] = $nestedData;
		}
		$json_data = array(
			"draw"            	=> intval( $requestData['draw'] ),
			"recordsTotal"    	=> intval( $totalData ),
			"recordsFiltered" 	=> intval( $totalFiltered ),
			"data"            	=> $data
		);
		echo json_encode($json_data);
	}
	public function get_query_json_deferred($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL){
		$sql = "
            SELECT
				a.*
			FROM
                tr_deferred a
            WHERE
                1=1 AND (
                a.no_so LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.kode_trans LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.keterangan LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.tipe LIKE '%".$this->db->escape_like_str($like_value)."%'
	        )
		";
		$data['totalData']  = $this->db->query($sql)->num_rows();
		$data['totalFiltered'] = $this->db->query($sql)->num_rows();
		$columns_order_by = array(
			0 => 'no_so',
			1 => 'tanggal',
			2 => 'kode_trans',
			3 => 'tipe',
		);
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir." ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		$data['query'] = $this->db->query($sql);
		return $data;
    }
}
