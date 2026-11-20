<?php
$pageTitle = 'Layanan usaha';
$role = 'pemilik_toko';
$current = 'layanan';
require dirname(__DIR__) . '/layouts/header.php';
?>
<main id="main" class="container dashboard-layout">
    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-heading">
            <div>
                <h1>Layanan usahamu</h1>
                <p>Kelola harga satuan, estimasi durasi, area layanan, dan status tampil di katalog.</p>
            </div>
            <a class="btn btn-accent" href="tambah_layanan.php">Tambah layanan</a>
        </div>
        <section data-state-scope>
            <?php
            $stateVariant = 'table';
            $emptyTitle = 'Belum ada layanan';
            $emptyDescription = 'Tambahkan layanan pertama agar usahamu tampil di katalog.';
            require dirname(__DIR__) . '/partials/states.php';
            ?>
            <div class="table-panel" data-state-content>
                <div class="table-heading">
                    <p class="table-scroll-hint">Geser ke samping pada layar kecil untuk melihat semua kolom.</p>
                    <label class="visually-hidden" for="layanan-search">Cari layanan</label>
                    <input id="layanan-search" class="form-control" type="search" data-table-search placeholder="Cari nama layanan">
                </div>
                <div class="table-responsive" tabindex="0" role="region" aria-label="Tabel layanan usaha, geser untuk melihat seluruh kolom" data-lenis-prevent>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Layanan</th>
                                <th scope="col">Harga &amp; Durasi</th>
                                <th scope="col">Status</th>
                                <th scope="col">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($services as $layanan): if ($layanan['store_id'] !== 1) continue; ?>
                            <tr data-record-id="layanan-<?= htmlspecialchars((string)$layanan['id']) ?>" data-record-status="<?= htmlspecialchars($layanan['status']) ?>">
                                <td>
                                    <strong><?= htmlspecialchars($layanan['nama_jasa']) ?></strong>
                                    <small><?= htmlspecialchars($layanan['area_layanan']) ?></small>
                                </td>
                                <td>
                                    Rp<?= htmlspecialchars(number_format($layanan['harga'], 0, ',', '.')) ?> / <?= htmlspecialchars($layanan['satuan_harga']) ?>
                                    <small><?= htmlspecialchars((string)$layanan['durasi_menit']) ?> menit</small>
                                </td>
                                <td><span class="status-badge status-selesai" data-record-status-label><?= htmlspecialchars($recordStatuses[$layanan['status']]) ?></span></td>
                                <td>
                                    <div class="table-actions">
                                        <a class="btn btn-outline-primary" href="edit_layanan.php?id=<?= htmlspecialchars((string)$layanan['id']) ?>">Edit</a>
                                        <button type="button" class="btn btn-outline-primary" data-title="Nonaktifkan layanan" data-confirm="Nonaktifkan <?= htmlspecialchars($layanan['nama_jasa']) ?>? Pelanggan tidak dapat memesan layanan ini sementara waktu." data-confirm-label="Nonaktifkan" data-next-record-status="nonaktif">Nonaktifkan</button>
                                        <button type="button" class="btn btn-accent" data-title="Aktifkan layanan" data-confirm="Aktifkan kembali <?= htmlspecialchars($layanan['nama_jasa']) ?> di katalog?" data-confirm-label="Aktifkan" data-next-record-status="aktif" hidden>Aktifkan</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="data-list-empty" data-table-empty hidden>Tidak ada layanan yang cocok dengan pencarianmu.</div>
                <div class="table-footer">
                    <span data-table-count>Maksimal 10 layanan per halaman</span>
                    <nav data-table-pagination aria-label="Halaman layanan"></nav>
                </div>
            </div>
        </section>
    </div>
</main>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
