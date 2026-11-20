<?php $pageTitle='Riwayat pekerjaan'; $role='pemilik_toko'; $current='riwayat'; require dirname(__DIR__).'/layouts/header.php'; ?>
<main id="main" class="container dashboard-layout"><?php require dirname(__DIR__).'/partials/sidebar.php'; ?><div class="dashboard-main"><div class="dashboard-heading"><div><h1>Riwayat pekerjaan</h1><p>Pekerjaan selesai, ditolak, dan dibatalkan dari usahamu.</p></div></div><?php require __DIR__.'/booking-table.php'; ?></div></main>
<?php require dirname(__DIR__).'/layouts/footer.php'; ?>
