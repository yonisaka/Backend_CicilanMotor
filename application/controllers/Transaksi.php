<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Transaksi_model');
		$this->load->model('Customer_model');
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
}
