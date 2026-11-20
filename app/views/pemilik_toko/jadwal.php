<?php
$pageTitle = 'Jadwal usaha';
$role = 'pemilik_toko';
$current = 'jadwal';
require dirname(__DIR__) . '/layouts/header.php';
$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1>Jadwal operasional</h1>
                <p>Atur jam buka mingguan dan tanggal libur usaha untuk membatasi slot booking pelanggan.</p>
            </div>
            <a class="btn btn-outline-primary" href="#tanggal-libur">Atur tanggal libur</a>
        </div>

        <form class="panel mb-4" data-ui-form data-message="Jadwal mingguan 7 hari sudah diperiksa. Semua jam buka dan tutup valid." method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <h2 class="field-group-title">Jam buka mingguan</h2>
            <p class="panel-description">Centang hari buka lalu atur jam operasional. Jam tutup wajib lebih akhir dari jam buka.</p>
            <div class="week-editor">
                <?php foreach ($days as $index => $day): ?>
                    <?php $dayNumber = $index + 1; ?>
                    <div class="week-row">
                        <input type="hidden" name="hari[<?= htmlspecialchars((string)$dayNumber) ?>]" value="<?= htmlspecialchars((string)$dayNumber) ?>">
                        <div class="week-day-toggle">
                            <label class="toggle-check">
                                <input type="checkbox" name="status[<?= htmlspecialchars((string)$dayNumber) ?>]" value="1" <?= $index < 6 ? 'checked' : '' ?>>
                                <strong><?= htmlspecialchars($day) ?></strong>
                            </label>
                        </div>
                        <div>
                            <label class="form-label" for="start-<?= htmlspecialchars((string)$index) ?>">Jam buka</label>
                            <input class="form-control" type="time" id="start-<?= htmlspecialchars((string)$index) ?>" name="jam_mulai[<?= htmlspecialchars((string)$dayNumber) ?>]" value="08:00">
                            <p class="field-error" data-error-for="jam_mulai[<?= htmlspecialchars((string)$dayNumber) ?>]"></p>
                        </div>
                        <div>
                            <label class="form-label" for="end-<?= htmlspecialchars((string)$index) ?>">Jam tutup</label>
                            <input class="form-control" type="time" id="end-<?= htmlspecialchars((string)$index) ?>" name="jam_selesai[<?= htmlspecialchars((string)$dayNumber) ?>]" value="17:00">
                            <p class="field-error" data-error-for="jam_selesai[<?= htmlspecialchars((string)$dayNumber) ?>]"></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="form-actions">
                <button class="btn btn-accent" type="submit">Periksa jadwal mingguan</button>
            </div>
        </form>

        <form id="tanggal-libur" class="panel" data-ui-form data-message="Tanggal libur sudah diperiksa. Slot booking pada tanggal tersebut akan dinonaktifkan." method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <h2 class="field-group-title">Tanggal libur khusus</h2>
            <p class="panel-description">Tambahkan tanggal tutup sementara di luar hari libur rutin mingguan.</p>
            <div class="form-grid">
                <div class="field">
                    <label class="form-label" for="tanggal">Tanggal tutup</label>
                    <input class="form-control" type="date" id="tanggal" name="tanggal" required>
                    <p class="field-error" data-error-for="tanggal"><?= htmlspecialchars($errors['tanggal'] ?? '') ?></p>
                </div>
                <div class="field">
                    <label class="form-label" for="keterangan">Keterangan libur</label>
                    <input class="form-control" id="keterangan" name="keterangan" maxlength="255" placeholder="Contoh: libur hari raya atau acara keluarga" required>
                    <p class="field-error" data-error-for="keterangan"><?= htmlspecialchars($errors['keterangan'] ?? '') ?></p>
                </div>
            </div>
            <button class="btn btn-outline-primary" type="submit">Periksa tanggal libur</button>
        </form>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
