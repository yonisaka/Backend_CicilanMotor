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

	function register_action(){
		$this->db->trans_start();

		$arr_input = array(
			'nama' => $this->input->post('nama'),
			'email' => $this->input->post('email'),
			'password' => md5($this->input->post('password')),
			'no_ktp' => $this->input->post('ktp'),
			'no_telepon' => $this->input->post('telp'),
			'pekerjaan' => $this->input->post('pekerjaan'),
			'alamat' => $this->input->post('alamat'),
			// 'status' => $this->input->post('status')
			);

		$this->Auth_model->register_data($arr_input);

		$id_customer = $this->db->insert_id();

		if ($_FILES != null) {
			$this->upload_foto($id_customer, $_FILES);
		}

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Input Data Barang');
		} else {
			$this->db->trans_commit();
			$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Input Data Barang');
		}

		echo json_encode($data_output);
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

	private function upload_foto($id_customer, $files)
	{
		$gallerPath = realpath(APPPATH . '../foto_customer');
		$path = $gallerPath.'/'.$id_customer;

		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}

		$konfigurasi = array(
			'allowed_types' => 'jpg|png|jpeg',
			'upload_path' => $path,
			'overwrite' => true
			);

		$this->load->library('upload',$konfigurasi);

		$_FILES['file']['name'] = $files['file']['name'];
		$_FILES['file']['type'] = $files['file']['type'];
		$_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
		$_FILES['file']['error'] = $files['file']['error'];
		$_FILES['file']['size'] = $files['file']['size'];

		if ($this->upload->do_upload('file')) {
			$data_customer = array(
				'foto' => $this->upload->data('file_name')
			);

			$this->Auth_model->add_foto($id_customer,$data_customer);
			return 'Success Upload';

		} else {
			return 'Error Upload';
		}
	}
	
}
