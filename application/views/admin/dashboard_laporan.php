<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<a href="<?= base_url('admin'); ?>" class="brand-link">
		<img src="<?= base_url('assets/dist/img/AdminLTELogo.png'); ?>" alt="Manganrek Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-light">Manganrek</span>
	</a>

	<div class="sidebar">
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?= base_url('assets/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
				<a href="#" class="d-block">
					<?php
					$user = $this->ion_auth->user()->row();
					echo ($user && !empty($user->first_name)) ? htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8') : 'Pengguna';
					?>
				</a>
			</div>
		</div>

		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<a href="<?= base_url('admin'); ?>" class="nav-link">
						<i class="nav-icon fas fa-cash-register"></i>
						<p>Dashboard Pesanan</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('admin/dashboard_kitchen'); ?>" class="nav-link">
						<i class="nav-icon fas fa-fire"></i>
						<p>Dashboard Dapur</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('admin/dashboard_pantry'); ?>" class="nav-link">
						<i class="nav-icon fas fa-boxes"></i>
						<p>Dashboard Pantry</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('admin/laporan'); ?>" class="nav-link active">
						<i class="nav-icon fas fa-file-invoice-dollar"></i>
						<p>Riwayat & Rekap</p>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</aside>

<!-- Content Wrapper -->
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Riwayat & Rekap Bulanan</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('admin'); ?>">Dashboard Pesanan</a></li>
						<li class="breadcrumb-item active"><?= $title; ?></li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<div class="content">
		<div class="container-fluid">

			<?php
			$masuk_bulan_ini  = 0;
			$keluar_bulan_ini = 0;

			if (!empty($history_pemasukan)) {
				foreach ($history_pemasukan as $pm) $masuk_bulan_ini += (float) $pm->subtotal;
			}
			if (!empty($history_pengeluaran)) {
				foreach ($history_pengeluaran as $kl) $keluar_bulan_ini += (float) $kl->jumlah_pengeluaran;
			}
			$selisih_bulan_ini = $masuk_bulan_ini - $keluar_bulan_ini;
			?>

			<!-- Filter Bulan & Tombol Catat Pengeluaran -->
			<div class="row mb-3">
				<div class="col-md-6">
					<form action="<?= base_url('admin/laporan'); ?>" method="get" class="form-inline">
						<label class="mr-2 font-weight-bold">Pilih Bulan:</label>
						<input type="month" name="bulan" class="form-control form-control-sm mr-2" value="<?= htmlspecialchars($bulan_pilih, ENT_QUOTES, 'UTF-8'); ?>">
						<button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
					</form>
				</div>
				<div class="col-md-6 text-md-right mt-2 mt-md-0">
					<button type="button" class="btn btn-sm btn-danger font-weight-bold" data-toggle="modal" data-target="#modalCatatKeluar">
						<i class="fas fa-plus mr-1"></i> Catat Pengeluaran Uang
					</button>
				</div>
			</div>

			<!-- Small Box Ringkasan Bulan Terpilih -->
			<div class="row">
				<div class="col-md-4 col-12">
					<div class="small-box bg-success">
						<div class="inner">
							<h3>Rp <?= number_format($masuk_bulan_ini, 0, ',', '.'); ?></h3>
							<p>Pemasukan (<?= date('M Y', strtotime($bulan_pilih . '-01')); ?>)</p>
						</div>
						<div class="icon"><i class="fas fa-arrow-down"></i></div>
					</div>
				</div>
				<div class="col-md-4 col-12">
					<div class="small-box bg-danger">
						<div class="inner">
							<h3>Rp <?= number_format($keluar_bulan_ini, 0, ',', '.'); ?></h3>
							<p>Pengeluaran (<?= date('M Y', strtotime($bulan_pilih . '-01')); ?>)</p>
						</div>
						<div class="icon"><i class="fas fa-arrow-up"></i></div>
					</div>
				</div>
				<div class="col-md-4 col-12">
					<div class="small-box bg-info">
						<div class="inner">
							<h3>Rp <?= number_format($selisih_bulan_ini, 0, ',', '.'); ?></h3>
							<p>Selisih / Laba Bersih</p>
						</div>
						<div class="icon"><i class="fas fa-wallet"></i></div>
					</div>
				</div>
			</div>

			<!-- Tabel Rekap Otomatis Setiap Bulan -->
			<div class="card card-primary card-outline mb-4">
				<div class="card-header">
					<h3 class="card-title font-weight-bold"><i class="fas fa-calendar-check mr-1"></i> Rekap Otomatis per Bulan</h3>
				</div>
				<div class="card-body table-responsive p-0">
					<table class="table table-bordered table-hover mb-0">
						<thead class="thead-light">
							<tr>
								<th>Bulan</th>
								<th class="text-right">Total Uang Masuk</th>
								<th class="text-right">Total Uang Keluar</th>
								<th class="text-right">Selisih / Laba</th>
								<th class="text-center">Detail</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($rekap_bulanan)) : ?>
								<?php foreach ($rekap_bulanan as $rb) : ?>
									<tr class="<?= ($rb->bulan === $bulan_pilih) ? 'table-active' : ''; ?>">
										<td><strong><?= date('F Y', strtotime($rb->bulan . '-01')); ?></strong></td>
										<td class="text-right text-success font-weight-bold">Rp <?= number_format($rb->total_pemasukan, 0, ',', '.'); ?></td>
										<td class="text-right text-danger font-weight-bold">Rp <?= number_format($rb->total_pengeluaran, 0, ',', '.'); ?></td>
										<td class="text-right font-weight-bold">Rp <?= number_format($rb->laba_bersih, 0, ',', '.'); ?></td>
										<td class="text-center">
											<a href="<?= base_url('admin/laporan?bulan=' . $rb->bulan); ?>" class="btn btn-xs btn-outline-primary">Lihat Rincian</a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr><td colspan="5" class="text-center text-muted py-3">Belum ada data transaksi bulanan.</td></tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Rincian Harian-->
			<div class="row">
				<!-- History Pemasukan Pesanan -->
				<div class="col-md-7 col-12">
					<div class="card card-success card-outline">
						<div class="card-header">
							<h3 class="card-title font-weight-bold"><i class="fas fa-receipt mr-1"></i> History Pemasukan Pesanan (<?= date('M Y', strtotime($bulan_pilih . '-01')); ?>)</h3>
						</div>
						<div class="card-body table-responsive p-0">
							<table class="table table-bordered table-hover mb-0">
								<thead class="thead-light">
									<tr>
										<th>Tanggal</th>
										<th>Kode Order</th>
										<th>Pemesan</th>
										<th class="text-right">Uang Masuk</th>
										<th class="text-center">Item</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($history_pemasukan)) : ?>
										<?php foreach ($history_pemasukan as $pm) : ?>
											<tr>
												<td class="text-nowrap"><?= !empty($pm->tanggal_order) ? date('d/m/Y', strtotime($pm->tanggal_order)) : '-'; ?></td>
												<td><strong>#<?= htmlspecialchars($pm->kode_order, ENT_QUOTES, 'UTF-8'); ?></strong></td>
												<td><?= htmlspecialchars($pm->nama_pemesan, ENT_QUOTES, 'UTF-8'); ?></td>
												<td class="text-right text-success font-weight-bold">Rp <?= number_format($pm->subtotal, 0, ',', '.'); ?></td>
												<td class="text-center">
													<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalHistItem<?= $pm->id; ?>">Detail</button>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr><td colspan="5" class="text-center text-muted py-3">Belum ada pemasukan di bulan ini.</td></tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<!-- History Pengeluaran Uang -->
				<div class="col-md-5 col-12">
					<div class="card card-danger card-outline">
						<div class="card-header">
							<h3 class="card-title font-weight-bold"><i class="fas fa-shopping-cart mr-1"></i> History Pengeluaran (<?= date('M Y', strtotime($bulan_pilih . '-01')); ?>)</h3>
						</div>
						<div class="card-body table-responsive p-0">
							<table class="table table-bordered table-hover mb-0">
								<thead class="thead-light">
									<tr>
										<th>Tanggal</th>
										<th>Keterangan</th>
										<th class="text-right">Nominal</th>
										<th class="text-center">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($history_pengeluaran)) : ?>
										<?php foreach ($history_pengeluaran as $kl) : ?>
											<tr>
												<td class="text-nowrap"><?= date('d/m/Y', strtotime($kl->tanggal)); ?></td>
												<td><?= htmlspecialchars($kl->keterangan, ENT_QUOTES, 'UTF-8'); ?></td>
												<td class="text-right text-danger font-weight-bold">Rp <?= number_format($kl->jumlah_pengeluaran, 0, ',', '.'); ?></td>
												<td class="text-center">
													<a href="<?= base_url('admin/hapus_pengeluaran/' . $kl->id); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Hapus catatan pengeluaran ini?');">Hapus</a>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr><td colspan="4" class="text-center text-muted py-3">Belum ada pengeluaran di bulan ini.</td></tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- Modal Catat Pengeluaran Uang -->
<div class="modal fade" id="modalCatatKeluar" tabindex="-1">
	<div class="modal-dialog">
		<form action="<?= base_url('admin/simpan_pengeluaran'); ?>" method="post" class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold">Catat Pengeluaran Uang</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Tanggal</label>
					<input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
				</div>
				<div class="form-group">
					<label>Keterangan Pengeluaran</label>
					<input type="text" name="keterangan" class="form-control" placeholder="Contoh: Belanja Beras 50kg & Ayam" required>
				</div>
				<div class="form-group">
					<label>Nominal Uang Keluar (Rp)</label>
					<input type="number" name="jumlah_pengeluaran" class="form-control" placeholder="Contoh: 750000" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger font-weight-bold">Simpan Pengeluaran</button>
			</div>
		</form>
	</div>
</div>

<!-- Modal Detail Item Pesanan -->
<?php if (!empty($history_pemasukan)) : ?>
	<?php foreach ($history_pemasukan as $pm) : ?>
		<?php $items = $this->Order_model->get_order_items($pm->id); ?>
		<div class="modal fade" id="modalHistItem<?= $pm->id; ?>" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold">Rincian #<?= htmlspecialchars($pm->kode_order, ENT_QUOTES, 'UTF-8'); ?></h5>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body p-0">
						<table class="table table-sm mb-0">
							<thead>
								<tr>
									<th>Menu</th>
									<th class="text-center">Qty</th>
									<th class="text-right">Subtotal</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($items as $it) : ?>
									<tr>
										<td><?= htmlspecialchars($it->nama_menu, ENT_QUOTES, 'UTF-8'); ?></td>
										<td class="text-center"><?= $it->qty; ?>x</td>
										<td class="text-right">
                                            Rp <?= number_format(isset($it->subtotal) ? $it->subtotal : ((isset($it->harga_satuan) ? $it->harga_satuan : 0) * $it->qty), 0, ',', '.'); ?>
                                        </td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>