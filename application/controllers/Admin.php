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
	public function dashboard_kitchen()
	{
		$kode = $this->input->get('kode', true);
		$data = array(
			'title'  => 'Dashboard Dapur',
			'users'  => $this->ion_auth->user()->row(),
			'kode'   => $kode,
			'orders' => $this->Order_model->get_orders_dashboard($kode)
		);

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/dashboard_kitchen', $data);
		$this->load->view('templates/footer_admin', $data);
	}

	public function dashboard_pantry() 
	{
		$this->load->model('Menu_model');
		$target_porsi = 500;

		$data = array(
			'title'          => 'Dashboard Pantry',
			'users'          => $this->ion_auth->user()->row(),
			'target_porsi'   => $target_porsi,
			'menus'          => $this->Menu_model->get_all_menus(),
			'bahan_baku'     => $this->db->get('bahan_baku')->result(),
			'list_resep'     => $this->Order_model->get_all_resep(),
			'rekap_keluar'   => $this->Order_model->get_rekap_barang_keluar(),
			'rincian_keluar' => $this->Order_model->get_rincian_pengeluaran(),
			'analisis_bahan' => $this->Order_model->get_analisis_bahan_pantry($target_porsi)
		);

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/dashboard_pantry', $data);
		$this->load->view('templates/footer_admin', $data);
	}

	public function proses($kode_order = null)
	{
		if (!empty($kode_order))
		{
			$this->Order_model->update_status_order($kode_order, array(
				'status_pembayaran' => 'lunas',
				'status_pesanan'    => 'diproses' 
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

	public function selesai_kitchen($kode_order = null)
	{
		if (!empty($kode_order))
		{
			$this->Order_model->update_status_order($kode_order, array(
				'status_pesanan' => 'selesai'
			));
		}
		redirect('admin/dashboard_kitchen');
	}

	// Halaman Riwayat & Rekap Bulanan: http://localhost/ci3/manganrek/admin/laporan
	public function laporan()
	{
		// Default otomatis bulan ini (YYYY-MM), atau sesuai pilihan filter user
		$bulan_pilih = $this->input->get('bulan', true);
		if (empty($bulan_pilih)) {
			$bulan_pilih = date('Y-m');
		}

		$data = array(
			'title'               => 'Riwayat & Rekap Bulanan',
			'users'               => $this->ion_auth->user()->row(),
			'bulan_pilih'         => $bulan_pilih,
			'rekap_bulanan'       => $this->Order_model->get_rekap_bulanan(),
			'history_pemasukan'   => $this->Order_model->get_history_pemasukan($bulan_pilih),
			'history_pengeluaran' => $this->Order_model->get_history_pengeluaran($bulan_pilih)
		);

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/dashboard_laporan', $data);
		$this->load->view('templates/footer_admin', $data);
	}

	public function simpan_pengeluaran()
	{
		$data = array(
			'tanggal'            => $this->input->post('tanggal', true),
			'keterangan'         => trim($this->input->post('keterangan', true)),
			'jumlah_pengeluaran' => (float) $this->input->post('jumlah_pengeluaran', true)
		);

		if (!empty($data['tanggal']) && !empty($data['keterangan']) && $data['jumlah_pengeluaran'] > 0) {
			$this->Order_model->tambah_pengeluaran_uang($data);
		}
		redirect('admin/laporan');
	}

	public function hapus_pengeluaran($id)
	{
		$this->Order_model->hapus_pengeluaran_uang($id);
		redirect('admin/laporan');
	}
}