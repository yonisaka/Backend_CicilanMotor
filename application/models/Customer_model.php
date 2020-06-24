<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

	public function get_customer()
	{
		$this->db->select('*');
		$this->db->from('customer');
		$this->db->where('status_delete', 0);
		return $this->db->get();
	}

	public function get_customer_by_id($id_customer)
	{
		$this->db->select('*');
		$this->db->from('customer');
		$this->db->where('id_customer', $id_customer);
		return $this->db->get();
	}
	public function get_customer_by_email($email)
	{
		$this->db->select('id_customer');
		$this->db->from('customer');
		$this->db->where('email', $email);

		return $this->db->get();
	}
	public function update_data($id_customer, $data)
	{
		$this->db->where('id_customer', $id_customer);
		$this->db->update('customer', $data);
	}

	public function insert_data($data)
	{
		$this->db->insert('customer', $data);
	}
	public function hapus_data($id_customer)
	{
		$this->db->where('id_customer', $id_customer);
		$this->db->delete('customer');
	}
	public function soft_delete_data($id_customer)
	{
		$data = array(
			'status_delete' => 1
		);
		$this->db->where('id_customer', $id_customer);
		$this->db->update('customer', $data);

		}
}
