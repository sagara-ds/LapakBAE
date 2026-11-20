<?php
require_once dirname(__DIR__) . '/partials/sample-data.php';
$pageTitle = $pageTitle ?? 'Marketplace jasa UMKM lokal';
$active = $active ?? '';
$role = $role ?? 'guest';
?>
<!doctype html>
<html lang="id" data-type="service">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#3A5A40">
    <meta name="page-type" content="service">
    <meta name="site-kind" content="other">
    <meta name="description" content="Cari jasa UMKM sekitar, pilih tanggal dan jam layanan, tunggu konfirmasi penyedia, lalu bayar tunai setelah pekerjaan selesai.">
    <title><?= htmlspecialchars($pageTitle) ?> | LapakBae</title>
    <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars($asset) ?>/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars($asset) ?>/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($asset) ?>/css/style.css">
</head>
<body data-role="<?= htmlspecialchars($role) ?>">
<a class="skip-link" href="#main">Langsung ke konten utama</a>
<svg xmlns="http://www.w3.org/2000/svg" class="icon-defs" aria-hidden="true">
    <defs>
        <symbol id="i-search" viewBox="0 0 24 24"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/></symbol>
        <symbol id="i-pin" viewBox="0 0 24 24"><path d="M20 10c0 6-8 11-8 11S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></symbol>
        <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M4 12h15m-6-6 6 6-6 6"/></symbol>
        <symbol id="i-chevron" viewBox="0 0 24 24"><path d="m9 5 7 7-7 7"/></symbol>
        <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
        <symbol id="i-check" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></symbol>
        <symbol id="i-calendar" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M7 3v4m10-4v4M3 11h18m-12 4h2m3 0h2"/></symbol>
        <symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></symbol>
        <symbol id="i-users" viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.5"/><path d="M2 20v-1.5a6.5 6.5 0 0 1 13 0V20"/><circle cx="17" cy="9" r="2.8"/><path d="M17.5 14.2a5 5 0 0 1 4.5 4.8V20"/></symbol>
        <symbol id="i-store" viewBox="0 0 24 24"><path d="M4 10v11h16V10M3 10l2-7h14l2 7M3 10a3 3 0 0 0 5 2 3 3 0 0 0 4 0 3 3 0 0 0 4 0 3 3 0 0 0 5-2M9 21v-7h6v7"/></symbol>
        <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
        <symbol id="i-clean" viewBox="0 0 24 24"><path d="m15 3-5 11m-5-3 10 4-3 7-10-4 3-7Zm13-3h4m-2-2v4"/></symbol>
        <symbol id="i-tool" viewBox="0 0 24 24"><path d="M20 4a6 6 0 0 1-7 8L5 21l-3-3 9-8a6 6 0 0 1 8-7l-4 4 2 2 4-4Z"/></symbol>
        <symbol id="i-bag" viewBox="0 0 24 24"><path d="M4 7h16l1 14H3L4 7Zm4 1V6a4 4 0 0 1 8 0v2"/></symbol>
        <symbol id="i-pen" viewBox="0 0 24 24"><path d="m15 3 6 6-11 11-7 1 1-7L15 3Zm-3 3 6 6M4 14l6 6"/></symbol>
        <symbol id="i-book" viewBox="0 0 24 24"><path d="M12 5C8 2 3 3 2 4v16c3-2 7-1 10 1 3-2 7-3 10-1V4c-3-2-7-1-10 1Zm0 0v16"/></symbol>
        <symbol id="i-car" viewBox="0 0 24 24"><path d="m4 9 2-6h12l2 6M3 9h18v10H3V9Zm3 10v3m12-3v3M6 13h2m8 0h2"/></symbol>
        <symbol id="i-star" viewBox="0 0 24 24"><path d="m12 3 2.8 5.8 6.4.9-4.6 4.5 1.1 6.3-5.7-3-5.7 3 1.1-6.3-4.6-4.5 6.4-.9L12 3Z"/></symbol>
        <symbol id="i-grid" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></symbol>
        <symbol id="i-close" viewBox="0 0 24 24"><path d="m6 6 12 12M6 18 18 6"/></symbol>
        <symbol id="i-wallet" viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="14" rx="2"/><path d="m4 6 13-3v3m4 6h-6v4h6"/></symbol>
        <symbol id="i-cancel" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m6 6 12 12"/></symbol>
        <symbol id="i-briefcase" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M3 12h18"/></symbol>
        <symbol id="i-inbox" viewBox="0 0 24 24"><path d="M4 5h16l2 9v5H2v-5l2-9Z"/><path d="M2 14h6l2 3h4l2-3h6"/></symbol>
        <symbol id="i-history" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l3 2"/></symbol>
        <symbol id="i-tag" viewBox="0 0 24 24"><path d="m20.5 13.5-7 7a2 2 0 0 1-2.8 0l-7.2-7.2V4h9.3l7.7 7.7a1.3 1.3 0 0 1 0 1.8Z"/><circle cx="8" cy="8.5" r="1.2"/></symbol>
        <symbol id="i-chart" viewBox="0 0 24 24"><path d="M4 20h16"/><rect x="6" y="12" width="3" height="8" rx=".5"/><rect x="11" y="7" width="3" height="13" rx=".5"/><rect x="16" y="4" width="3" height="16" rx=".5"/></symbol>
        <symbol id="i-logout" viewBox="0 0 24 24"><path d="M10 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5"/><path d="M15 16l4-4-4-4M19 12H9"/></symbol>
        <symbol id="i-shield" viewBox="0 0 24 24"><path d="M12 3 5 6v6c0 4.5 3 7.5 7 9 4-1.5 7-4.5 7-9V6l-7-3Z"/><path d="m9 12 2 2 4-4"/></symbol>
    </defs>
</svg>
<?php require __DIR__ . '/navbar.php'; ?>
