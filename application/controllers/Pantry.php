<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pantry extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Order_model');
        $this->load->model('Menu_model');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
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

    public function tambah_stok() {
        $nama   = trim($this->input->post('nama_bahan', true));
        $satuan = $this->input->post('satuan', true);
        $jumlah = (float) $this->input->post('stok_gudang', true);

        if (!empty($nama) && $jumlah > 0) {
            $this->Order_model->simpan_stok_gudang($nama, $satuan, $jumlah);
        }
        redirect('pantry');
    }

    public function edit_stok($id) {
        $data = [
            'nama_bahan'  => trim($this->input->post('nama_bahan', true)),
            'satuan'      => $this->input->post('satuan', true),
            'stok_gudang' => (float) $this->input->post('stok_gudang', true)
        ];
        if (!empty($data['nama_bahan'])) {
            $this->Order_model->update_bahan($id, $data);
        }
        redirect('pantry');
    }

    public function hapus_stok($id) {
        $this->Order_model->hapus_bahan($id);
        redirect('pantry');
    }

    public function tambah_resep() {
        $menu_id  = (int) $this->input->post('menu_id', true);
        $bahan_id = (int) $this->input->post('bahan_id', true);
        $takaran  = (float) $this->input->post('takaran_per_porsi', true);

        if ($menu_id > 0 && $bahan_id > 0 && $takaran > 0) {
            $this->Order_model->simpan_resep_menu($menu_id, $bahan_id, $takaran);
        }
        redirect('pantry');
    }

    public function edit_resep($id) {
        $takaran = (float) $this->input->post('takaran_per_porsi', true);
        if ($takaran > 0) {
            $this->Order_model->update_resep($id, $takaran);
        }
        redirect('pantry');
    }

    public function hapus_resep($id) {
        $this->Order_model->hapus_resep($id);
        redirect('pantry');
    }
}