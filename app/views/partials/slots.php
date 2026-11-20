<div class="field">
    <label for="tanggal_booking" class="form-label">Tanggal layanan</label>
    <div class="calendar-quick-dates" data-quick-dates role="group" aria-label="Pilihan tanggal terdekat"></div>
    <input class="form-control" id="tanggal_booking" name="tanggal_booking" type="date" required aria-describedby="tanggal-help">
    <p class="form-hint" id="tanggal-help">Layanan tersedia Senin sampai Sabtu, pukul 08.00 sampai 17.00.</p>
    <p class="field-error" data-error-for="tanggal_booking"><?= htmlspecialchars($errors['tanggal_booking'] ?? '') ?></p>
</div>
<fieldset class="slot-fieldset" data-slot-picker data-awaiting-date="true">
    <legend class="form-label">Pilih jam mulai</legend>
    <p class="slot-legend" data-slot-message aria-live="polite">Pilih tanggal di atas untuk menampilkan jam mulai yang tersedia.</p>
    <div class="slot-grid" data-slot-grid>
        <?php foreach (['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'] as $slot): ?>
            <label class="slot-option">
                <input type="radio" name="jam_mulai" value="<?= htmlspecialchars($slot) ?>" required>
                <span><?= htmlspecialchars($slot) ?></span>
            </label>
        <?php endforeach; ?>
    </div>
    <p class="field-error" data-error-for="jam_mulai"><?= htmlspecialchars($errors['jam_mulai'] ?? '') ?></p>
</fieldset>
