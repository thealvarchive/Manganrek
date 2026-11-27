<!-- Main Sidebar Container -->
<?php $this->load->view('admin/sidebar'); ?>

<?php
if ($row) {
	$nomor_val   = set_value('nomor_meja', $row->nomor_meja);
	$status_val  = set_value('status', $row->status);
	$form_action = base_url('meja_admin/edit/' . $row->id);
	$judul_form  = 'Edit Meja';
} else {
	$nomor_val   = set_value('nomor_meja');
	$status_val  = set_value('status', 'tersedia');
	$form_action = base_url('meja_admin/tambah');
	$judul_form  = 'Tambah Meja';
}
$daftar_status = array('tersedia', 'terpakai', 'nonaktif');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0"><?= $judul_form; ?></h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="<?= base_url('meja_admin'); ?>">Kelola Meja</a></li>
						<li class="breadcrumb-item active"><?= $judul_form; ?></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="content">
		<div class="container-fluid">
			<?php if (validation_errors()) : ?>
				<div class="alert alert-danger">
					<?= validation_errors(); ?>
				</div>
			<?php endif; ?>

			<div class="card card-primary card-outline">
				<div class="card-body">
					<form action="<?= $form_action; ?>" method="post">
						<div class="form-group">
							<label for="nomor_meja">Nomor Meja</label>
							<input type="text" name="nomor_meja" id="nomor_meja" class="form-control" placeholder="contoh: 01, 02, A1" value="<?= htmlspecialchars($nomor_val, ENT_QUOTES, 'UTF-8'); ?>" required>
						</div>

						<div class="form-group">
							<label for="status">Status Meja</label>
							<select name="status" id="status" class="form-control">
								<?php foreach ($daftar_status as $st) : ?>
									<?php if ($status_val === $st) : ?>
										<option value="<?= $st; ?>" selected><?= ucfirst($st); ?></option>
									<?php else : ?>
										<option value="<?= $st; ?>"><?= ucfirst($st); ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</div>

						<button type="submit" class="btn btn-primary">Simpan</button>
						<a href="<?= base_url('meja_admin'); ?>" class="btn btn-secondary">Batal</a>
					</form>
				</div>
			</div>
		</div>
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
