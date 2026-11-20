<?php
$pageTitle = 'Dashboard usaha';
$role = 'pemilik_toko';
$current = 'dashboard';
require dirname(__DIR__) . '/layouts/header.php';
$pendingCount = 0;
foreach ($allBookings as $item) {
    if ($item['store_id'] === 1 && $item['status'] === 'menunggu_konfirmasi') {
        $pendingCount++;
    }
}
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1>Ringkasan Resik Apik</h1>
                <p>Tanggapi permintaan booking masuk dan pantau jadwal kerja tim hari ini.</p>
            </div>
            <a class="btn btn-outline-primary" href="tambah_layanan.php">Tambah layanan</a>
        </div>

        <section class="priority-callout" data-stat-scope="owner">
            <div>
                <h2><span data-stat-booking="pending"><?= htmlspecialchars((string)$pendingCount) ?></span> booking perlu keputusanmu</h2>
                <p>Periksa tanggal, alamat pelanggan, dan catatan pekerjaan sebelum menyetujui jadwal.</p>
            </div>
            <a class="btn btn-accent" href="booking.php">Tanggapi Booking</a>
        </section>

        <?php require dirname(__DIR__) . '/partials/booking-stats.php'; ?>

        <div class="section-header">
            <h2>Permintaan aktif usaha</h2>
            <a class="text-link" href="booking.php">Lihat semua booking</a>
        </div>
        <?php require __DIR__ . '/booking-table.php'; ?>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
