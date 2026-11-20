<?php
$pageTitle = 'Laporan booking';
$role = 'admin';
$current = 'laporan';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1>Laporan booking</h1>
                <p>Rekap jumlah booking berdasarkan status pengerjaan. Cakupan: seluruh data booking tercatat.</p>
            </div>
            <button class="btn btn-outline-primary" type="button" data-print>Cetak laporan</button>
        </div>
        <?php require dirname(__DIR__) . '/partials/booking-report.php'; ?>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
