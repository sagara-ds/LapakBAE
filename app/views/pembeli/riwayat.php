<?php
$pageTitle = 'Booking saya';
$role = 'pembeli';
$current = 'riwayat';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1>Booking saya</h1>
                <p>Filter jadwal aktif, menunggu konfirmasi, selesai, atau dibatalkan.</p>
            </div>
            <a class="btn btn-accent" href="<?= htmlspecialchars($base) ?>/layanan/index.php">Pesan Jasa</a>
        </div>
        <section data-state-scope>
            <div class="table-toolbar panel-toolbar" data-state-content>
                <p class="small-text muted" data-result-count aria-live="polite"></p>
                <div class="toolbar-control">
                    <label class="form-label" for="status-filter">Status booking</label>
                    <select class="form-select" id="status-filter" data-filter-status>
                        <option value="">Semua status</option>
                        <?php foreach ($statuses as $key => $label): ?>
                            <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php
            $stateVariant = 'cards';
            $emptyTitle = 'Belum ada booking pada status ini';
            $emptyDescription = 'Pilih status lain atau cari jasa yang dibutuhkan di katalog.';
            require dirname(__DIR__) . '/partials/states.php';
            ?>
            <div data-booking-list data-list data-page-size="10" data-item-label="booking" data-state-content>
                <?php foreach ($allBookings as $booking) {
                    if ($booking['user_id'] === 1) {
                        require dirname(__DIR__) . '/partials/booking-card.php';
                    }
                } ?>
            </div>
            <nav class="pagination-wrap" data-pagination aria-label="Halaman booking"></nav>
        </section>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
