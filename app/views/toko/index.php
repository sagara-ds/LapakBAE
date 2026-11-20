<?php
$pageTitle = 'Temukan usaha';
$active = 'toko';
require dirname(__DIR__) . '/layouts/header.php';
$serviceCountByStore = [];
foreach ($services as $svc) {
    if ($svc['status'] === 'aktif') {
        $serviceCountByStore[$svc['store_id']] = ($serviceCountByStore[$svc['store_id']] ?? 0) + 1;
    }
}
?>
<main id="main" class="container">
    <div class="page-head">
        <nav class="breadcrumb-nav" aria-label="Jejak halaman">
            <a href="<?= htmlspecialchars($base) ?>/home/index.php">Beranda</a>
            <span aria-hidden="true">/</span>
            <span>Temukan usaha</span>
        </nav>
        <h1>Daftar usaha UMKM</h1>
        <p>Lihat bidang keahlian, pemilik usaha, area kerja, dan jumlah layanan aktif tiap penyedia.</p>
    </div>
    <section class="catalog-section" data-state-scope>
        <form class="filter-bar filter-bar-stores" data-filter method="get">
            <div class="field-search">
                <label class="form-label" for="q">Cari usaha</label>
                <input class="form-control" id="q" name="q" type="search" placeholder="Nama usaha atau pemilik">
            </div>
            <div>
                <label class="form-label" for="kategori">Kategori</label>
                <select class="form-select" name="kategori" id="kategori">
                    <option value="">Semua kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['nama']) ?>"><?= htmlspecialchars($category['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label" for="lokasi">Area</label>
                <select class="form-select" name="lokasi" id="lokasi">
                    <option value="">Semua area</option>
                    <option>Purwokerto Utara</option>
                    <option>Purwokerto Timur</option>
                    <option>Purwokerto Selatan</option>
                    <option>Purwokerto Barat</option>
                </select>
            </div>
            <button type="submit" class="btn btn-accent">Cari usaha</button>
        </form>
        <div class="catalog-toolbar" data-state-content>
            <p data-result-count aria-live="polite"></p>
            <a class="text-link" href="index.php">Hapus filter</a>
        </div>
        <?php
        $stateVariant = 'cards';
        $emptyTitle = 'Usaha tidak ditemukan';
        $emptyDescription = 'Ubah nama usaha atau filter kategori dan area.';
        require dirname(__DIR__) . '/partials/states.php';
        ?>
        <div class="store-grid" data-list data-page-size="12" data-item-label="usaha" data-state-content>
            <?php foreach ($stores as $store): if ($store['status'] !== 'aktif') continue; ?>
                <?php $count = $serviceCountByStore[$store['id']] ?? 0; ?>
                <article class="store-card" data-item data-name="<?= htmlspecialchars($store['nama_toko'] . ' ' . $store['pemilik']) ?>" data-category="<?= htmlspecialchars($store['kategori']) ?>" data-location="<?= htmlspecialchars($store['area_layanan']) ?>" data-id="<?= htmlspecialchars((string)$store['id']) ?>">
                    <div class="store-card-media">
                        <img src="<?= htmlspecialchars($asset) ?>/images/<?= htmlspecialchars($store['foto_toko']) ?>" alt="<?= htmlspecialchars($store['nama_toko']) ?>" width="600" height="350" loading="lazy">
                    </div>
                    <div class="store-card-body">
                        <div class="store-card-head">
                            <span class="avatar avatar-sm"><?= htmlspecialchars($store['inisial']) ?></span>
                            <div>
                                <h2><?= htmlspecialchars($store['nama_toko']) ?></h2>
                                <span class="store-owner">Pemilik: <?= htmlspecialchars($store['pemilik']) ?></span>
                            </div>
                            <span class="category-chip"><?= htmlspecialchars($store['kategori']) ?></span>
                        </div>
                        <p class="store-desc"><?= htmlspecialchars($store['deskripsi']) ?></p>
                        <div class="store-card-foot">
                            <span class="store-meta-item">
                                <svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg>
                                <?= htmlspecialchars($store['area_layanan']) ?>
                            </span>
                            <span class="store-meta-item"><?= htmlspecialchars((string)$count) ?> layanan aktif</span>
                            <a class="text-link ms-auto" href="detail.php?id=<?= htmlspecialchars((string)$store['id']) ?>">
                                <span>Lihat profil</span>
                                <svg class="icon" aria-hidden="true"><use href="#i-arrow"/></svg>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <nav class="pagination-wrap" data-pagination aria-label="Halaman usaha"></nav>
    </section>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
