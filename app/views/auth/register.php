<?php
$pageTitle = 'Buat akun LapakBae';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container auth-section">
    <div class="auth-card-shell auth-card-wide">
        <section class="panel auth-form">
            <h1>Buat akun LapakBae</h1>
            <p class="panel-description">Pilih jenis akun sesuai kebutuhanmu sebagai pencari jasa atau pemilik usaha UMKM.</p>
            <form data-ui-form data-auth="register" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <fieldset class="field">
                    <legend class="form-label">Jenis akun</legend>
                    <div class="role-picker">
                        <label class="role-choice">
                            <input type="radio" name="role" value="pembeli" checked>
                            <span>
                                <strong>Pembeli</strong>
                                <small>Cari jasa sekitar, pilih jadwal, dan pantau status booking.</small>
                            </span>
                        </label>
                        <label class="role-choice">
                            <input type="radio" name="role" value="pemilik_toko">
                            <span>
                                <strong>Pemilik usaha</strong>
                                <small>Pasang layanan, atur jam buka, dan kelola pesanan masuk.</small>
                            </span>
                        </label>
                    </div>
                </fieldset>
                <div class="form-grid">
                    <?php foreach ([
                        ['nama', 'Nama lengkap', 'text', 'Nama lengkapmu'],
                        ['email', 'Email', 'email', 'nama@email.com'],
                        ['no_telepon', 'Nomor telepon', 'tel', '081234567890'],
                        ['alamat', 'Alamat', 'text', 'Nama jalan dan kecamatan'],
                    ] as $field): ?>
                        <div class="field">
                            <label class="form-label" for="<?= htmlspecialchars($field[0]) ?>"><?= htmlspecialchars($field[1]) ?></label>
                            <input class="form-control" id="<?= htmlspecialchars($field[0]) ?>" name="<?= htmlspecialchars($field[0]) ?>" type="<?= htmlspecialchars($field[2]) ?>" placeholder="<?= htmlspecialchars($field[3]) ?>" required>
                            <p class="field-error" data-error-for="<?= htmlspecialchars($field[0]) ?>"></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="field">
                    <label class="form-label" for="password">Kata sandi</label>
                    <div class="password-wrap">
                        <input class="form-control" id="password" name="password" type="password" minlength="8" autocomplete="new-password" required>
                        <button class="password-toggle" type="button" data-password-toggle="password" aria-pressed="false">Lihat</button>
                    </div>
                    <p class="form-hint">Minimal 8 karakter.</p>
                    <p class="field-error" data-error-for="password"></p>
                </div>
                <button class="btn btn-accent w-100" type="submit" data-register-submit>Daftar sebagai Pembeli</button>
            </form>
            <p class="auth-bottom">Sudah memiliki akun? <a href="<?= htmlspecialchars($base) ?>/auth/login.php">Masuk ke akun</a></p>
        </section>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
