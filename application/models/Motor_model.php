<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Motor_model extends CI_Model {

	public function get_motor()
	{
		$this->db->select('*');
		$this->db->from('motor');
		$this->db->where('status_delete', 0);
		return $this->db->get();
	}

	public function get_motor_by_id($id_motor)
	{
		$this->db->select('*');
		$this->db->from('motor');
		$this->db->where('id_motor', $id_motor);
		return $this->db->get();
	}

	public function update_data($id_motor, $data)
	{
		$this->db->where('id_motor', $id_motor);
		$this->db->update('motor', $data);
	}

	public function insert_data($data)
	{
		$this->db->insert('motor', $data);
	}

	public function hapus_data($id_motor)
	{
		$this->db->where('id_motor', $id_motor);
		$this->db->delete('motor');
	}
	public function soft_delete_data($id_motor)
	{
		$data = array(
			'status_delete' => 1
		);
		$this->db->where('id_motor', $id_motor);
		$this->db->update('motor', $data);

		}
	}
