<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_admin_model extends CI_Model
{
	public function cek_login($email, $password)
	{
		$this->db->select('*');
		$this->db->from('admin');
		$this->db->where('email',$email);
		$this->db->where('password',$password);
		return $this->db->get();
	}

	function register_data($data){
		$this->db->insert('admin', $data);
	}

}
