<?php if ($service['status'] !== 'aktif' || $service['store_status'] !== 'aktif') return; ?>
<article class="service-card" data-service-card data-item data-name="<?= htmlspecialchars($service['nama_jasa']) ?>" data-category="<?= htmlspecialchars($service['category_slug']) ?>" data-location="<?= htmlspecialchars($service['area_layanan']) ?>" data-price="<?= htmlspecialchars((string)$service['harga']) ?>" data-id="<?= htmlspecialchars((string)$service['id']) ?>">
    <a class="service-image-wrap" href="<?= htmlspecialchars($base) ?>/layanan/detail.php?id=<?= htmlspecialchars((string)$service['id']) ?>" tabindex="-1" aria-hidden="true">
        <img class="service-image" src="<?= htmlspecialchars($asset) ?>/images/<?= htmlspecialchars($service['gambar']) ?>" alt="" width="600" height="400" loading="lazy">
    </a>
    <div class="service-body">
        <div class="service-top-meta">
            <span class="service-provider">
                <span class="tiny-avatar" aria-hidden="true"><?= htmlspecialchars($service['inisial']) ?></span>
                <span><?= htmlspecialchars($service['toko']) ?></span>
            </span>
            <span class="category-chip"><?= htmlspecialchars(ucfirst($service['category_slug'])) ?></span>
        </div>
        <h3>
            <a class="service-title-link" href="<?= htmlspecialchars($base) ?>/layanan/detail.php?id=<?= htmlspecialchars((string)$service['id']) ?>">
                <?= htmlspecialchars($service['nama_jasa']) ?>
            </a>
        </h3>
        <div class="service-meta">
            <span>
                <svg class="icon" aria-hidden="true"><use href="#i-pin"/></svg>
                <?= htmlspecialchars($service['area_layanan']) ?>
            </span>
            <span>
                <svg class="icon" aria-hidden="true"><use href="#i-clock"/></svg>
                <?= htmlspecialchars((string)$service['durasi_menit']) ?> mnt
            </span>
            <span class="rating">
                <svg class="icon" aria-hidden="true"><use href="#i-star"/></svg>
                <?= htmlspecialchars($service['rating']) ?> (<?= htmlspecialchars((string)$service['ulasan']) ?>)
            </span>
        </div>
        <div class="service-price">
            <div class="price-stack">
                <strong>Rp<?= htmlspecialchars(number_format($service['harga'], 0, ',', '.')) ?></strong>
                <span>/ <?= htmlspecialchars($service['satuan_harga']) ?></span>
            </div>
            <a class="btn btn-accent btn-xs service-cta" href="<?= htmlspecialchars($base) ?>/layanan/detail.php?id=<?= htmlspecialchars((string)$service['id']) ?>">Pesan Jasa</a>
        </div>
    </div>
</article>
