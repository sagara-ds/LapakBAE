<?php
$pageTitle = 'Cari jasa';
$active = 'layanan';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container">
    <div class="page-head">
        <nav class="breadcrumb-nav" aria-label="Jejak halaman">
            <a href="<?= htmlspecialchars($base) ?>/home/index.php">Beranda</a>
            <span aria-hidden="true">/</span>
            <span>Cari jasa</span>
        </nav>
        <h1>Katalog jasa UMKM</h1>
        <p>Bandingkan harga satuan, estimasi durasi, dan area layanan sebelum memilih tanggal booking.</p>
    </div>
    <section class="catalog-section" data-state-scope>
        <div class="category-tabs catalog-tabs" role="tablist" aria-label="Filter cepat kategori" data-catalog-category-tabs>
            <button type="button" class="category-tab active" role="tab" aria-selected="true" data-filter-tab="">Semua kategori</button>
            <?php foreach ($categories as $category): ?>
                <button type="button" class="category-tab" role="tab" aria-selected="false" data-filter-tab="<?= htmlspecialchars($category['slug']) ?>">
                    <svg class="icon" aria-hidden="true"><use href="#i-<?= htmlspecialchars($category['icon']) ?>"/></svg>
                    <span><?= htmlspecialchars($category['nama']) ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <form class="filter-bar" data-filter method="get">
            <div class="field-search">
                <label class="form-label" for="q">Kata kunci</label>
                <input class="form-control" id="q" name="q" type="search" placeholder="Nama jasa atau kebutuhan">
            </div>
            <div>
                <label class="form-label" for="kategori">Kategori</label>
                <select class="form-select" name="kategori" id="kategori">
                    <option value="">Semua kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['slug']) ?>"><?= htmlspecialchars($category['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label" for="lokasi">Area layanan</label>
                <select class="form-select" name="lokasi" id="lokasi">
                    <option value="">Semua area</option>
                    <option>Purwokerto Utara</option>
                    <option>Purwokerto Timur</option>
                    <option>Purwokerto Selatan</option>
                    <option>Purwokerto Barat</option>
                </select>
            </div>
            <div class="field-sort">
                <label class="form-label" for="urutan">Urutkan</label>
                <select class="form-select" name="urutan" id="urutan">
                    <option value="terbaru">Terbaru</option>
                    <option value="harga_terendah">Harga terendah</option>
                    <option value="harga_tertinggi">Harga tertinggi</option>
                </select>
            </div>
            <button type="submit" class="btn btn-accent">Terapkan</button>
        </form>

        <div class="catalog-toolbar" data-state-content>
            <p data-result-count aria-live="polite">Menampilkan layanan tersedia</p>
            <a class="text-link" href="index.php">Hapus filter</a>
        </div>
        <?php
        $stateVariant = 'cards';
        $emptyTitle = 'Layanan tidak ditemukan';
        $emptyDescription = 'Ubah kata kunci pencarian atau hapus filter kategori dan area.';
        require dirname(__DIR__) . '/partials/states.php';
        ?>
        <h2 class="visually-hidden">Daftar layanan</h2>
        <div class="service-grid" data-catalog data-list data-page-size="12" data-item-label="jasa" data-state-content>
            <?php foreach ($services as $service) require dirname(__DIR__) . '/partials/service-card.php'; ?>
        </div>
        <?php require dirname(__DIR__) . '/partials/pagination.php'; ?>
    </section>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
