<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Angsuran extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Angsuran_model');
	}

	public function data_angsuran() {
		$limit = $this->input->get('length');
		$offset = $this->input->get('start');
		$orderby = $this->input->get('order[0][column]');
		$method = $this->input->get('order[0][dir]');
		$search = $this->input->get('search[value]');

		if ($orderby == 0) {
			$orderby = "id_transaksi";
		}elseif($orderby == 1){
			$orderby = "tanggal_angsuran";
		}elseif($orderby == 2){
			$orderby = "kali_angsuran";
		}elseif($orderby == 3){
			$orderby = "bukti_angsuran";
		}else {
			$orderby = "id_transaksi";
		}

		$data_angsuran = $this->Angsuran_model->get_all_angsuran($limit, $offset, $orderby, $method, $search);

		$draw = (int)$this->input->get('draw');
		$recordsTotal = $this->Angsuran_model->get_all_angsuran($limit, $offset, $orderby, $method, $search)->num_rows();
		$recordsFiltered = $recordsTotal;
		$data = array();

		foreach ($data_angsuran->result() as $key => $value) {
			$row = array();
			$row[] = $value->id_transaksi;
			$row[] = $value->tanggal_angsuran;
			$row[] = $value->kali_angsuran;
			$row[] = '<a href="'.base_url().'/bukti_angsuran/'.$value->id_angsuran.'/'.$value->bukti_angsuran.'"><img src="'.base_url().'/bukti_angsuran/'.$value->id_angsuran.'/'.$value->bukti_angsuran.'" width="50"></a>';
			$row[] = '<a href="#'.$value->id_angsuran.'" class="linkKonfirmasiAngsuran">Konfirmasi</a>';
			
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

	public function create_action()
	{
		$this->db->trans_start();

		$arr_input = array(
			'id_transaksi' => $this->input->post('id_transaksi'),
			'kali_angsuran' => $this->input->post('kali_angsuran'),
			'tanggal_angsuran' => $this->input->post('tanggal'),
			'sisa_angsuran' => $this->input->post('sisa_angsuran'),
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
