<?php
$controller = strtolower($this->router->fetch_class());
$method = strtolower($this->router->fetch_method());

$active_orders = $controller === 'admin' && $method === 'index';
$active_menu = $controller === 'menu_admin';
$active_tables = $controller === 'meja_admin';
$active_kitchen = $controller === 'admin' && $method === 'dashboard_kitchen';
$active_pantry = $controller === 'pantry' || ($controller === 'admin' && $method === 'dashboard_pantry');
$active_reports = $controller === 'admin' && $method === 'laporan';
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<a href="<?= base_url('admin'); ?>" class="brand-link">
		<img src="<?= base_url('assets/dist/img/AdminLTELogo.png'); ?>" alt="Manganrek" class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-light">Manganrek</span>
	</a>

	<div class="sidebar">
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?= base_url('assets/dist/img/user2-160x160.jpg'); ?>" class="img-circle elevation-2" alt="User">
			</div>
			<div class="info">
				<a href="#" class="d-block">
					<?php
					$user = $this->ion_auth->user()->row();
					echo $user && !empty($user->first_name)
						? htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8')
						: 'Pengguna';
					?>
				</a>
			</div>
		</div>

		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<a href="<?= base_url('admin'); ?>" class="nav-link<?= $active_orders ? ' active' : ''; ?>">
						<i class="nav-icon fas fa-cash-register"></i>
						<p>Dashboard Pesanan</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('menu_admin'); ?>" class="nav-link<?= $active_menu ? ' active' : ''; ?>">
						<i class="nav-icon fas fa-utensils"></i>
						<p>Kelola Menu</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('meja_admin'); ?>" class="nav-link<?= $active_tables ? ' active' : ''; ?>">
						<i class="nav-icon fas fa-chair"></i>
						<p>Kelola Meja</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('admin/dashboard_kitchen'); ?>" class="nav-link<?= $active_kitchen ? ' active' : ''; ?>">
						<i class="nav-icon fas fa-fire"></i>
						<p>Dashboard Dapur</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('pantry'); ?>" class="nav-link<?= $active_pantry ? ' active' : ''; ?>">
						<i class="nav-icon fas fa-boxes"></i>
						<p>Dashboard Pantry</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('admin/laporan'); ?>" class="nav-link<?= $active_reports ? ' active' : ''; ?>">
						<i class="nav-icon fas fa-file-invoice-dollar"></i>
						<p>Riwayat &amp; Rekap</p>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</aside>
