<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

	public function get_customer()
	{
		$this->db->select('*');
		$this->db->from('customer');

		return $this->db->get();
	}
}
