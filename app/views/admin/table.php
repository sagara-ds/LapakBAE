<?php $role = 'admin'; require dirname(__DIR__) . '/layouts/header.php'; ?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1><?= htmlspecialchars($pageTitle) ?></h1>
                <p><?= htmlspecialchars($intro ?? 'Pantau dan perbarui data aktif pada tabel berikut.') ?></p>
            </div>
            <?php if (in_array($current, ['pengguna', 'toko', 'layanan', 'kategori'], true)): ?>
                <a class="btn btn-accent" href="edit.php?type=<?= htmlspecialchars($current) ?>">Tambah <?= htmlspecialchars($current) ?></a>
            <?php endif; ?>
        </div>
        <section data-state-scope>
            <?php
            $stateVariant = 'table';
            $emptyTitle = 'Belum ada data';
            $emptyDescription = 'Data yang sesuai pencarian atau filter akan tampil di sini.';
            require dirname(__DIR__) . '/partials/states.php';
            ?>
            <div class="table-panel" data-state-content>
                <div class="table-heading">
                    <p class="table-scroll-hint">Geser ke samping pada layar kecil untuk melihat seluruh kolom.</p>
                    <label class="visually-hidden" for="table-search">Cari data</label>
                    <input id="table-search" class="form-control" type="search" data-table-search placeholder="Cari dalam tabel">
                </div>
                <div class="table-responsive" tabindex="0" role="region" aria-label="Tabel <?= htmlspecialchars($pageTitle) ?>, geser untuk melihat seluruh kolom" data-lenis-prevent>
                    <table class="table">
                        <thead>
                            <tr>
                                <?php foreach ($columns as $column): ?>
                                    <th scope="col"><?= htmlspecialchars($column) ?></th>
                                <?php endforeach; ?>
                                <th scope="col">Status</th>
                                <th scope="col">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($records as $record): ?>
                            <?php
                            $rowTitle = (string)($record['label'] ?? ($record['cells'][0] ?? 'data ini'));
                            $recStatus = $record['status'] ?? 'aktif';
                            $statusDisplay = $current === 'ulasan'
                                ? ($recStatus === 'aktif' ? 'Ditampilkan' : 'Disembunyikan')
                                : ($recordStatuses[$recStatus] ?? 'Aktif');
                            ?>
                            <tr <?= $current === 'booking' ? 'data-booking-id' : 'data-record-id' ?>="<?= htmlspecialchars($current === 'booking' ? (string)$record['id'] : $current . '-' . $record['id']) ?>" <?= $current === 'booking' ? 'data-status' : 'data-record-status' ?>="<?= htmlspecialchars($recStatus) ?>" <?= $current === 'ulasan' ? 'data-label-mode="visibility"' : '' ?>>
                                <?php foreach ($record['cells'] as $cell): ?>
                                    <td><?= htmlspecialchars((string)$cell) ?></td>
                                <?php endforeach; ?>
                                <td>
                                    <?php if ($current === 'booking'): $booking = $record; require dirname(__DIR__) . '/partials/status-badge.php'; else: ?>
                                        <span class="status-badge <?= $recStatus === 'aktif' ? 'status-selesai' : ($recStatus === 'menunggu' ? 'status-menunggu_konfirmasi' : 'status-dibatalkan') ?>" data-record-status-label><?= htmlspecialchars($statusDisplay) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <?php if ($current === 'booking'): ?>
                                            <a class="btn btn-outline-primary" href="detail_booking.php?id=<?= htmlspecialchars((string)$record['id']) ?>">Detail</a>
                                        <?php else: ?>
                                            <?php if ($current !== 'ulasan'): ?>
                                                <a class="btn btn-outline-primary" href="edit.php?type=<?= htmlspecialchars($current) ?>&amp;id=<?= htmlspecialchars((string)$record['id']) ?>">Edit</a>
                                            <?php endif; ?>
                                            <?php if ($current === 'toko'): ?>
                                                <button type="button" class="btn btn-accent" data-title="Verifikasi <?= htmlspecialchars($rowTitle) ?>" data-confirm="Setujui verifikasi usaha <?= htmlspecialchars($rowTitle) ?>? Setelah aktif, layanan usaha ini akan tampil di katalog publik." data-confirm-label="Verifikasi Toko" data-next-record-status="aktif" data-allowed-record-status="menunggu" <?= $recStatus !== 'menunggu' ? 'hidden' : '' ?>>Verifikasi</button>
                                                <button type="button" class="btn btn-outline-primary" data-title="Tolak <?= htmlspecialchars($rowTitle) ?>" data-confirm="Tolak pengajuan usaha <?= htmlspecialchars($rowTitle) ?>?" data-confirm-label="Tolak usaha" data-next-record-status="ditolak" data-allowed-record-status="menunggu" <?= $recStatus !== 'menunggu' ? 'hidden' : '' ?>>Tolak</button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-outline-primary" data-title="<?= $current === 'ulasan' ? 'Sembunyikan ulasan' : 'Nonaktifkan ' . htmlspecialchars($rowTitle) ?>" data-confirm="<?= $current === 'ulasan' ? 'Sembunyikan ulasan dari ' . htmlspecialchars($rowTitle) . ' agar tidak tampil di halaman publik?' : 'Nonaktifkan ' . htmlspecialchars($rowTitle) . '? Riwayat transaksi tetap tersimpan.' ?>" data-confirm-label="<?= $current === 'ulasan' ? 'Sembunyikan' : 'Nonaktifkan' ?>" data-next-record-status="nonaktif" <?= $recStatus !== 'aktif' ? 'hidden' : '' ?>><?= $current === 'ulasan' ? 'Sembunyikan' : 'Nonaktifkan' ?></button>
                                            <button type="button" class="btn btn-accent" data-title="<?= $current === 'ulasan' ? 'Tampilkan ulasan' : 'Aktifkan ' . htmlspecialchars($rowTitle) ?>" data-confirm="<?= $current === 'ulasan' ? 'Tampilkan kembali ulasan dari ' . htmlspecialchars($rowTitle) . '?' : 'Aktifkan kembali ' . htmlspecialchars($rowTitle) . '?' ?>" data-confirm-label="<?= $current === 'ulasan' ? 'Tampilkan' : 'Aktifkan' ?>" data-next-record-status="aktif" data-allowed-record-status="nonaktif" <?= $recStatus !== 'nonaktif' ? 'hidden' : '' ?>><?= $current === 'ulasan' ? 'Tampilkan' : 'Aktifkan' ?></button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="data-list-empty" data-table-empty hidden role="status">Tidak ada data yang cocok dengan kata pencarian tersebut.</div>
                <div class="table-footer">
                    <span data-table-count>Maksimal 10 baris per halaman</span>
                    <nav data-table-pagination aria-label="Halaman data"></nav>
                </div>
            </div>
        </section>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
