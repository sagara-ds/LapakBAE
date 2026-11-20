<span class="status-badge status-<?= htmlspecialchars($booking['status']) ?>" data-status-label>
    <svg class="icon" aria-hidden="true"><use href="#i-<?= htmlspecialchars($statusIcons[$booking['status']]) ?>"/></svg>
    <?= htmlspecialchars($statuses[$booking['status']]) ?>
</span>
