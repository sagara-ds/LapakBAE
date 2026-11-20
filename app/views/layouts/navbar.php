<header class="site-header" data-site-header>
    <div class="scroll-progress" data-scroll-progress aria-hidden="true"></div>
    <div class="container header-inner">
        <a class="brand" href="<?= htmlspecialchars($base) ?>/home/index.php" aria-label="LapakBae, beranda">
            <span class="brand-mark" aria-hidden="true">
                <svg class="icon" aria-hidden="true"><use href="#i-store"/></svg>
            </span>
            <span class="brand-word">lapak<em>bae</em><span class="brand-dot" aria-hidden="true">.</span></span>
        </a>
        <nav class="desktop-nav" aria-label="Navigasi utama">
            <a class="<?= $active==='home'?'active':'' ?>" href="<?= htmlspecialchars($base) ?>/home/index.php" <?= $active==='home'?'aria-current="page"':'' ?>>Beranda</a>
            <a class="<?= $active==='layanan'?'active':'' ?>" href="<?= htmlspecialchars($base) ?>/layanan/index.php" <?= $active==='layanan'?'aria-current="page"':'' ?>>Cari jasa</a>
            <a class="<?= $active==='toko'?'active':'' ?>" href="<?= htmlspecialchars($base) ?>/toko/index.php" <?= $active==='toko'?'aria-current="page"':'' ?>>Temukan usaha</a>
            <a href="<?= htmlspecialchars($base) ?>/home/index.php#cara-kerja">Cara kerja</a>
            <a class="<?= $active==='tentang'?'active':'' ?>" href="<?= htmlspecialchars($base) ?>/home/tentang.php" <?= $active==='tentang'?'aria-current="page"':'' ?>>Tentang</a>
        </nav>
        <div class="header-actions">
            <div class="header-actions-group" data-guest-actions <?= $role!=='guest'?'hidden':'' ?>>
                <a class="login-link" href="<?= htmlspecialchars($base) ?>/auth/login.php">Masuk</a>
                <a class="btn btn-accent btn-sm" href="<?= htmlspecialchars($base) ?>/layanan/index.php">Pesan Jasa</a>
            </div>
            <div class="header-actions-group" data-account-actions <?= $role==='guest'?'hidden':'' ?>>
                <a class="account-link" data-account-link href="<?= htmlspecialchars($base) ?>/<?= htmlspecialchars($role==='guest'?'pembeli':$role) ?>/dashboard.php">
                    <span class="avatar avatar-sm" data-account-initials><?= $role==='admin'?'AD':($role==='pemilik_toko'?'RA':'NP') ?></span>
                    <span>Akun saya</span>
                </a>
            </div>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" aria-label="Buka menu navigasi">
                <svg class="icon" aria-hidden="true"><use href="#i-menu"/></svg>
                <span>Menu</span>
            </button>
        </div>
    </div>
    <nav id="mobile-nav" class="mobile-nav" aria-label="Navigasi seluler" data-lenis-prevent hidden>
        <div class="container mobile-nav-inner">
            <a class="<?= $active==='home'?'active':'' ?>" href="<?= htmlspecialchars($base) ?>/home/index.php">Beranda</a>
            <a class="<?= $active==='layanan'?'active':'' ?>" href="<?= htmlspecialchars($base) ?>/layanan/index.php">Cari jasa</a>
            <a class="<?= $active==='toko'?'active':'' ?>" href="<?= htmlspecialchars($base) ?>/toko/index.php">Temukan usaha</a>
            <a href="<?= htmlspecialchars($base) ?>/home/index.php#cara-kerja">Cara kerja</a>
            <a class="<?= $active==='tentang'?'active':'' ?>" href="<?= htmlspecialchars($base) ?>/home/tentang.php">Tentang</a>
            <div class="mobile-nav-actions" data-guest-actions <?= $role!=='guest'?'hidden':'' ?>>
                <a class="btn btn-outline-light" href="<?= htmlspecialchars($base) ?>/auth/login.php">Masuk</a>
                <a class="btn btn-accent" href="<?= htmlspecialchars($base) ?>/auth/register.php">Daftar akun</a>
            </div>
            <div class="mobile-nav-actions" data-account-actions <?= $role==='guest'?'hidden':'' ?>>
                <a class="btn btn-accent" href="<?= htmlspecialchars($base) ?>/<?= htmlspecialchars($role==='guest'?'pembeli':$role) ?>/dashboard.php" data-account-link>Buka dashboard akun</a>
            </div>
        </div>
    </nav>
</header>
