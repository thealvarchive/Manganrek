<div class="content-header">
	<div class="container-fluid">
		<div class="row align-items-center mb-2">
			<div class="col-12 d-flex align-items-center">
				<a href="<?= base_url('order'); ?>" class="btn btn-sm btn-default mr-2">
					<i class="fas fa-arrow-left mr-1"></i> Kembali
				</a>
				<h1 class="m-0 text-dark h4 font-weight-bold">Keranjang Pesanan</h1>
			</div>
		</div>
	</div>
</div>

<section class="content">
	<div class="container-fluid">
		<?php if (validation_errors()): ?>
			<div class="alert alert-danger alert-dismissible small">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<?= validation_errors(); ?>
			</div>
		<?php endif; ?>

		<?php if (!empty($cart_items)): ?>
			<form action="<?= base_url('order/checkout'); ?>" method="post">
				<div class="row">
					<!-- Kolom Kiri: Daftar Item -->
					<div class="col-lg-7 col-md-12">
						<div class="card card-outline card-success shadow-sm">
							<div class="card-header">
								<h3 class="card-title font-weight-bold">
									<i class="fas fa-shopping-basket mr-1 text-success"></i> Daftar Menu Dipilih
								</h3>
							</div>
							<div class="card-body p-3">
								<?php
								$total_item_jenis = count($cart_items);
								$index = 0;
								?>
								<?php foreach ($cart_items as $item): ?>
									<?php
									$index++;
									$border_class = ($index < $total_item_jenis) ? 'border-bottom pb-3 mb-3' : '';
									$subtotal_item = $item['harga'] * $item['qty'];
									?>
									<div class="d-flex justify-content-between align-items-start <?= $border_class; ?>">
										<div class="mr-3 flex-grow-1">
											<h6 class="font-weight-bold mb-1"><?= htmlspecialchars($item['nama_menu']); ?></h6>
											<div class="text-success small font-weight-bold mb-2">
												Rp <?= number_format($item['harga'], 0, ',', '.'); ?> / Porsi
											</div>
										</div>
										<div class="text-right">
											<div class="font-weight-bold mb-2">
												Rp <?= number_format($subtotal_item, 0, ',', '.'); ?>
											</div>
											<div class="input-group input-group-sm" style="width: 105px;">
												<div class="input-group-prepend">
													<a href="<?= base_url('order/ubah_qty/' . $item['menu_id'] . '/minus'); ?>" class="btn btn-default">-</a>
												</div>
												<input type="text" class="form-control text-center bg-white" value="<?= $item['qty']; ?>" readonly>
												<div class="input-group-append">
													<a href="<?= base_url('order/ubah_qty/' . $item['menu_id'] . '/plus'); ?>" class="btn btn-default">+</a>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<!-- Kolom Kanan: Form Pemesan & Total -->
					<div class="col-lg-5 col-md-12">
						<div class="card card-outline card-secondary shadow-sm">
							<div class="card-header">
								<h3 class="card-title font-weight-bold">
									<i class="fas fa-user-edit mr-1 text-secondary"></i> Informasi Pesanan
								</h3>
							</div>
							<div class="card-body p-3">
								<div class="form-group mb-3">
									<label for="nama_pemesan" class="small font-weight-bold">Nama Pemesan <span class="text-danger">*</span></label>
									<input type="text" class="form-control form-control-sm" id="nama_pemesan" name="nama_pemesan" value="<?= set_value('nama_pemesan'); ?>" placeholder="Masukkan nama Anda" required>
								</div>
								<div class="form-group mb-3">
									<label for="nomor_handphone" class="small font-weight-bold">Nomor Handphone <span class="text-danger">*</span></label>
									<input type="tel" class="form-control form-control-sm" id="nomor_handphone" name="nomor_handphone" value="<?= set_value('nomor_handphone'); ?>" placeholder="Masukkan nomor handphone Anda" required>
								</div>
								<div class="form-group mb-0">
									<label for="catatan_umum" class="small font-weight-bold">Catatan Tambahan</label>
									<textarea name="catatan_umum" id="catatan_umum" class="form-control form-control-sm" rows="2" placeholder="Contoh: Tidak pedas, es dipisah..."><?= set_value('catatan_umum'); ?></textarea>
								</div>
							</div>
						</div>

						<div class="card shadow-sm">
							<div class="card-body p-3">
								<div class="d-flex justify-content-between small mb-2">
									<span class="text-muted">Subtotal (<?= $cart_summary['total_qty']; ?> item)</span>
									<span class="font-weight-bold">Rp <?= number_format($cart_summary['subtotal'], 0, ',', '.'); ?></span>
								</div>
								<div class="d-flex justify-content-between small mb-2">
									<span class="text-muted">Pajak Layanan</span>
									<span class="font-weight-bold">Rp <?= number_format($cart_summary['pajak'], 0, ',', '.'); ?></span>
								</div>
								<hr class="my-2">
								<div class="d-flex justify-content-between align-items-center mb-3">
									<span class="font-weight-bold">Total Pembayaran</span>
									<span class="font-weight-bold text-success h5 mb-0">
										Rp <?= number_format($cart_summary['total'], 0, ',', '.'); ?>
									</span>
								</div>
								<button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm">
									<i class="fas fa-check-circle mr-1"></i> Pesan Sekarang
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		<?php else: ?>
			<div class="card shadow-sm text-center py-5">
				<div class="card-body">
					<i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
					<h5 class="font-weight-bold">Keranjang Masih Kosong</h5>
					<p class="text-muted small mb-3">Silakan pilih menu makanan atau minuman terlebih dahulu.</p>
					<a href="<?= base_url('order'); ?>" class="btn btn-success px-4 font-weight-bold">
						<i class="fas fa-utensils mr-1"></i> Lihat Daftar Menu
					</a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>