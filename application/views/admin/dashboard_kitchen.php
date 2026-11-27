<!-- Auto refresh halaman dapur tiap 5 detik -->
<meta http-equiv="refresh" content="5">

<!-- Main Sidebar Container -->
<?php $this->load->view('admin/sidebar'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Dashboard Dapur</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo site_url('admin'); ?>">Dashboard Pesanan</a></li>
						<li class="breadcrumb-item active"><?php echo $title; ?></li>
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
			$jml_diproses  = 0;
			$jml_selesai   = 0;
			$antrean_dapur = array();

            if (!empty($orders)) {
                foreach ($orders as $item_hitung) {
                    $status = strtolower(trim($item_hitung->status_pesanan));
                    
                    if ($status === 'diproses') {
                        $jml_diproses = $jml_diproses + 1;
                        $antrean_dapur[] = $item_hitung; // Masuk ke layar TV KDS
                    } elseif ($status === 'selesai') {
                        $jml_selesai = $jml_selesai + 1;
                    }
                }
            }
			?>

			<!-- Ringkasan Angka Sederhana -->
			<div class="row">
				<div class="col-md-6 col-12">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3><?= $jml_diproses; ?></h3>
							<p>Sedang Dimasak</p>
						</div>
						<div class="icon">
							<i class="fas fa-fire"></i>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-12">
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

			<!-- Card Utama Daftar Pesanan Dapur -->
			<div class="card card-warning card-outline">
				<div class="card-header">
					<h3 class="card-title font-weight-bold">
						<i class="fas fa-utensils mr-1"></i> Pesanan Masuk Dapur
					</h3>
					<div class="card-tools">
						<a href="<?= base_url('admin/dashboard_kitchen'); ?>" class="btn btn-sm btn-default">
							<i class="fas fa-sync-alt mr-1"></i> Refresh
						</a>
					</div>
				</div>

				<div class="card-body">
					<div class="row">
						<?php if (!empty($antrean_dapur)) : ?>
							<?php foreach ($antrean_dapur as $row) : ?>
								<?php
								if (!empty($row->nomor_meja)) {
									$label_meja = 'Meja #' . htmlspecialchars($row->nomor_meja, ENT_QUOTES, 'UTF-8');
								} else {
									$label_meja = 'Meja -';
								}
								$items = $this->Order_model->get_order_items($row->id);
								?>
								<div class="col-md-4 col-12 mb-3">
									<div class="card card-primary card-outline h-100 mb-0">
										<div class="card-header">
											<h3 class="card-title font-weight-bold">
												<span class="badge badge-dark mr-1"><?= $label_meja; ?></span>
												#<?= htmlspecialchars($row->kode_order, ENT_QUOTES, 'UTF-8'); ?>
											</h3>
											<div class="card-tools">
												<span class="badge badge-primary">Diproses</span>
											</div>
										</div>

										<div class="card-body d-flex flex-column justify-content-between">
											<div>
												<p class="text-muted small mb-2">
													Pemesan: <strong><?= htmlspecialchars($row->nama_pemesan, ENT_QUOTES, 'UTF-8'); ?></strong>
												</p>

												<ul class="list-group list-group-unbordered mb-3">
													<?php if (!empty($items)) : ?>
														<?php foreach ($items as $item) : ?>
															<li class="list-group-item py-2">
																<b><?= $item->qty; ?>x</b> <?= htmlspecialchars($item->nama_menu, ENT_QUOTES, 'UTF-8'); ?>
															</li>
														<?php endforeach; ?>
													<?php else : ?>
														<li class="list-group-item py-2 text-muted small">Tidak ada rincian item.</li>
													<?php endif; ?>
												</ul>
											</div>

											<a href="<?= base_url('admin/selesai_kitchen/' . $row->kode_order); ?>" class="btn btn-success btn-block font-weight-bold">
												<i class="fas fa-check mr-1"></i> Selesai Dimasak
											</a>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php else : ?>
							<div class="col-12 text-center text-muted py-4">
								Belum ada pesanan yang perlu dimasak.
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<div class="p-3">
		<h6>Alur Cepat Dapur</h6>
		<p class="small text-muted mb-0">1. Pesanan muncul setelah kasir klik <strong>Bayar & Proses</strong>.<br>2. Klik <strong>Selesai Dimasak</strong> saat makanan siap.</p>
	</div>
</aside>
<!-- /.control-sidebar -->