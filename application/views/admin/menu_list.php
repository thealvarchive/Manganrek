<!-- Main Sidebar Container -->
<?php $this->load->view('admin/sidebar'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Kelola Menu</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>">Dashboard</a></li>
						<li class="breadcrumb-item active">Kelola Menu</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<div class="content">
		<div class="container-fluid">
			<?php $pesan = $this->session->flashdata('pesan'); ?>
			<?php if (!empty($pesan)) : ?>
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'); ?>
				</div>
			<?php endif; ?>

			<div class="card card-primary card-outline">
				<div class="card-header">
					<h3 class="card-title font-weight-bold">Daftar Menu</h3>
					<div class="card-tools">
						<a href="<?= base_url('menu_admin/tambah'); ?>" class="btn btn-sm btn-primary">
							<i class="fas fa-plus mr-1"></i> Tambah Menu
						</a>
					</div>
				</div>

				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover text-nowrap mb-0">
							<thead class="thead-light">
								<tr>
									<th>No</th>
									<th>Nama Menu</th>
									<th>Kategori</th>
									<th>Harga</th>
									<th>Status</th>
									<th class="text-center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($menus)) : ?>
									<?php $no = 1; ?>
									<?php foreach ($menus as $m) : ?>
										<tr>
											<td><?= $no++; ?></td>
											<td><strong><?= htmlspecialchars($m->nama_menu, ENT_QUOTES, 'UTF-8'); ?></strong></td>
											<td><?= htmlspecialchars($m->kategori, ENT_QUOTES, 'UTF-8'); ?></td>
											<td class="font-weight-bold text-success">
												Rp <?= number_format($m->harga, 0, ',', '.'); ?>
											</td>
											<td>
												<?php if ((int) $m->tersedia === 1) : ?>
													<span class="badge badge-success">Tersedia</span>
												<?php else : ?>
													<span class="badge badge-secondary">Habis</span>
												<?php endif; ?>
											</td>
											<td class="text-center">
												<a href="<?= base_url('menu_admin/edit/' . $m->id); ?>" class="btn btn-xs btn-warning">
													Edit
												</a>
												<a href="<?= base_url('menu_admin/hapus/' . $m->id); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Hapus menu ini?');">
													Hapus
												</a>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="6" class="text-center text-muted">Belum ada data menu.</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
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
