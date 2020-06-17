<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Customer_model');
	}

	public function get_customer()
	{
		$data_customer = $this->Customer_model->get_customer();
		$data = array();

		foreach ($data_customer->result() as $key => $value) {
			$data[] = $value;
		}

		$data_json = array(
			'data' => $data,
		);

		echo json_encode($data_json);
	}

	public function get_customer_by_id()
	{
		$id_customer = 1;
		$data_customer = $this->Customer_model->get_customer_by_id($id_customer);

		if ($data_customer->num_rows() > 0){
			$data_output = array('sukses' => 'ya', 'detail' => $data_customer->row_array());
		} else {
			$data_output = array('sukses' => 'tidak');
		}

		echo json_encode($data_output);
	}
}
