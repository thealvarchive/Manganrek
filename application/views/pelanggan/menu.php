<style>
	.card-menu .card-img-top {
		width: 100%;
		height: 180px; 
		object-fit: cover; 
		object-position: center;
	}

	@media (min-width: 768px) {
		.card-menu .card-img-top {
			height: 400px;
		}
	}
</style>
<div class="content-header">
	<div class="container-fluid">
		<div class="d-flex justify-content-between align-items-center mb-2">
			<h1 class="m-0 text-dark h4 font-weight-bold">Daftar Menu</h1>
			<span class="text-muted small">Meja #<?= htmlspecialchars($nomor_meja ?? '05'); ?></span>
		</div>

		<!-- Filter Kategori -->
		<?php $daftar_kategori = ['Semua', 'Makanan Berat', 'Minuman', 'Camilan']; ?>
		<div class="d-flex flex-nowrap overflow-auto pb-2">
			<?php foreach ($daftar_kategori as $kat): ?>
				<?php $is_active = ($kategori_aktif === $kat); ?>
				<a href="<?= base_url('order?kategori=' . urlencode($kat)); ?>"
					class="btn <?= $is_active ? 'btn-dark' : 'btn-outline-dark'; ?> btn-sm rounded-pill px-3 mr-2">
					<?= $kat; ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<?php if (!empty($menus)): ?>
				<?php foreach ($menus as $menu): ?>
					<div class="col-6 col-md-4 mb-3">
						<div class="card card-menu h-500 border-0 shadow-sm">
							<?php
							$img_path = FCPATH . 'assets/img/menu/' . $menu->gambar;
							$img_url  = (!empty($menu->gambar) && file_exists($img_path))
								? base_url('assets/img/menu/' . $menu->gambar)
								: 'https://via.placeholder.com/300x200?text=' . urlencode($menu->nama_menu);
							?>
							<img src="<?= $img_url; ?>" class="card-img-top" alt="<?= htmlspecialchars($menu->nama_menu); ?>">
							<div class="card-body d-flex flex-column p-2">
								<h6 class="font-weight-bold mb-1"><?= htmlspecialchars($menu->nama_menu); ?></h6>
								<p class="text-success font-weight-bold small mb-2">
									Rp <?= number_format($menu->harga, 0, ',', '.'); ?>
								</p>
								<a href="<?= base_url('order/tambah/' . $menu->id); ?>" class="btn btn-sm btn-outline-success mt-auto btn-block font-weight-bold">
									<i class="fas fa-plus mr-1"></i> Tambah
								</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<div class="col-12">
					<div class="alert alert-light border text-center py-4">
						<i class="fas fa-utensils fa-2x text-muted mb-2 d-block"></i>
						<span class="text-muted">Menu tidak ditemukan untuk kategori ini.</span>
					</div>
				</div>
			<?php endif; ?>


			<?php if (empty($hide_bottom_bar)): ?>
				<div class="bottom-nav bg-white border-top shadow-lg py-2 px-3">
					<div class="container-fluid d-flex justify-content-between align-items-center">
						<div>
							<small class="text-muted d-block">Total Sementara</small>
							<strong class="text-success h6 font-weight-bold mb-0">
								Rp <?= number_format(isset($cart_summary['subtotal']) ? $cart_summary['subtotal'] : 0, 0, ',', '.'); ?>
							</strong>
						</div>
						<a href="<?= base_url('order/keranjang'); ?>" class="btn btn-success rounded-pill px-4 font-weight-bold <?= (isset($cart_summary['total_qty']) && $cart_summary['total_qty'] == 0) ? 'disabled' : ''; ?>">
							<i class="fas fa-shopping-cart mr-1"></i> Keranjang (<?= isset($cart_summary['total_qty']) ? $cart_summary['total_qty'] : 0; ?>)
						</a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
