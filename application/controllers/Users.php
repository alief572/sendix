<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_model');
		// Your own constructor code
		if (!$this->session->userdata('isORIlogin')) {
			redirect('login');
		}
	}

	public function index()
	{
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if ($Arr_Akses['read'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('dashboard'));
		}

		$data_Group			= $this->master_model->getArray('groups', array(), 'id', 'name');
		$data = array(
			'title'			=> 'Indeks Of Users',
			'action'		=> 'index',
			'row_group'		=> $data_Group,
			'akses_menu'	=> $Arr_Akses
		);
		history('View Data User');
		$this->load->view('Users/index', $data);
	}

	function display_data()
	{
		$det_Akses		= akses_server_side();

		$WHERE			= "deleted<>1 AND (LOWER(username) !='admin' AND LOWER(username) !='json')";

		$table = 'users';
		$primaryKey = 'id_user';
		$columns = array(
			array('db' => 'id_user', 'dt' => 'id_user'),
			array(
				'db' => 'id_user',
				'dt' => 'DT_RowId'
			),
			array('db' => 'username', 'dt' => 'username'),
			array('db' => 'nm_lengkap', 'dt' => 'nm_lengkap'),
			array('db' => 'group_id', 'dt' => 'group_id'),
			array('db' => 'st_aktif', 'dt' => 'st_aktif'),
			array(
				'db' => 'id_user',
				'dt' => 'action',
				'formatter' => function ($d, $row) {
					return '';
				}
			),
			array(
				'db' => 'st_aktif',
				'dt' => 'status',
				'formatter' => function ($d, $row) {
					return '';
				}
			),
			array(
				'db' => 'group_id',
				'dt' => 'group_name',
				'formatter' => function ($d, $row) {
					return '';
				}
			),
		);
		$sql_details = array(
			'user' => $det_Akses['hostuser'],
			'pass' => $det_Akses['hostpass'],
			'db'   => $det_Akses['hostdb'],
			'host' => $det_Akses['hostname']
		);
		require('ssp.class.php');


		echo json_encode(
			SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $WHERE)
		);
		//echo "<pre>";print_r($data);exit;



	}

	public function register_user()
	{
		if ($this->input->post()) {
			$Arr_Kembali			= array();
			$data					= $this->input->post();
			$UserCek				= $this->input->post('username');
			$Password				= cryptSHA1($this->input->post('password'));
			$Data_Insert			= array(
				'username'			=> $UserCek,
				'nm_lengkap'		=> strtoupper($this->input->post('nm_lengkap')),
				'email'				=> $this->input->post('user_email'),
				'alamat'			=> $this->input->post('user_address'),
				'kota'				=> $this->input->post('user_province'),
				'hp'				=> $this->input->post('user_phone'),
				'kdcab'				=> $this->input->post('branch'),
				'work_location'		=> $this->input->post('kdcab'),
				'password'			=> $Password,
				'group_id'			=> $this->input->post('group_id'),
				'department_id'		=> $this->input->post('department_id'),
				'st_aktif'			=> 1,
				'created_on'		=> date('Y-m-d H:i:s')
			);

			## CEK USER ##
			$countUser				= $this->master_model->getCount('users', 'username', $UserCek);
			if ($countUser > 0) {
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Username Already Exists. Please input different username......'
				);
			} else {
				if ($this->master_model->simpan('users', $Data_Insert)) {
					$Arr_Kembali		= array(
						'status'		=> 1,
						'pesan'			=> 'Register User Success. Thank you & have a nice day.......'
					);
					history('Add Data User ' . $Data_Insert['username']);
				} else {
					$Arr_Kembali		= array(
						'status'		=> 2,
						'pesan'			=> 'Register User failed. Please try again later......'
					);
				}
			}
			echo json_encode($Arr_Kembali);
		} else {
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if ($Arr_Akses['create'] != '1') {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('users'));
			}

			$data_Group			= $this->master_model->getArray('groups', array(), 'id', 'name');
			$det_Province		= $this->master_model->getArray('provinsi', array(), 'nama', 'nama');
			$det_branch			= $this->master_model->getArray('branch', array(), 'nocab', 'cabang');
			$det_Plant			= $this->master_model->getArray('company_plants', array(), 'id_plant', 'nm_plant');
			$department			= $this->master_model->getArray('department', array(), 'id', 'nm_dept');
			$data = array(
				'title'			=> 'Add Users',
				'action'		=> 'register_user',
				'rows_province'	=> $det_Province,
				'rows_branch'	=> $det_Plant,
				'data_group'	=> $data_Group,
				'department'	=> $department,
				'branch'		=> $det_branch
			);
			$this->load->view('Users/add', $data);
		}
	}

	public function edit_user($id = '')
	{
		if ($this->input->post()) {
			$Arr_Kembali			= array();
			$Kode_User				= $this->input->post('id_user');
			$Data_Insert			= array(
				'username'			=> $this->input->post('username'),
				'nm_lengkap'		=> strtoupper($this->input->post('nm_lengkap')),
				'email'				=> $this->input->post('user_email'),
				'alamat'			=> $this->input->post('user_address'),
				'kota'				=> $this->input->post('user_province'),
				'hp'				=> $this->input->post('user_phone'),
				'kdcab'				=> $this->input->post('kdcab'),
				'group_id'			=> $this->input->post('group_id'),
				'department_id'		=> $this->input->post('department_id'),
				'st_aktif'			=> $this->input->post('st_aktif')
			);

			if ($this->master_model->getUpdate('users', $Data_Insert, 'id_user', $Kode_User)) {
				$Arr_Kembali		= array(
					'status'		=> 1,
					'pesan'			=> 'Edit User Success. Thank you & have a nice day.......'
				);
				history('Update Data User' . $Data_Insert['username']);
			} else {
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Edit User failed. Please try again later......'
				);
			}

			echo json_encode($Arr_Kembali);
		} else {
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if ($Arr_Akses['update'] != '1') {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('users'));
			}

			$data_Group			= $this->master_model->getArray('groups', array(), 'id', 'name');
			$rows_data			= $this->master_model->getData('users', 'id_user', $id);
			$det_Plant			= $this->master_model->getArray('company_plants', array(), 'id_plant', 'nm_plant');
			$det_Province		= $this->master_model->getArray('provinsi', array(), 'nama', 'nama');
			$department			= $this->master_model->getArray('department', array(), 'id', 'nm_dept');
			$data = array(
				'title'			=> 'Edit Users',
				'action'		=> 'edit_user',
				'data_group'	=> $data_Group,
				'rows_data'		=> $rows_data,
				'rows_branch'	=> $det_Plant,
				'department'	=> $department,
				'rows_province'	=> $det_Province
			);
			$this->load->view('Users/edit', $data);
		}
	}

	function delete_user($id = '')
	{
		$controller			= ucfirst(strtolower($this->uri->segment(1)));
		$Arr_Akses			= getAcccesmenu($controller);
		if ($Arr_Akses['delete'] != '1') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
			redirect(site_url('users'));
		}
		$rows_data			= $this->master_model->getData('users', 'id_user', $id);
		if (strtolower($rows_data[0]->username) == 'admin') {
			$this->session->set_flashdata("alert_data", "<div class=\"alert alert-danger\" id=\"flash-message\">This Account Can't Be Deleted...........!!</div>");
		} else {
			$this->db->update("users", array('deleted' => 1), array('id_user' => $id));
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-success\" id=\"flash-message\">Data has been successfully deleted...........!!</div>");
				history('Delete Data Username : ' . $rows_data[0]->username);
			}
		}
		redirect(site_url('users'));
	}

	public function view_user($id = '')
	{
		$data_Group			= $this->master_model->getArray('groups', array(), 'id', 'name');
		$rows_data			= $this->master_model->getData('users', 'id_user', $id);
		$det_Plant			= $this->master_model->getArray('company_plants', array(), 'id_plant', 'nm_plant');
		$det_Province		= $this->master_model->getArray('provinsi', array(), 'nama', 'nama');
		$department			= $this->master_model->getArray('department', array(), 'id', 'nm_dept');
		$data = array(
			'title'			=> 'View Users',
			'action'		=> 'view_user',
			'data_group'	=> $data_Group,
			'rows_data'		=> $rows_data,
			'rows_branch'	=> $det_Plant,
			'department'	=> $department,
			'rows_province'	=> $det_Province
		);
		$this->load->view('Users/view', $data);
	}

	public function profile($id = '')
	{
		$data_Group			= $this->master_model->getArray('groups', array(), 'id', 'name');
		$rows_data			= $this->master_model->getData('users', 'id_user', $id);
		$det_Plant			= $this->master_model->getArray('company_plants', array(), 'id_plant', 'nm_plant');
		$det_Province		= $this->master_model->getArray('provinsi', array(), 'nama', 'nama');
		$department			= $this->master_model->getArray('department', array(), 'id', 'nm_dept');
		$data = array(
			'title'			=> 'User Profile',
			'action'		=> 'profile',
			'data_group'	=> $data_Group,
			'rows_data'		=> $rows_data,
			'rows_branch'	=> $det_Plant,
			'department'	=> $department,
			'rows_province'	=> $det_Province
		);
		$this->load->view('Users/view', $data);
	}

	public function change_password($id = '')
	{
		if ($this->input->post()) {
			$Arr_Kembali			= array();
			$Kode_User				= $this->input->post('id_user');
			$New_Pass				= cryptSHA1($this->input->post('new_password'));
			$Data_Insert			= array(
				'username'			=> $this->input->post('username'),
				'password'			=> $New_Pass
			);

			if ($this->master_model->getUpdate('users', $Data_Insert, 'id_user', $Kode_User)) {
				$Arr_Kembali		= array(
					'status'		=> 1,
					'pesan'			=> 'Change Password Success. Thank you & have a nice day.......'
				);
				history('Change Password User' . $Data_Insert['username']);
			} else {
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Edit User failed. Please try again later......'
				);
			}

			echo json_encode($Arr_Kembali);
		} else {
			$controller			= ucfirst(strtolower($this->uri->segment(1)));
			$Arr_Akses			= getAcccesmenu($controller);
			if ($Arr_Akses['update'] != '1') {
				$this->session->set_flashdata("alert_data", "<div class=\"alert alert-warning\" id=\"flash-message\">You Don't Have Right To Access This Page, Please Contact Your Administrator....</div>");
				redirect(site_url('users'));
			}

			$data_Group			= $this->master_model->getArray('groups', array(), 'id', 'name');
			$rows_data			= $this->master_model->getData('users', 'id_user', $id);
			$data = array(
				'title'			=> 'Change Password Users',
				'action'		=> 'change_password',
				'data_group'	=> $data_Group,
				'rows_data'		=> $rows_data
			);
			$this->load->view('Users/edit_password', $data);
		}
	}

	public function Validasi_Password()
	{
		if ($this->input->post()) {
			$Arr_Kembali			= array();
			$Kode_User				= $this->input->post('id_user');
			$Password				= cryptSHA1($this->input->post('password'));
			## CEK USER ##
			$prosesUser				= $this->db->get_where('users', array('id_user' => $Kode_User, 'password' => $Password));
			$countUser				= $prosesUser->num_rows();
			if ($countUser > 0) {
				$Arr_Kembali		= array(
					'status'		=> 1,
					'pesan'			=> 'Valid Password....'
				);
			} else {
				$Arr_Kembali		= array(
					'status'		=> 2,
					'pesan'			=> 'Invalid Password....'
				);
			}
			echo json_encode($Arr_Kembali);
		}
	}

	public function getWorkLocation()
	{
		$branch = $this->input->post('branch');
		$sqlWorkLoc	= "SELECT * FROM company_plants WHERE kdcab='" . $branch . "' AND `delete`='N' ORDER BY nm_plant ASC ";
		$restWorlLoc	= $this->db->query($sqlWorkLoc)->result_array();

		$option	= "<option value='0'>Select An Work Location</option>";
		if ($branch == '100') {
			$option .= "<option value='HO'>Head Office</option>";
		}

		foreach ($restWorlLoc as $val => $valx) {
			$option .= "<option value='" . $valx['id_plant'] . "'>" . ucwords(strtolower($valx['nm_plant'])) . "</option>";
		}

		$ArrJson	= array(
			'option' => $option
		);
		echo json_encode($ArrJson);
	}
}
