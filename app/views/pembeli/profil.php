<?php
$pageTitle = 'Profil saya';
$role = 'pembeli';
$current = 'profil';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1>Profil saya</h1>
                <p>Data nama, nomor telepon, dan alamat dipakai otomatis saat kamu mengisi formulir booking.</p>
            </div>
        </div>
        <form class="panel" data-ui-form data-message="Perubahan data profil sudah diperiksa. Kontak dan alamat siap digunakan pada pemesanan berikutnya." method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <div class="form-grid">
                <?php foreach ([
                    ['nama', 'Nama lengkap', 'text', 'Nadia Putri'],
                    ['email', 'Email', 'email', 'nadia@email.com'],
                    ['no_telepon', 'Nomor telepon', 'tel', '081234567890'],
                    ['alamat', 'Alamat utama', 'text', 'Jl. Kenanga No. 12, Purwokerto Utara'],
                ] as $field): ?>
                    <div class="field">
                        <label class="form-label" for="<?= htmlspecialchars($field[0]) ?>"><?= htmlspecialchars($field[1]) ?></label>
                        <input class="form-control" name="<?= htmlspecialchars($field[0]) ?>" id="<?= htmlspecialchars($field[0]) ?>" type="<?= htmlspecialchars($field[2]) ?>" value="<?= htmlspecialchars($field[3]) ?>" required>
                        <p class="field-error" data-error-for="<?= htmlspecialchars($field[0]) ?>"></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="form-actions">
                <button class="btn btn-accent" type="submit">Periksa perubahan profil</button>
                <a class="btn btn-outline-primary" href="dashboard.php">Kembali ke ringkasan</a>
            </div>
        </form>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
