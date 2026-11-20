<?php
$pageTitle = 'Tentang LapakBae';
$active = 'tentang';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container">
    <div class="page-head">
        <nav class="breadcrumb-nav" aria-label="Jejak halaman">
            <a href="<?= htmlspecialchars($base) ?>/home/index.php">Beranda</a>
            <span aria-hidden="true">/</span>
            <span>Tentang</span>
        </nav>
        <h1>Tentang LapakBae</h1>
        <p>LapakBae adalah marketplace jasa UMKM lokal untuk mencari penyedia di sekitar, menyepakati jadwal kunjungan, dan membayar tunai setelah pekerjaan selesai.</p>
    </div>

    <section class="about-grid">
        <article class="panel prose-panel">
            <h2>Mekanisme pemesanan jasa</h2>
            <p>Pelanggan memilih layanan berdasarkan kategori, harga satuan, area kerja, dan estimasi durasi. Setelah memilih tanggal serta jam yang masih kosong, permintaan dikirimkan ke penyedia bersama alamat dan catatan kebutuhan.</p>

            <h2>Konfirmasi penyedia dan status kerja</h2>
            <p>Setiap permintaan booking dimulai dengan status menunggu konfirmasi. Penyedia memeriksa ketersediaan tenaga dan detail permintaan, lalu memutuskan menerima atau menolak. Jika diterima, status berlanjut ke dikonfirmasi, sedang dikerjakan, hingga selesai.</p>

            <h2>Pembayaran tunai dan pembatalan</h2>
            <p>Pembayaran dilakukan secara tunai langsung kepada penyedia setelah layanan tuntas dikerjakan. Pembeli dapat membatalkan pesanan selama status masih menunggu konfirmasi atau dikonfirmasi agar slot waktu kembali tersedia.</p>

            <div class="form-actions">
                <a class="btn btn-accent" href="<?= htmlspecialchars($base) ?>/layanan/index.php">Pesan Jasa</a>
                <a class="btn btn-outline-primary" href="<?= htmlspecialchars($base) ?>/toko/index.php">Lihat daftar usaha</a>
            </div>
        </article>
        <div class="about-visual-panel">
            <img src="<?= htmlspecialchars($asset) ?>/images/illus-step-done.jpg" width="1200" height="896" alt="Ilustrasi serah terima pembayaran tunai langsung setelah layanan rumah selesai dikerjakan" loading="lazy">
        </div>
    </section>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
