<?php
$pageTitle = 'Detail booking';
$current = $role === 'pembeli' ? 'riwayat' : 'booking';
require dirname(__DIR__) . '/layouts/header.php';
$booking = null;
foreach ($allBookings as $item) {
    if ((string)$item['id'] !== ($_GET['id'] ?? '1')) continue;
    if ($role === 'pemilik_toko' && $item['store_id'] !== 1) continue;
    if ($role === 'pembeli' && $item['user_id'] !== 1) continue;
    $booking = $item;
}

$nextStepsByRole = [
    'pembeli' => [
        'menunggu_konfirmasi' => 'Tunggu penyedia menyetujui jadwal. Kamu masih bisa membatalkan permintaan sebelum pengerjaan dimulai.',
        'dikonfirmasi' => 'Jadwal disepakati. Pastikan kontak aktif pada tanggal layanan dan siapkan pembayaran tunai setelah pekerjaan selesai.',
        'sedang_dikerjakan' => 'Penyedia sedang mengerjakan layanan di lokasi sesuai catatan yang disepakati.',
        'selesai' => 'Pekerjaan selesai. Silakan kirim ulasan untuk membantu pelanggan lain menilai kualitas layanan.',
        'ditolak' => 'Permintaan ditolak penyedia. Pilih penyedia atau jadwal lain dari katalog jasa.',
        'dibatalkan' => 'Booking dibatalkan. Slot waktu telah dilepas kembali.',
    ],
    'pemilik_toko' => [
        'menunggu_konfirmasi' => 'Periksa jadwal, lokasi, dan catatan pelanggan lalu konfirmasi atau tolak permintaan ini.',
        'dikonfirmasi' => 'Jadwal sudah kamu terima. Tekan Mulai pengerjaan saat tim tiba di lokasi pelanggan.',
        'sedang_dikerjakan' => 'Pekerjaan sedang berlangsung. Tandai selesai setelah pekerjaan tuntas dan pembayaran tunai diterima.',
        'selesai' => 'Pekerjaan selesai dicatat. Pelanggan kini dapat menulis ulasan untuk usahamu.',
        'ditolak' => 'Permintaan ini sudah kamu tolak. Slot waktu kembali tersedia bagi pelanggan lain.',
        'dibatalkan' => 'Pelanggan membatalkan booking ini. Pekerjaan tidak dilanjutkan.',
    ],
    'admin' => [
        'menunggu_konfirmasi' => 'Booking menunggu keputusan penyedia terkait ketersediaan jadwal.',
        'dikonfirmasi' => 'Penyedia telah mengonfirmasi jadwal pelaksanaan layanan.',
        'sedang_dikerjakan' => 'Layanan sedang dikerjakan oleh penyedia.',
        'selesai' => 'Transaksi selesai dicatat dengan metode pembayaran tunai langsung.',
        'ditolak' => 'Penyedia menolak permintaan booking ini.',
        'dibatalkan' => 'Booking dibatalkan sebelum layanan dikerjakan.',
    ],
];
$stepDescriptions = $role === 'pemilik_toko'
    ? [
        'menunggu_konfirmasi' => 'Permintaan masuk dari pelanggan dan menunggu keputusanmu.',
        'dikonfirmasi' => 'Jadwal layanan telah kamu konfirmasi.',
        'sedang_dikerjakan' => 'Layanan sedang dikerjakan sesuai catatan pelanggan.',
        'selesai' => 'Pekerjaan tuntas dan dibayar tunai langsung.',
    ]
    : [
        'menunggu_konfirmasi' => 'Rincian jadwal dan alamat diterima penyedia.',
        'dikonfirmasi' => 'Penyedia menyepakati tanggal dan jam layanan.',
        'sedang_dikerjakan' => 'Pengerjaan dilakukan sesuai kesepakatan.',
        'selesai' => 'Pembayaran tunai langsung ke penyedia setelah selesai.',
    ];
?>
<main id="main" class="container dashboard-layout">
<?php require __DIR__ . '/sidebar.php'; ?>
<?php if (!$booking): ?>
    <div class="dashboard-main">
        <div class="empty-state">
            <h1>Booking tidak ditemukan</h1>
            <p>Pilih booking yang sesuai dari daftar akunmu.</p>
            <a class="btn btn-primary" href="<?= htmlspecialchars($base) ?>/<?= htmlspecialchars($role) ?>/<?= $role === 'pembeli' ? 'riwayat' : 'booking' ?>.php">Kembali ke daftar</a>
        </div>
    </div>
<?php else: ?>
<div class="dashboard-main" data-booking-detail data-booking-id="<?= htmlspecialchars((string)$booking['id']) ?>" data-status="<?= htmlspecialchars($booking['status']) ?>" data-detail-role="<?= htmlspecialchars($role) ?>">
    <div class="dashboard-heading">
        <div>
            <h1>Detail booking</h1>
            <p>Kode booking <?= htmlspecialchars($booking['kode_booking']) ?></p>
        </div>
        <a class="text-link" href="<?= htmlspecialchars($base) ?>/<?= htmlspecialchars($role) ?>/<?= $role === 'pembeli' ? 'riwayat' : 'booking' ?>.php">Kembali ke daftar</a>
    </div>

    <div class="next-action-callout" data-next-step-box>
        <?php require __DIR__ . '/status-badge.php'; ?>
        <p data-next-step-text><?= htmlspecialchars($nextStepsByRole[$role][$booking['status']] ?? '') ?></p>
    </div>

    <?php if ($role === 'pembeli'): ?>
    <section id="form-ulasan" class="panel review-panel mb-4" data-allowed-status="selesai" <?= $booking['status'] !== 'selesai' ? 'hidden' : '' ?>>
        <h2>Beri ulasan untuk <?= htmlspecialchars($booking['toko']) ?></h2>
        <p class="panel-description">Ceritakan hasil pekerjaan agar pelanggan lain mendapat gambaran yang jelas.</p>
        <form data-ui-form data-review-form data-message="Ulasan sudah diperiksa dan siap dikirim ke penyedia." method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <input type="hidden" name="booking_id" value="<?= htmlspecialchars((string)$booking['id']) ?>">
            <fieldset class="field">
                <legend class="form-label">Nilai layanan (1 kurang sampai 5 sangat baik)</legend>
                <div class="rating-picker">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <label>
                            <input type="radio" name="rating" value="<?= htmlspecialchars((string)$i) ?>" required>
                            <span><?= htmlspecialchars((string)$i) ?></span>
                            <svg class="icon" aria-hidden="true"><use href="#i-star"/></svg>
                        </label>
                    <?php endfor; ?>
                </div>
                <p class="field-error" data-error-for="rating"><?= htmlspecialchars($errors['rating'] ?? '') ?></p>
            </fieldset>
            <div class="field">
                <label class="form-label" for="komentar">Ulasan pekerjaan</label>
                <textarea class="form-control" name="komentar" id="komentar" rows="3" maxlength="2000" placeholder="Tulis ketepatan waktu, kerapian hasil, dan komunikasi penyedia" required></textarea>
                <p class="field-error" data-error-for="komentar"><?= htmlspecialchars($errors['komentar'] ?? '') ?></p>
            </div>
            <button class="btn btn-accent" type="submit">Kirim Ulasan</button>
        </form>
    </section>
    <?php endif; ?>

    <div class="booking-form-layout">
        <article class="panel">
            <h2><?= htmlspecialchars($booking['nama_jasa']) ?></h2>
            <p class="panel-description">Penyedia: <strong><?= htmlspecialchars($booking['toko']) ?></strong></p>

            <?php if ($role === 'admin'): ?>
                <div class="admin-relation-links">
                    <a class="btn btn-outline-primary btn-sm" href="pengguna.php?q=<?= urlencode($booking['nama_pemesan']) ?>">Lihat pembeli: <?= htmlspecialchars($booking['nama_pemesan']) ?></a>
                    <a class="btn btn-outline-primary btn-sm" href="toko.php?q=<?= urlencode($booking['toko']) ?>">Lihat toko: <?= htmlspecialchars($booking['toko']) ?></a>
                </div>
            <?php endif; ?>

            <dl class="info-list">
                <div><dt>Tanggal</dt><dd><?= htmlspecialchars($booking['tanggal_label']) ?></dd></div>
                <div><dt>Waktu</dt><dd><?= htmlspecialchars($booking['jam_mulai']) ?> - <?= htmlspecialchars($booking['jam_selesai']) ?></dd></div>
                <div><dt>Nama pemesan</dt><dd><?= htmlspecialchars($booking['nama_pemesan']) ?></dd></div>
                <div><dt>Nomor telepon</dt><dd><a href="tel:<?= htmlspecialchars($booking['no_telepon']) ?>"><?= htmlspecialchars($booking['no_telepon']) ?></a></dd></div>
                <div class="full"><dt>Alamat layanan</dt><dd><?= htmlspecialchars($booking['alamat_layanan']) ?></dd></div>
                <div class="full"><dt>Catatan kebutuhan</dt><dd><?= htmlspecialchars($booking['catatan']) ?></dd></div>
            </dl>
            <div class="summary-row total mt-4">
                <span>Total harga jasa</span>
                <strong>Rp<?= htmlspecialchars(number_format($booking['harga_booking'], 0, ',', '.')) ?></strong>
            </div>
            <?php if ($role !== 'pemilik_toko'): ?>
                <p class="payment-note">
                    <svg class="icon" aria-hidden="true"><use href="#i-wallet"/></svg>
                    Bayar tunai langsung ke penyedia setelah jasa selesai dikerjakan.
                </p>
            <?php endif; ?>

            <?php if ($role === 'pembeli'): ?>
                <div class="booking-cancel-wrap mt-3" data-allowed-status="menunggu_konfirmasi dikonfirmasi" <?= !in_array($booking['status'], ['menunggu_konfirmasi', 'dikonfirmasi'], true) ? 'hidden' : '' ?>>
                    <button type="button" class="btn btn-outline-primary" data-status-action data-title="Batalkan booking <?= htmlspecialchars($booking['kode_booking']) ?>?" data-confirm="Batalkan <?= htmlspecialchars($booking['nama_jasa']) ?> pada <?= htmlspecialchars($booking['tanggal_label']) ?> pukul <?= htmlspecialchars($booking['jam_mulai']) ?>? Slot waktu akan tersedia kembali." data-confirm-label="Batalkan booking" data-next-status="dibatalkan">Batalkan booking</button>
                    <p class="form-hint">Pembatalan tersedia selama status masih menunggu konfirmasi atau dikonfirmasi.</p>
                </div>
            <?php elseif ($role === 'pemilik_toko'): ?>
                <div class="form-actions mt-3">
                    <button type="button" class="btn btn-accent" data-status-action data-title="Konfirmasi pesanan <?= htmlspecialchars($booking['kode_booking']) ?>" data-confirm="Terima booking <?= htmlspecialchars($booking['nama_jasa']) ?> dari <?= htmlspecialchars($booking['nama_pemesan']) ?> pada <?= htmlspecialchars($booking['tanggal_label']) ?> pukul <?= htmlspecialchars($booking['jam_mulai']) ?>?" data-confirm-label="Konfirmasi Pesanan" data-next-status="dikonfirmasi" <?= $booking['status'] !== 'menunggu_konfirmasi' ? 'hidden' : '' ?>>Konfirmasi Pesanan</button>
                    <button type="button" class="btn btn-outline-primary" data-status-action data-title="Tolak booking <?= htmlspecialchars($booking['kode_booking']) ?>" data-confirm="Tolak permintaan <?= htmlspecialchars($booking['nama_jasa']) ?> dari <?= htmlspecialchars($booking['nama_pemesan']) ?>? Slot waktu akan terbuka kembali." data-confirm-label="Tolak booking" data-next-status="ditolak" <?= $booking['status'] !== 'menunggu_konfirmasi' ? 'hidden' : '' ?>>Tolak booking</button>
                    <button type="button" class="btn btn-accent" data-status-action data-title="Mulai pengerjaan" data-confirm="Ubah status <?= htmlspecialchars($booking['kode_booking']) ?> menjadi sedang dikerjakan?" data-confirm-label="Mulai pengerjaan" data-next-status="sedang_dikerjakan" <?= $booking['status'] !== 'dikonfirmasi' ? 'hidden' : '' ?>>Mulai pengerjaan</button>
                    <button type="button" class="btn btn-accent" data-status-action data-title="Selesaikan pekerjaan" data-confirm="Tandai <?= htmlspecialchars($booking['nama_jasa']) ?> selesai? Pembayaran tunai diterima dan pelanggan dapat memberi ulasan." data-confirm-label="Tandai selesai" data-next-status="selesai" <?= $booking['status'] !== 'sedang_dikerjakan' ? 'hidden' : '' ?>>Selesaikan pekerjaan</button>
                </div>
            <?php endif; ?>
        </article>

        <aside class="panel">
            <h2>Tahapan booking</h2>
            <ol class="booking-timeline">
                <?php
                $steps = [
                    'menunggu_konfirmasi' => 'Permintaan masuk',
                    'dikonfirmasi' => 'Jadwal dikonfirmasi',
                    'sedang_dikerjakan' => 'Sedang dikerjakan',
                    'selesai' => 'Pekerjaan selesai',
                ];
                $stepIndex = array_search($booking['status'], array_keys($steps), true);
                foreach ($steps as $key => $label):
                ?>
                    <li data-timeline-status="<?= htmlspecialchars($key) ?>" class="<?= $stepIndex !== false && array_search($key, array_keys($steps), true) <= $stepIndex ? 'done' : '' ?> <?= $key === $booking['status'] ? 'current' : '' ?>">
                        <?= htmlspecialchars($label) ?>
                        <small><?= htmlspecialchars($stepDescriptions[$key]) ?></small>
                    </li>
                <?php endforeach; ?>
            </ol>
            <p class="alert alert-info" data-terminal-message <?= !in_array($booking['status'], ['ditolak', 'dibatalkan'], true) ? 'hidden' : '' ?>>
                Booking <?= htmlspecialchars(strtolower($statuses[$booking['status']])) ?>. Pekerjaan tidak dilanjutkan.
            </p>
        </aside>
    </div>
</div>
<?php endif; ?>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
