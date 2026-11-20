<?php
$pageTitle = 'Masuk ke akun';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container auth-section">
    <div class="auth-card-shell">
        <section class="panel auth-form">
            <h1>Masuk ke akun LapakBae</h1>
            <p class="panel-description">Gunakan email dan kata sandi untuk melanjutkan booking atau mengelola usahamu.</p>
            <form data-ui-form data-auth="login" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <div class="field">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" type="email" id="email" name="email" placeholder="nama@email.com" autocomplete="email" required>
                    <p class="field-error" data-error-for="email"></p>
                </div>
                <div class="field">
                    <label class="form-label" for="password">Kata sandi</label>
                    <div class="password-wrap">
                        <input class="form-control" type="password" id="password" name="password" autocomplete="current-password" required minlength="8">
                        <button class="password-toggle" type="button" data-password-toggle="password" aria-pressed="false">Lihat</button>
                    </div>
                    <p class="field-error" data-error-for="password"></p>
                </div>
                <div class="field auth-preview-role">
                    <label class="form-label" for="role">Mode pratinjau peran</label>
                    <select class="form-select" id="role" name="role">
                        <option value="pembeli">Pembeli</option>
                        <option value="pemilik_toko">Pemilik toko</option>
                        <option value="admin">Admin</option>
                    </select>
                    <p class="form-hint">Pilih peran tujuan untuk memeriksa tampilan masing-masing dashboard.</p>
                </div>
                <button class="btn btn-accent w-100" type="submit">Masuk ke akun</button>
            </form>
            <p class="auth-bottom">Belum memiliki akun? <a href="<?= htmlspecialchars($base) ?>/auth/register.php">Buat akun baru</a></p>
        </section>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
