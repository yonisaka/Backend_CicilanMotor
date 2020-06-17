<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

	public function get_customer()
	{
		$this->db->select('*');
		$this->db->from('customer');

		return $this->db->get();
	}

	public function get_customer_by_id($id_customer)
	{
		$this->db->select('*');
		$this->db->from('customer');
		$this->db->where('id_customer', $id_customer);
		return $this->db->get();
	}
}
