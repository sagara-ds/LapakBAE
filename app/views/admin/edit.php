<?php
require_once dirname(__DIR__) . '/partials/sample-data.php';
$role = 'admin';
$type = is_string($_GET['type'] ?? null) ? $_GET['type'] : 'pengguna';
$specs = [
    'pengguna' => [
        'title' => 'pengguna',
        'intro' => 'Kelompokkan data akun, peran, dan kontak pengguna dengan lengkap.',
        'data' => $users,
        'fields' => [
            ['nama', 'Nama lengkap', 'text', ''],
            ['email', 'Email', 'email', ''],
            ['role', 'Peran', 'role', ''],
            ['no_telepon', 'Nomor telepon', 'tel', 'Contoh: 081234567890'],
            ['alamat', 'Alamat', 'textarea', ''],
            ['password', 'Kata sandi', 'password', 'Minimal 8 karakter.'],
        ],
    ],
    'toko' => [
        'title' => 'toko',
        'intro' => 'Perbarui profil usaha, alamat operasional, kontak, dan foto tempat kerja.',
        'data' => $stores,
        'fields' => [
            ['nama_toko', 'Nama usaha', 'text', ''],
            ['no_telepon', 'Nomor telepon', 'tel', 'Contoh: 081234567891'],
            ['deskripsi', 'Deskripsi usaha', 'textarea', ''],
            ['alamat', 'Alamat usaha', 'textarea', ''],
            ['foto_toko', 'Foto usaha', 'file', 'Format JPG, PNG, atau WebP maksimal 5 MB.'],
        ],
    ],
    'layanan' => [
        'title' => 'layanan',
        'intro' => 'Pastikan penyedia, kategori, tarif satuan, dan durasi layanan sesuai.',
        'data' => $services,
        'fields' => [
            ['nama_jasa', 'Nama layanan', 'text', ''],
            ['store_id', 'Penyedia', 'store', ''],
            ['category_id', 'Kategori', 'category', ''],
            ['harga', 'Harga (Rp)', 'number', ''],
            ['satuan_harga', 'Satuan harga', 'text', 'Contoh: kunjungan, unit, jam, atau pekerjaan.'],
            ['durasi_menit', 'Durasi (menit)', 'number', 'Kelipatan 15 menit (15 sampai 540).'],
            ['area_layanan', 'Area layanan', 'text', ''],
            ['deskripsi', 'Deskripsi', 'textarea', ''],
            ['gambar', 'Foto layanan', 'file', 'Format JPG, PNG, atau WebP maksimal 5 MB.'],
        ],
    ],
    'kategori' => [
        'title' => 'kategori',
        'intro' => 'Tentukan nama kategori dan slug huruf kecil tanpa spasi untuk URL filter.',
        'data' => $categories,
        'fields' => [
            ['nama_kategori', 'Nama kategori', 'text', ''],
            ['slug', 'Slug kategori', 'text', 'Huruf kecil dengan tanda hubung, contoh: perbaikan-rumah.'],
            ['deskripsi', 'Keterangan singkat', 'textarea', ''],
        ],
    ],
];
$spec = $specs[$type] ?? null;
$editing = isset($_GET['id']);
$record = null;
if ($spec && $editing) {
    foreach ($spec['data'] as $item) {
        if ((string)$item['id'] === ($_GET['id'] ?? '')) {
            $record = $item;
        }
    }
}
if ($type === 'kategori' && $record) {
    $record['nama_kategori'] = $record['nama'];
    $record['deskripsi'] = $record['teks'];
}
$pageTitle = ($editing ? 'Edit ' : 'Tambah ') . ($spec['title'] ?? 'data');
$current = $type;
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1><?= htmlspecialchars($pageTitle) ?></h1>
                <p><?= htmlspecialchars($spec['intro'] ?? 'Periksa rincian data sebelum menyimpan.') ?></p>
            </div>
            <a class="text-link" href="<?= htmlspecialchars($spec ? $type : 'pengguna') ?>.php">Kembali ke daftar</a>
        </div>
        <?php if (!$spec || ($editing && !$record)): ?>
            <div class="empty-state">
                <h2>Data tidak ditemukan</h2>
                <p>Pilih baris data dari tabel pengelolaan terlebih dahulu.</p>
                <a class="btn btn-accent" href="pengguna.php">Kembali ke pengelolaan</a>
            </div>
        <?php else: ?>
            <?php if ($type === 'toko' && $editing && !empty($record['pemilik'])): ?>
                <div class="next-action-callout mb-3">
                    <span class="category-chip">Pemilik terdaftar</span>
                    <p>Akun pemilik usaha: <strong><?= htmlspecialchars($record['pemilik']) ?></strong> (Area: <?= htmlspecialchars($record['area_layanan'] ?? '-') ?>)</p>
                </div>
            <?php endif; ?>
            <form class="panel <?= $type === 'kategori' ? 'form-narrow' : '' ?>" data-ui-form data-message="<?= htmlspecialchars(($editing ? 'Perubahan ' : 'Data baru ') . $spec['title'] . ' sudah diperiksa dan valid.') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <?php if ($editing): ?><input type="hidden" name="id" value="<?= htmlspecialchars((string)$record['id']) ?>"><?php endif; ?>
                <div class="form-grid">
                    <?php foreach ($spec['fields'] as [$name, $label, $inputType, $hint]): ?>
                        <div class="field <?= in_array($inputType, ['textarea', 'file'], true) || $type === 'kategori' ? 'full' : '' ?>">
                            <label class="form-label" for="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($label) ?></label>
                            <?php if ($inputType === 'textarea'): ?>
                                <textarea class="form-control" id="<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>" rows="3" required><?= htmlspecialchars($record[$name] ?? '') ?></textarea>
                            <?php elseif (in_array($inputType, ['role', 'store', 'category'], true)): ?>
                                <select class="form-select" name="<?= htmlspecialchars($name) ?>" id="<?= htmlspecialchars($name) ?>" required>
                                    <option value="">Pilih <?= htmlspecialchars(strtolower($label)) ?></option>
                                    <?php if ($inputType === 'role'): ?>
                                        <?php foreach (['pembeli' => 'Pembeli', 'pemilik_toko' => 'Pemilik toko', 'admin' => 'Admin'] as $value => $text): ?>
                                            <option value="<?= htmlspecialchars($value) ?>" <?= ($record[$name] ?? '') === $value ? 'selected' : '' ?>><?= htmlspecialchars($text) ?></option>
                                        <?php endforeach; ?>
                                    <?php elseif ($inputType === 'store'): ?>
                                        <?php foreach ($stores as $option): ?>
                                            <option value="<?= htmlspecialchars((string)$option['id']) ?>" <?= ($record[$name] ?? null) === $option['id'] ? 'selected' : '' ?>><?= htmlspecialchars($option['nama_toko']) ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php foreach ($categories as $option): ?>
                                            <option value="<?= htmlspecialchars((string)$option['id']) ?>" <?= ($record[$name] ?? null) === $option['id'] ? 'selected' : '' ?>><?= htmlspecialchars($option['nama']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            <?php elseif ($inputType === 'file'): ?>
                                <input class="form-control" type="file" id="<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>" accept="image/jpeg,image/png,image/webp" data-upload-preview>
                                <?php if ($hint): ?><p class="file-help"><?= htmlspecialchars($hint) ?></p><?php endif; ?>
                                <div class="upload-preview <?= !empty($record[$name]) ? 'has-image' : '' ?>" data-image-preview>
                                    <?php if ($record[$name] ?? ''): ?>
                                        <img src="<?= htmlspecialchars($asset) ?>/images/<?= htmlspecialchars($record[$name]) ?>" alt="Foto <?= htmlspecialchars($spec['title']) ?>" width="600" height="400">
                                    <?php else: ?>
                                        <span>Belum ada foto dipilih</span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <input class="form-control" type="<?= htmlspecialchars($inputType) ?>" id="<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>" value="<?= htmlspecialchars($inputType === 'password' ? '' : (string)($record[$name] ?? '')) ?>" <?= $inputType === 'password' ? 'minlength="8" autocomplete="new-password"' : ($inputType === 'number' ? 'min="0"' : '') ?> <?= !($editing && $inputType === 'password') ? 'required' : '' ?>>
                                <?php if ($inputType === 'password' && $editing): ?>
                                    <p class="form-hint">Kosongkan jika tidak ingin mengganti kata sandi. Minimal 8 karakter jika diisi.</p>
                                <?php elseif ($hint): ?>
                                    <p class="form-hint"><?= htmlspecialchars($hint) ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                            <p class="field-error" data-error-for="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($errors[$name] ?? '') ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="form-actions">
                    <button class="btn btn-accent" type="submit">Periksa <?= $editing ? 'perubahan ' . htmlspecialchars($spec['title']) : htmlspecialchars($spec['title']) ?></button>
                    <a class="btn btn-outline-primary" href="<?= htmlspecialchars($type) ?>.php">Batal</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
