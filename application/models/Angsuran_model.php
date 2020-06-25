<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angsuran_model extends CI_Model {

	public function insert_data($data)
	{
		$this->db->insert('angsuran', $data);
		$this->db->from('angsuran');

		return $this->db->get();
	}

	public function update_data($id_angsuran, $data)
	{
		$this->db->where('id_angsuran', $id_angsuran);
		$this->db->update('angsuran', $data);
	}

	public function get_all_angsuran($limit, $offset, $orderby, $method, $search)
	{
		$this->db->select('*');
		$this->db->from('angsuran');

		if($limit > -1){
			$this->db->limit($limit, $offset);
		}

		if($search != '' && $search!= null){
			$this->db->like('kali_angsuran', $search);
		}

		$this->db->order_by($orderby, $method);
		
		return $this->db->get();
	}

}