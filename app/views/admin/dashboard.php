<?php
$pageTitle = 'Dashboard admin';
$role = 'admin';
$current = 'dashboard';
require dirname(__DIR__) . '/layouts/header.php';
$pendingStores = array_values(array_filter($stores, static fn(array $s): bool => $s['status'] === 'menunggu'));
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1>Ringkasan platform</h1>
                <p>Tinjau antrean verifikasi usaha baru, distribusi status booking, dan data aktif platform.</p>
            </div>
            <a class="btn btn-outline-primary" href="laporan.php">Lihat laporan</a>
        </div>

        <section class="priority-callout">
            <div>
                <h2><?= htmlspecialchars((string)count($pendingStores)) ?> usaha menunggu verifikasi</h2>
                <p><?php if ($pendingStores): ?>Usaha terbaru dalam antrean: <strong><?= htmlspecialchars($pendingStores[0]['nama_toko']) ?></strong> (<?= htmlspecialchars($pendingStores[0]['pemilik']) ?>). Periksa profil dan kontak sebelum menyetujui.<?php else: ?>Seluruh pengajuan usaha telah ditinjau.<?php endif; ?></p>
            </div>
            <a class="btn btn-accent" href="toko.php">Verifikasi Toko</a>
        </section>

        <div class="stat-grid" data-stat-scope="admin">
            <div class="stat-card" data-anim="stat">
                <span>Pengguna terdaftar</span>
                <strong><?= htmlspecialchars((string)count($users)) ?></strong>
                <small>Pembeli dan pemilik usaha</small>
            </div>
            <div class="stat-card" data-anim="stat">
                <span>Layanan tercatat</span>
                <strong><?= htmlspecialchars((string)count($services)) ?></strong>
                <small>Dalam <?= htmlspecialchars((string)count($categories)) ?> kategori</small>
            </div>
            <div class="stat-card" data-anim="stat">
                <span>Total booking</span>
                <strong data-stat-booking="total"><?= htmlspecialchars((string)count($allBookings)) ?></strong>
                <small>Seluruh status tercatat</small>
            </div>
        </div>

        <?php require dirname(__DIR__) . '/partials/booking-report.php'; ?>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
