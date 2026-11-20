<?php
$pageTitle = 'Profil usaha';
$role = 'pemilik_toko';
$current = 'profil_toko';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1>Profil usaha</h1>
                <p>Informasi usaha ini ditampilkan di halaman publik agar pelanggan mengenal tim dan kontakmu.</p>
            </div>
        </div>
        <form class="panel" data-ui-form data-message="Perubahan profil usaha sudah diperiksa dan siap diperbarui." method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <div class="field">
                <label class="form-label" for="nama_toko">Nama usaha</label>
                <input class="form-control" id="nama_toko" name="nama_toko" value="Resik Apik" required>
                <div class="field-error" data-error-for="nama_toko"></div>
            </div>
            <div class="field">
                <label class="form-label" for="deskripsi">Deskripsi usaha</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required>Jasa kebersihan rumah untuk keluarga. Kami membawa peralatan sendiri dan mengerjakan sesuai kebutuhan rumahmu.</textarea>
                <div class="field-error" data-error-for="deskripsi"></div>
            </div>
            <div class="form-grid">
                <div class="field">
                    <label class="form-label" for="alamat">Alamat usaha</label>
                    <input class="form-control" id="alamat" name="alamat" value="Jl. Melati No. 8, Purwokerto Utara" required>
                    <div class="field-error" data-error-for="alamat"></div>
                </div>
                <div class="field">
                    <label class="form-label" for="no_telepon">Nomor telepon aktif</label>
                    <input class="form-control" type="tel" id="no_telepon" name="no_telepon" value="081234567891" required>
                    <div class="field-error" data-error-for="no_telepon"></div>
                </div>
            </div>
            <div class="field">
                <label class="form-label" for="foto_toko">Foto usaha saat ini</label>
                <div class="upload-preview has-image" data-image-preview>
                    <img src="<?= htmlspecialchars($asset) ?>/images/cleaning.jpg" width="600" height="400" alt="Foto usaha Resik Apik">
                </div>
                <input class="form-control" type="file" id="foto_toko" name="foto_toko" accept="image/jpeg,image/png,image/webp" data-upload-preview aria-describedby="foto-toko-help">
                <p class="file-help" id="foto-toko-help">Pilih foto baru (JPG, PNG, atau WebP maksimal 5 MB) yang menampilkan tim atau hasil kerja asli.</p>
                <div class="field-error" data-error-for="foto_toko"></div>
            </div>
            <div class="form-actions">
                <button class="btn btn-accent" type="submit">Periksa profil usaha</button>
                <a class="btn btn-outline-primary" href="dashboard.php">Kembali</a>
            </div>
        </form>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
