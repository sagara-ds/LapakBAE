<?php
require_once dirname(__DIR__) . '/partials/sample-data.php';
$service = null;
foreach ($services as $item) {
    if ((string)$item['id'] === ($_GET['service_id'] ?? '1') && $item['status'] === 'aktif' && $item['store_status'] === 'aktif') {
        $service = $item;
    }
}
$pageTitle = 'Booking layanan';
$role = 'pembeli';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container">
    <div class="page-head">
        <nav class="breadcrumb-nav" aria-label="Jejak halaman">
            <a href="<?= htmlspecialchars($base) ?>/home/index.php">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="<?= htmlspecialchars($base) ?>/layanan/index.php">Cari jasa</a>
            <span aria-hidden="true">/</span>
            <span>Booking</span>
        </nav>
        <h1>Formulir pemesanan jasa</h1>
        <p>Pilih tanggal, jam mulai, dan pastikan alamat layanan lengkap sebelum meninjau pesanan.</p>
    </div>
    <?php if (!$service): ?>
        <div class="empty-state">
            <h2>Layanan tidak ditemukan</h2>
            <p>Pilih layanan dari katalog jasa terlebih dahulu sebelum menentukan jadwal.</p>
            <a class="btn btn-accent" href="<?= htmlspecialchars($base) ?>/layanan/index.php">Cari jasa</a>
        </div>
    <?php else: ?>
        <?php $formattedPrice = 'Rp' . number_format($service['harga'], 0, ',', '.'); ?>
        <div class="booking-form-layout booking-checkout-layout pb-5">
            <aside class="panel booking-summary-card" aria-labelledby="summary-heading">
                <h2 id="summary-heading">Ringkasan pesanan</h2>
                <div class="summary-service-head">
                    <img src="<?= htmlspecialchars($asset) ?>/images/<?= htmlspecialchars($service['gambar']) ?>" alt="<?= htmlspecialchars($service['nama_jasa']) ?>" width="600" height="360">
                    <div>
                        <h3><?= htmlspecialchars($service['nama_jasa']) ?></h3>
                        <p class="muted small-text"><?= htmlspecialchars($service['toko']) ?> · <?= htmlspecialchars($service['area_layanan']) ?></p>
                    </div>
                </div>
                <div aria-live="polite" aria-atomic="true">
                    <div class="summary-row"><span>Tanggal</span><strong id="summary-date">Belum dipilih</strong></div>
                    <div class="summary-row"><span>Jam mulai</span><strong id="summary-time">Belum dipilih</strong></div>
                    <div class="summary-row"><span>Perkiraan selesai</span><strong id="summary-end">Belum dipilih</strong></div>
                </div>
                <div class="summary-row"><span>Estimasi durasi</span><strong><?= htmlspecialchars((string)$service['durasi_menit']) ?> menit</strong></div>
                <div class="summary-row total"><span>Total harga</span><strong><?= htmlspecialchars($formattedPrice) ?></strong></div>
                <p class="payment-note">
                    <svg class="icon" aria-hidden="true"><use href="#i-wallet"/></svg>
                    Bayar tunai langsung ke penyedia setelah jasa selesai. Penyedia akan mengonfirmasi jadwal terlebih dahulu.
                </p>
            </aside>

            <form class="panel booking-form" data-booking-form data-ui-form data-slot-form data-duration="<?= htmlspecialchars((string)$service['durasi_menit']) ?>" data-store-id="<?= htmlspecialchars((string)$service['store_id']) ?>" data-service-name="<?= htmlspecialchars($service['nama_jasa']) ?>" data-store-name="<?= htmlspecialchars($service['toko']) ?>" data-price-label="<?= htmlspecialchars($formattedPrice) ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <input type="hidden" name="service_id" value="<?= htmlspecialchars((string)$service['id']) ?>">

                <fieldset class="fieldset-card">
                    <legend>1. Jadwal layanan</legend>
                    <?php require dirname(__DIR__) . '/partials/slots.php'; ?>
                </fieldset>

                <fieldset class="fieldset-card">
                    <legend>2. Kontak dan lokasi pengerjaan</legend>
                    <?php foreach ([
                        ['nama_pemesan', 'Nama pemesan', 'text', 'Nadia Putri'],
                        ['no_telepon', 'Nomor telepon aktif', 'tel', '081234567890'],
                        ['alamat_layanan', 'Alamat lengkap layanan', 'text', 'Jl. Kenanga No. 12, Purwokerto Utara'],
                    ] as $field): ?>
                        <div class="field">
                            <label class="form-label" for="<?= htmlspecialchars($field[0]) ?>"><?= htmlspecialchars($field[1]) ?></label>
                            <input class="form-control" id="<?= htmlspecialchars($field[0]) ?>" name="<?= htmlspecialchars($field[0]) ?>" type="<?= htmlspecialchars($field[2]) ?>" value="<?= htmlspecialchars($field[3]) ?>" required>
                            <p class="field-error" data-error-for="<?= htmlspecialchars($field[0]) ?>"><?= htmlspecialchars($errors[$field[0]] ?? '') ?></p>
                        </div>
                    <?php endforeach; ?>
                    <div class="field">
                        <label class="form-label" for="catatan">Catatan kebutuhan <span class="muted">(opsional)</span></label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3" maxlength="2000" placeholder="Jelaskan area fokus, kondisi ruangan, atau catatan akses rumah"></textarea>
                        <p class="field-error" data-error-for="catatan"><?= htmlspecialchars($errors['catatan'] ?? '') ?></p>
                    </div>
                </fieldset>

                <div class="checkout-submit-bar">
                    <div>
                        <span class="small-text muted">Total dibayar tunai setelah selesai</span>
                        <strong><?= htmlspecialchars($formattedPrice) ?></strong>
                    </div>
                    <button class="btn btn-accent" type="submit">Konfirmasi Pesanan</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
