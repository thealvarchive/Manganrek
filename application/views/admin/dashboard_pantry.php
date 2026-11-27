<!-- Main Sidebar Container -->
<?php $this->load->view('admin/sidebar'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Dashboard Pantry & Gudang</h1>
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
			$total_porsi = 0;
			if (!empty($rekap_keluar)) {
				foreach ($rekap_keluar as $rk) {
					$total_porsi += (int) $rk->total_keluar;
				}
			}
			?>

			<!-- Ringkasan Angka -->
			<div class="row">
				<div class="col-md-6 col-12">
					<div class="small-box bg-info">
						<div class="inner">
							<h3><?= !empty($rekap_keluar) ? count($rekap_keluar) : 0; ?></h3>
							<p>Jenis Menu Terpakai</p>
						</div>
						<div class="icon"><i class="fas fa-utensils"></i></div>
					</div>
				</div>
				<div class="col-md-6 col-12">
					<div class="small-box bg-danger">
						<div class="inner">
							<h3><?= $total_porsi; ?> <sup style="font-size: 20px">Porsi</sup></h3>
							<p>Total Porsi Keluar</p>
						</div>
						<div class="icon"><i class="fas fa-dolly-flatbed"></i></div>
					</div>
				</div>
			</div>

			<!-- 1. Stok Gudang & Analisis Target 500 Porsi -->
			<div class="card card-warning card-outline mb-4">
				<div class="card-header">
					<h3 class="card-title font-weight-bold"><i class="fas fa-warehouse mr-1"></i> Stok Gudang & Kebutuhan (<?= $target_porsi; ?> Porsi)</h3>
					<div class="card-tools">
						<button type="button" class="btn btn-sm btn-primary mr-1" data-toggle="modal" data-target="#modalStok">+ Stok Gudang</button>
						<button type="button" class="btn btn-sm btn-warning font-weight-bold" data-toggle="modal" data-target="#modalResep">+ Takaran Resep</button>
					</div>
				</div>
				<div class="card-body table-responsive p-0">
					<table class="table table-bordered table-hover mb-0">
						<thead class="thead-light">
							<tr>
								<th>Bahan Baku</th>
								<th>Takaran / Porsi</th>
								<th class="text-right">Stok Awal</th>
								<th class="text-right">Terpakai</th>
								<th class="text-right">Sisa Gudang</th>
								<th class="text-right">Target <?= $target_porsi; ?> Porsi</th>
								<th class="text-center">Status</th>
								<th class="text-center">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($analisis_bahan)) : ?>
								<?php foreach ($analisis_bahan as $b) : ?>
									<tr>
										<td><strong><?= htmlspecialchars($b->nama_bahan, ENT_QUOTES, 'UTF-8'); ?></strong></td>
										<td class="small text-muted"><?= !empty($b->rincian_menu) ? implode(', ', $b->rincian_menu) : '-'; ?></td>
										<td class="text-right"><?= number_format($b->stok_gudang, 0, ',', '.'); ?> <?= $b->satuan; ?></td>
										<td class="text-right text-danger">- <?= number_format($b->terpakai_order, 0, ',', '.'); ?> <?= $b->satuan; ?></td>
										<td class="text-right font-weight-bold"><?= number_format($b->sisa_aktual, 0, ',', '.'); ?> <?= $b->satuan; ?></td>
										<td class="text-right text-primary"><?= number_format($b->kebutuhan_target, 0, ',', '.'); ?> <?= $b->satuan; ?></td>
										<td class="text-center">
											<?php if ($b->selisih_target >= 0) : ?>
												<span class="badge badge-success">Cukup</span>
											<?php else : ?>
												<span class="badge badge-danger">Kurang <?= number_format(abs($b->selisih_target), 0, ',', '.'); ?> <?= $b->satuan; ?></span>
											<?php endif; ?>
										</td>
										<td class="text-center text-nowrap">
											<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalEditBahan<?= $b->id; ?>">Edit</button>
											<a href="<?= base_url('pantry/hapus_stok/' . $b->id); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Hapus bahan ini?');">Hapus</a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr><td colspan="8" class="text-center text-muted py-3">Belum ada data bahan baku.</td></tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- 2. Daftar Takaran Resep & Rincian Keluar -->
			<div class="row">
				<div class="col-md-5 col-12">
					<div class="card card-info card-outline">
						<div class="card-header"><h3 class="card-title font-weight-bold"><i class="fas fa-balance-scale mr-1"></i> Takaran Resep / Porsi</h3></div>
						<div class="card-body table-responsive p-0">
							<table class="table table-bordered table-hover mb-0">
								<thead class="thead-light">
									<tr>
										<th>Menu</th>
										<th>Bahan</th>
										<th class="text-right">Takaran</th>
										<th class="text-center">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($list_resep)) : ?>
										<?php foreach ($list_resep as $r) : ?>
											<tr>
												<td><strong><?= htmlspecialchars($r->nama_menu, ENT_QUOTES, 'UTF-8'); ?></strong></td>
												<td><?= htmlspecialchars($r->nama_bahan, ENT_QUOTES, 'UTF-8'); ?></td>
												<td class="text-right"><?= (float) $r->takaran_per_porsi; ?> <?= $r->satuan; ?></td>
												<td class="text-center text-nowrap">
													<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalEditResep<?= $r->id; ?>">Edit</button>
													<a href="<?= base_url('pantry/hapus_resep/' . $r->id); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Hapus resep ini?');">Hapus</a>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr><td colspan="4" class="text-center text-muted py-3">Belum ada takaran resep.</td></tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="col-md-7 col-12">
					<div class="card card-primary card-outline">
						<div class="card-header">
							<h3 class="card-title font-weight-bold"><i class="fas fa-clipboard-list mr-1"></i> Rincian Pengeluaran Pesanan</h3>
							<div class="card-tools"><a href="<?= base_url('pantry'); ?>" class="btn btn-sm btn-default"><i class="fas fa-sync-alt"></i> Refresh</a></div>
						</div>
						<div class="card-body table-responsive p-0">
							<table class="table table-bordered table-hover mb-0">
								<thead class="thead-light">
									<tr>
										<th>Kode Order</th>
										<th>Meja</th>
										<th>Pengeluaran</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($rincian_keluar)) : ?>
										<?php foreach ($rincian_keluar as $log) : ?>
											<tr>
												<td><strong>#<?= htmlspecialchars($log->kode_order, ENT_QUOTES, 'UTF-8'); ?></strong></td>
												<td><span class="badge badge-dark"><?= !empty($log->nomor_meja) ? 'Meja #' . htmlspecialchars($log->nomor_meja, ENT_QUOTES, 'UTF-8') : '-'; ?></span></td>
												<td><span class="text-danger font-weight-bold"><?= $log->qty; ?>x</span> <?= htmlspecialchars($log->nama_menu, ENT_QUOTES, 'UTF-8'); ?></td>
												<td><?= ($log->status_pesanan === 'diproses') ? '<span class="badge badge-warning">Dimasak</span>' : '<span class="badge badge-success">Selesai</span>'; ?></td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr><td colspan="4" class="text-center text-muted py-3">Belum ada pengeluaran.</td></tr>
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

<!-- Modal Tambah Stok -->
<div class="modal fade" id="modalStok" tabindex="-1">
	<div class="modal-dialog">
		<form action="<?= base_url('pantry/tambah_stok'); ?>" method="post" class="modal-content">
			<div class="modal-header"><h5 class="modal-title font-weight-bold">Tambah Stok Gudang</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
			<div class="modal-body">
				<div class="form-group">
					<label>Nama Bahan</label>
					<input type="text" name="nama_bahan" list="listBahan" class="form-control" required>
					<datalist id="listBahan">
						<?php if (!empty($bahan_baku)) : ?>
							<?php foreach ($bahan_baku as $bb) : ?>
								<option value="<?= htmlspecialchars($bb->nama_bahan, ENT_QUOTES, 'UTF-8'); ?>">
							<?php endforeach; ?>
						<?php endif; ?>
					</datalist>
				</div>
				<div class="row">
					<div class="col-7"><label>Jumlah Masuk</label><input type="number" step="0.01" name="stok_gudang" class="form-control" required></div>
					<div class="col-5">
						<label>Satuan</label>
						<select name="satuan" class="form-control">
							<option value="gram">gram</option>
							<option value="ml">ml</option>
							<option value="pcs">pcs</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
		</form>
	</div>
</div>

<!-- Modal Tambah Resep -->
<div class="modal fade" id="modalResep" tabindex="-1">
	<div class="modal-dialog">
		<form action="<?= base_url('pantry/tambah_resep'); ?>" method="post" class="modal-content">
			<div class="modal-header"><h5 class="modal-title font-weight-bold">Atur Takaran Resep / Porsi</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
			<div class="modal-body">
				<div class="form-group">
					<label>Menu</label>
					<select name="menu_id" class="form-control" required>
						<option value="">-- Pilih Menu --</option>
						<?php if (!empty($menus)) : ?>
							<?php foreach ($menus as $m) : ?>
								<option value="<?= $m->id; ?>"><?= htmlspecialchars($m->nama_menu, ENT_QUOTES, 'UTF-8'); ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>
				<div class="form-group">
					<label>Bahan Baku</label>
					<select name="bahan_id" class="form-control" required>
						<option value="">-- Pilih Bahan --</option>
						<?php if (!empty($bahan_baku)) : ?>
							<?php foreach ($bahan_baku as $bb) : ?>
								<option value="<?= $bb->id; ?>"><?= htmlspecialchars($bb->nama_bahan, ENT_QUOTES, 'UTF-8'); ?> (<?= $bb->satuan; ?>)</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>
				<div class="form-group"><label>Takaran per 1 Porsi</label><input type="number" step="0.01" name="takaran_per_porsi" class="form-control" required></div>
			</div>
			<div class="modal-footer"><button type="submit" class="btn btn-warning font-weight-bold">Simpan</button></div>
		</form>
	</div>
</div>

<!-- Modal Edit Bahan (Murni PHP foreach per ID) -->
<?php if (!empty($analisis_bahan)) : ?>
	<?php foreach ($analisis_bahan as $b) : ?>
		<div class="modal fade" id="modalEditBahan<?= $b->id; ?>" tabindex="-1">
			<div class="modal-dialog">
				<form action="<?= base_url('pantry/edit_stok/' . $b->id); ?>" method="post" class="modal-content">
					<div class="modal-header"><h5 class="modal-title font-weight-bold">Edit Bahan Baku</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
					<div class="modal-body">
						<div class="form-group"><label>Nama Bahan</label><input type="text" name="nama_bahan" class="form-control" value="<?= htmlspecialchars($b->nama_bahan, ENT_QUOTES, 'UTF-8'); ?>" required></div>
						<div class="row">
							<div class="col-7"><label>Stok Gudang</label><input type="number" step="0.01" name="stok_gudang" class="form-control" value="<?= (float) $b->stok_gudang; ?>" required></div>
							<div class="col-5">
								<label>Satuan</label>
								<select name="satuan" class="form-control">
									<option value="gram" <?= ($b->satuan === 'gram') ? 'selected' : ''; ?>>gram</option>
									<option value="ml" <?= ($b->satuan === 'ml') ? 'selected' : ''; ?>>ml</option>
									<option value="pcs" <?= ($b->satuan === 'pcs') ? 'selected' : ''; ?>>pcs</option>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer"><button type="submit" class="btn btn-info">Update</button></div>
				</form>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<!-- Modal Edit Resep (Murni PHP foreach per ID) -->
<?php if (!empty($list_resep)) : ?>
	<?php foreach ($list_resep as $r) : ?>
		<div class="modal fade" id="modalEditResep<?= $r->id; ?>" tabindex="-1">
			<div class="modal-dialog">
				<form action="<?= base_url('pantry/edit_resep/' . $r->id); ?>" method="post" class="modal-content">
					<div class="modal-header"><h5 class="modal-title font-weight-bold">Edit Takaran: <?= htmlspecialchars($r->nama_menu, ENT_QUOTES, 'UTF-8'); ?></h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
					<div class="modal-body">
						<p class="mb-2">Bahan Baku: <strong><?= htmlspecialchars($r->nama_bahan, ENT_QUOTES, 'UTF-8'); ?></strong></p>
						<div class="form-group"><label>Takaran per 1 Porsi (<?= $r->satuan; ?>)</label><input type="number" step="0.01" name="takaran_per_porsi" class="form-control" value="<?= (float) $r->takaran_per_porsi; ?>" required></div>
					</div>
					<div class="modal-footer"><button type="submit" class="btn btn-info">Update</button></div>
				</form>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>