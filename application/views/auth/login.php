<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login Petugas - RESTO QR</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
	<div class="card card-outline card-primary">
		<div class="card-header text-center">
			<a href="<?= base_url(); ?>" class="h3 font-weight-bold">Manganrek</a>
		</div>
		<div class="card-body">
			<p class="login-box-msg small">Login Kasir, Pelayan, Admin</p>

			<?php if (!empty($message)): ?>
				<div class="alert alert-danger alert-dismissible small">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?= $message; ?>
				</div>
			<?php endif; ?>

			<?= form_open('auth/login'); ?>
				<div class="input-group mb-3">
					<input type="text" name="identity" class="form-control" placeholder="Email / Username" required>
					<div class="input-group-append">
						<div class="input-group-text"><span class="fas fa-user"></span></div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" name="password" class="form-control" placeholder="Password" required>
					<div class="input-group-append">
						<div class="input-group-text"><span class="fas fa-lock"></span></div>
					</div>
				</div>
				<div class="row align-items-center mb-3">
					<div class="col-7">
						<div class="icheck-primary small">
							<input type="checkbox" name="remember" value="1" id="remember">
							<label for="remember" class="mb-0">Ingat saya</label>
						</div>
					</div>
					<div class="col-5">
						<button type="submit" class="btn btn-primary btn-block font-weight-bold">Masuk</button>
					</div>
				</div>
			<?= form_close(); ?>

			<p class="mb-0 text-center small">
				<a href="<?= base_url('auth/register'); ?>">Daftar akun baru</a>
			</p>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
