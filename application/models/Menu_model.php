<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function get_all_menus($kategori = null)
    {
        $this->db->where('tersedia', 1);
        if (!empty($kategori) && $kategori !== 'Semua') {
            $this->db->where('kategori', $kategori);
        }
        $this->db->order_by('id', 'ASC');
        return $this->db->get('menus')->result();
    }

    public function get_menu_by_id($id)
    {
        return $this->db->get_where('menus', [
            'id'       => (int) $id,
            'tersedia' => 1
        ])->row();
    }

    public function get_meja_by_nomor($nomor_meja)
    {
        return $this->db->get_where('meja', [
            'nomor_meja' => $nomor_meja
        ])->row();
    }
}
