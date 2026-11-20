<?php
$pageTitle = 'Marketplace jasa UMKM sekitar';
$active = 'home';
require dirname(__DIR__) . '/layouts/header.php';
$activeServices = array_values(array_filter(
    $services,
    static fn(array $item): bool => $item['status'] === 'aktif' && $item['store_status'] === 'aktif'
));
$flagshipStores = [];
foreach ($activeServices as $activeService) {
    $categorySlug = $activeService['category_slug'] ?? '';
    if ($categorySlug !== '' && !isset($flagshipStores[$categorySlug])) {
        $flagshipStores[$categorySlug] = $activeService['toko'];
    }
}
?>
<main id="main">
    <noscript>
        <style>
            .splash-wipe { display: none !important; }
        </style>
    </noscript>
    <div class="splash-wipe" data-splash-wipe role="dialog" aria-modal="true" aria-label="Animasi pembuka LapakBae" tabindex="-1">
        <canvas class="splash-wipe-canvas" data-splash-wipe-canvas aria-hidden="true"></canvas>
        <div class="splash-wipe-cloth" data-splash-wipe-cloth aria-hidden="true">
            <svg viewBox="0 0 180 156" fill="none">
                <defs>
                    <clipPath id="lapak-cloth-shape">
                        <path d="M 24 22 C 62 14, 118 16, 154 24 C 164 62, 162 104, 150 136 C 110 144, 58 142, 22 132 C 14 96, 16 56, 24 22 Z"/>
                    </clipPath>
                    <pattern id="lapak-plaid" width="28" height="28" patternUnits="userSpaceOnUse" patternTransform="rotate(-5)">
                        <rect width="28" height="28" fill="#FBF6EB"/>
                        <rect x="0" y="0" width="28" height="14" fill="#C94734" fill-opacity="0.46"/>
                        <rect x="0" y="0" width="14" height="28" fill="#C94734" fill-opacity="0.46"/>
                        <line x1="0" y1="14" x2="28" y2="14" stroke="#9F2E22" stroke-opacity="0.22" stroke-width="1"/>
                        <line x1="14" y1="0" x2="14" y2="28" stroke="#9F2E22" stroke-opacity="0.22" stroke-width="1"/>
                        <line x1="0" y1="21" x2="28" y2="21" stroke="#FFFFFF" stroke-opacity="0.28" stroke-width="0.8"/>
                        <line x1="21" y1="0" x2="21" y2="28" stroke="#FFFFFF" stroke-opacity="0.28" stroke-width="0.8"/>
                    </pattern>
                    <linearGradient id="lapak-cloth-fold" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.24"/>
                        <stop offset="48%" stop-color="#FFFFFF" stop-opacity="0"/>
                        <stop offset="100%" stop-color="#4A1710" stop-opacity="0.18"/>
                    </linearGradient>
                </defs>
                <g clip-path="url(#lapak-cloth-shape)">
                    <rect width="180" height="156" fill="url(#lapak-plaid)"/>
                    <rect width="180" height="156" fill="url(#lapak-cloth-fold)"/>
                    <path d="M 122 18 C 138 22, 152 34, 158 50 C 142 46, 128 34, 122 18 Z" fill="#F3E5D0" fill-opacity="0.72"/>
                </g>
                <path d="M 29 28 C 64 21, 114 23, 147 29 C 155 62, 154 99, 143 129 C 107 136, 61 134, 28 125 C 21 93, 23 58, 29 28 Z" stroke="#8F281E" stroke-opacity="0.48" stroke-width="1.3" stroke-dasharray="5 4"/>
                <path d="M 24 22 C 62 14, 118 16, 154 24 C 164 62, 162 104, 150 136 C 110 144, 58 142, 22 132 C 14 96, 16 56, 24 22 Z" stroke="#7A241B" stroke-opacity="0.34" stroke-width="1.4"/>
            </svg>
        </div>
        <button type="button" class="splash-wipe-skip" data-splash-wipe-skip aria-label="Lewati animasi pembuka">Lewati</button>
        <p class="visually-hidden" role="status" aria-live="polite" data-splash-wipe-status></p>
    </div>
    <script>
        (function () {
            var wipe = document.querySelector('[data-splash-wipe]');
            if (!wipe) return;

            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (reduced || window.scrollY > 8 || window.location.hash) {
                wipe.hidden = true;
                return;
            }

            document.documentElement.classList.add('is-splash-wipe-locked');
            var scrollbar = Math.max(0, window.innerWidth - document.documentElement.clientWidth);
            if (scrollbar > 0) {
                document.documentElement.style.setProperty('--splash-wipe-scrollbar', scrollbar + 'px');
            }

            var dismiss = function () {
                if (window.__lapakSplashWipeReady) return;
                wipe.hidden = true;
                document.documentElement.classList.remove('is-splash-wipe-locked');
                document.documentElement.style.removeProperty('--splash-wipe-scrollbar');
                if (window.__lapakLenis) window.__lapakLenis.start();
            };

            try {
                wipe.focus({ preventScroll: true });
            } catch (error) {
                wipe.focus();
            }

            var skip = wipe.querySelector('[data-splash-wipe-skip]');
            if (skip) skip.addEventListener('click', dismiss);
            document.addEventListener('keydown', function (event) {
                if (wipe.hidden || wipe.classList.contains('is-done')) return;
                if (event.key === 'Escape') {
                    dismiss();
                    return;
                }
                if (event.key === 'Tab') {
                    event.preventDefault();
                    if (skip) skip.focus();
                }
            });

            window.setTimeout(dismiss, 4500);
        })();
    </script>
    <section class="splash-scroll-section" id="splash-intro" data-splash-scroll aria-label="Pengantar layanan UMKM LapakBae">
        <div class="container splash-inner">
            <div class="splash-topbar">
                <span class="splash-brand-pill">LapakBae <span class="splash-brand-detail">· Marketplace Jasa UMKM</span></span>
                <a class="splash-skip" href="#beranda-utama">Lewati intro</a>
            </div>
            <div class="splash-stage" data-splash-stage>
                <svg class="splash-orbit-svg" viewBox="0 0 680 480" aria-hidden="true">
                    <circle cx="340" cy="240" r="138" class="orbit-ring orbit-ring-inner"/>
                    <circle cx="340" cy="240" r="218" class="orbit-ring orbit-ring-outer"/>
                </svg>
                <div class="splash-center-logo" data-splash-center>
                    <span class="splash-center-mark" aria-hidden="true">
                        <svg class="icon" aria-hidden="true"><use href="#i-store"/></svg>
                    </span>
                    <span class="splash-center-word">lapak<em>bae</em><span class="brand-dot" aria-hidden="true">.</span></span>
                    <p>6 bidang jasa UMKM sekitar dalam satu jadwal</p>
                </div>
                <div class="splash-nodes">
                    <?php foreach ($categories as $idx => $category): ?>
                        <?php $storePartner = $flagshipStores[$category['slug']] ?? $category['nama']; ?>
                        <div class="splash-node splash-node-<?= htmlspecialchars((string)$idx) ?>" data-splash-node data-node-index="<?= htmlspecialchars((string)$idx) ?>">
                            <span class="splash-node-icon" aria-hidden="true">
                                <svg class="icon" aria-hidden="true"><use href="#i-<?= htmlspecialchars($category['icon']) ?>"/></svg>
                            </span>
                            <div class="splash-node-copy">
                                <strong><?= htmlspecialchars($category['nama']) ?></strong>
                                <small><?= htmlspecialchars($storePartner) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="splash-scroll-cue" data-splash-cue>
                <span class="scroll-cue-dot" aria-hidden="true"></span>
                <span>Gulir perlahan untuk menyatukan layanan</span>
            </div>
        </div>
    </section>

    <section class="hero" id="beranda-utama" data-hero-section>
        <div class="container hero-grid">
            <div class="hero-copy" data-anim="hero">
                <h1 class="hero-title">Jasa UMKM sekitar, pesan jadwal langsung.</h1>
                <p class="hero-description">Cari layanan kebersihan, perbaikan rumah, les privat, hingga desain usaha. Pilih tanggal dan jam, tunggu konfirmasi penyedia, lalu bayar tunai setelah pekerjaan selesai.</p>
                <form class="search-bar" action="<?= htmlspecialchars($base) ?>/layanan/index.php" method="get" role="search">
                    <label class="visually-hidden" for="hero-search">Cari jasa yang dibutuhkan</label>
                    <svg class="icon" aria-hidden="true"><use href="#i-search"/></svg>
                    <input id="hero-search" name="q" type="search" placeholder="Cari bersih rumah, servis AC, atau les privat">
                    <button class="btn btn-accent" type="submit">Cari Jasa</button>
                </form>
                <div class="popular-search">
                    <span>Pencarian cepat:</span>
                    <a href="<?= htmlspecialchars($base) ?>/layanan/index.php?q=bersih">Bersih rumah</a>
                    <a href="<?= htmlspecialchars($base) ?>/layanan/index.php?q=AC">Servis AC</a>
                    <a href="<?= htmlspecialchars($base) ?>/layanan/index.php?q=privat">Les privat</a>
                    <a href="<?= htmlspecialchars($base) ?>/layanan/index.php?q=logo">Desain logo</a>
                </div>
                <dl class="hero-facts">
                    <div>
                        <dt>Kategori tersedia</dt>
                        <dd><?= htmlspecialchars((string)count($categories)) ?> bidang jasa</dd>
                    </div>
                    <div>
                        <dt>Jam operasional</dt>
                        <dd>Senin - Sabtu, 08.00 - 17.00</dd>
                    </div>
                    <div>
                        <dt>Pembayaran</dt>
                        <dd>Tunai setelah selesai</dd>
                    </div>
                </dl>
            </div>
            <div class="hero-visual" data-anim="hero-media">
                <div class="hero-frame" data-develop>
                    <img class="hero-illustration" src="<?= htmlspecialchars($asset) ?>/images/illus-hero-marketplace.jpg" width="1200" height="896" alt="Ilustrasi pelaku jasa UMKM membersihkan rumah, menyervis AC, dan menyepakati jadwal layanan bersama pemilik rumah" fetchpriority="high">
                </div>
            </div>
        </div>
    </section>

    <section class="section catalog-showcase" aria-labelledby="katalog-heading" data-home-catalog>
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 id="katalog-heading">Pilih jasa sesuai kebutuhanmu</h2>
                    <p>Filter kategori untuk membandingkan durasi, area layanan, dan harga satuan secara langsung.</p>
                </div>
                <a class="text-link" data-home-catalog-link href="<?= htmlspecialchars($base) ?>/layanan/index.php">
                    <span>Lihat semua <?= htmlspecialchars((string)count($activeServices)) ?> jasa</span>
                    <svg class="icon" aria-hidden="true"><use href="#i-arrow"/></svg>
                </a>
            </div>

            <div class="category-tabs" role="tablist" aria-label="Filter kategori jasa" data-category-tabs>
                <button type="button" class="category-tab active" role="tab" aria-selected="true" data-category-tab="semua">
                    <svg class="icon" aria-hidden="true"><use href="#i-grid"/></svg>
                    <span>Semua jasa</span>
                </button>
                <?php foreach ($categories as $category): ?>
                    <button type="button" class="category-tab" role="tab" aria-selected="false" data-category-tab="<?= htmlspecialchars($category['slug']) ?>">
                        <svg class="icon" aria-hidden="true"><use href="#i-<?= htmlspecialchars($category['icon']) ?>"/></svg>
                        <span><?= htmlspecialchars($category['nama']) ?></span>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="service-grid" data-home-service-grid>
                <?php foreach ($activeServices as $index => $service): ?>
                    <div class="home-service-slot" data-home-card data-category="<?= htmlspecialchars($service['category_slug']) ?>" <?= $index >= 8 ? 'hidden' : '' ?>>
                        <?php require dirname(__DIR__) . '/partials/service-card.php'; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="workflow-section" aria-labelledby="cara-kerja-heading" data-pinned-chapters>
        <div class="container">
            <div class="workflow-header">
                <h2 id="cara-kerja-heading">Alur booking dari pilih jadwal sampai selesai</h2>
                <p>Setiap pesanan melewati konfirmasi penyedia agar jadwal dan kebutuhan pekerjaan disepakati sebelum hari layanan.</p>
            </div>
            <div class="workflow-grid">
                <div class="workflow-steps" role="tablist" aria-label="Tahapan alur booking">
                    <div class="workflow-progress-track" aria-hidden="true">
                        <span class="workflow-progress-fill" data-chapter-progress></span>
                    </div>
                    <button type="button" class="workflow-step is-active" role="tab" aria-selected="true" aria-controls="chapter-panel-1" id="chapter-tab-1" data-chapter-trigger="0">
                        <span class="step-index">01</span>
                        <div class="step-copy">
                            <h3>Pilih jasa, tanggal, dan jam mulai</h3>
                            <p>Bandingkan harga satuan serta estimasi durasi, lalu pilih slot waktu yang masih kosong pada jadwal usaha.</p>
                        </div>
                    </button>
                    <button type="button" class="workflow-step" role="tab" aria-selected="false" aria-controls="chapter-panel-2" id="chapter-tab-2" data-chapter-trigger="1">
                        <span class="step-index">02</span>
                        <div class="step-copy">
                            <h3>Tunggu konfirmasi dari penyedia</h3>
                            <p>Penyedia meninjau alamat dan catatan kebutuhanmu sebelum menyetujui jadwal. Kamu bisa memantau status dari dashboard.</p>
                        </div>
                    </button>
                    <button type="button" class="workflow-step" role="tab" aria-selected="false" aria-controls="chapter-panel-3" id="chapter-tab-3" data-chapter-trigger="2">
                        <span class="step-index">03</span>
                        <div class="step-copy">
                            <h3>Pekerjaan selesai, bayar tunai dan beri ulasan</h3>
                            <p>Pembayaran dilakukan tunai langsung ke penyedia setelah pekerjaan tuntas, lalu tulis ulasan berdasarkan pengalamanmu.</p>
                        </div>
                    </button>
                </div>
                <div class="workflow-stage" data-chapter-stage>
                    <div class="chapter-visual is-active" id="chapter-panel-1" role="tabpanel" aria-labelledby="chapter-tab-1" data-chapter-panel="0">
                        <img src="<?= htmlspecialchars($asset) ?>/images/illus-step-schedule.jpg" width="1200" height="896" alt="Ilustrasi pengguna memilih tanggal dan jam layanan pada kalender di meja rumah">
                    </div>
                    <div class="chapter-visual" id="chapter-panel-2" role="tabpanel" aria-labelledby="chapter-tab-2" data-chapter-panel="1">
                        <img src="<?= htmlspecialchars($asset) ?>/images/illus-step-confirm.jpg" width="1200" height="896" alt="Ilustrasi penyedia jasa UMKM memeriksa konfirmasi pesanan sambil menyiapkan peralatan kerja">
                    </div>
                    <div class="chapter-visual" id="chapter-panel-3" role="tabpanel" aria-labelledby="chapter-tab-3" data-chapter-panel="2">
                        <img src="<?= htmlspecialchars($asset) ?>/images/illus-step-done.jpg" width="1200" height="896" alt="Ilustrasi pelanggan menyerahkan pembayaran tunai langsung kepada penyedia jasa setelah pekerjaan selesai">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section partner-section" aria-labelledby="mitra-heading">
        <div class="container">
            <div class="owner-banner">
                <div class="owner-banner-copy">
                    <h2 id="mitra-heading">Kelola jasa dan jadwal usahamu di LapakBae</h2>
                    <p>Pasang daftar layanan dengan harga jelas, atur jam operasional mingguan dan tanggal libur, lalu konfirmasi pesanan masuk dari satu panel usaha.</p>
                    <div class="owner-banner-actions">
                        <a class="btn btn-accent" href="<?= htmlspecialchars($base) ?>/auth/register.php?role=pemilik_toko">Daftarkan Usaha</a>
                        <a class="btn btn-outline-light" href="<?= htmlspecialchars($base) ?>/toko/index.php">Lihat daftar usaha</a>
                    </div>
                </div>
                <div class="owner-banner-visual">
                    <img src="<?= htmlspecialchars($asset) ?>/images/illus-owner-partner.jpg" width="1200" height="896" alt="Ilustrasi tiga pelaku usaha jasa UMKM berdiri di depan tempat usaha dengan peralatan masing-masing">
                </div>
            </div>
        </div>
    </section>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
