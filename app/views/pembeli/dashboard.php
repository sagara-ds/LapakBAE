<?php
$pageTitle = 'Dashboard pembeli';
$role = 'pembeli';
$current = 'dashboard';
require dirname(__DIR__) . '/layouts/header.php';
$activeBuyerBookings = array_values(array_filter(
    $bookings,
    static fn(array $item): bool => !in_array($item['status'], ['selesai', 'ditolak', 'dibatalkan'], true)
));
$finishedBuyerBookings = array_values(array_filter(
    $bookings,
    static fn(array $item): bool => $item['status'] === 'selesai'
));
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1>Halo, Nadia</h1>
                <p>Pantau jadwal terdekat, status konfirmasi penyedia, dan riwayat layananmu.</p>
            </div>
            <a class="btn btn-accent" href="<?= htmlspecialchars($base) ?>/layanan/index.php">Pesan Jasa</a>
        </div>

        <?php require dirname(__DIR__) . '/partials/booking-stats.php'; ?>

        <section class="dashboard-section">
            <div class="section-header">
                <h2>Jadwal aktif dan menunggu konfirmasi</h2>
                <a class="text-link" href="riwayat.php">Lihat semua booking</a>
            </div>
            <?php foreach ($activeBuyerBookings as $booking) require dirname(__DIR__) . '/partials/booking-card.php'; ?>
        </section>

        <?php if ($finishedBuyerBookings): ?>
        <section class="dashboard-section">
            <div class="section-header">
                <h2>Pekerjaan selesai terbaru</h2>
            </div>
            <?php foreach ($finishedBuyerBookings as $booking) require dirname(__DIR__) . '/partials/booking-card.php'; ?>
        </section>
        <?php endif; ?>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
