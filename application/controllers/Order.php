<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{
	#[Override]
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Menu_model');
		$this->load->model('Order_model');

		if (!$this->session->userdata('meja_nomro'))
		{
			$this->session->set_userdata('nomor_meja', '05');
		}
	}

	private function _cart()
	{
		$cart = $this->session->userdata('cart');
		if (is_array($cart))
		{
			return $cart;
		}
		return array();
	}

	private function _ringkasan($cart)
	{
		$qty = 0;
		$total = 0;

		foreach ($cart as $item)
		{
			$qty += $item['qty'];
			$total += $item['harga'] * $item['qty'];
		}

		return array
		(
			'total_qty' => $qty,
			'subtotal' => $total,
			'pajak' => 0,
			'total' => $total
		);
		
	}

	public function index($meja = null)
	{
		if (!empty($meja))
		{
			$this->session->set_userdata('nomor_meja', $meja);
		}

		$kategori = $this->input->get('kategori');
		if (empty($kategori))
		{
			$kategori_aktif = 'semua';
		}
		else
		{
			$kategori_aktif = $kategori;
		}

		$cart = $this->_cart();
		
		$data = array(
			'title' => 'daftar menu',
			'nomor_meja' => $this->session->userdata('nomor_meja'),	
			'menus' => $this->Menu_model->get_all_menus($kategori),
			'kategori_aktif' => $kategori_aktif,
			'cart_summary' => $this->_ringkasan($cart),
		);

		$this->load->view('templates/header_pelanggan', $data);
		$this->load->view('pelanggan/menu', $data);
		$this->load->view('templates/footer_pelanggan', $data);
	}

	public function tambah($menu_id)
	{
		$menu = $this->Menu_model->get_menu_by_id($menu_id);
		if ($menu)
		{
			$cart = $this->_cart();
			if (isset($cart[$menu->id]))
			{
				$cart[$menu->id]['qty'] = $cart[$menu->id]['qty'] + 1;
			}
			else
			{
				$cart[$menu->id] = array(
					'menu_id' => $menu_id,
					'nama_menu' => $menu->nama_menu,
					'harga' => (int) $menu->harga,
					'gambar' => $menu->gambar,
					'qty' => 1
				);
			}
			$this->session->set_userdata('cart', $cart);
		}
		redirect('order');
	}

	public function ubah_qty($menu_id, $aksi)
	{
		$cart = $this->_cart();
		if (isset($cart[$menu_id]))
		{
			if ($aksi === 'plus')
			{
				$cart[$menu_id]['qty']++;
			}
			elseif ($aksi === 'minus')
			{
				$cart[$menu_id]['qty']--;
				if ($cart[$menu_id]['qty'] <= 0)
				{
					unset($cart[$menu_id]);
				}
			}

			$this->session->set_userdata('cart', $cart);
		}
		redirect('order/keranjang');
	}

	public function keranjang()
	{
		$cart = $this->_cart();

		$data = array(
			'title' => 'keranjang pesanan',
			'nomor_meja' => $this->session->userdata('nomor_meja'),
			'cart_items' => $cart,
			'cart_summary' => $this->_ringkasan($cart)
		);

		$this->load->view('templates/header_pelanggan', $data);
		$this->load->view('pelanggan/keranjang', $data);
		$this->load->view('templates/footer_pelanggan', $data);
	}

	public function checkout()
	{
		$cart = $this->_cart();
		if (empty($cart))
		{
			redirect('order');
		}

		$this->form_validation->set_rules('nama_pemesan', 'Nama Pemesan', 'trim|required');
		$this->form_validation->set_rules('nomor_handphone', 'Nomor Handphone', 'trim|required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->keranjang();
			return;
		}	

		$meja = $this->session->userdata('nomor_meja');
		$meja_row = $this->Menu_model->get_meja_by_nomor($meja);
		$ringakasan = $this->_ringkasan($cart);
		$kode_order = 'ORD-' . date('Ymd') . '-' . $meja . '-' . rand(100, 999);
		
		if ($meja_row)
		{
			$meja_id = $meja_row->id;
		}
		else
		{
			$meja_id = null;
		}

		$order_data = array(
			'kode_order' => $kode_order,
			'meja_id' => $meja_id,
			'nama_pemesan' => $this->input->post('nama_pemesan', true),
			'nomor_handphone' => $this->input->post('nomor_handphone', true),
			'catatan_umum' => $this->input->post('catatan_umum', true),
			'pajak_layanan' => 0,
			'subtotal' => $ringakasan['total'],
			'status_pembayaran' => 'belum_bayar',
			'status_pesanan' => 'menunggu'
		);

		if (!$this->Order_model->create_order($order_data, $cart))
		{
			show_error('gagal menyimpan pesanna', 500);
		}

		$this->session->unset_userdata('cart');
		redirect('order/status_pesanan/' . $kode_order);
	}

	public function status_pesanan($kode_order = null)
	{
		$order = null;
		$items = array();

		if (!empty($kode_order))
		{
			$order = $this->Order_model->get_order_by_kode($kode_order);
			if ($order)
			{
				$items = $this->Order_model->get_order_items($order->id);
			}
		}

		if ($order && !empty($order->nomor_meja))
		{
			$nomor_meja = $order->nomor_meja;
		}
		else
		{
			$nomor_meja = $this->session->userdata('nomor_meja');
		}

		$data = array(
			'title' =>	'kode qr pesanan',
			'nomor_meja' => $nomor_meja,
			'order' => $order,
			'order_items' => $items,
		);

		$this->load->view('templates/header_pelanggan', $data);
		$this->load->view('pelanggan/status_pesanan', $data);
		$this->load->view('templates/footer_pelanggan', $data);
	}
}
