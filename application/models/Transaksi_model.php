<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {

	public function get_transaksi_by_id($id_customer)
	{
		$this->db->select('*');
		$this->db->from('transaksi');
		$this->db->where('id_customer', $id_customer);
		$this->db->order_by('id_angsuran', 'desc');
		$this->db->limit(1);
		$this->db->join('angsuran', 'angsuran.id_transaksi = transaksi.id_transaksi');

		return $this->db->get();
	}

	public function get_dt_transaksi_angsuran_by_id($id_customer, $limit, $offset, $orderby, $method, $search)
	{
		$this->db->select('*');
		$this->db->from('transaksi');
		$this->db->where('id_customer', $id_customer);
		$this->db->join('angsuran', 'angsuran.id_transaksi = transaksi.id_transaksi');

		if($limit > -1){
			$this->db->limit($limit, $offset);
		}

		if($search != '' && $search!= null){
			$this->db->like('kali_angsuran', $search);
		}

		$this->db->order_by($orderby, $method);

		return $this->db->get();
	}

	public function insert_data($data)
	{
		$this->db->insert('transaksi', $data);
	}

	public function get_transaksi_by_id_customer($id_customer)
	{
		$this->db->select('*');
		$this->db->from('transaksi');
		$this->db->where('id_customer', $id_customer);

		return $this->db->get();
	}
}
