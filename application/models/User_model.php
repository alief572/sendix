<?php

class User_model extends CI_Model {

	public function __construct() {
			parent::__construct();
			// Your own constructor code
			$this->rental = $this->load->database("rental",TRUE);
	}
	function check_login($data){ 
		$array = array(
					'pn_name'=>$data['pn_name'],
					'pn_pass'=>$data['pn_pass']
				);
		$this->rental->where($array);
		$this->rental->from('pastibisa_users');
		return $this->rental->get();
	}
	function get_rental($table, $wherefield = '',$where_value=''){
		$this->rental->select('*');		
		if($wherefield != '' && $where_value != ''){
			return	$this->rental->get_where($table, array($wherefield => $where_value))->result();
		}else{
			return	$this->rental->get($table)->result();
		}
	}

}

