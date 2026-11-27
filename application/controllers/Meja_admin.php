<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Meja_admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Menu_model');

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
	}

	public function index()
	{
		$data = array(
			'title' => 'Dashboard Kelola Meja',
			'meja' => $this->Menu_model->get_all_meja()
		);

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/meja_list', $data);
		$this->load->view('templates/footer_admin', $data);
	}

	public function tambah()
	{
		$this->form_validation->set_rules('nomor_meja', 'Nomor Meja', 'trim|required|max_length[20]');
		$this->form_validation->set_rules('status', 'Status Meja', 'trim|required|in_list[tersedia,terpakai,nonaktif]');

		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'title' => 'Dashboard Tambah Meja',
				'row' => null
			);

			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/meja_form', $data);
			$this->load->view('templates/footer_admin', $data);
		} else {
			$data_insert = array(
				'nomor_meja' => $this->input->post('nomor_meja', true),
				'status' => $this->input->post('status', true)
			);

			$this->Menu_model->insert_meja($data_insert);
			$this->session->set_flashdata('pesan', 'Meja berhasil ditambahkan');
			redirect('meja_admin');
		}
	}

	public function edit($id)
	{
		$row = $this->Menu_model->get_meja_by_id($id);
		if (!$row) {
			show_404();
		}

		$this->form_validation->set_rules('nomor_meja', 'Nomor Meja', 'trim|required');
		$this->form_validation->set_rules('status', 'Status Meja', 'required|in_list[tersedia,terpakai,nonaktif]');

		if ($this->form_validation->run() === FALSE) {
			$data = array(
				'title' => 'Edit meja',
				'row' => $row
			);

			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/meja_form', $data);
			$this->load->view('templates/footer_admin', $data);
			return;
		}

		$update_data = array(
			'nomor_meja' => $this->input->post('nomor_meja', true),
			'status' => $this->input->post('status', true)
		);

		if (!$this->Menu_model->update_meja($id, $update_data)) {
			show_error('Data meja gagal diperbarui.', 500);
		}
		$this->session->set_flashdata('pesan', 'Meja berhasil diupdate');
		redirect('meja_admin');
	}

	public function hapus($id)
	{
		if (!$this->Menu_model->delete_meja($id)) {
			show_error('Meja gagal dihapus. Pastikan meja tidak digunakan oleh pesanan.', 400);
		}
		$this->session->set_flashdata('pesan', 'meja berhasil dihapus');
		redirect('meja_admin');
	}
}
