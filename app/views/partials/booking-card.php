<article class="booking-card" data-item data-booking-id="<?= htmlspecialchars((string)$booking['id']) ?>" data-status="<?= htmlspecialchars($booking['status']) ?>">
    <div class="booking-card-top">
        <span class="booking-code"><?= htmlspecialchars($booking['kode_booking']) ?></span>
        <?php require __DIR__ . '/status-badge.php'; ?>
    </div>
    <div class="booking-card-main">
        <div>
            <h2><?= htmlspecialchars($booking['nama_jasa']) ?></h2>
            <span class="booking-store"><?= htmlspecialchars($booking['toko']) ?></span>
        </div>
        <strong class="booking-price">Rp<?= htmlspecialchars(number_format($booking['harga_booking'], 0, ',', '.')) ?></strong>
    </div>
    <div class="booking-card-bottom">
        <span class="booking-date">
            <svg class="icon" aria-hidden="true"><use href="#i-calendar"/></svg>
            <?= htmlspecialchars($booking['tanggal_label']) ?>, <?= htmlspecialchars($booking['jam_mulai']) ?> - <?= htmlspecialchars($booking['jam_selesai']) ?>
        </span>
        <div class="booking-card-actions">
            <a class="btn btn-accent btn-sm" data-allowed-status="selesai" href="<?= htmlspecialchars($base) ?>/pembeli/detail_booking.php?id=<?= htmlspecialchars((string)$booking['id']) ?>#form-ulasan" <?= $booking['status'] !== 'selesai' ? 'hidden' : '' ?>>Kirim Ulasan</a>
            <a class="btn btn-outline-primary btn-sm" href="<?= htmlspecialchars($base) ?>/pembeli/detail_booking.php?id=<?= htmlspecialchars((string)$booking['id']) ?>">Lihat detail</a>
        </div>
    </div>
</article>
