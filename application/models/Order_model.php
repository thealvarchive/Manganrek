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

	// Ambil semua pesanan yang masuk untuk layar dapur
	public function get_orders_kitchen() 
	{	
		$this->db->select('orders.*, meja.no_meja'); 
		$this->db->from('orders');
		$this->db->join('meja', 'meja.id = orders.meja_id', 'left');
		$this->db->where_in('orders.status', ['pending', 'dimasak', 'proses']); 
		$orders = $this->db->get()->result();

    // Ambil rincian item menu untuk setiap pesanan
    foreach ($orders as $order) {
        $this->db->select('order_items.*, menus.nama_menu');
        $this->db->from('order_items');
        $this->db->join('menus', 'menus.id = order_items.menu_id', 'left');
        $this->db->where('order_items.order_id', $order->id);
        $order->items = $this->db->get()->result();
    }

		return $orders;
	}

	// Ubah status pesanan jadi selesai saat diklik koki
	public function update_status_kitchen($id, $status = 'selesai') 
	{
		$this->db->where('id', $id);
		return $this->db->update('orders', ['status' => $status]);
	}

    // Rekap total porsi keluar per menu
    public function get_rekap_barang_keluar() {
        $this->db->select('menus.nama_menu, menus.kategori, SUM(order_items.qty) as total_keluar');
        $this->db->from('order_items');
        $this->db->join('orders', 'orders.id = order_items.order_id');
        $this->db->join('menus', 'menus.id = order_items.menu_id', 'left');
        $this->db->where_in('orders.status_pesanan', ['diproses', 'selesai']);
        $this->db->where("STR_TO_DATE(SUBSTRING(orders.kode_order, 5, 8), '%Y%m%d') = CURDATE()", null, false);
        $this->db->group_by('order_items.menu_id');
        $this->db->order_by('total_keluar', 'DESC');
        return $this->db->get()->result();
    }

    // Log rincian pengeluaran per pesanan
    public function get_rincian_pengeluaran() {
        $this->db->select('orders.kode_order, orders.status_pesanan, meja.nomor_meja, menus.nama_menu, order_items.qty');
        $this->db->from('order_items');
        $this->db->join('orders', 'orders.id = order_items.order_id');
        $this->db->join('menus', 'menus.id = order_items.menu_id', 'left');
        $this->db->join('meja', 'meja.id = orders.meja_id', 'left');
        $this->db->where_in('orders.status_pesanan', ['diproses', 'selesai']);
        $this->db->where("STR_TO_DATE(SUBSTRING(orders.kode_order, 5, 8), '%Y%m%d') = CURDATE()", null, false);
        $this->db->order_by('orders.id', 'DESC');
        return $this->db->get()->result();
    }

    // Analisis kebutuhan bahan baku & kecukupan stok gudang
    public function get_analisis_bahan_pantry($target_porsi = 500) {
        $bahan_list = $this->db->get('bahan_baku')->result();

        $this->db->select('order_items.menu_id, SUM(order_items.qty) as terjual');
        $this->db->from('order_items');
        $this->db->join('orders', 'orders.id = order_items.order_id');
        $this->db->where_in('orders.status_pesanan', ['diproses', 'selesai']);
        $this->db->group_by('order_items.menu_id');
        $terjual_rows = $this->db->get()->result();

        $map_terjual = [];
        foreach ($terjual_rows as $tr) {
            $map_terjual[$tr->menu_id] = (int) $tr->terjual;
        }

        $this->db->select('menu_resep.*, menus.nama_menu');
        $this->db->from('menu_resep');
        $this->db->join('menus', 'menus.id = menu_resep.menu_id', 'left');
        $resep_rows = $this->db->get()->result();

        foreach ($bahan_list as $b) {
            $b->terpakai_order   = 0;
            $b->kebutuhan_target = 0;
            $b->rincian_menu     = [];

            foreach ($resep_rows as $r) {
                if ($r->bahan_id == $b->id) {
                    $qty_terjual  = isset($map_terjual[$r->menu_id]) ? $map_terjual[$r->menu_id] : 0;
                    $pakai        = $qty_terjual * $r->takaran_per_porsi;
                    $target_butuh = $target_porsi * $r->takaran_per_porsi;

                    $b->terpakai_order   += $pakai;
                    $b->kebutuhan_target += $target_butuh;
                    $b->rincian_menu[]    = $r->nama_menu . ' (' . (float)$r->takaran_per_porsi . ' ' . $b->satuan . '/porsi)';
                }
            }

            $b->sisa_aktual    = $b->stok_gudang - $b->terpakai_order;
            $b->selisih_target = $b->stok_gudang - $b->kebutuhan_target;
        }

        return $bahan_list;
    }

    // Ambil daftar resep buat menu
    public function get_all_resep() {
        $this->db->select('menu_resep.*, menus.nama_menu, bahan_baku.nama_bahan, bahan_baku.satuan');
        $this->db->from('menu_resep');
        $this->db->join('menus', 'menus.id = menu_resep.menu_id', 'left');
        $this->db->join('bahan_baku', 'bahan_baku.id = menu_resep.bahan_id', 'left');
        $this->db->order_by('menus.nama_menu', 'ASC');
        return $this->db->get()->result();
    }

    // CRUD Bahan Baku Gudang
    public function simpan_stok_gudang($nama_bahan, $satuan, $jumlah_tambah) {
        $cek = $this->db->get_where('bahan_baku', ['nama_bahan' => $nama_bahan])->row();
        if ($cek) {
            $this->db->where('id', $cek->id);
            return $this->db->update('bahan_baku', ['stok_gudang' => $cek->stok_gudang + $jumlah_tambah]);
        }
        return $this->db->insert('bahan_baku', [
            'nama_bahan'  => $nama_bahan,
            'satuan'      => $satuan,
            'stok_gudang' => $jumlah_tambah
        ]);
    }

    public function update_bahan($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('bahan_baku', $data);
    }

    public function hapus_bahan($id) {
        $this->db->delete('menu_resep', ['bahan_id' => $id]);
        return $this->db->delete('bahan_baku', ['id' => $id]);
    }

    // Takaran Resep Menu
    public function simpan_resep_menu($menu_id, $bahan_id, $takaran) {
        $cek = $this->db->get_where('menu_resep', ['menu_id' => $menu_id, 'bahan_id' => $bahan_id])->row();
        if ($cek) {
            $this->db->where('id', $cek->id);
            return $this->db->update('menu_resep', ['takaran_per_porsi' => $takaran]);
        }
        return $this->db->insert('menu_resep', [
            'menu_id'           => $menu_id,
            'bahan_id'          => $bahan_id,
            'takaran_per_porsi' => $takaran
        ]);
    }

    public function update_resep($id, $takaran) {
        $this->db->where('id', $id);
        return $this->db->update('menu_resep', ['takaran_per_porsi' => $takaran]);
    }

    public function hapus_resep($id) {
        return $this->db->delete('menu_resep', ['id' => $id]);
    }

	// Rekap Bulanan Otomatis
    public function get_rekap_bulanan() {
        $sql = "
            SELECT 
                bulan,
                SUM(pemasukan) AS total_pemasukan,
                SUM(pengeluaran) AS total_pengeluaran,
                (SUM(pemasukan) - SUM(pengeluaran)) AS laba_bersih
            FROM (
                SELECT DATE_FORMAT(STR_TO_DATE(SUBSTRING(kode_order, 5, 8), '%Y%m%d'), '%Y-%m') AS bulan,
                       subtotal AS pemasukan,
                       0 AS pengeluaran
                FROM orders
                WHERE status_pembayaran = 'lunas' OR status_pesanan IN ('diproses', 'selesai')
                
                UNION ALL
                
                SELECT DATE_FORMAT(tanggal, '%Y-%m') AS bulan,
                       0 AS pemasukan,
                       jumlah_pengeluaran AS pengeluaran
                FROM pengeluaran_pantry
            ) AS gabungan
            WHERE bulan IS NOT NULL
            GROUP BY bulan
            ORDER BY bulan DESC
        ";
        return $this->db->query($sql)->result();
    }

    // Riwayat Pemasukan Harian (Bisa difilter per bulan YYYY-MM)
    public function get_history_pemasukan($bulan = null) {
        $this->db->select("orders.*, meja.nomor_meja, STR_TO_DATE(SUBSTRING(orders.kode_order, 5, 8), '%Y%m%d') as tanggal_order");
        $this->db->from('orders');
        $this->db->join('meja', 'meja.id = orders.meja_id', 'left');
        $this->db->where_in('orders.status_pesanan', ['diproses', 'selesai']);
        if (!empty($bulan)) {
            $this->db->where("DATE_FORMAT(STR_TO_DATE(SUBSTRING(orders.kode_order, 5, 8), '%Y%m%d'), '%Y-%m') =", $bulan);
        }
        $this->db->order_by('orders.id', 'DESC');
        return $this->db->get()->result();
    }

    // Riwayat Pengeluaran Uang Harian & Simpan Pengeluaran Baru
    public function get_history_pengeluaran($bulan = null) {
        if (!empty($bulan)) {
            $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
        }
        $this->db->order_by('tanggal', 'DESC');
        $this->db->order_by('id', 'DESC');
        return $this->db->get('pengeluaran_pantry')->result();
    }

    public function tambah_pengeluaran_uang($data) {
        return $this->db->insert('pengeluaran_pantry', $data);
    }

    public function hapus_pengeluaran_uang($id) {
        return $this->db->delete('pengeluaran_pantry', ['id' => $id]);
    }
}
