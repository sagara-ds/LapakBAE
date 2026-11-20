<?php
require_once dirname(__DIR__) . '/partials/sample-data.php';
$pageTitle = 'Pengelolaan layanan';
$current = 'layanan';
$intro = 'Layanan hanya tampil di katalog publik apabila status layanan dan status usahanya sama-sama aktif.';
$columns = ['Nama layanan', 'Penyedia (status toko)', 'Kategori', 'Harga'];
$records = [];
foreach ($services as $service) {
    $storeState = $recordStatuses[$service['store_status'] ?? 'aktif'] ?? 'Aktif';
    $records[] = [
        'id' => $service['id'],
        'label' => $service['nama_jasa'],
        'status' => $service['status'],
        'cells' => [
            $service['nama_jasa'],
            $service['toko'] . ' (' . $storeState . ')',
            ucfirst($service['category_slug']),
            'Rp' . number_format($service['harga'], 0, ',', '.') . ' / ' . $service['satuan_harga'],
        ],
    ];
}
require __DIR__ . '/table.php';
