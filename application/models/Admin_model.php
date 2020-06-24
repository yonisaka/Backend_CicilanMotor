<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

	public function get_admin()
	{
		$this->db->select('*');
		$this->db->from('admin');

		return $this->db->get();
	}

	public function get_admin_by_id($id_admin)
	{
		$this->db->select('*');
		$this->db->from('admin');
		$this->db->where('id_admin', $id_admin);
		return $this->db->get();
	}

	public function get_admin_by_email($email)
	{
		$this->db->select('id_admin');
		$this->db->from('admin');
		$this->db->where('email', $email);

		return $this->db->get();
	}
}
