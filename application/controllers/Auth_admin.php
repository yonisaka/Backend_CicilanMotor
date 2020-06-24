<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_admin_model');
	}

	public function check_login()
	{
		$email=$this->input->post('email');
		$password=$this->input->post('password');

		$cek=$this->Auth_admin_model->cek_login($email, md5($password));

		if ($cek->num_rows() > 0)
		{
			$data_json = array(
				'sukses' => 'Ya', 
				'pesan' => 'Berhasil Login!',
				'admin' => $cek->row_array()
			);
		} else {
			$data_json = array('sukses' => 'Tidak', 'pesan' => 'Gagal Login!');
		}
		echo json_encode($data_json);
	}

	function register_action(){
		$this->db->trans_start();

		$arr_input = array(
			'nama' => $this->input->post('nama'),
			'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password')),
            'divisi_pekerjaan' => $this->input->post('divisi_pekerjaan'),
			'alamat' => $this->input->post('alamat'),
			'no_telepon' => $this->input->post('telp'),
			// 'status' => $this->input->post('status')
			);

		$this->Auth_admin_model->register_data($arr_input);

		$id_admin = $this->db->insert_id();

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Input Data Admin');
		} else {
			$this->db->trans_commit();
			$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Input Data Admin');
		}

		echo json_encode($data_output);
	}
}
