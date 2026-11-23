<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{
	public function create_order($header_data, $cart_items)
	{
		$this->db->trans_start();

		$this->db->insert('orders', $header_data);
		$order_id = $this->db->insert_id();

		foreach ($cart_items as $item) {
			$details = [
				'order_id' => $order_id,
				'menu_id' => $item['menu_id'],
				'nama_menu' => $item['nama_menu'],
				'harga_satuan' => $item['harga'],
				'qty' => $item['qty'],
				'subtotal' => $item['harga'] * $item['qty']
			];
			$this->db->insert('order_items', $details);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return FALSE;
		}

		return $this->get_order_by_kode($header_data['kode_order']);
	}

	public function get_order_by_kode($kode_order)
	{
		$this->db->select('orders.*, meja.nomor_meja');
		$this->db->from('orders');
		$this->db->join('meja', 'meja.id = orders.meja_id', 'left');
		$this->db->where('orders.kode_order', $kode_order);
		return $this->db->get()->row();
	}

	public function get_order_items($order_id)
	{
		return $this->db->get_where('order_items', [
			'order_id' => (int) $order_id
		])->result();
	}

	public function get_orders_dashboard($keyword_kode = null)
	{
		$this->db->select('orders.*, meja.nomor_meja');
		$this->db->from('orders');
		$this->db->join('meja', 'meja.id = orders.meja_id', 'left');

		if (!empty($keyword_kode)) {
			$clean_kode = ltrim(trim($keyword_kode), '#');
			$this->db->like('orders.kode_order', $clean_kode);
		} else {
			$this->db->where('DATE(orders.created_at)', date('Y-m-d'));
		}

		$this->db->order_by("FIELD(orders.status_pesanan, 'menunggu', 'diproses', 'selesai', 'dibatalkan')", '', FALSE);
		$this->db->order_by('orders.created_at', 'DESC');
		return $this->db->get()->result();
	}

	public function get_summary_hari_ini()
	{
		$today = date('Y-m-d');

		$menunggu = $this->db->where('DATE(created_at)', $today)
			->where('status_pesanan', 'menunggu')
			->count_all_results('orders');

		$diproses = $this->db->where('DATE(created_at)', $today)
			->where('status_pesanan', 'diproses')
			->count_all_results('orders');

		$selesai  = $this->db->where('DATE(created_at)', $today)
			->where('status_pesanan', 'selesai')
			->count_all_results('orders');

		return [
			'menunggu' => $menunggu,
			'diproses' => $diproses,
			'selesai'  => $selesai
		];
	}

	public function update_status_order($kode_order, $data_update)
	{
		$this->db->where('kode_order', $kode_order);
		return $this->db->update('orders', $data_update);
	}
}
