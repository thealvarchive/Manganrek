<?php $this->load->view('admin/sidebar'); ?>

<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Kelola Meja</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>">Dashboard</a></li>
						<li class="breadcrumb-item active">Kelola Meja</li>
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
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'); ?>
				</div>
			<?php endif; ?>

			<div class="card card-primary card-outline">
				<div class="card-header">
					<h3 class="card-title font-weight-bold">Daftar Meja</h3>
					<div class="card-tools">
						<a href="<?= base_url('meja_admin/tambah'); ?>" class="btn btn-sm btn-primary">
							<i class="fas fa-plus mr-1"></i> Tambah Meja
						</a>
					</div>
				</div>

				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover text-nowrap mb-0">
							<thead class="thead-light">
								<tr>
									<th>No</th>
									<th>Nomor Meja</th>
									<th>QR Meja</th>
									<th>Status</th>
									<th class="text-center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($meja)) : ?>
									<?php foreach ($meja as $index => $row) : ?>
										<?php
										$status_labels = array(
											'tersedia' => array('Tersedia', 'success'),
											'terpakai' => array('Terpakai', 'warning'),
											'nonaktif' => array('Nonaktif', 'secondary')
										);
										$status = isset($status_labels[$row->status])
											? $status_labels[$row->status]
											: array(ucfirst($row->status), 'light');

										$qr_url = base_url('order/index/' . rawurlencode($row->nomor_meja));
										$qr_image_url = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' . rawurlencode($qr_url);
										?>
										<tr>
											<td><?= $index + 1; ?></td>
											<td><strong><?= htmlspecialchars($row->nomor_meja, ENT_QUOTES, 'UTF-8'); ?></strong></td>
											<td class="text-center">
												<a href="<?= htmlspecialchars($qr_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
													<img src="<?= htmlspecialchars($qr_image_url, ENT_QUOTES, 'UTF-8'); ?>"
														alt="QR Meja <?= htmlspecialchars($row->nomor_meja, ENT_QUOTES, 'UTF-8'); ?>"
														width="100" height="100">
												</a>
											</td>
											<td><span class="badge badge-<?= $status[1]; ?>"><?= htmlspecialchars($status[0], ENT_QUOTES, 'UTF-8'); ?></span></td>
											<td class="text-center">
												<a href="<?= base_url('meja_admin/edit/' . (int) $row->id); ?>" class="btn btn-xs btn-warning">Edit</a>
												<a href="<?= base_url('meja_admin/hapus/' . (int) $row->id); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Hapus meja ini?');">Hapus</a>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="5" class="text-center text-muted">Belum ada data meja.</td>
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
