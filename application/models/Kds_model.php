<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kds_model extends CI_Model {

    public function get_antrean() {
        $this->db->where('status', 'dimasak');
        $this->db->order_by('id', 'ASC');
        return $this->db->get('kds_pesanan')->result();
    }

    public function kirim_pesanan($data) {
        return $this->db->insert('kds_pesanan', $data);
    }

    public function selesai_masak($id) {
        $this->db->where('id', $id);
        return $this->db->update('kds_pesanan', ['status' => 'selesai']);
    }
}