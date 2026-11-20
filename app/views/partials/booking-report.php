<?php
$reportCounts = array_fill_keys(array_keys($statuses), 0);
foreach ($allBookings as $reportBooking) {
    $reportCounts[$reportBooking['status']]++;
}
?>
<section class="panel" data-stat-scope="admin" aria-labelledby="report-title">
    <div class="report-head">
        <div>
            <h2 id="report-title">Distribusi status booking</h2>
            <p class="report-scope">Cakupan data: seluruh booking tercatat pada sistem.</p>
        </div>
        <span class="report-total-chip">Total: <strong data-booking-total><?= htmlspecialchars((string)count($allBookings)) ?></strong></span>
    </div>
    <div class="report-bars">
        <?php foreach ($statuses as $key => $label): ?>
            <div class="report-row">
                <span><?= htmlspecialchars($label) ?></span>
                <div class="report-bar" aria-hidden="true">
                    <span data-report-bar="<?= htmlspecialchars($key) ?>" style="width:<?= htmlspecialchars((string)(count($allBookings) ? round($reportCounts[$key] / count($allBookings) * 100) : 0)) ?>%"></span>
                </div>
                <strong data-report-count="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars((string)$reportCounts[$key]) ?></strong>
            </div>
        <?php endforeach; ?>
    </div>
</section>
