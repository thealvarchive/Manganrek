<div class="content-header">
	<div class="container-fluid">
		<div class="row align-items-center mb-2">
			<div class="col-12 d-flex align-items-center justify-content-between">
				<div class="d-flex align-items-center">
					<a href="<?= base_url('order'); ?>" class="btn btn-sm btn-default mr-2">
						<i class="fas fa-arrow-left mr-1"></i> Ke Menu
					</a>
					<h1 class="m-0 text-dark h4 font-weight-bold">Kode QR Pesanan</h1>
				</div>
				<span class="badge badge-success px-3 py-2">
					<i class="fas fa-chair mr-1"></i> Meja #<?= htmlspecialchars($nomor_meja ?? '05'); ?>
				</span>
			</div>
		</div>
	</div>
</div>

<section class="content">
	<div class="container-fluid">
		<?php if (!empty($order)): ?>
			<div class="row justify-content-center">
				<!-- Kolom Kiri: QR Code -->
				<div class="col-lg-6 col-md-12">
					<div class="card card-outline card-success shadow-sm text-center">
						<div class="card-header">
							<h3 class="card-title font-weight-bold float-none">
								<i class="fas fa-qrcode mr-1 text-success"></i> Tunjukkan QR ke Kasir / Pelayan
							</h3>
						</div>
						<div class="card-body p-4">
							<div class="mb-2">
								<?php if ($order->status_pesanan === 'menunggu'): ?>
									<span class="badge badge-warning text-dark px-3 py-1">Menunggu Konfirmasi / Scan</span>
								<?php elseif ($order->status_pesanan === 'diproses'): ?>
									<span class="badge badge-primary px-3 py-1">Sedang Dimasak / Diproses</span>
								<?php elseif ($order->status_pesanan === 'selesai'): ?>
									<span class="badge badge-success px-3 py-1">Pesanan Selesai</span>
								<?php else: ?>
									<span class="badge badge-danger px-3 py-1">Dibatalkan</span>
								<?php endif; ?>
							</div>

							<h4 class="font-weight-bold text-dark mb-1">#<?= htmlspecialchars($order->kode_order); ?></h4>
							<p class="text-muted small mb-3">
								Atas nama: <strong><?= htmlspecialchars($order->nama_pemesan); ?></strong>
							</p>

							<!-- Kotak QR Code -->
							<div class="d-inline-block p-3 border rounded bg-white mb-3">
								<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($order->kode_order); ?>" 
									 alt="QR Code Pesanan" 
									 class="img-fluid" 
									 style="width: 200px; height: 200px;">
							</div>

							<p class="text-muted small mb-0">
								Simpan atau screenshot halaman ini sebagai bukti pesanan Anda.
							</p>
						</div>
					</div>
				</div>

				<!-- Kolom Kanan: Rincian Tagihan -->
				<div class="col-lg-6 col-md-12">
					<div class="card card-outline card-secondary shadow-sm">
						<div class="card-header">
							<h3 class="card-title font-weight-bold">
								<i class="fas fa-receipt mr-1 text-secondary"></i> Ringkasan Tagihan
							</h3>
						</div>
						<div class="card-body p-3">
							<div class="d-flex justify-content-between small mb-2">
								<span class="text-muted">Kode Pesanan</span>
								<span class="font-weight-bold">#<?= htmlspecialchars($order->kode_order); ?></span>
							</div>
							<div class="d-flex justify-content-between small mb-2">
								<span class="text-muted">Nama Pemesan</span>
								<span class="font-weight-bold"><?= htmlspecialchars($order->nama_pemesan); ?></span>
							</div>
							<div class="d-flex justify-content-between small mb-2">
								<span class="text-muted">Status Pembayaran</span>
								<?php if ($order->status_pembayaran === 'lunas'): ?>
									<span class="badge badge-success">Lunas</span>
								<?php else: ?>
									<span class="badge badge-danger">Belum Bayar</span>
								<?php endif; ?>
							</div>

							<?php if (!empty($order_items)): ?>
								<hr class="my-2">
								<div class="small font-weight-bold mb-2">Rincian Menu:</div>
								<?php foreach ($order_items as $item): ?>
									<div class="d-flex justify-content-between small mb-1">
										<span><?= $item->qty; ?>x <?= htmlspecialchars($item->nama_menu); ?></span>
										<span>Rp <?= number_format($item->subtotal, 0, ',', '.'); ?></span>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>

							<hr class="my-2">
							<div class="d-flex justify-content-between small mb-1 text-muted">
								<span>Pajak Layanan</span>
								<span>Rp <?= number_format($order->pajak_layanan, 0, ',', '.'); ?></span>
							</div>
							<div class="d-flex justify-content-between align-items-center mb-3">
								<span class="font-weight-bold">Total Pembayaran</span>
								<span class="font-weight-bold text-success h5 mb-0">
									Rp <?= number_format((int) $order->subtotal + (int) $order->pajak_layanan, 0, ',', '.'); ?>
								</span>
							</div>

							<a href="<?= base_url('order/status_pesanan/' . $order->kode_order); ?>" class="btn btn-outline-success btn-block font-weight-bold mb-2">
								<i class="fas fa-sync-alt mr-1"></i> Muat Ulang Status
							</a>
							<a href="<?= base_url('order'); ?>" class="btn btn-success btn-block font-weight-bold shadow-sm">
								<i class="fas fa-plus-circle mr-1"></i> Pesan Menu Tambahan
							</a>
						</div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<div class="card shadow-sm text-center py-5">
				<div class="card-body">
					<i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
					<h5 class="font-weight-bold">Pesanan Tidak Ditemukan</h5>
					<p class="text-muted small mb-3">Kode pesanan tidak valid atau belum ada transaksi yang dibuat.</p>
					<a href="<?= base_url('order'); ?>" class="btn btn-success px-4 font-weight-bold">
						Kembali ke Daftar Menu
					</a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
