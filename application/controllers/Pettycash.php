<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pettycash extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->load->model('master_model');
		$this->load->model('all_model');
		$this->load->database();
        if(!$this->session->userdata('isORIlogin')){
			redirect('login');
		}
    }

	public function index(){
		$controller			= 'Pettycash';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		
		$get_Data			= $this->db->query("SELECT * FROM ms_petty_cash order by id")->result();
		$menu_akses			= $this->master_model->getMenu();
		$data = array(
			'title'			=> 'Master Petty Cash',
			'action'		=> 'index',
			'row'			=> $get_Data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data Petty Cash');
		$this->load->view('Pettycash/index',$data);
	}
	public function create(){
		$controller			= 'Pettycash';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$menu_akses			= $this->master_model->getMenu();
		$data_coa = $this->all_model->GetCoaCombo();
		$data_approval = $this->all_model->GetOneTable('user_emp','','nama_karyawan');
		$data = array(
			'title'			=> 'Master Petty Cash',
			'action'		=> 'index',
			'datacoa'		=> $data_coa,
			'data_approval'	=> $data_approval,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('New Data Petty Cash');
		$this->load->view('Pettycash/form',$data);
		
	}
	public function edit($id){
		$controller			= 'Pettycash';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$menu_akses			= $this->master_model->getMenu();
		$data_coa = $this->all_model->GetCoaCombo();
		$data_approval = $this->all_model->GetOneTable('user_emp','','nama_karyawan');
		$data =$this->db->query("SELECT * FROM ms_petty_cash where id='".$id."'")->row();
		$data = array(
			'title'			=> 'Master Petty Cash',
			'action'		=> 'index',
			'datacoa'		=> $data_coa,
			'data'			=> $data,
			'data_approval'	=> $data_approval,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('New Data Petty Cash');
		$this->load->view('Pettycash/form',$data);	
	}

	public function delete($id){
        $result=$this->all_model->dataDelete('ms_petty_cash',array('id'=>$id));
        $param = array( 'delete' => $result );
        echo json_encode($param);
	}
	
	public function save(){
        $id	= $this->input->post("id");
        $nama	= $this->input->post("nama");
		$pengelola	= $this->input->post("pengelola");
        $keterangan	= $this->input->post("keterangan");
		$coadata	= $this->input->post("coa");
		$coa=implode(';',$coadata);
		$budget	= $this->input->post("budget");
		$approval	= $this->input->post("approval");
        if($id!="") {
			$data = array(
						'nama'=>$nama,
						'pengelola'=>$pengelola,
						'keterangan'=>$keterangan,
						'coa'=>$coa,
						'budget'=>$budget,
						'approval'=>$approval,
					);
			$this->all_model->dataUpdate('ms_petty_cash',$data,array('id'=>$id)); 
			$result	= TRUE;
        } else {
            $data =  array(
						'nama'=>$nama,
						'pengelola'=>$pengelola,
						'keterangan'=>$keterangan,
						'coa'=>$coa,
						'budget'=>$budget,
						'approval'=>$approval,
					);
            $id = $this->all_model->dataSave('ms_petty_cash',$data);
            if(is_numeric($id)) {
                $result         = TRUE;
            } else {
                $result = FALSE;
            }
        }
        $param = array(
                'save' => $result
                );
        echo json_encode($param);
	}
	public function coa_edit($tipe){
		$controller			= 'Pettycash';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$menu_akses			= $this->master_model->getMenu();
		$data_coa = $this->all_model->GetCoaCombo();
		$data_approval = $this->all_model->GetOneTable('user_emp','','nama_karyawan');
		$data =$this->db->query("SELECT * FROM ms_generate where tipe='".$tipe."'")->row();
		$data = array(
			'title'			=> 'Master Expense',
			'action'		=> 'index',
			'datacoa'		=> $data_coa,
			'tipe'			=> $tipe,
			'data'			=> $data,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		history('Edit Data COA');
		$this->load->view('Pettycash/form_coa',$data);	
	}
	public function coa_save(){
		$coadata	= $this->input->post("coa");
		$tipe	= $this->input->post("tipe");
		if($coadata){
			$coa=implode(';',$coadata);
			$data = array(
					'kode_text'=>$coa,
				);
		}else{
			$data = array(
					'kode_text'=>'',
				);
		}
		$result = $this->all_model->dataUpdate('ms_generate',$data,array('tipe'=>$tipe));
		$result         = TRUE;
        $param = array(
			'save' => $result
			);
        echo json_encode($param);
	}
	public function form_bank($id){
		$controller			= 'Pettycash';
		$Arr_Akses			= getAcccesmenu($controller);
		if($Arr_Akses['read'] !='1'){
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}
		$menu_akses	= $this->master_model->getMenu();
		$data_coa = $this->all_model->GetCoaCombo();
		$databank =$this->db->query("SELECT * FROM ms_generate where id='".$id."' and tipe='kode_bank'")->row();
		$data = array(
			'title'			=> 'Kode Bank Jurnal',
			'action'		=> 'index',
			'datacoa'		=> $data_coa,
			'id'			=> $id,
			'data'			=> $databank,
			'data_menu'		=> $menu_akses,
			'akses_menu'	=> $Arr_Akses
		);
		if($id==0){
			history('Add Kode Bank');
		}else{
			history('Edit Kode Bank');
		}
		$this->load->view('Pettycash/form_bank',$data);	
	}	
	public function bank_save(){
		$coadata	= $this->input->post("coa");
		$kode	= $this->input->post("kode");
		$id	= $this->input->post("id");
		$data = array(
				'info'=>$coadata,
				'kode_1'=>$kode,
				'tipe'=>'kode_bank',
			);
		if($id==0){
			$result = $this->all_model->dataSave('ms_generate',$data);
		}else{
			$result = $this->all_model->dataUpdate('ms_generate',$data,array('id'=>$id));
		}
		$result         = TRUE;
        $param = array(
			'save' => $result
			);
        echo json_encode($param);
	}
	public function delete_bank($id){
        $result=$this->all_model->dataDelete('ms_generate',array('id'=>$id,'tipe'=>'kode_bank'));
        $param = array( 'delete' => $result );
        echo json_encode($param);
	}
}