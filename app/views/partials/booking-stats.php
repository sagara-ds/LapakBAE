<?php
$statFields = $role === 'pemilik_toko'
    ? [
        'pending' => ['Perlu konfirmasi', 'Perlu keputusanmu sekarang'],
        'confirmed' => ['Jadwal dikonfirmasi', 'Siap dikerjakan sesuai tanggal'],
        'finished' => ['Pekerjaan selesai', 'Sudah dibayar tunai'],
    ]
    : [
        'active' => ['Booking aktif', 'Jadwal berjalan dan mendatang'],
        'pending' => ['Menunggu konfirmasi', 'Menunggu tanggapan penyedia'],
        'finished' => ['Pekerjaan selesai', 'Dapat diberi ulasan'],
    ];
$statValues = ['pending' => 0, 'confirmed' => 0, 'finished' => 0, 'active' => 0];
foreach ($allBookings as $statBooking) {
    if ($role === 'pemilik_toko' && $statBooking['store_id'] !== 1) continue;
    if ($role === 'pembeli' && $statBooking['user_id'] !== 1) continue;
    if ($statBooking['status'] === 'menunggu_konfirmasi') $statValues['pending']++;
    if ($statBooking['status'] === 'dikonfirmasi') $statValues['confirmed']++;
    if ($statBooking['status'] === 'selesai') $statValues['finished']++;
    if (in_array($statBooking['status'], ['menunggu_konfirmasi', 'dikonfirmasi', 'sedang_dikerjakan'], true)) $statValues['active']++;
}
?>
<div class="stat-grid" data-stat-scope="<?= $role === 'pemilik_toko' ? 'owner' : 'buyer' ?>">
    <?php foreach ($statFields as $statKey => [$statLabel, $statNote]): ?>
        <div class="stat-card <?= $statKey === 'pending' && $role === 'pemilik_toko' ? 'stat-card-priority' : '' ?>" data-anim="stat">
            <span><?= htmlspecialchars($statLabel) ?></span>
            <strong data-stat-booking="<?= htmlspecialchars($statKey) ?>"><?= htmlspecialchars((string)$statValues[$statKey]) ?></strong>
            <small><?= htmlspecialchars($statNote) ?></small>
        </div>
    <?php endforeach; ?>
</div>
