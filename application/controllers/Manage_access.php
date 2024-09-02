<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_access extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->load->model('agent_model');
		$this->load->model('user_model');
        $this->load->library('session');
		$this->load->database();
        if (!$this->session->has_userdata('logged_user')) {
			redirect('login');
		}

		$this->session->set_flashdata('page','Role');
        
    }
	public function index()
	{
		$data = array(
			'title'			=> 'Daftar Role',
			'data_role'	=> $this->db->query("SELECT * FROM travel_role ORDER BY role_id DESC")->result(),
		);
		$this->load->view('access/index',$data);
	}
	
	public function add_edit_data($id_role){
		if($id_role==0){
			$save = $this->input->post('save');
			if(isset($save)){
				$data_role = $this->db->query("SELECT * FROM travel_role ORDER BY role_id DESC")->result();
				if($data_role){
					$role_id = $data_role[0]->role_id + 1;
				}else{
					$role_id = 1;
				}
				$data = array(
					'role_id'			=> $role_id,
					'role_name'			=> $this->input->post('role_name'),
					'role_status'		=> $this->input->post('role_status'),
					'created_by'		=> $this->session->userdata('user_id'),
					'created_date'		=> date('Y-m-d')
				);
				
				$this->db->insert("travel_role", $data);
				
				$menu_id = $this->input->post('menu_id');
				$create = $this->input->post('create');
				$read = $this->input->post('read');
				$update = $this->input->post('update');
				$delete = $this->input->post('delete');
				
				$jum = count($menu_id);
				for($i=1; $i<=$jum; $i++){
					$menu_id2 = isset($menu_id[$i]) ? $menu_id[$i] : null;
					$create2 = isset($create[$i]) ? $create[$i] : null;
					$read2 = isset($read[$i]) ? $read[$i] : null;
					$update2 = isset($update[$i]) ? $update[$i] : null;
					$delete2 = isset($delete[$i]) ? $delete[$i] : null;
					$data2 = array(
						'role_id'			=> $role_id,
						'menu_id'			=> $menu_id2,
						'read'				=> $read2,
						'create'			=> $create2,
						'update'			=> $update2,
						'delete'			=> $delete2,
						'created_by'		=> $this->session->userdata('user_id'),
						'created_date'		=> date('Y-m-d')
					);
					$this->db->insert("travel_role_menu", $data2);
				}
				if($this->db->affected_rows()>0){
					$this->session->set_flashdata("notifikasi", "<div class=\"alert alert-success\" id=\"alert\">Data has been succesfully added !!</div>");
					redirect("manage_access");
				}	
			}else{
				$data = array(
					'title'				=> 'Add Role',
					'data_role'			=> 0,
					'data_menu'			=> $this->db->query("SELECT * FROM travel_menu WHERE link!='#' ORDER BY group_menu ASC")->result()
				);
				$this->load->view('access/add_edit',$data);			
			}	
		}else{
			$save = $this->input->post('save');
			if(isset($save)){
				$data = array(
					'role_name'			=> $this->input->post('role_name'),
					'role_status'		=> $this->input->post('role_status'),
					'updated_by'	=> $this->session->userdata('user_id'),
					'updated_date'	=> date('Y-m-d')
				);
				$this->db->where('role_id', $id_role);
			    $this->db->update("travel_role", $data);
				
				$menu_id = $this->input->post('menu_id');
				$create = $this->input->post('create');
				$read = $this->input->post('read');
				$update = $this->input->post('update');
				$delete = $this->input->post('delete');
				
				$jum = count($menu_id);
				for($i=1; $i<=$jum; $i++){
					$menu_id2 = isset($menu_id[$i]) ? $menu_id[$i] : null;
					$create2 = isset($create[$i]) ? $create[$i] : null;
					$read2 = isset($read[$i]) ? $read[$i] : null;
					$update2 = isset($update[$i]) ? $update[$i] : null;
					$delete2 = isset($delete[$i]) ? $delete[$i] : null;
					$data3 = array(
						'read'				=> $read2,
						'create'			=> $create2,
						'update'			=> $update2,
						'delete'			=> $delete2,
						'created_by'		=> $this->session->userdata('user_id'),
						'created_date'		=> date('Y-m-d')
					);
					$this->db->where('role_id', $id_role);
					$this->db->where('menu_id', $menu_id2);
					$this->db->update("travel_role_menu", $data3);
				}
				
					$this->session->set_flashdata("notifikasi", "<div class=\"alert alert-success\" id=\"alert\">Data has been succesfully updated !!</div>");
					redirect("manage_access");
				
			}else{
				$data = array(
					'title'			=> 'Update Role',
					'data_menu'			=> $this->db->query("SELECT * FROM travel_menu WHERE link!='#' ORDER BY group_menu ASC")->result(),
					'data_role'	=> $this->db->query("SELECT * FROM travel_role a, travel_role_menu b WHERE a.role_id='$id_role' AND b.role_id='$id_role'")->result()
				);
				$this->load->view('access/add_edit',$data);	
			}
		}
	}
}