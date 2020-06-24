<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Admin_model');
	}

	public function get_admin()
	{
		$data_admin = $this->Admin_model->get_admin();
		$data = array();

		foreach ($data_admin->result() as $key => $value) {
			$data[] = $value;
		}

		$data_json = array(
			'data' => $data,
		);

		echo json_encode($data_json);
	}

	public function get_admin_by_id()
	{
		//get admin by session email

		$email = $this->session->email;

		$data_admin = $this->Admin_model->get_admin_by_email($email);

		foreach ($data_admin->result() as $key => $value) {
			$value;
		}

		$json = json_encode($value);

		$json = json_decode($json, true);


		$id_admin = $json['id_admin'];
		$data_admin = $this->Admin_model->get_admin_by_id($id_admin);

		if ($data_admin->num_rows() > 0){
			$data_output = array('sukses' => 'ya', 'detail' => $data_admin->row_array());
		} else {
			$data_output = array('sukses' => 'tidak');
		}

		echo json_encode($data_output);
	}

	public function get_admin_by_email()
	{

		$email = $this->session->userdata('email');
		$data_admin = $this->Admin_model->get_admin_by_email($email);

		foreach ($data_admin->result() as $key => $value) {
			$value;
		}

		$json = json_encode($value);

		$json = json_decode($json, true);
		echo $json['id_admin'];
		
	}
}
