<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register Akun - RESTO QR</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
	<div class="card card-outline card-primary">
		<div class="card-header text-center">
			<a href="<?= base_url(); ?>" class="h3 font-weight-bold">Manganrek</a>
		</div>
		<div class="card-body">
			<p class="login-box-msg small">Buat akun baru</p>

			<?php if (!empty($message)): ?>
				<div class="alert alert-danger alert-dismissible small">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?= $message; ?>
				</div>
			<?php endif; ?>

			<?= form_open('auth/register'); ?>
				<div class="input-group mb-3">
					<input type="text" name="first_name" class="form-control" placeholder="Nama Lengkap" required>
					<div class="input-group-append">
						<div class="input-group-text"><span class="fas fa-user"></span></div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="email" name="email" class="form-control" placeholder="Email" required>
					<div class="input-group-append">
						<div class="input-group-text"><span class="fas fa-envelope"></span></div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" name="password" class="form-control" placeholder="Password" required>
					<div class="input-group-append">
						<div class="input-group-text"><span class="fas fa-lock"></span></div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" name="password_confirm" class="form-control" placeholder="Ulangi Password" required>
					<div class="input-group-append">
						<div class="input-group-text"><span class="fas fa-lock"></span></div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-block font-weight-bold">Daftar</button>
			<?= form_close(); ?>

			<p class="mt-3 mb-0 text-center small">
				<a href="<?= base_url('auth/login'); ?>">Sudah punya akun? Login</a>
			</p>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
