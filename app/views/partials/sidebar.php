<?php
$navConfig = [
    'pembeli' => [
        'name' => 'Nadia Putri',
        'initials' => 'NP',
        'label' => 'Akun Pembeli',
        'items' => [
            'dashboard' => ['Ringkasan', 'grid'],
            'riwayat' => ['Booking saya', 'calendar'],
            'profil' => ['Profil saya', 'user'],
        ],
    ],
    'pemilik_toko' => [
        'name' => 'Resik Apik',
        'initials' => 'RA',
        'label' => 'Pemilik Usaha',
        'items' => [
            'dashboard' => ['Ringkasan', 'grid'],
            'booking' => ['Booking masuk', 'inbox'],
            'layanan' => ['Layanan', 'briefcase'],
            'jadwal' => ['Jadwal', 'calendar'],
            'riwayat' => ['Riwayat', 'history'],
            'ulasan' => ['Ulasan', 'star'],
            'profil_toko' => ['Profil usaha', 'store'],
        ],
    ],
    'admin' => [
        'name' => 'Administrator',
        'initials' => 'AD',
        'label' => 'Panel Admin',
        'items' => [
            'dashboard' => ['Ringkasan', 'grid'],
            'toko' => ['Toko', 'store'],
            'booking' => ['Booking', 'inbox'],
            'layanan' => ['Layanan', 'briefcase'],
            'pengguna' => ['Pengguna', 'users'],
            'kategori' => ['Kategori', 'tag'],
            'ulasan' => ['Ulasan', 'star'],
            'laporan' => ['Laporan', 'chart'],
        ],
    ],
];
$account = $navConfig[$role] ?? $navConfig['pembeli'];
$activeKey = $current ?? 'dashboard';
$activeLabel = $account['items'][$activeKey][0] ?? 'Ringkasan';
?>
<aside class="account-sidebar" aria-label="Navigasi akun <?= htmlspecialchars($account['label']) ?>">
    <div class="sidebar-account">
        <span class="avatar avatar-sidebar"><?= htmlspecialchars($account['initials']) ?></span>
        <div class="sidebar-account-meta">
            <strong><?= htmlspecialchars($account['name']) ?></strong>
            <small><?= htmlspecialchars($account['label']) ?></small>
        </div>
        <button class="sidebar-mobile-toggle" type="button" data-sidebar-toggle aria-expanded="false" aria-controls="sidebar-nav-list">
            <span><?= htmlspecialchars($activeLabel) ?></span>
            <svg class="icon" aria-hidden="true"><use href="#i-chevron"/></svg>
        </button>
    </div>
    <nav id="sidebar-nav-list" class="sidebar-nav" aria-label="Menu <?= htmlspecialchars(strtolower($account['label'])) ?>" data-sidebar-nav>
        <?php foreach ($account['items'] as $file => [$label, $icon]): ?>
            <?php $isCurrent = $activeKey === $file; ?>
            <a class="<?= $isCurrent ? 'active' : '' ?>" href="<?= htmlspecialchars($base) ?>/<?= htmlspecialchars($role) ?>/<?= htmlspecialchars($file) ?>.php" <?= $isCurrent ? 'aria-current="page"' : '' ?>>
                <svg class="icon" aria-hidden="true"><use href="#i-<?= htmlspecialchars($icon) ?>"/></svg>
                <span><?= htmlspecialchars($label) ?></span>
            </a>
        <?php endforeach; ?>
        <a class="logout-link" href="<?= htmlspecialchars($base) ?>/auth/login.php">
            <svg class="icon" aria-hidden="true"><use href="#i-logout"/></svg>
            <span>Keluar</span>
        </a>
    </nav>
</aside>
