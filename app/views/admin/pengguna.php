<?php
require_once dirname(__DIR__) . '/partials/sample-data.php';
$pageTitle = 'Pengguna';
$current = 'pengguna';
$intro = 'Daftar akun pembeli dan pemilik usaha beserta kontak terdaftar.';
$columns = ['Nama', 'Peran', 'Email', 'Telepon'];
$records = [];
foreach ($users as $user) {
    $records[] = [
        'id' => $user['id'],
        'label' => $user['nama'],
        'status' => $user['status'],
        'cells' => [$user['nama'], ucwords(str_replace('_', ' ', $user['role'])), $user['email'], $user['no_telepon']],
    ];
}
require __DIR__ . '/table.php';
