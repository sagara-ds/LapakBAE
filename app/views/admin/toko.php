<?php
require_once dirname(__DIR__) . '/partials/sample-data.php';
$pageTitle = 'Pengelolaan toko';
$current = 'toko';
$intro = 'Usaha berstatus menunggu verifikasi ditampilkan paling atas untuk ditinjau terlebih dahulu.';
$columns = ['Nama usaha', 'Pemilik', 'Area', 'Telepon'];
$sortedStores = $stores;
// Keep unverified stores at the top so admin review queue is immediately actionable.
usort($sortedStores, static function (array $a, array $b): int {
    $rank = ['menunggu' => 0, 'aktif' => 1, 'nonaktif' => 2, 'ditolak' => 3];
    return ($rank[$a['status']] ?? 9) <=> ($rank[$b['status']] ?? 9);
});
$records = [];
foreach ($sortedStores as $store) {
    $records[] = [
        'id' => $store['id'],
        'label' => $store['nama_toko'],
        'status' => $store['status'],
        'cells' => [$store['nama_toko'], $store['pemilik'], $store['area_layanan'], $store['no_telepon']],
    ];
}
require __DIR__ . '/table.php';
