<?php
require_once dirname(__DIR__) . '/partials/sample-data.php';
$pageTitle = 'Pengelolaan ulasan';
$current = 'ulasan';
$intro = 'Tinjau komentar pelanggan dan atur apakah ulasan ditampilkan atau disembunyikan di halaman jasa.';
$columns = ['Komentar', 'Layanan', 'Pelanggan', 'Rating'];
$records = [
    ['id' => 1, 'label' => 'Dewi P.', 'status' => 'aktif', 'cells' => ['Datang sesuai jadwal dan pekerjaannya rapi.', 'Bersih rumah', 'Dewi P.', '5 / 5']],
    ['id' => 2, 'label' => 'Nadia P.', 'status' => 'aktif', 'cells' => ['Revisi dijelaskan dengan baik.', 'Desain logo', 'Nadia P.', '5 / 5']],
];
require __DIR__ . '/table.php';
