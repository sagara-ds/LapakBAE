<?php $isTableState = ($stateVariant ?? 'cards') === 'table'; ?>
<div class="empty-state" data-empty hidden>
    <svg class="icon" aria-hidden="true"><use href="#i-search"/></svg>
    <h2><?= htmlspecialchars($emptyTitle ?? 'Belum ada data yang cocok') ?></h2>
    <p><?= htmlspecialchars($emptyDescription ?? 'Ubah kata kunci pencarian atau hapus filter yang aktif.') ?></p>
    <a class="btn btn-outline-primary" href="<?= htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? '') ?>"><?= htmlspecialchars($emptyActionLabel ?? 'Hapus filter') ?></a>
</div>
<div class="alert alert-danger state-alert" data-error-state hidden role="alert">
    <strong>Data belum dapat dimuat.</strong>
    <span>Periksa koneksi atau muat ulang halaman untuk mencoba lagi.</span>
    <a class="btn btn-outline-primary btn-sm" href="<?= htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? '') ?>">Coba lagi</a>
</div>
<div data-loading-state hidden aria-busy="true">
    <p role="status" class="loading-label">Sedang menyiapkan data...</p>
    <?php if ($isTableState): ?>
        <div class="skeleton-table" aria-hidden="true">
            <div class="skeleton-row"></div>
            <div class="skeleton-row"></div>
            <div class="skeleton-row"></div>
            <div class="skeleton-row"></div>
        </div>
    <?php else: ?>
        <div class="service-grid" aria-hidden="true">
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
            <div class="skeleton-card"></div>
        </div>
    <?php endif; ?>
</div>
