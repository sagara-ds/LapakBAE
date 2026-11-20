<?php
$isHistory = ($current ?? '') === 'riwayat';
$ownerRows = array_values(array_filter(
    $allBookings,
    static fn(array $item): bool => $item['store_id'] === 1
));
// Place bookings waiting for owner confirmation first so pending decisions stay visible.
usort($ownerRows, static function (array $a, array $b): int {
    $rank = ['menunggu_konfirmasi' => 0, 'sedang_dikerjakan' => 1, 'dikonfirmasi' => 2, 'selesai' => 3, 'dibatalkan' => 4, 'ditolak' => 5];
    return ($rank[$a['status']] ?? 9) <=> ($rank[$b['status']] ?? 9);
});
?>
<section data-state-scope>
<?php
$stateVariant = 'table';
$emptyTitle = $isHistory ? 'Belum ada riwayat pekerjaan' : 'Belum ada booking masuk';
$emptyDescription = $isHistory ? 'Pekerjaan selesai, ditolak, atau dibatalkan akan tercatat di sini.' : 'Permintaan pelanggan yang baru masuk akan tampil pada daftar ini.';
require dirname(__DIR__) . '/partials/states.php';
?>
<div class="table-panel" data-state-content data-history="<?= $isHistory ? 'true' : 'false' ?>">
    <div class="table-heading">
        <p class="table-scroll-hint">Geser ke samping pada layar kecil untuk melihat seluruh kolom.</p>
        <label for="booking-search" class="visually-hidden">Cari booking</label>
        <input id="booking-search" class="form-control" type="search" data-table-search placeholder="Cari nama pelanggan atau kode">
    </div>
    <div class="table-responsive" tabindex="0" role="region" aria-label="Tabel booking usaha, geser untuk melihat seluruh kolom" data-lenis-prevent>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Jadwal &amp; Layanan</th>
                    <th scope="col">Status</th>
                    <th scope="col">Tindakan</th>
                    <th scope="col">Pelanggan</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($ownerRows as $booking): ?>
                <tr data-booking-id="<?= htmlspecialchars((string)$booking['id']) ?>" data-status="<?= htmlspecialchars($booking['status']) ?>">
                    <td>
                        <strong><?= htmlspecialchars($booking['nama_jasa']) ?></strong>
                        <small><?= htmlspecialchars($booking['tanggal_label']) ?>, <?= htmlspecialchars($booking['jam_mulai']) ?> - <?= htmlspecialchars($booking['jam_selesai']) ?> · <?= htmlspecialchars($booking['kode_booking']) ?></small>
                    </td>
                    <td><?php require dirname(__DIR__) . '/partials/status-badge.php'; ?></td>
                    <td>
                        <div class="table-actions">
                            <?php if (!$isHistory): ?>
                                <button type="button" class="btn btn-accent" data-status-action data-title="Konfirmasi pesanan <?= htmlspecialchars($booking['kode_booking']) ?>" data-confirm="Terima <?= htmlspecialchars($booking['nama_jasa']) ?> untuk <?= htmlspecialchars($booking['nama_pemesan']) ?> pada <?= htmlspecialchars($booking['tanggal_label']) ?> pukul <?= htmlspecialchars($booking['jam_mulai']) ?>?" data-confirm-label="Konfirmasi Pesanan" data-next-status="dikonfirmasi" <?= $booking['status'] !== 'menunggu_konfirmasi' ? 'hidden' : '' ?>>Konfirmasi</button>
                                <button type="button" class="btn btn-outline-primary" data-status-action data-title="Tolak pesanan <?= htmlspecialchars($booking['kode_booking']) ?>" data-confirm="Tolak permintaan <?= htmlspecialchars($booking['nama_jasa']) ?> dari <?= htmlspecialchars($booking['nama_pemesan']) ?>? Slot waktu akan tersedia kembali." data-confirm-label="Tolak booking" data-next-status="ditolak" <?= $booking['status'] !== 'menunggu_konfirmasi' ? 'hidden' : '' ?>>Tolak</button>
                                <button type="button" class="btn btn-accent" data-status-action data-title="Mulai pengerjaan" data-confirm="Ubah status <?= htmlspecialchars($booking['kode_booking']) ?> menjadi sedang dikerjakan?" data-confirm-label="Mulai" data-next-status="sedang_dikerjakan" <?= $booking['status'] !== 'dikonfirmasi' ? 'hidden' : '' ?>>Mulai</button>
                                <button type="button" class="btn btn-accent" data-status-action data-title="Selesaikan pekerjaan" data-confirm="Tandai <?= htmlspecialchars($booking['nama_jasa']) ?> selesai?" data-confirm-label="Selesai" data-next-status="selesai" <?= $booking['status'] !== 'sedang_dikerjakan' ? 'hidden' : '' ?>>Selesai</button>
                            <?php endif; ?>
                            <a class="btn btn-outline-primary" href="detail_booking.php?id=<?= htmlspecialchars((string)$booking['id']) ?>">Detail</a>
                        </div>
                    </td>
                    <td>
                        <?= htmlspecialchars($booking['nama_pemesan']) ?>
                        <small><?= htmlspecialchars($booking['alamat_layanan']) ?></small>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="data-list-empty" data-table-empty hidden role="status">Tidak ada booking yang cocok dengan kata pencarian tersebut.</div>
    <div class="table-footer">
        <span data-table-count>Maksimal 10 booking per halaman</span>
        <nav data-table-pagination aria-label="Halaman booking"></nav>
    </div>
</div>
</section>
