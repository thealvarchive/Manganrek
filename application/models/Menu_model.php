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

// CRUD Menu Dashboard Admin
	public function get_all_menus_admin()
	{
		$this->db->order_by('id', 'ASC');
		return $this->db->get('menus')->result();
	}

	public function get_menu_by_id_admin($id)
	{
		return $this->db->get_where('menus', array(
			'id' => (int) $id
		))->row();
	}

	public function insert_menu($data)
	{
		return $this->db->insert('menus', $data);
	}

	public function update_menu($id, $data)
	{
		$this->db->where('id', (int) $id);
		return $this->db->update('menus', $data);
	}

	public function delete_menu($id)
	{
		$this->db->where('id', (int) $id);
		return $this->db->delete('menus');
	}

// CRUD Meja Dashboard Admin
	public function get_all_meja()
	{
		$this->db->order_by('id', 'ASC');
		return $this->db->get('meja')->result();
	}

	public function get_meja_by_id($id)
	{
		return $this->db->get_where('meja', array(
			'id' => (int) $id
		))->row();
	}

	public function insert_meja($data)
	{
		return $this->db->insert('meja', $data);
	}

	public function update_meja($id, $data)
	{
		$this->db->where('id', (int) $id);
		return $this->db->update('meja', $data);
	}

	public function delete_meja($id)
	{
		$this->db->where('id', (int) $id);
		return $this->db->delete('meja');
	}
}
