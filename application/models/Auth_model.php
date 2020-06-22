<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

	function cek_login($table, $where){
		return $this->db->get_where($table, $where);
	}

	function register_data($data){
		$this->db->insert('customer', $data);
	}

	public function add_foto($id_customer, $data)
	{
		$this->db->where('id_customer', $id_customer);
		$this->db->update('customer', $data);
	}
}
