<footer class="site-footer">
    <div class="container">
        <div class="footer-top">
            <div class="footer-brand">
                <a class="brand brand-footer" href="<?= htmlspecialchars($base) ?>/home/index.php">
                    <span class="brand-mark" aria-hidden="true">
                        <svg class="icon" aria-hidden="true"><use href="#i-store"/></svg>
                    </span>
                    <span class="brand-word">lapak<em>bae</em><span class="brand-dot" aria-hidden="true">.</span></span>
                </a>
                <p>Marketplace jasa UMKM sekitar dengan pilihan jadwal pasti, konfirmasi penyedia, dan pembayaran tunai setelah pekerjaan selesai.</p>
            </div>
            <div class="footer-nav-groups">
                <nav class="footer-links" aria-label="Navigasi layanan">
                    <a href="<?= htmlspecialchars($base) ?>/layanan/index.php">Cari jasa</a>
                    <a href="<?= htmlspecialchars($base) ?>/toko/index.php">Temukan usaha</a>
                    <a href="<?= htmlspecialchars($base) ?>/home/index.php#cara-kerja">Cara kerja</a>
                    <a href="<?= htmlspecialchars($base) ?>/home/tentang.php">Tentang LapakBae</a>
                    <a href="<?= htmlspecialchars($base) ?>/auth/register.php?role=pemilik_toko">Daftarkan usaha</a>
                </nav>
                <p class="footer-credits">Foto jasa bersumber dari Unsplash; ilustrasi editorial dibuat khusus untuk LapakBae.</p>
            </div>
        </div>
    </div>
    <div class="footer-bottom-band">
        <div class="container footer-bottom">
            <span>© 2026 LapakBae. Marketplace jasa UMKM Indonesia.</span>
            <nav class="footer-role-switch" aria-label="Pratinjau halaman peran">
                <span class="footer-role-label">Pratinjau peran:</span>
                <a href="<?= htmlspecialchars($base) ?>/pembeli/dashboard.php">Pembeli</a>
                <a href="<?= htmlspecialchars($base) ?>/pemilik_toko/dashboard.php">Pemilik toko</a>
                <a href="<?= htmlspecialchars($base) ?>/admin/dashboard.php">Admin</a>
            </nav>
        </div>
    </div>
</footer>
<div class="toast-region" id="toast-region" role="status" aria-live="polite" aria-atomic="true"></div>
<dialog class="confirm-dialog" id="confirm-dialog" aria-labelledby="dialog-title" aria-describedby="dialog-description" data-lenis-prevent>
    <form method="dialog">
        <button class="icon-button dialog-close" type="submit" aria-label="Tutup dialog" value="cancel">
            <svg class="icon" aria-hidden="true"><use href="#i-close"/></svg>
        </button>
        <h2 id="dialog-title">Konfirmasi tindakan</h2>
        <div id="dialog-description" class="dialog-description"></div>
        <div class="dialog-actions">
            <button class="btn btn-outline-primary" type="submit" value="cancel" autofocus>Kembali</button>
            <button class="btn btn-accent" id="dialog-confirm" type="submit" value="confirm">Konfirmasi Pesanan</button>
        </div>
    </form>
</dialog>
<?php require dirname(__DIR__) . '/partials/view-data.php'; ?>
<script src="<?= htmlspecialchars($asset) ?>/vendor/bootstrap.bundle.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js" defer></script>
<script src="<?= htmlspecialchars($asset) ?>/js/main.js" defer></script>
<script src="<?= htmlspecialchars($asset) ?>/js/animation.js" defer></script>
</body>
</html>
