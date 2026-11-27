<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="<?= base_url('admin'); ?>" class="brand-link">
		<img src="<?php echo base_url('assets/dist/img/AdminLTELogo.png'); ?>" alt="Resto QR Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-light">Manganrek</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?php echo base_url('assets/dist/img/image.png') ?>" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
				<a href="#" class="d-block">
					<?php
					$user = $this->ion_auth->user()->row();
					if ($user && !empty($user->first_name)) {
						echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8');
					} else {
						echo 'Pengguna';
					}
					?>
				</a>
			</div>
		</div>

		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<a href="<?= base_url('admin'); ?>" class="nav-link active">
						<i class="nav-icon fas fa-cash-register"></i>
						<p>Dashboard Pesanan</p>
					</a>
				</li>
			</ul>
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<a href="<?= base_url('admin/dashboard_kitchen'); ?>" class="nav-link">
						<i class="nav-icon fas fa-coffee"></i>
						<p>Dashboard Dapur</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('admin/dashboard_pantry'); ?>" class="nav-link">
						<i class="nav-icon fas fa-warehouse"></i>
						<p>Dashboard Pantry</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('admin/laporan'); ?>" class="nav-link">
						<i class="nav-icon fas fa-file-invoice-dollar"></i>
						<p>Riwayat & Rekap</p>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Dashboard Pesanan</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $title; ?></li>
						<li class="breadcrumb-item active"><a href="<?php echo site_url('admin/dashboard_kitchen'); ?>">Dashboard Dapur</a></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="content">
		<div class="container-fluid">

			<?php
			$jml_menunggu = 0;
			$jml_diproses = 0;
			$jml_selesai  = 0;

			if (!empty($orders)) {
				foreach ($orders as $item_hitung) {
					if ($item_hitung->status_pesanan === 'menunggu') {
						$jml_menunggu = $jml_menunggu + 1;
					} elseif ($item_hitung->status_pesanan === 'diproses') {
						$jml_diproses = $jml_diproses + 1;
					} elseif ($item_hitung->status_pesanan === 'selesai') {
						$jml_selesai = $jml_selesai + 1;
					}
				}
			}

			if (!empty($kode)) {
				$nilai_kode = $kode;
			} else {
				$nilai_kode = '';
			}
			?>

			<!-- Ringkasan Angka Sederhana -->
			<div class="row">
				<div class="col-md-4 col-12">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3><?= $jml_menunggu; ?></h3>
							<p>Menunggu Konfirmasi</p>
						</div>
						<div class="icon">
							<i class="fas fa-qrcode"></i>
						</div>
					</div>
				</div>

				<div class="col-md-4 col-12">
					<div class="small-box bg-info">
						<div class="inner">
							<h3><?= $jml_diproses; ?></h3>
							<p>Sedang Diproses</p>
						</div>
						<div class="icon">
							<i class="fas fa-fire"></i>
						</div>
					</div>
				</div>

				<div class="col-md-4 col-12">
					<div class="small-box bg-success">
						<div class="inner">
							<h3><?= $jml_selesai; ?></h3>
							<p>Selesai Hari Ini</p>
						</div>
						<div class="icon">
							<i class="fas fa-check-circle"></i>
						</div>
					</div>
				</div>
			</div>

			<!-- Satu Card Utama: Cari Kode QR + Daftar Pesanan + Aksi -->
			<div class="card card-primary card-outline">
				<div class="card-header">
					<h3 class="card-title font-weight-bold">
						<i class="fas fa-list-alt mr-1"></i> Daftar Pesanan Masuk
					</h3>
					<div class="card-tools">
						<a href="<?= base_url('admin'); ?>" class="btn btn-sm btn-default">
							<i class="fas fa-sync-alt mr-1"></i> Refresh
						</a>
					</div>
				</div>

				<div class="card-body">
					<!-- Cari / Scan Kode Order -->
					<form action="<?= base_url('admin'); ?>" method="get" class="form-inline mb-3">
						<input type="text" name="kode" value="<?= htmlspecialchars($nilai_kode, ENT_QUOTES, 'UTF-8'); ?>" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" placeholder="Cari / scan kode order (ORD-...)">
						<button type="submit" class="btn btn-sm btn-primary">
							<i class="fas fa-search mr-1"></i> Cari
						</button>
					</form>

					<!-- Tabel Pesanan -->
					<div class="table-responsive">
						<table class="table table-bordered table-hover text-nowrap mb-0">
							<thead class="thead-light">
								<tr>
									<th>Kode Order</th>
									<th>Meja</th>
									<th>Pemesan</th>
									<th>Total</th>
									<th>Bayar</th>
									<th>Status</th>
									<th class="text-center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($orders)) : ?>
									<?php foreach ($orders as $row) : ?>
										<?php
										if (!empty($row->nomor_meja)) {
											$label_meja = 'Meja #' . htmlspecialchars($row->nomor_meja, ENT_QUOTES, 'UTF-8');
										} else {
											$label_meja = 'Meja -';
										}
										?>
										<tr>
											<td><strong>#<?= htmlspecialchars($row->kode_order, ENT_QUOTES, 'UTF-8'); ?></strong></td>
											<td>
												<span class="badge badge-dark">
													<?= $label_meja; ?>
												</span>
											</td>
											<td>
												<div><?= htmlspecialchars($row->nama_pemesan, ENT_QUOTES, 'UTF-8'); ?></div>
												<small class="text-muted"><?= htmlspecialchars($row->nomor_handphone, ENT_QUOTES, 'UTF-8'); ?></small>
											</td>
											<td class="font-weight-bold text-success">
												Rp <?= number_format($row->subtotal, 0, ',', '.'); ?>
											</td>
											<td>
												<?php if ($row->status_pembayaran === 'lunas') : ?>
													<span class="badge badge-success">Lunas</span>
												<?php else : ?>
													<span class="badge badge-danger">Belum Bayar</span>
												<?php endif; ?>
											</td>
											<td>
												<?php if ($row->status_pesanan === 'menunggu') : ?>
													<span class="badge badge-warning text-dark">Menunggu</span>
												<?php elseif ($row->status_pesanan === 'diproses') : ?>
													<span class="badge badge-primary">Diproses</span>
												<?php else : ?>
													<span class="badge badge-success">Selesai</span>
												<?php endif; ?>
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalItem<?= $row->id; ?>">
													Item
												</button>

												<?php if ($row->status_pesanan === 'menunggu') : ?>
													<!-- Tombol konfirmasi agar pesanan masuk ke layar TV Dapur (KDS) -->
													<a href="<?= base_url('admin/proses/' . $row->kode_order); ?>" class="btn btn-xs btn-warning font-weight-bold">
														<i class="fas fa-paper-plane mr-1"></i> Konfirmasi ke Dapur
													</a>
												<?php elseif ($row->status_pesanan === 'diproses') : ?>
													<span class="badge badge-info">Sudah di TV Dapur</span>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="7" class="text-center text-muted">Belum ada pesanan masuk.</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- Modal Detail Item per Pesanan -->
			<?php if (!empty($orders)) : ?>
				<?php foreach ($orders as $row) : ?>
					<?php $items = $this->Order_model->get_order_items($row->id); ?>
					<div class="modal fade" id="modalItem<?= $row->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-sm" role="document">
							<div class="modal-content">
								<div class="modal-header py-2">
									<h6 class="modal-title font-weight-bold">Detail Item (#<?= htmlspecialchars($row->kode_order, ENT_QUOTES, 'UTF-8'); ?>)</h6>
									<button type="button" class="close" data-dismiss="modal"><span>×</span></button>
								</div>
								<div class="modal-body p-2">
									<ul class="list-unstyled mb-0 small">
										<?php if (!empty($items)) : ?>
											<?php foreach ($items as $item) : ?>
												<li class="d-flex justify-content-between py-1 border-bottom">
													<span><?= $item->qty; ?>x <?= htmlspecialchars($item->nama_menu, ENT_QUOTES, 'UTF-8'); ?></span>
													<strong>Rp <?= number_format($item->subtotal, 0, ',', '.'); ?></strong>
												</li>
											<?php endforeach; ?>
										<?php else : ?>
											<li class="py-1 text-muted">Tidak ada rincian item.</li>
										<?php endif; ?>
										<li class="d-flex justify-content-between pt-2 font-weight-bold text-success">
											<span>Total</span>
											<span>Rp <?= number_format($row->subtotal, 0, ',', '.'); ?></span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<div class="p-3">
		<h6>Alur Cepat MVP</h6>
		<p class="small text-muted mb-0">1. Cek kode order dari QR pelanggan.<br>2. Klik <strong>Bayar & Proses</strong>.<br>3. Klik <strong>Selesai</strong> saat diantar.</p>
	</div>
</aside>
<!-- /.control-sidebar -->
