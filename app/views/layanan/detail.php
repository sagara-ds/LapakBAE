<?php
require_once dirname(__DIR__) . '/partials/sample-data.php';
$service = null;
foreach ($services as $item) {
    if ((string)$item['id'] === ($_GET['id'] ?? '1') && $item['status'] === 'aktif' && $item['store_status'] === 'aktif') {
        $service = $item;
    }
}
$pageTitle = $service ? $service['nama_jasa'] : 'Layanan tidak ditemukan';
$active = 'layanan';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container">
    <div class="page-head">
        <nav class="breadcrumb-nav" aria-label="Jejak halaman">
            <a href="<?= htmlspecialchars($base) ?>/home/index.php">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="<?= htmlspecialchars($base) ?>/layanan/index.php">Cari jasa</a>
            <span aria-hidden="true">/</span>
            <span><?= htmlspecialchars(ucfirst($service['category_slug'] ?? 'Layanan')) ?></span>
        </nav>
    </div>
    <?php if (!$service): ?>
        <div class="empty-state">
            <h1>Layanan tidak ditemukan</h1>
            <p>Layanan ini tidak tersedia atau sedang nonaktif. Pilih jasa lain dari katalog.</p>
            <a class="btn btn-accent" href="index.php">Kembali ke katalog jasa</a>
        </div>
    <?php else: ?>
        <div class="detail-layout">
            <div class="detail-header-block">
                <img class="detail-image" src="<?= htmlspecialchars($asset) ?>/images/<?= htmlspecialchars($service['gambar']) ?>" alt="<?= htmlspecialchars($service['nama_jasa']) ?>" width="900" height="600" fetchpriority="high">
                <div class="detail-summary-head">
                    <span class="category-chip"><?= htmlspecialchars(ucfirst($service['category_slug'])) ?></span>
                    <h1 class="detail-title"><?= htmlspecialchars($service['nama_jasa']) ?></h1>
                    <div class="detail-price">
                        Rp<?= htmlspecialchars(number_format($service['harga'], 0, ',', '.')) ?>
                        <small>/ <?= htmlspecialchars($service['satuan_harga']) ?></small>
                    </div>
                    <div class="detail-facts">
                        <span><svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg><?= htmlspecialchars($service['area_layanan']) ?></span>
                        <span><svg class="icon" aria-hidden="true"><use href="#i-clock"/></svg><?= htmlspecialchars((string)$service['durasi_menit']) ?> menit</span>
                        <span><svg class="icon" aria-hidden="true"><use href="#i-star"/></svg><?= htmlspecialchars($service['rating']) ?> (<?= htmlspecialchars((string)$service['ulasan']) ?> ulasan)</span>
                    </div>
                </div>
            </div>

            <aside class="panel booking-panel">
                <h2>Pilih jadwal layanan</h2>
                <p class="panel-description">Tentukan tanggal dan jam mulai untuk melanjutkan pemesanan.</p>
                <form action="<?= htmlspecialchars($base) ?>/auth/login.php" method="get" data-booking-entry data-slot-form data-duration="<?= htmlspecialchars((string)$service['durasi_menit']) ?>" data-store-id="<?= htmlspecialchars((string)$service['store_id']) ?>">
                    <input type="hidden" name="redirect" value="booking">
                    <input type="hidden" name="service_id" value="<?= htmlspecialchars((string)$service['id']) ?>">
                    <?php require dirname(__DIR__) . '/partials/slots.php'; ?>
                    <button class="btn btn-accent w-100" type="submit" data-booking-entry-button>Pesan Jasa</button>
                </form>
                <p class="payment-note">
                    <svg class="icon" aria-hidden="true"><use href="#i-wallet"/></svg>
                    Bayar tunai langsung ke penyedia setelah jasa selesai dikerjakan.
                </p>
            </aside>

            <div class="detail-body-blocks">
                <section class="content-block">
                    <h2>Rincian layanan</h2>
                    <p><?= htmlspecialchars($service['deskripsi']) ?></p>
                    <p>Sampaikan kebutuhan tambahan pada kolom catatan saat mengisi formulir booking. Penyedia memastikan ruang lingkup pekerjaan sebelum mengonfirmasi jadwal.</p>
                </section>
                <section class="content-block">
                    <h2>Penyedia layanan</h2>
                    <div class="store-inline">
                        <span class="avatar"><?= htmlspecialchars($service['inisial']) ?></span>
                        <div>
                            <h3><?= htmlspecialchars($service['toko']) ?></h3>
                            <p><?= htmlspecialchars($service['area_layanan']) ?></p>
                        </div>
                        <a class="btn btn-outline-primary btn-sm ms-auto" href="<?= htmlspecialchars($base) ?>/toko/detail.php?id=<?= htmlspecialchars((string)$service['store_id']) ?>">Lihat profil usaha</a>
                    </div>
                </section>
                <section class="content-block">
                    <h2>Ulasan pelanggan</h2>
                    <?php if ($service['id'] === 1): ?>
                        <article class="review-card">
                            <header>
                                <span class="avatar avatar-sm">DP</span>
                                <div>
                                    <h3>Dewi P.</h3>
                                    <time datetime="2026-11-12">12 November 2026</time>
                                </div>
                            </header>
                            <div class="stars" aria-label="5 dari 5 bintang">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <svg class="icon" aria-hidden="true"><use href="#i-star"/></svg>
                                <?php endfor; ?>
                            </div>
                            <p>Datang sesuai jadwal dan pekerjaannya rapi. Peralatan kebersihan dibawa lengkap oleh penyedia.</p>
                        </article>
                    <?php else: ?>
                        <p>Belum ada rincian ulasan tertulis untuk layanan ini.</p>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    <?php endif; ?>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
