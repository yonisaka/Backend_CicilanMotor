<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Customer_model');
	}

	public function index()
	{
		echo 'asdsad';
	}

	public function data_customer()
	{
		$data_customer = $this->Customer_model->get_customer();
		$konten = 
		'<thead>
			<tr>
				<th>Nama</th>
				<th>Email</th>
				<th>Nomor KTP</th>
				<th>Pekerjaan</th>
				<th>Nomor Telepon</th>
				<th>Alamat</th>
				<th>Status</th>
				<th>Aksi</th>
			</tr>
		</thead>';
		foreach ($data_customer->result() as $key => $value) {
		$konten .= '
		<tbody>
			<tr>
				<td>'.$value->nama.'</td>>
				<td>'.$value->email.'</td>
				<td>'.$value->no_ktp.'</td>
				<td>'.$value->pekerjaan.'</td>
				<td>'.$value->no_telepon.'</td>
				<td>'.$value->alamat.'</td>
				<td>'.$value->status.'</td>
				<td>Read | <a href="#'.$value->id_customer.'" class="linkHapusCustomer"> Hapus</a> | <a href="#'.$value->id_customer.'" class="linkEditCustomer">Edit</a></td>
			</tr>
		</tbody>
			';
			}

		$data_json = array(
			'konten' => $konten,
		);
		echo json_encode($data_json);
	}

		public function update_action()
		{
			$this->db->trans_start();

			$id_customer = $this->input->post('id_customer');

			$arr_input = array(
				'nama' => $this->input->post('nama'),
				'email' => $this->input->post('email'),
				'no_ktp' => $this->input->post('no_ktp'),
				'pekerjaan' => $this->input->post('pekerjaan'),
				'no_telepon' => $this->input->post('no_telepon'),
				'alamat' => $this->input->post('alamat'),
				'status' => $this->input->post('status'),
			);


			$this->Customer_model->update_data($id_customer, $arr_input);


			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Update Data Customer');
			} else {
				$this->db->trans_commit();
				$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Update Data Customer');
			}	
			echo json_encode($data_output);
		}

		public function delete_data()
		{
			$this->db->trans_start();

			$id_customer = $this->input->get('id_customer');

			$this->Customer_model->hapus_data($id_customer);

			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Hapus Data Customer');
			} else {
				$this->db->trans_commit();
				$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Hapus Data Customer');
			}

			echo json_encode($data_output);
		}

		public function soft_delete_data()
		{
			$this->db->trans_start();

			$id_customer = $this->input->get('id_customer');

			$this->Customer_model->soft_delete_data($id_customer);

			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$data_output = array('sukses' => 'tidak', 'pesan' => 'Gagal Hapus Data Customer');
			} else {
				$this->db->trans_commit();
				$data_output = array('sukses' => 'ya', 'pesan' => 'Berhasil Hapus Data Customer');
			}

			echo json_encode($data_output);
		}

		public function detail()
		{
			$id_customer = $this->input->get('id_customer');
			$data_detail = $this->Customer_model->get_customer_by_id($id_customer);

			if ($data_detail->num_rows() > 0) {
				$data_output = array('sukses' => 'ya', 'detail' => $data_detail->row_array());
			}else{
				$data_output = array('sukses' => 'tidak');
			}

			echo json_encode($data_output);
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

		$data_customer = $this->Customer_model->get_customer_by_email($email);

		foreach ($data_customer->result() as $key => $value) {
			$value;
		}

		$json = json_encode($value);

		$json = json_decode($json, true);
		echo $json['id_customer'];	
	}
}