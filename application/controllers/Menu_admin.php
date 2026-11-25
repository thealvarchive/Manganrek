<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Menu_model');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
	}

	public function index()
	{
		$data = array(
			'title' => 'Dashboard Kelola Menu',
			'menus' => $this->Menu_model->get_all_menus_admin()
		);

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/menu_list', $data);
		$this->load->view('templates/footer_admin', $data);
	}

	private function upload_menu_image()
	{
		if (empty($_FILES['gambar']['name']))
		{
			return null;
		}

		$upload_path = FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'menu' . DIRECTORY_SEPARATOR;
		if (!is_dir($upload_path) && !mkdir($upload_path, 0755, TRUE) && !is_dir($upload_path))
		{
			show_error('Folder penyimpanan gambar menu tidak dapat dibuat.', 500);
		}

		$this->load->library('upload');
		$this->upload->initialize(array(
			'upload_path' => $upload_path,
			'allowed_types' => 'jpg|jpeg|png|webp',
			'max_size' => 2048,
			'encrypt_name' => TRUE,
			'file_ext_tolower' => TRUE
		));

		if (!$this->upload->do_upload('gambar'))
		{
			show_error('Gambar gagal diunggah: ' . strip_tags($this->upload->display_errors('', '')), 400);
		}

		$uploaded = $this->upload->data();
		return $uploaded['file_name'];
	}

	public function tambah()
	{
		$this->form_validation->set_rules('nama_menu', 'Nama Menu', 'trim|required');
		$this->form_validation->set_rules('kategori', 'Kategori', 'required|in_list[Makanan Berat,Minuman,Camilan]');
		$this->form_validation->set_rules('harga', 'Harga', 'trim|required|integer|greater_than[0]');
		$this->form_validation->set_rules('tersedia', 'Status Menu', 'required|in_list[0,1]');

		if ($this->form_validation->run() === FALSE)
		{
			$data = array(
				'title' => 'Tambah Menu',
				'menus' => null
			);

			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/menu_form', $data);
			$this->load->view('templates/footer_admin', $data);
			return;
		}

		$tersedia_post = $this->input->post('tersedia');
		if ($tersedia_post === '1')
		{
			$tersedia = 1;
		}
		else
		{
			$tersedia = 0;
		}

		$gambar = $this->upload_menu_image();
		$data_insert = array(
			'nama_menu' => $this->input->post('nama_menu', true),
			'kategori' => $this->input->post('kategori', true),
			'harga' => (int) $this->input->post('harga', true),
			'gambar' => $gambar === null ? '' : $gambar,
			'tersedia' => $tersedia
		);

		if (!$this->Menu_model->insert_menu($data_insert))
		{
			show_error('Menu gagal disimpan.', 500);
		}
		$this->session->set_flashdata('pesan', 'menu berhasil ditambahkan');
		redirect('menu_admin');
	}

	public function	edit($id)
	{
		$menu = $this->Menu_model->get_menu_by_id_admin($id);
		if (!$menu)
		{
			redirect('menu_admin');
		}

		$this->form_validation->set_rules('nama_menu', 'Nama Menu', 'trim|required');
		$this->form_validation->set_rules('kategori', 'Kategori', 'required|in_list[Makanan Berat,Minuman,Camilan]');
		$this->form_validation->set_rules('harga', 'Harga', 'trim|required|integer|greater_than[0]');
		$this->form_validation->set_rules('tersedia', 'Status Menu', 'required|in_list[0,1]');

		if ($this->form_validation->run() === FALSE)
		{
			$data = array(
				'title' => 'Edit menu',
				'menu' => $menu
			);

			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/menu_form', $data);
			$this->load->view('templates/footer_admin', $data);
			return;
		}

		$tersedia_post = $this->input->post('tersedia');
		if ($tersedia_post === '1')
		{
			$tersedia = 1;
		}
		else
		{
			$tersedia = 0;
		}

		$gambar = $this->upload_menu_image();
		$data_update = array(
			'nama_menu' => $this->input->post('nama_menu', true),
			'kategori' => $this->input->post('kategori', true),
			'harga' => (int) $this->input->post('harga', true),
			'gambar' => $gambar === null ? $menu->gambar : $gambar,
			'tersedia' => $tersedia
		);

		if (!$this->Menu_model->update_menu($id, $data_update))
		{
			show_error('Menu gagal diperbarui.', 500);
		}
		$this->session->set_flashdata('pesan', 'menu berhasil diupdate');
		redirect('menu_admin');
	}

	public function hapus($id)
	{
		$this->Menu_model->delete_menu($id);
		$this->session->set_flashdata('pesan', 'menu berhasil dihapus');
		redirect('menu_admin');
	}
}
?>
