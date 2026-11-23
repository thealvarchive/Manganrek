<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Order_model');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		if (!$this->ion_auth->in_group(array('admin', 'kasir')))
		{
			show_error('Anda tidak memiliki hak akses ke dashboard', 403, 'Akses ditolak');
		}
	}
	public function index()
	{
		$kode = $this->input->get('kode', true);
		$data = array
		(
			'title'  => 'Dashboard Pesanan',
			'users' => $this->ion_auth->user()->row(),
			'kode'   => $kode,
			'orders' => $this->Order_model->get_orders_dashboard($kode)
		);

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/dashboard', $data);
		$this->load->view('templates/footer_admin', $data);

	}

	public function proses($kode_order = null)
	{
		if (!empty($kode_order))
		{
			$this->Order_model->update_status_order($kode_order, array(
				'status_pembayaran' => 'lunas',
				'status_pesanan' => 'selesai'
			));
		}
		redirect('admin');
	}

	public function selesai($kode_order = null)
	{
		if (!empty($kode_order))
		{
			$this->Order_model->update_status_order($kode_order, array(
			'status_pesanan' => 'selesai'
			));
		}
		redirect('admin');
	}
}
