<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Transaksi_model');
	}

	public function get_transaksi_by_id()
	{
		$id_customer = 1;
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
		$id_customer = 1;
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
		}else{
			$orderby = "total_pembelian";
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
