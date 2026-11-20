<?php
$pageTitle = 'Ulasan pelanggan';
$role = 'pemilik_toko';
$current = 'ulasan';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1>Ulasan pelanggan</h1>
                <p>Masukan dari pelanggan setelah status booking ditandai selesai.</p>
            </div>
        </div>
        <section data-state-scope>
            <?php
            $stateVariant = 'cards';
            $emptyTitle = 'Belum ada ulasan masuk';
            $emptyDescription = 'Ulasan pelanggan akan tampil setelah pekerjaan diselesaikan.';
            require dirname(__DIR__) . '/partials/states.php';
            ?>
            <div class="panel" data-state-content>
                <article class="review-card">
                    <header>
                        <span class="avatar">DP</span>
                        <div>
                            <h2>Dewi Pratiwi</h2>
                            <p class="muted small-text">Bersih rumah, sampai ke sudutnya · Booking LB-121126-006 · 12 November 2026</p>
                        </div>
                        <a class="btn btn-outline-primary btn-sm ms-auto" href="detail_booking.php?id=6">Lihat booking</a>
                    </header>
                    <p class="stars" aria-label="5 dari 5 bintang">
                        <?php for ($star = 1; $star <= 5; $star++): ?>
                            <svg class="icon" aria-hidden="true"><use href="#i-star"/></svg>
                        <?php endfor; ?>
                    </p>
                    <p>Datang sesuai jadwal. Dapur dan kamar mandi bersih rapi, peralatannya juga sudah dibawa sendiri.</p>
                </article>
            </div>
        </section>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
