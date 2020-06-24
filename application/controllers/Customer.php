<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
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
		//get customer by session email

		$email = $this->session->email;

		$data_customer = $this->Customer_model->get_customer_by_email($email);

		foreach ($data_customer->result() as $key => $value) {
			$value;
		}

		$json = json_encode($value);

		$json = json_decode($json, true);


		$id_customer = $json['id_customer'];
		$data_customer = $this->Customer_model->get_customer_by_id($id_customer);
		$data_foto = $data_customer->row_array();
		$foto_customer = '<img src="'.base_url().'foto_customer/'.$id_customer.'/'.$data_foto['foto'].'" alt="User Avatar" class="rounded-circle user-avatar-xxl">';

		if ($data_customer->num_rows() > 0){
			$data_output = array(
				'sukses' => 'ya', 
				'detail' => $data_customer->row_array(),
				'foto' => $foto_customer
			);
		} else {
			$data_output = array('sukses' => 'tidak');
		}

		echo json_encode($data_output);
	}

	public function get_customer_by_email()
	{
		$email = $this->session->userdata('email');

		// $email = "yonisaka@gmail.com";
		$data_customer = $this->Customer_model->get_customer_by_email($email);

		foreach ($data_customer->result() as $key => $value) {
			$value;
		}

		$json = json_encode($value);

		$json = json_decode($json, true);
		echo $json['id_customer'];
		
	}
}
