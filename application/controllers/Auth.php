<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Auth_model');
	}

	function login_action(){

		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$where = array(
			'email' => $email,
			'password' => md5($password)
			);

		$cek = $this->Auth_model->cek_login("customer", $where)->num_rows();

		if($cek > 0){
			$data_session = array(
				'email' => $email,
				'status' => "login"
				);
 
			$this->session->set_userdata($data_session);
			echo "success";
		} else {
			echo "error";
		}
	}

	function logout(){
		$this->db->trans_start();
		$this->session->sess_destroy();

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Logout');
		} else {
			$this->db->trans_commit();
			$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Logout');
		}

		echo json_encode($data_output);
		
	}
	
}
