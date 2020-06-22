<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Motor extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Motor_model');
	}

	public function index()
	{
		echo 'asdsad';
	}

	public function data_motor()
	{
		$motor = $this->Motor_model->get_motor();
		$konten = 
		'<thead>
			<tr>
				<th>Merk</th>
				<th>Seri</th>
				<th>Foto</th>
				<th>Jenis</th>
				<th>Harga</th>
				<th>Tahun Pembuatan</th>
				<th>Aksi</th>
			</tr>
		</thead>';
		foreach ($motor->result() as $key => $value) {
		$konten .= '
			<tr>
				<td>'.$value->merek.'</td>
				<td>'.$value->seri.'</td>
				<td><img src="'.base_url().'foto/'.$value->id_motor.'/'.$value->foto_produk.'"
				width="50"></td>
				<td>'.$value->jenis.'</td>
				<td>'.$value->harga.'</td>
				<td>'.$value->tahun_pembuatan.'</td>
				<td>Read | <a href="#'.$value->id_motor.'" class="linkHapusMotor"> Hapus</a> | <a href="#'.$value->id_motor.'" class="linkEditMotor">Edit</a></td>
			</tr>';
			}

		$data_json = array(
		'konten' => $konten,
		);
		echo json_encode($data_json);
	}

	public function create_action()
	{
		$this->db->trans_start();

		$id_motor = $this->input->post('id_motor');

		$arr_input = array(
			'merek' => $this->input->post('merek'),
			'seri' => $this->input->post('seri'),
			'jenis' => $this->input->post('jenis'),
			'stok' => $this->input->post('stok'),
			'harga' => $this->input->post('harga'),
			'tahun_pembuatan' => $this->input->post('tahun_pembuatan'),
		);

		$this->Motor_model->insert_data($arr_input);

		$id_motor = $this->db->insert_id();

			if ($_FILES != null) {
				$this->upload_foto($id_motor, $_FILES);
			}

			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Simpan Data Motor');
			} else {
				$this->db->trans_commit();
				$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasi;; Simpan Data Motor');
			}

			echo json_encode($data_output);
		}

		public function update_action()
		{
			$this->db->trans_start();

			$id_motor = $this->input->post('id_motor');

			$arr_input = array(
				'merek' => $this->input->post('merek'),
				'seri' => $this->input->post('seri'),
				'jenis' => $this->input->post('jenis'),
				'stok' => $this->input->post('stok'),
				'harga' => $this->input->post('harga'),
				'tahun_pembuatan' => $this->input->post('tahun_pembuatan'),
			);

			$this->Motor_model->update_data($id_motor, $arr_input);

			if ($_FILES != null) {
				$this->upload_foto($id_motor, $_FILES);
			}

			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Update Data Motor');
			} else {
				$this->db->trans_commit();
				$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Update Data Motor');
			}	
			echo json_encode($data_output);
		}


		public function detail()
		{
			$id_motor = $this->input->get('id_motor');
			$data_detail = $this->Motor_model->get_motor_by_id($id_motor);

			if ($data_detail->num_rows() > 0) {
				$data_output = array('sukses' => 'ya', 'detail' => $data_detail->row_array());
			}else{
				$data_output = array('sukses' => 'tidak');
			}

			echo json_encode($data_output);
		}
		public function delete_data()
		{
			$this->db->trans_start();

			$id_motor = $this->input->get('id_motor');

			$this->Motor_model->hapus_data($id_motor);

			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Hapus Data Motor');
			} else {
				$this->db->trans_commit();
				$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Hapus Data Motor');
			}

			echo json_encode($data_output);
		}

		public function soft_delete_data()
		{
			$this->db->trans_start();

			$id_motor = $this->input->get('id_motor');

			$this->Motor_model->soft_delete_data($id_motor);

			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Hapus Data Motor');
			} else {
				$this->db->trans_commit();
				$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Hapus Data Motor');
			}

			echo json_encode($data_output);
		}

		private function upload_foto($id_motor, $files)
		{
			$gallerPath = realpath(APPPATH . '../foto');
			$path = $gallerPath.'/'.$id_motor;

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

			if ($this->upload->do_upload('file')){
				$data_motor = array(
					'foto_produk' => $this->upload->data('file_name')
				);

				$this->Motor_model->update_data($id_motor,$data_motor);
				return 'Success Upload';

			} else {
				return 'Error Upload';
		}

	}

}
