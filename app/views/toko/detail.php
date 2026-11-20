<?php
require_once dirname(__DIR__) . '/partials/sample-data.php';
$store = null;
foreach ($stores as $item) {
    if ((string)$item['id'] === ($_GET['id'] ?? '1') && $item['status'] === 'aktif') {
        $store = $item;
    }
}
$pageTitle = $store ? $store['nama_toko'] : 'Usaha tidak ditemukan';
$active = 'toko';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container">
    <div class="page-head">
        <nav class="breadcrumb-nav" aria-label="Jejak halaman">
            <a href="<?= htmlspecialchars($base) ?>/home/index.php">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="index.php">Temukan usaha</a>
            <span aria-hidden="true">/</span>
            <span><?= htmlspecialchars($store['nama_toko'] ?? 'Profil usaha') ?></span>
        </nav>
    </div>
    <?php if (!$store): ?>
        <div class="empty-state">
            <h1>Usaha tidak ditemukan</h1>
            <p>Usaha ini belum aktif atau tidak tersedia. Lihat daftar penyedia lain.</p>
            <a class="btn btn-accent" href="index.php">Lihat usaha lain</a>
        </div>
    <?php else: ?>
        <section class="store-profile">
            <div class="store-profile-main">
                <span class="avatar avatar-lg"><?= htmlspecialchars($store['inisial']) ?></span>
                <div>
                    <span class="category-chip"><?= htmlspecialchars($store['kategori']) ?></span>
                    <h1><?= htmlspecialchars($store['nama_toko']) ?></h1>
                    <p><?= htmlspecialchars($store['deskripsi']) ?></p>
                    <dl class="store-profile-facts">
                        <div><dt>Pemilik</dt><dd><?= htmlspecialchars($store['pemilik']) ?></dd></div>
                        <div><dt>Alamat</dt><dd><?= htmlspecialchars($store['alamat']) ?></dd></div>
                        <div><dt>Telepon</dt><dd><a href="tel:<?= htmlspecialchars($store['no_telepon']) ?>"><?= htmlspecialchars($store['no_telepon']) ?></a></dd></div>
                        <div><dt>Jam operasional</dt><dd>Senin - Sabtu, 08.00 - 17.00</dd></div>
                    </dl>
                </div>
            </div>
            <div class="store-profile-cta">
                <a class="btn btn-accent" href="#layanan-usaha">Pilih layanan</a>
            </div>
        </section>
        <section id="layanan-usaha" class="catalog-section">
            <div class="section-header">
                <h2>Layanan dari <?= htmlspecialchars($store['nama_toko']) ?></h2>
            </div>
            <div class="service-grid">
                <?php foreach ($services as $service) {
                    if ($service['store_id'] === $store['id'] && $service['status'] === 'aktif') {
                        require dirname(__DIR__) . '/partials/service-card.php';
                    }
                } ?>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
