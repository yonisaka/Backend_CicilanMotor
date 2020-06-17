<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angsuran_model extends CI_Model {

	public function insert_data($data)
	{
		$this->db->insert('angsuran', $data);
	}

	public function update_data($id_angsuran, $data)
	{
		$this->db->where('id_angsuran', $id_angsuran);
		$this->db->update('angsuran', $data);
	}
}
