<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?? 'Pesan Makanan'; ?></title>
	<!-- Bootstrap 5 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
	<style>
		body {
			background-color: #f8f9fa;
			padding-bottom: 80px;
		}

		.card-menu img {
			height: 140px;
			object-fit: cover;
		}

		.bottom-nav {
			position: fixed;
			bottom: 0;
			width: 100%;
			z-index: 1000;
		}
	</style>
</head>

<body>

	<!-- Info Nomor Meja (Hasil dari Scan QR) -->
	<nav class="navbar navbar-dark bg-dark sticky-top shadow-sm">
		<div class="container">
			<span class="navbar-brand mb-0 h1">Manganrek</span>
			<span class="badge bg-warning text-dark fs-6">Meja #<?= htmlspecialchars($nomor_meja ?? '05'); ?></span>
		</div>
	</nav>
