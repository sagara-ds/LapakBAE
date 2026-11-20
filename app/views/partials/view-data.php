<template id="view-data"
    data-role="<?= htmlspecialchars($role) ?>"
    data-base="<?= htmlspecialchars($base) ?>"
    data-services="<?= htmlspecialchars(json_encode($services, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE), ENT_QUOTES, 'UTF-8') ?>"
    data-bookings="<?= htmlspecialchars(json_encode($allBookings, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE), ENT_QUOTES, 'UTF-8') ?>"
    data-statuses="<?= htmlspecialchars(json_encode($statuses, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"></template>
