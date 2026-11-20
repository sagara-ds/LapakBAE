<?php
require_once dirname(__DIR__) . '/partials/sample-data.php';
$pageTitle = 'Kategori jasa';
$current = 'kategori';
$intro = 'Atur kategori utama yang digunakan untuk mengelompokkan layanan di katalog publik.';
$columns = ['Kategori', 'Slug', 'Keterangan'];
$records = [];
foreach ($categories as $category) {
    $records[] = [
        'id' => $category['id'],
        'label' => $category['nama'],
        'status' => 'aktif',
        'cells' => [$category['nama'], $category['slug'], $category['teks']],
    ];
}
require __DIR__ . '/table.php';
