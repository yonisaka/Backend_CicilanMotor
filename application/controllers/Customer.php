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
}
