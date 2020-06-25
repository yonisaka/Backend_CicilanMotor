<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Transaksi_model');
		$this->load->model('Customer_model');
		$this->load->model('Transaksi_baru_model');
	}

	public function create_action(){
		$this->db->trans_start();

		$id_motor = $this->input->post('id_motor');

		$arr_input = array(
			'id_customer' => $this->input->post('id_customer'),
			'id_motor' => $this->input->post('id_motor'),
			'jangka_waktu' => $this->input->post('kali_angsuran'),
			'nominal_angsuran' => $this->input->post('perbulan'),
			'total_pembelian' => $this->input->post('total_pembelian'),
		);

		$this->Transaksi_model->insert_data($arr_input);

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Simpan Data Motor');
		} else {
			$this->db->trans_commit();
			$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasi;; Simpan Data Motor');
		}

		echo json_encode($data_output);
	}

	public function get_transaksi_by_id()
	{
		$email = $this->session->userdata('email');

		$data_customer = $this->Customer_model->get_customer_by_email($email);

		foreach ($data_customer->result() as $key => $value) {
			$value;
		}

		$json = json_encode($value);

		$json = json_decode($json, true);


		$id_customer = $json['id_customer'];
		
		$data_customer = $this->Transaksi_model->get_transaksi_by_id($id_customer);

		if ($data_customer->num_rows() > 0){
			$data_output = array('sukses' => 'ya', 'detail' => $data_customer->row_array());
		} else {
			$data_output = array('sukses' => 'tidak');
		}

		echo json_encode($data_output);
	}

	public function get_dt_transaksi_angsuran_by_id()
	{
		$email = $this->session->userdata('email');

		$data_customer = $this->Customer_model->get_customer_by_email($email);

		foreach ($data_customer->result() as $key => $value) {
			$value;
		}

		$json = json_encode($value);

		$json = json_decode($json, true);

		$id_customer = $json['id_customer'];

		$limit = $this->input->get('length');
		$offset = $this->input->get('start');
		$orderby = $this->input->get('order[0][column]');
		$method = $this->input->get('order[0][dir]');
		$search = $this->input->get('search[value]');

		if ($orderby == 0) {
			$orderby = "tanggal_angsuran";
		}elseif($orderby == 1){
			$orderby = "nominal_angsuran";
		}elseif($orderby == 2){
			$orderby = "kali_angsuran";
		}elseif($orderby == 3){
			$orderby = "sisa_angsuran";
		}else {
			$orderby = "status";
		}

		$data_transaksi = $this->Transaksi_model->get_dt_transaksi_angsuran_by_id($id_customer, $limit, $offset, $orderby, $method, $search);

		$draw = (int) $this->input->get('draw');
		$recordsTotal = $this->Transaksi_model->get_dt_transaksi_angsuran_by_id($id_customer, $limit, $offset, $orderby, $method, $search)->num_rows();
		$recordsFiltered = $recordsTotal;
		$data = array();

		foreach ($data_transaksi->result() as $key => $value) {
			$row = array();
			$row[] = $value->tanggal_angsuran;
			$row[] = $value->nominal_angsuran;
			$row[] = $value->kali_angsuran;
			$row[] = $value->sisa_angsuran;
			$row[] = $value->status;
			
			$data[] = $row;
		}

		$data_json = array(
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data,
		);

		echo json_encode($data_json);
	}

	public function get_transaksi_by_id_customer()
	{
		$id_customer = $this->input->get('id_customer');
		$data_detail = $this->Transaksi_model->get_transaksi_by_id_customer($id_customer);

		if ($data_detail->num_rows() > 0) {
			$data_output = array(
				'sukses' => 'ya', 
				'detail' => $data_detail->row_array());
		}else{
			$data_output = array('sukses' => 'tidak');
		}

		echo json_encode($data_output);
	}

	public function get_all_transaksi()
	{
		$limit = $this->input->get('length');
		$offset = $this->input->get('start');
		$orderby = $this->input->get('order[0][column]');
		$method = $this->input->get('order[0][dir]');
		$search = $this->input->get('search[value]');

		if ($orderby == 0) {
			$orderby = "id_transaksi";
		} elseif ($orderby == 1) {
			$orderby = "id_customer";
		} elseif ($orderby == 2) {
			$orderby = "id_motor";
		} elseif ($orderby == 3) {
			$orderby = "waktu_pembelian";
		} elseif ($orderby == 4) {
			$orderby = "jangka_waktu";
		}elseif ($orderby == 5) {
			$orderby = "nominal_angsuran";
		}else {
			$orderby = "total_pembelian";
		}

		$data_transaksi = $this->Transaksi_baru_model->get_transaksi($limit, $offset, $orderby,
		$method, $search);

		$draw = (int) $this->input->get('draw');
		$recordsTotal = $this->Transaksi_baru_model->get_transaksi($limit, $offset, $orderby,
		$method, $search)->num_rows();
		$recordsFiltered = $recordsTotal;
		$data = array();

		foreach ($data_transaksi->result() as $key => $value) 
		{
			$row = array();
			$row[] = $value->id_transaksi;
			$row[] = $value->id_customer;
			$row[] = $value->id_motor;
			$row[] = $value->waktu_pembelian;
			$row[] = $value->jangka_waktu;
			$row[] = $value->nominal_angsuran;
			$row[] = $value->total_pembelian;

			$data[] = $row;
		}

		$data_json = array(
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data,
		);

		echo json_encode($data_json);
	}
}