<?php
require_once dirname(__DIR__) . '/partials/sample-data.php';
$pageTitle = 'Seluruh booking';
$current = 'booking';
$intro = 'Pantau jadwal layanan, penyedia, dan status terbaru dari semua pesanan platform.';
$columns = ['Layanan', 'Jadwal', 'Kode', 'Penyedia'];
$records = [];
foreach ($allBookings as $booking) {
    $records[] = [
        'id' => $booking['id'],
        'label' => $booking['kode_booking'],
        'status' => $booking['status'],
        'cells' => [$booking['nama_jasa'], $booking['tanggal_label'] . ', ' . $booking['jam_mulai'], $booking['kode_booking'], $booking['toko']],
    ];
}
require __DIR__ . '/table.php';
