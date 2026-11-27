<!-- Main Sidebar Container -->
<?php $this->load->view('admin/sidebar'); ?>

<?php
$menu = isset($menu) ? $menu : null;
$fields = array('nama_menu', 'kategori', 'harga', 'gambar', 'tersedia');
$values = array();

foreach ($fields as $field) {
	$posted = $this->input->post($field, TRUE);
	$values[$field] = $posted !== null
		? $posted
		: ($menu && isset($menu->$field) ? $menu->$field : '');
}

$errors = $this->form_validation->error_array();
$categories = array('Makanan Berat', 'Minuman', 'Camilan');
$form_action = $menu
	? 'menu_admin/edit/' . $menu->id
	: 'menu_admin/tambah';
$page_title = isset($title) ? $title : ($menu ? 'Edit Menu' : 'Tambah Menu');
$image_path = $menu && !empty($menu->gambar)
	? FCPATH . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'menu' . DIRECTORY_SEPARATOR . basename($menu->gambar)
	: '';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0"><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="<?= base_url('menu_admin'); ?>">Kelola Menu</a></li>
						<li class="breadcrumb-item"><a href="<?= base_url('meja_admin'); ?>">Kelola Meja</a></li>
						<li class="breadcrumb-item active"><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="content">
		<div class="container-fluid">
			<?php foreach ($errors as $error) : ?>
				<div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php endforeach; ?>

			<div class="card card-primary card-outline">
				<div class="card-body">
					<form action="<?= site_url($form_action); ?>" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="nama_menu">Nama Menu</label>
							<input type="text" name="nama_menu" id="nama_menu" class="form-control" required
								value="<?= htmlspecialchars($values['nama_menu'], ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="form-group">
							<label for="kategori">Kategori</label>
							<select name="kategori" id="kategori" class="form-control" required>
								<option value="">Pilih kategori</option>
								<?php foreach ($categories as $category) : ?>
									<option value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>" <?= $values['kategori'] === $category ? 'selected' : ''; ?>>
										<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="form-group">
							<label for="harga">Harga</label>
							<input type="number" name="harga" id="harga" class="form-control" min="1" required
								value="<?= htmlspecialchars((string) $values['harga'], ENT_QUOTES, 'UTF-8'); ?>">
						</div>

						<div class="form-group">
							<label for="gambar">Gambar menu</label>
							<input type="file" name="gambar" id="gambar" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
							<small class="form-text text-muted">Pilih JPG, PNG, atau WebP, maksimal 2 MB. Gambar akan disimpan di assets/img/menu.</small>
							<?php if ($image_path && file_exists($image_path)) : ?>
								<div class="mt-2">
									<img src="<?= base_url('assets/img/menu/' . rawurlencode(basename($menu->gambar))); ?>" alt="Gambar <?= htmlspecialchars($menu->nama_menu, ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 180px; max-height: 120px;">
									<small class="d-block text-muted">Gambar saat ini akan dipakai jika tidak memilih gambar baru.</small>
								</div>
							<?php endif; ?>
						</div>

						<div class="form-group">
							<label for="tersedia">Status</label>
							<select name="tersedia" id="tersedia" class="form-control">
								<option value="1" <?= (string) $values['tersedia'] === '1' ? 'selected' : ''; ?>>Tersedia</option>
								<option value="0" <?= (string) $values['tersedia'] === '0' ? 'selected' : ''; ?>>Tidak tersedia</option>
							</select>
						</div>

						<button type="submit" class="btn btn-primary">Simpan</button>
						<a href="<?= base_url('menu_admin'); ?>" class="btn btn-secondary">Batal</a>
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
