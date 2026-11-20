<?php
$editing = $editing ?? false;
$pageTitle = $editing ? 'Edit layanan' : 'Tambah layanan';
$role = 'pemilik_toko';
$current = 'layanan';
require dirname(__DIR__) . '/layouts/header.php';
$layanan = null;
if ($editing) {
    foreach ($services as $item) {
        if ((string)$item['id'] === ($_GET['id'] ?? '1') && $item['store_id'] === 1) {
            $layanan = $item;
        }
    }
}
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1><?= htmlspecialchars($pageTitle) ?></h1>
                <p><?= $editing ? 'Perbarui detail, harga, atau foto untuk layanan yang sudah tayang.' : 'Isi detail layanan, tarif, durasi, dan area jangkauan agar pelanggan mudah memesan.' ?></p>
            </div>
            <a class="text-link" href="layanan.php">Kembali ke layanan</a>
        </div>
        <?php if ($editing && !$layanan): ?>
            <div class="empty-state">
                <h2>Layanan tidak ditemukan</h2>
                <p>Pilih layanan dari daftar usahamu terlebih dahulu.</p>
                <a class="btn btn-accent" href="layanan.php">Lihat layanan</a>
            </div>
        <?php else: ?>
            <form class="panel" data-ui-form data-service-form data-message="<?= $editing ? 'Perubahan layanan sudah diperiksa dan siap disimpan.' : 'Layanan baru sudah diperiksa dan siap ditambahkan.' ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <?php if ($editing): ?><input type="hidden" name="id" value="<?= htmlspecialchars((string)$layanan['id']) ?>"><?php endif; ?>

                <fieldset class="fieldset-card">
                    <legend>1. Detail layanan</legend>
                    <div class="field">
                        <label class="form-label" for="nama_jasa">Nama layanan</label>
                        <input class="form-control" id="nama_jasa" name="nama_jasa" maxlength="150" value="<?= htmlspecialchars($layanan['nama_jasa'] ?? '') ?>" required>
                        <p class="field-error" data-error-for="nama_jasa"><?= htmlspecialchars($errors['nama_jasa'] ?? '') ?></p>
                    </div>
                    <div class="field">
                        <label class="form-label" for="category_id">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Pilih kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars((string)$cat['id']) ?>" <?= ($layanan['category_id'] ?? null) === $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="field-error" data-error-for="category_id"><?= htmlspecialchars($errors['category_id'] ?? '') ?></p>
                    </div>
                    <div class="field">
                        <label class="form-label" for="deskripsi">Deskripsi pekerjaan</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?= htmlspecialchars($layanan['deskripsi'] ?? '') ?></textarea>
                        <p class="field-error" data-error-for="deskripsi"><?= htmlspecialchars($errors['deskripsi'] ?? '') ?></p>
                    </div>
                </fieldset>

                <fieldset class="fieldset-card">
                    <legend>2. Harga dan durasi</legend>
                    <div class="form-grid">
                        <div class="field">
                            <label class="form-label" for="harga">Harga (Rp)</label>
                            <input class="form-control" type="number" id="harga" name="harga" min="0" step="1000" value="<?= htmlspecialchars((string)($layanan['harga'] ?? '')) ?>" required>
                            <p class="field-error" data-error-for="harga"><?= htmlspecialchars($errors['harga'] ?? '') ?></p>
                        </div>
                        <div class="field">
                            <label class="form-label" for="satuan_harga">Satuan harga</label>
                            <input class="form-control" id="satuan_harga" name="satuan_harga" value="<?= htmlspecialchars($layanan['satuan_harga'] ?? '') ?>" required aria-describedby="satuan-help">
                            <p class="form-hint" id="satuan-help">Contoh: kunjungan, unit, jam, motor, atau pekerjaan.</p>
                            <p class="field-error" data-error-for="satuan_harga"><?= htmlspecialchars($errors['satuan_harga'] ?? '') ?></p>
                        </div>
                        <div class="field">
                            <label class="form-label" for="durasi_menit">Durasi pengerjaan (menit)</label>
                            <input class="form-control" type="number" id="durasi_menit" name="durasi_menit" min="15" max="540" step="15" value="<?= htmlspecialchars((string)($layanan['durasi_menit'] ?? '')) ?>" required aria-describedby="durasi-help">
                            <p class="form-hint" id="durasi-help">Dipakai untuk memblokir benturan jadwal booking.</p>
                            <p class="field-error" data-error-for="durasi_menit"><?= htmlspecialchars($errors['durasi_menit'] ?? '') ?></p>
                        </div>
                        <div class="field">
                            <label class="form-label" for="area_layanan">Area layanan</label>
                            <input class="form-control" id="area_layanan" name="area_layanan" value="<?= htmlspecialchars($layanan['area_layanan'] ?? '') ?>" required>
                            <p class="field-error" data-error-for="area_layanan"><?= htmlspecialchars($errors['area_layanan'] ?? '') ?></p>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="fieldset-card">
                    <legend>3. Foto pekerjaan</legend>
                    <div class="field">
                        <label class="form-label" for="gambar">Foto layanan</label>
                        <input class="form-control" type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/webp" data-upload-preview aria-describedby="gambar-help">
                        <p class="file-help" id="gambar-help">Format JPG, PNG, atau WebP maksimal 5 MB. Gunakan foto asli hasil pekerjaan atau tim.</p>
                        <p class="field-error" data-error-for="gambar"><?= htmlspecialchars($errors['gambar'] ?? '') ?></p>
                        <div class="upload-preview <?= $editing ? 'has-image' : '' ?>" data-image-preview>
                            <?php if ($editing): ?>
                                <img src="<?= htmlspecialchars($asset) ?>/images/<?= htmlspecialchars($layanan['gambar']) ?>" width="600" height="400" alt="Foto <?= htmlspecialchars($layanan['nama_jasa']) ?>">
                            <?php else: ?>
                                <span>Belum ada foto dipilih</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </fieldset>

                <div class="form-actions">
                    <button class="btn btn-accent" type="submit">Periksa <?= $editing ? 'perubahan' : 'layanan' ?></button>
                    <a class="btn btn-outline-primary" href="layanan.php">Batal</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
