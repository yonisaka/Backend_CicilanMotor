<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angsuran extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Angsuran_model');
	}

	public function create_action()
	{
		$this->db->trans_start();

		$arr_input = array(
			'id_transaksi' => $this->input->post('id_transaksi'),
			'kali_angsuran' => $this->input->post('kali_angsuran'),
			'tanggal_angsuran' => $this->input->post('tanggal'),
			);

		$this->Angsuran_model->insert_data($arr_input);

		$id_angsuran = $this->db->insert_id();

		if ($_FILES != null) {
			$this->upload_foto($id_angsuran, $_FILES);
		}

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Input Data Angsuran');
		} else {
			$this->db->trans_commit();
			$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Input Data Angsuran');
		}

		echo json_encode($data_output);
	}

	private function upload_foto($id_angsuran, $files)
	{
		$gallerPath = realpath(APPPATH . '../bukti_angsuran');
		$path = $gallerPath.'/'.$id_angsuran;

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
			$data_angsuran = array(
				'bukti_angsuran' => $this->upload->data('file_name')
			);

			$this->Angsuran_model->update_data($id_angsuran, $data_angsuran);
			return 'Success Upload';

		} else {
			return 'Error Upload';
		}
	}
}
