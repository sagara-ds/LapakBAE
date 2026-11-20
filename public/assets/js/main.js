(() => {
  'use strict';

  const $ = (selector, scope = document) => scope?.querySelector(selector) || null;
  const $$ = (selector, scope = document) => scope ? [...scope.querySelectorAll(selector)] : [];

  const readStored = (key, fallback) => {
    try { return JSON.parse(sessionStorage.getItem(key) || 'null') ?? fallback; }
    catch (_) { return fallback; }
  };

  const store = (key, value) => {
    try { sessionStorage.setItem(key, JSON.stringify(value)); }
    catch (_) { /* Storage can be restricted in private browsing contexts. */ }
  };

  const readData = (name, fallback) => {
    try { return JSON.parse($('#view-data')?.dataset[name] || 'null') ?? fallback; }
    catch (_) { return fallback; }
  };

  const statusLabels = {
    menunggu_konfirmasi: 'Menunggu konfirmasi',
    dikonfirmasi: 'Dikonfirmasi',
    ditolak: 'Ditolak',
    sedang_dikerjakan: 'Sedang dikerjakan',
    selesai: 'Selesai',
    dibatalkan: 'Dibatalkan',
    ...readData('statuses', {})
  };

  const transitions = {
    menunggu_konfirmasi: ['dikonfirmasi', 'ditolak', 'dibatalkan'],
    dikonfirmasi: ['sedang_dikerjakan', 'dibatalkan'],
    sedang_dikerjakan: ['selesai'],
    ditolak: [],
    selesai: [],
    dibatalkan: []
  };

  const nextStepsByRole = {
    pembeli: {
      menunggu_konfirmasi: 'Tunggu penyedia menyetujui jadwal. Kamu masih bisa membatalkan permintaan sebelum pengerjaan dimulai.',
      dikonfirmasi: 'Jadwal disepakati. Pastikan kontak aktif pada tanggal layanan dan siapkan pembayaran tunai setelah selesai.',
      sedang_dikerjakan: 'Penyedia sedang mengerjakan layanan di lokasi sesuai catatan yang disepakati.',
      selesai: 'Pekerjaan selesai. Silakan kirim ulasan untuk membantu pelanggan lain menilai kualitas layanan.',
      ditolak: 'Permintaan ditolak penyedia. Pilih penyedia atau jadwal lain dari katalog jasa.',
      dibatalkan: 'Booking dibatalkan. Slot waktu telah dilepas kembali.'
    },
    pemilik_toko: {
      menunggu_konfirmasi: 'Periksa jadwal, lokasi, dan catatan pelanggan lalu konfirmasi atau tolak permintaan ini.',
      dikonfirmasi: 'Jadwal sudah kamu terima. Tekan Mulai pengerjaan saat tim tiba di lokasi pelanggan.',
      sedang_dikerjakan: 'Pekerjaan sedang berlangsung. Tandai selesai setelah pekerjaan tuntas dan pembayaran tunai diterima.',
      selesai: 'Pekerjaan selesai dicatat. Pelanggan kini dapat menulis ulasan untuk usahamu.',
      ditolak: 'Permintaan ini sudah kamu tolak. Slot waktu kembali tersedia bagi pelanggan lain.',
      dibatalkan: 'Pelanggan membatalkan booking ini. Pekerjaan tidak dilanjutkan.'
    },
    admin: {
      menunggu_konfirmasi: 'Booking menunggu keputusan penyedia terkait ketersediaan jadwal.',
      dikonfirmasi: 'Penyedia telah mengonfirmasi jadwal pelaksanaan layanan.',
      sedang_dikerjakan: 'Layanan sedang dikerjakan oleh penyedia.',
      selesai: 'Transaksi selesai dicatat dengan metode pembayaran tunai langsung.',
      ditolak: 'Penyedia menolak permintaan booking ini.',
      dibatalkan: 'Booking dibatalkan sebelum layanan dikerjakan.'
    }
  };

  const bookingFlow = ['menunggu_konfirmasi', 'dikonfirmasi', 'sedang_dikerjakan', 'selesai'];
  const terminalStatuses = ['ditolak', 'dibatalkan', 'selesai'];
  const slotControllers = [];
  const knownRoles = ['pembeli', 'pemilik_toko', 'admin'];
  const base = $('#view-data')?.dataset.base || '/app/views';
  const pageRole = document.body.dataset.role || $('#view-data')?.dataset.role || 'guest';

  let role = knownRoles.includes(pageRole) ? pageRole : readStored('lapakbae-role', 'guest');
  if (!knownRoles.includes(role)) role = 'guest';
  if (role !== 'guest') store('lapakbae-role', role);

  const toast = (message) => {
    const region = $('#toast-region');
    if (!region) return;
    const item = document.createElement('div');
    item.className = 'toast-message';
    item.textContent = message;
    region.append(item);
    window.setTimeout(() => item.remove(), 6000);
  };

  let dialogCallback = null;
  const dialog = $('#confirm-dialog');
  dialog?.addEventListener('close', () => {
    window.LapakBaeMotion?.resumeScroll();
    const callback = dialogCallback;
    dialogCallback = null;
    if (dialog.returnValue === 'confirm') callback?.();
  });

  const confirmAction = (title, message, onConfirm, confirmLabel = 'Konfirmasi', summaryItems = null) => {
    if (!dialog || typeof dialog.showModal !== 'function') {
      if (window.confirm(message)) onConfirm?.();
      return;
    }
    if (dialog.open) return;
    $('#dialog-title').textContent = title;
    const desc = $('#dialog-description');
    desc.replaceChildren();
    const intro = document.createElement('p');
    intro.textContent = message;
    desc.append(intro);
    if (Array.isArray(summaryItems) && summaryItems.length) {
      const list = document.createElement('dl');
      list.className = 'dialog-summary-list';
      summaryItems.forEach(([label, value]) => {
        const row = document.createElement('div');
        const dt = document.createElement('dt');
        const dd = document.createElement('dd');
        dt.textContent = label;
        dd.textContent = value;
        row.append(dt, dd);
        list.append(row);
      });
      desc.append(list);
    }
    $('#dialog-confirm').textContent = confirmLabel;
    dialog.returnValue = '';
    dialogCallback = onConfirm;
    window.LapakBaeMotion?.pauseScroll();
    dialog.showModal();
  };

  const paintRole = () => {
    $$('[data-guest-actions]').forEach(node => { node.hidden = role !== 'guest'; });
    $$('[data-account-actions]').forEach(node => { node.hidden = role === 'guest'; });
    $$('[data-account-link]').forEach(link => {
      link.href = `${base}/${role === 'guest' ? 'auth/login.php' : role + '/dashboard.php'}`;
    });
    $$('[data-account-initials]').forEach(node => {
      node.textContent = role === 'admin' ? 'AD' : role === 'pemilik_toko' ? 'RA' : 'NP';
    });
    $$('[data-booking-entry]').forEach(form => {
      form.action = role === 'pembeli' ? `${base}/pembeli/booking.php` : `${base}/auth/login.php`;
      let redirect = $('input[name=redirect]', form);
      if (!redirect) {
        redirect = document.createElement('input');
        redirect.type = 'hidden';
        redirect.name = 'redirect';
        form.append(redirect);
      }
      redirect.value = 'booking';
      redirect.disabled = role === 'pembeli';
      const button = $('button[type=submit]', form);
      if (button) {
        button.textContent = role === 'pembeli' ? 'Pesan Jasa' : (role === 'guest' ? 'Masuk untuk Pesan Jasa' : 'Masuk sebagai pembeli untuk memesan');
      }
    });
  };
  paintRole();

  $$('.logout-link, [data-logout]').forEach(link => {
    link.addEventListener('click', () => {
      role = 'guest';
      store('lapakbae-role', 'guest');
      paintRole();
    });
  });

  // Mobile header navigation toggle.
  const menuButton = $('.menu-toggle');
  const mobileNav = $('#mobile-nav');
  const closeMenu = (focus = false) => {
    if (mobileNav) mobileNav.hidden = true;
    menuButton?.setAttribute('aria-expanded', 'false');
    menuButton?.setAttribute('aria-label', 'Buka menu navigasi');
    if (focus) menuButton?.focus();
  };
  menuButton?.addEventListener('click', () => {
    const open = menuButton.getAttribute('aria-expanded') !== 'true';
    menuButton.setAttribute('aria-expanded', String(open));
    menuButton.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
    if (mobileNav) mobileNav.hidden = !open;
  });
  $$('a', mobileNav).forEach(link => link.addEventListener('click', () => closeMenu()));

  // Mobile dashboard sidebar toggle.
  const sidebarToggle = $('[data-sidebar-toggle]');
  const sidebarNav = $('[data-sidebar-nav]');
  const closeSidebar = (focus = false) => {
    sidebarNav?.classList.remove('is-open');
    sidebarToggle?.setAttribute('aria-expanded', 'false');
    if (focus) sidebarToggle?.focus();
  };
  sidebarToggle?.addEventListener('click', () => {
    const open = sidebarToggle.getAttribute('aria-expanded') !== 'true';
    sidebarToggle.setAttribute('aria-expanded', String(open));
    sidebarNav?.classList.toggle('is-open', open);
  });

  document.addEventListener('keydown', event => {
    if (event.key !== 'Escape') return;
    if (mobileNav && !mobileNav.hidden) closeMenu(true);
    if (sidebarNav?.classList.contains('is-open')) closeSidebar(true);
  });

  // Homepage live category filter tabs synced with featured service grid.
  const homeCatalog = $('[data-home-catalog]');
  if (homeCatalog) {
    const tabs = $$('[data-category-tab]', homeCatalog);
    const cards = $$('[data-home-card]', homeCatalog);
    const catalogLink = $('[data-home-catalog-link]', homeCatalog);
    const catalogLinkText = $('span', catalogLink);

    const activateCategory = (slug) => {
      tabs.forEach(tab => {
        const active = tab.dataset.categoryTab === slug;
        tab.classList.toggle('active', active);
        tab.setAttribute('aria-selected', String(active));
      });
      const visibleCards = [];
      let shown = 0;
      cards.forEach(card => {
        const match = slug === 'semua' || card.dataset.category === slug;
        const shouldShow = match && (slug !== 'semua' || shown < 8);
        card.hidden = !shouldShow;
        if (shouldShow) {
          shown++;
          visibleCards.push(card);
        }
      });
      if (catalogLink && catalogLinkText) {
        catalogLink.href = slug === 'semua' ? `${base}/layanan/index.php` : `${base}/layanan/index.php?kategori=${encodeURIComponent(slug)}`;
        catalogLinkText.textContent = slug === 'semua' ? `Lihat semua ${cards.length} jasa` : `Lihat semua di kategori ini`;
      }
      window.LapakBaeMotion?.animateGrid(visibleCards);
    };

    tabs.forEach(tab => {
      tab.addEventListener('click', () => activateCategory(tab.dataset.categoryTab || 'semua'));
    });
  }

  // Register role CTA label reflects selected account path.
  const registerForm = $('form[data-auth="register"]');
  if (registerForm) {
    const submitBtn = $('[data-register-submit]', registerForm);
    const syncRegisterLabel = () => {
      const checked = $('input[name=role]:checked', registerForm)?.value || 'pembeli';
      if (submitBtn) submitBtn.textContent = checked === 'pemilik_toko' ? 'Daftar sebagai Pemilik Usaha' : 'Daftar sebagai Pembeli';
    };
    registerForm.addEventListener('change', syncRegisterLabel);
    syncRegisterLabel();
  }

  $$('[data-password-toggle], .password-toggle').forEach(button => {
    button.addEventListener('click', () => {
      const input = document.getElementById(button.dataset.passwordToggle || '') || $('input', button.closest('.password-wrap'));
      if (!input) return;
      const visible = input.type === 'password';
      input.type = visible ? 'text' : 'password';
      button.textContent = visible ? 'Sembunyikan' : 'Lihat';
      button.setAttribute('aria-pressed', String(visible));
    });
  });

  // Form validation and accessible field errors.
  let errorSequence = 0;
  const getError = field => {
    const form = field.form;
    const row = field.closest('.week-row');
    const scope = row || form;
    let error = $$('[data-error-for]', scope).find(node => node.dataset.errorFor === field.name || node.dataset.errorFor === field.id);
    if (!error && !row) error = $$('[data-error-for]', form).find(node => node.dataset.errorFor === field.name.replace(/\[.*?\]/g, ''));
    if (!error) {
      error = document.createElement('p');
      error.className = 'field-error';
      error.dataset.errorFor = field.name;
      const anchor = field.type === 'radio' ? field.closest('fieldset') || field.closest('.field') || field : field.closest('.password-wrap') || field;
      anchor.insertAdjacentElement('afterend', error);
    }
    if (!error.id) error.id = `field-error-${++errorSequence}`;
    return error;
  };

  const setError = (field, message) => {
    const group = field.type === 'radio' ? $$('input[type=radio]', field.form).filter(input => input.name === field.name) : [field];
    const error = getError(field);
    error.textContent = message;
    group.forEach(input => {
      input.setAttribute('aria-invalid', String(Boolean(message)));
      const ids = new Set((input.getAttribute('aria-describedby') || '').split(/\s+/).filter(Boolean));
      ids.add(error.id);
      input.setAttribute('aria-describedby', [...ids].join(' '));
    });
  };

  const validatePhone = field => {
    if (field.type !== 'tel' || !field.value.trim()) return '';
    const phone = field.value.replace(/[\s().-]/g, '');
    return /^(?:\+62|62|0)\d{8,13}$/.test(phone) ? '' : 'Tulis nomor telepon Indonesia yang valid, contoh 081234567890.';
  };

  const fieldMessage = field => {
    if (field.disabled || !field.willValidate) return '';
    if (field.dataset.fileError) return field.dataset.fileError;
    if (field.validity.valueMissing) return field.type === 'radio' ? 'Pilih salah satu pilihan yang tersedia.' : 'Bagian ini wajib diisi.';
    if (field.validity.typeMismatch) return field.type === 'email' ? 'Tulis alamat email yang valid.' : 'Periksa kembali format isian.';
    if (field.minLength > 0 && field.value && field.value.length < field.minLength) return `Gunakan minimal ${field.minLength} karakter.`;
    if (field.validity.rangeUnderflow) return field.type === 'date' ? 'Pilih hari ini atau tanggal sesudahnya.' : 'Nilai lebih kecil dari batas minimum.';
    if (field.validity.rangeOverflow) return 'Nilai melebihi batas maksimum.';
    if (!field.validity.valid) return field.validationMessage;
    const weekRow = field.closest('.week-row');
    if (weekRow && field.name.startsWith('jam_selesai')) {
      const start = $('input[name^=jam_mulai]', weekRow);
      if (start?.value && field.value && field.value <= start.value) return 'Jam tutup harus lebih akhir dari jam buka.';
    }
    return validatePhone(field);
  };

  const validate = form => {
    let firstInvalid = null;
    const radioNames = new Set();
    $$('input, select, textarea', form).forEach(field => {
      if (!field.willValidate || field.disabled) return;
      if (field.type === 'radio') {
        if (radioNames.has(field.name)) return;
        radioNames.add(field.name);
      }
      const message = fieldMessage(field);
      setError(field, message);
      if (message && !firstInvalid) firstInvalid = field;
    });
    const picker = $('[data-slot-picker]', form);
    if (picker && !$('input[name=jam_mulai]:checked:not(:disabled)', picker)) {
      const radio = $('input[name=jam_mulai]', picker);
      if (radio) setError(radio, 'Pilih jam mulai yang tersedia untuk tanggal layanan.');
      if (!firstInvalid) {
        picker.tabIndex = -1;
        firstInvalid = picker;
      }
    }
    if (firstInvalid) firstInvalid.focus();
    return !firstInvalid;
  };

  const formFeedback = (form, message) => {
    let feedback = $('[data-form-feedback]', form);
    if (!feedback) {
      feedback = document.createElement('div');
      feedback.dataset.formFeedback = '';
      feedback.className = 'alert alert-success mb-3';
      feedback.setAttribute('role', 'status');
      feedback.tabIndex = -1;
      form.prepend(feedback);
    }
    feedback.textContent = message;
    feedback.hidden = false;
    feedback.focus();
  };

  $$('form[data-booking-entry]').forEach(form => {
    form.noValidate = true;
    form.addEventListener('submit', event => {
      if (!validate(form)) event.preventDefault();
    });
  });

  $$('.week-row').forEach(row => {
    const checkbox = $('input[type=checkbox]', row);
    const update = () => {
      $$('input[type=time]', row).forEach(field => {
        field.disabled = checkbox ? !checkbox.checked : false;
        field.required = !field.disabled;
        if (field.disabled && field.getAttribute('aria-invalid') === 'true') setError(field, '');
      });
    };
    checkbox?.addEventListener('change', update);
    update();
  });

  const params = new URLSearchParams(location.search);
  const requestedRole = params.get('role');
  if (['pembeli', 'pemilik_toko'].includes(requestedRole) && registerForm) {
    const choice = $$('input[name=role]', registerForm).find(input => input.value === requestedRole);
    if (choice) {
      choice.checked = true;
      registerForm.dispatchEvent(new Event('change'));
    }
  }

  $$('form[data-ui-form], form[data-auth], form[data-booking-form]').forEach(form => {
    form.noValidate = true;
    form.addEventListener('input', event => {
      const field = event.target;
      if (!field.matches('input, select, textarea')) return;
      if (field.getAttribute('aria-invalid') === 'true') setError(field, fieldMessage(field));
      const feedback = $('[data-form-feedback]', form);
      if (feedback) feedback.hidden = true;
    });

    form.addEventListener('submit', event => {
      event.preventDefault();
      if (!validate(form)) return;
      const data = new FormData(form);
      if (form.hasAttribute('data-auth')) {
        const selectedRole = data.get('role') || 'pembeli';
        role = knownRoles.includes(selectedRole) ? selectedRole : 'pembeli';
        store('lapakbae-role', role);
        const current = new URLSearchParams(location.search);
        const bookingParams = new URLSearchParams();
        ['service_id', 'tanggal_booking', 'jam_mulai'].forEach(name => {
          if (current.has(name)) bookingParams.set(name, current.get(name));
        });
        location.assign(role === 'pembeli' && current.get('redirect') === 'booking'
          ? `${base}/pembeli/booking.php?${bookingParams}`
          : `${base}/${role}/dashboard.php`);
      } else if (form.hasAttribute('data-booking-form')) {
        const dateValue = String(data.get('tanggal_booking') || '');
        const dateLabel = dateValue ? new Intl.DateTimeFormat('id-ID', { dateStyle: 'long' }).format(new Date(`${dateValue}T12:00:00`)) : '-';
        const startTime = String(data.get('jam_mulai') || '');
        const duration = Number(form.dataset.duration) || 120;
        let timeRange = startTime.replace(':', '.');
        if (startTime) {
          const [hours, mins] = startTime.split(':').map(Number);
          const endTotal = hours * 60 + mins + duration;
          const endLabel = `${String(Math.floor(endTotal / 60) % 24).padStart(2, '0')}.${String(endTotal % 60).padStart(2, '0')}`;
          timeRange = `${timeRange} - ${endLabel}`;
        }
        const summary = [
          ['Layanan', form.dataset.serviceName || 'Layanan dipilih'],
          ['Penyedia', form.dataset.storeName || '-'],
          ['Jadwal', `${dateLabel}, pukul ${timeRange}`],
          ['Pemesan', String(data.get('nama_pemesan') || '-')],
          ['Alamat', String(data.get('alamat_layanan') || '-')],
          ['Total tunai', form.dataset.priceLabel || '-']
        ];
        confirmAction(
          'Konfirmasi rincian pesanan',
          'Pastikan jadwal, alamat, dan harga sudah benar. Bayar tunai langsung ke penyedia setelah pekerjaan selesai.',
          () => {
            formFeedback(form, 'Rincian pesanan sudah diperiksa dan lengkap. Permintaan siap diteruskan ke penyedia untuk konfirmasi jadwal.');
            form.dispatchEvent(new CustomEvent('lapakbae:booking-checked', { bubbles: true, detail: { values: Object.fromEntries(data) } }));
          },
          'Konfirmasi Pesanan',
          summary
        );
      } else {
        formFeedback(form, form.dataset.message || 'Isian formulir sudah diperiksa dan semua kolom wajib valid.');
      }
    });
  });

  // Image file upload validation and object URL preview.
  $$('input[type=file][data-preview], input[type=file][data-upload-preview]').forEach(input => {
    const target = document.getElementById(input.dataset.preview || '') || $('[data-image-preview]', input.closest('.field') || input.closest('form') || document);
    if (!target) return;
    const initial = [...target.childNodes].map(node => node.cloneNode(true));
    const initialHasImage = target.classList.contains('has-image');
    let objectUrl = null;
    let removeButton = null;

    const clear = () => {
      if (objectUrl) URL.revokeObjectURL(objectUrl);
      objectUrl = null;
      input.value = '';
      delete input.dataset.fileError;
      target.replaceChildren(...initial.map(node => node.cloneNode(true)));
      target.classList.toggle('has-image', initialHasImage);
      removeButton?.remove();
      removeButton = null;
      setError(input, '');
    };

    input.addEventListener('change', () => {
      const file = input.files?.[0];
      delete input.dataset.fileError;
      if (!file) { clear(); return; }
      const message = !['image/jpeg', 'image/png', 'image/webp'].includes(file.type)
        ? 'Pilih gambar berformat JPG, PNG, atau WebP.'
        : file.size > 5 * 1024 * 1024
          ? 'Ukuran gambar maksimal 5 MB.'
          : '';
      if (message) {
        clear();
        input.dataset.fileError = message;
        setError(input, message);
        input.focus();
        return;
      }
      if (objectUrl) URL.revokeObjectURL(objectUrl);
      objectUrl = URL.createObjectURL(file);
      const image = document.createElement('img');
      image.src = objectUrl;
      image.alt = `Pratinjau ${file.name}`;
      image.width = 640;
      image.height = 420;
      target.replaceChildren(image);
      target.classList.add('has-image');
      setError(input, '');
      if (!removeButton) {
        removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn btn-outline-primary btn-sm mt-2';
        removeButton.textContent = 'Batalkan pilihan gambar';
        removeButton.addEventListener('click', clear);
        target.insertAdjacentElement('afterend', removeButton);
      }
    });
    window.addEventListener('pagehide', () => { if (objectUrl) URL.revokeObjectURL(objectUrl); }, { once: true });
  });

  // Booking status synchronization across cards, details, timelines, and reports.
  let bookingStatus = readStored('lapakbae-booking-status', {});
  if (!bookingStatus || typeof bookingStatus !== 'object' || Array.isArray(bookingStatus)) bookingStatus = {};
  let bookingProgress = readStored('lapakbae-booking-progress', {});
  if (!bookingProgress || typeof bookingProgress !== 'object' || Array.isArray(bookingProgress)) bookingProgress = {};
  const fixtureBookings = readData('bookings', []);

  const readBookingStatus = node => bookingStatus[node.dataset.bookingId] || node.dataset.status || $('[data-status-label], .status-badge[data-status]', node)?.dataset.status || 'menunggu_konfirmasi';
  const splitStatuses = value => String(value || '').split(/[\s,|]+/).filter(Boolean);

  const statusIcon = status => {
    const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    icon.classList.add('icon');
    icon.setAttribute('aria-hidden', 'true');
    icon.setAttribute('viewBox', '0 0 24 24');
    const use = document.createElementNS('http://www.w3.org/2000/svg', 'use');
    use.setAttribute('href', `#i-${{
      menunggu_konfirmasi: 'clock',
      dikonfirmasi: 'calendar',
      ditolak: 'close',
      sedang_dikerjakan: 'tool',
      selesai: 'check',
      dibatalkan: 'cancel'
    }[status] || 'clock'}`);
    icon.append(use);
    return icon;
  };

  const paintBooking = node => {
    const initialStatus = node.dataset.initialStatus || node.dataset.status || 'menunggu_konfirmasi';
    node.dataset.initialStatus = initialStatus;
    const status = readBookingStatus(node);
    if (!statusLabels[status]) return;
    node.dataset.status = status;

    $$('[data-status-label], .status-badge', node)
      .filter(badge => badge.closest('[data-booking-id]') === node)
      .forEach(badge => {
        badge.className = `status-badge status-${status}`;
        badge.replaceChildren(statusIcon(status), document.createTextNode(statusLabels[status]));
        badge.dataset.status = status;
      });

    $$('[data-status-action], [data-next-status]', node)
      .filter(button => button.closest('[data-booking-id]') === node)
      .forEach(button => {
        button.hidden = !(transitions[status] || []).includes(button.dataset.nextStatus);
        button.disabled = false;
      });

    $$('[data-allowed-status]', node)
      .filter(item => item.closest('[data-booking-id]') === node)
      .forEach(item => {
        item.hidden = !splitStatuses(item.dataset.allowedStatus).includes(status);
      });

    const nextStepText = $('[data-next-step-text]', node);
    if (nextStepText) {
      const detailRole = node.dataset.detailRole || role || 'pembeli';
      nextStepText.textContent = nextStepsByRole[detailRole]?.[status] || '';
    }

    const terminal = ['ditolak', 'dibatalkan'].includes(status);
    const stage = terminal
      ? Math.max(0, Number(bookingProgress[node.dataset.bookingId] ?? bookingFlow.indexOf(initialStatus)))
      : bookingFlow.indexOf(status);

    $$('[data-timeline-status]', node).forEach(item => {
      const index = bookingFlow.indexOf(item.dataset.timelineStatus);
      const done = index >= 0 && (index < stage || status === 'selesai' || terminal);
      const current = !terminal && status !== 'selesai' && index === stage;
      item.hidden = terminal && index > stage;
      item.classList.toggle('done', done);
      item.classList.toggle('current', current);
      if (current) item.setAttribute('aria-current', 'step');
      else item.removeAttribute('aria-current');
    });

    $$('[data-terminal-message]', node).forEach(message => {
      message.hidden = !terminal;
      message.textContent = status === 'ditolak'
        ? 'Permintaan ditolak. Kamu bisa memilih penyedia atau jadwal lain.'
        : status === 'dibatalkan'
          ? 'Booking dibatalkan. Jadwal ini tidak lagi aktif.'
          : '';
    });
  };

  const scopedBookings = node => {
    const scope = node.closest('[data-stat-scope]')?.dataset.statScope || 'admin';
    return (Array.isArray(fixtureBookings) ? fixtureBookings : []).filter(booking => (
      scope === 'owner' ? String(booking.store_id) === '1'
        : scope === 'buyer' ? String(booking.user_id) === '1'
          : true
    ));
  };

  const syncStats = () => {
    const countsFor = node => {
      const records = scopedBookings(node);
      const counts = Object.fromEntries(Object.keys(statusLabels).map(status => [status, 0]));
      records.forEach(booking => {
        const status = bookingStatus[String(booking.id)] || booking.status;
        if (Object.hasOwn(counts, status)) counts[status]++;
      });
      return {
        ...counts,
        total: records.length,
        pending: counts.menunggu_konfirmasi || 0,
        active: (counts.menunggu_konfirmasi || 0) + (counts.dikonfirmasi || 0) + (counts.sedang_dikerjakan || 0),
        finished: counts.selesai || 0,
        confirmed: counts.dikonfirmasi || 0,
        working: counts.sedang_dikerjakan || 0
      };
    };

    $$('[data-stat-booking]').forEach(node => {
      node.textContent = String(countsFor(node)[node.dataset.statBooking] || 0);
    });
    $$('[data-report-count]').forEach(node => {
      node.textContent = String(countsFor(node)[node.dataset.reportCount] || 0);
    });
    $$('[data-report-bar]').forEach(node => {
      const counts = countsFor(node);
      const count = counts[node.dataset.reportBar] || 0;
      node.style.width = `${counts.total ? (count / counts.total * 100).toFixed(2) : 0}%`;
    });
    $$('[data-booking-total]').forEach(node => {
      node.textContent = String(countsFor(node).total);
    });
  };

  const syncBookings = () => {
    $$('[data-booking-id]').forEach(paintBooking);
    slotControllers.forEach(controller => controller.refresh());
    syncStats();
  };
  syncBookings();

  // Admin and owner record activation / verification states.
  const recordLabels = {
    aktif: 'Aktif',
    active: 'Aktif',
    nonaktif: 'Nonaktif',
    inactive: 'Nonaktif',
    ditolak: 'Ditolak',
    rejected: 'Ditolak',
    menunggu: 'Menunggu verifikasi',
    menunggu_verifikasi: 'Menunggu verifikasi',
    pending: 'Menunggu verifikasi'
  };
  const visibilityLabels = { aktif: 'Ditampilkan', nonaktif: 'Disembunyikan' };
  const canonicalRecordStatus = status => ({ active: 'aktif', inactive: 'nonaktif', rejected: 'ditolak', pending: 'menunggu', menunggu_verifikasi: 'menunggu' }[status] || status);
  const recordTransitions = { menunggu: ['aktif', 'ditolak'], aktif: ['nonaktif'], nonaktif: ['aktif'], ditolak: ['menunggu'] };
  let recordStatus = readStored('lapakbae-record-status', {});
  if (!recordStatus || typeof recordStatus !== 'object' || Array.isArray(recordStatus)) recordStatus = {};

  const paintRecord = row => {
    const status = canonicalRecordStatus(recordStatus[row.dataset.recordId] || row.dataset.recordStatus);
    if (!recordLabels[status]) return;
    row.dataset.recordStatus = status;
    const badge = $('[data-record-status-label]', row);
    if (badge) {
      badge.textContent = row.dataset.labelMode === 'visibility' ? (visibilityLabels[status] || recordLabels[status]) : recordLabels[status];
      badge.className = `status-badge status-${status === 'aktif' ? 'selesai' : status === 'ditolak' ? 'ditolak' : status === 'menunggu' ? 'menunggu_konfirmasi' : 'dibatalkan'}`;
    }
    $$('[data-next-record-status]', row).forEach(button => {
      const next = canonicalRecordStatus(button.dataset.nextRecordStatus);
      const allowed = button.dataset.allowedRecordStatus?.split(/\s+/);
      button.hidden = !(recordTransitions[status] || []).includes(next) || (allowed && !allowed.includes(status));
    });
  };
  $$('[data-record-id][data-record-status]').forEach(paintRecord);

  const tableControllers = [];
  const refreshTables = () => tableControllers.forEach(controller => controller.refresh());

  $$('[data-confirm]').forEach(button => {
    button.addEventListener('click', () => {
      const booking = button.closest('[data-booking-id]');
      const row = button.closest('[data-record-id], tr, .booking-card, [data-booking-detail]');
      const next = button.dataset.nextStatus;
      if (next && booking && !(transitions[readBookingStatus(booking)] || []).includes(next)) {
        toast('Tindakan ini tidak tersedia untuk status booking saat ini.');
        return;
      }
      const nextRecord = canonicalRecordStatus(button.dataset.nextRecordStatus);
      const currentRecord = row ? canonicalRecordStatus(recordStatus[row.dataset.recordId] || row.dataset.recordStatus) : null;
      if (nextRecord && row && !(recordTransitions[currentRecord] || []).includes(nextRecord)) {
        toast('Tindakan ini tidak tersedia untuk status data saat ini.');
        return;
      }
      confirmAction(
        button.dataset.title || 'Konfirmasi tindakan',
        button.dataset.confirm || 'Lanjutkan tindakan ini?',
        () => {
          if (next && booking && statusLabels[next]) {
            const previous = readBookingStatus(booking);
            bookingProgress[booking.dataset.bookingId] = Math.max(Number(bookingProgress[booking.dataset.bookingId]) || 0, bookingFlow.indexOf(previous));
            store('lapakbae-booking-progress', bookingProgress);
            bookingStatus[booking.dataset.bookingId] = next;
            store('lapakbae-booking-status', bookingStatus);
            syncBookings();
            refreshTables();
            toast(`Status booking diperbarui menjadi ${statusLabels[next].toLowerCase()}.`);
          } else if (button.dataset.nextRecordStatus && row) {
            const target = canonicalRecordStatus(button.dataset.nextRecordStatus);
            if (!recordLabels[target]) return;
            row.dataset.recordStatus = target;
            if (row.dataset.recordId) {
              recordStatus[row.dataset.recordId] = target;
              store('lapakbae-record-status', recordStatus);
            }
            paintRecord(row);
            refreshTables();
            toast(`Status data diperbarui menjadi ${(row.dataset.labelMode === 'visibility' ? visibilityLabels[target] : recordLabels[target]).toLowerCase()}.`);
          }
        },
        button.dataset.confirmLabel || 'Konfirmasi'
      );
    });
  });

  // Query state, catalog list filtering, quick category tabs, and table pagination.
  const updateQuery = (entries, push = false) => {
    const url = new URL(location.href);
    Object.entries(entries).forEach(([name, value]) => {
      if (value === '' || value == null) url.searchParams.delete(name);
      else url.searchParams.set(name, String(value));
    });
    url.searchParams.delete('state');
    if (url.href !== location.href) history[push ? 'pushState' : 'replaceState'](null, '', url);
  };

  const buildPagination = (container, total, current, pageSize, onPage) => {
    if (!container) return;
    container.replaceChildren();
    const pages = Math.ceil(total / pageSize);
    if (pages <= 1) { container.hidden = true; return; }
    container.hidden = false;
    const list = document.createElement('ul');
    list.className = 'pagination';
    const add = (label, page, disabled, active = false) => {
      const item = document.createElement('li');
      item.className = `page-item${active ? ' active' : ''}${disabled ? ' disabled' : ''}`;
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'page-link';
      button.textContent = label;
      button.disabled = disabled || active;
      if (active) button.setAttribute('aria-current', 'page');
      button.addEventListener('click', () => onPage(page));
      item.append(button);
      list.append(item);
    };
    add('Sebelumnya', current - 1, current <= 1);
    for (let page = 1; page <= pages; page++) add(String(page), page, false, page === current);
    add('Berikutnya', current + 1, current >= pages);
    container.append(list);
  };

  const normalize = value => String(value || '').toLocaleLowerCase('id').trim();
  const stateScope = node => node.closest('[data-state-scope], [data-list-scope]') || node.closest('section') || $('main') || document;
  const stateHidden = new Map();

  const applyState = (scope, forced = new URLSearchParams(location.search).get('state')) => {
    for (const [node, wasHidden] of stateHidden) {
      if (scope === node || scope.contains(node)) {
        node.hidden = wasHidden;
        stateHidden.delete(node);
      }
    }
    const selector = { empty: '[data-empty], [data-catalog-empty]', error: '[data-error-state]', loading: '[data-loading-state]' }[forced];
    if (!selector) {
      $$('[data-state-content]', scope).forEach(node => { node.hidden = false; });
      $$('[data-error-state], [data-loading-state]', scope).forEach(node => { node.hidden = true; });
      return;
    }
    const target = $(selector, scope);
    if (!target) return;
    const contents = $$('[data-state-content]', scope);
    const hide = node => {
      if (!stateHidden.has(node)) stateHidden.set(node, node.hidden);
      node.hidden = true;
    };
    (contents.length ? contents : $$('[data-catalog], .table-panel, [data-list]', scope)).forEach(hide);
    $$('[data-pagination], [data-table-pagination]', scope).forEach(hide);
    $$('[data-empty], [data-catalog-empty], [data-error-state], [data-loading-state]', scope).forEach(node => { node.hidden = true; });
    target.hidden = false;
  };

  const listControllers = [];
  const claimedLists = new Set();

  const setupList = (scope, items, form = null, explicitList = null) => {
    if (!items.length && !explicitList) return;
    const host = explicitList || items[0]?.parentElement;
    if (!host || claimedLists.has(host)) return;
    claimedLists.add(host);

    const pageSize = Math.max(1, Number(host.dataset.pageSize || scope.dataset.pageSize) || (items[0]?.classList.contains('booking-card') ? 10 : 12));
    const pageKey = host.dataset.pageParam || 'page';
    const findField = names => names.map(name => form?.elements.namedItem(name)).find(Boolean);
    const fields = {
      q: findField(['q', 'search', 'pencarian']),
      category: findField(['category', 'kategori', 'category_id']),
      location: findField(['location', 'lokasi', 'area_layanan']),
      sort: findField(['sort', 'urutan']),
      status: findField(['status']) || $('[data-filter-status]', scope)
    };
    const pagination = $('[data-pagination]', scope);
    const count = $('[data-results-count], [data-result-count]', scope);
    const empty = $('[data-empty], [data-catalog-empty]', scope);
    const noun = host.dataset.itemLabel || (items[0]?.classList.contains('store-card') ? 'usaha' : items[0]?.classList.contains('booking-card') ? 'booking' : 'jasa');
    const quickTabs = $$('[data-filter-tab]', scope);
    let current = 1;

    const syncQuickTabs = () => {
      const activeCat = normalize(fields.category?.value);
      quickTabs.forEach(tab => {
        const selected = normalize(tab.dataset.filterTab) === activeCat;
        tab.classList.toggle('active', selected);
        tab.setAttribute('aria-selected', String(selected));
      });
    };

    const restoreFields = () => {
      const query = new URLSearchParams(location.search);
      Object.entries(fields).forEach(([key, field]) => {
        if (field) field.value = query.get(field.name || key) ?? query.get(key) ?? (key === 'sort' ? field.options?.[0]?.value || '' : '');
      });
      current = Math.max(1, Number(query.get(pageKey)) || 1);
      syncQuickTabs();
    };

    const render = (requested = current, update = false, push = false) => {
      const q = normalize(fields.q?.value);
      const category = normalize(fields.category?.value);
      const locationVal = normalize(fields.location?.value);
      const status = fields.status?.value || '';
      const sort = fields.sort?.value || '';

      const matches = items.filter(item => (
        item.dataset.removed !== 'true'
        && (!q || normalize(item.dataset.name || item.textContent).includes(q))
        && (!category || category === 'semua' || normalize(item.dataset.category) === category)
        && (!locationVal || locationVal === 'semua' || normalize(item.dataset.location).includes(locationVal))
        && (!status || item.dataset.status === status)
      ));

      if (['harga_terendah', 'price_asc'].includes(sort)) matches.sort((a, b) => Number(a.dataset.price) - Number(b.dataset.price));
      if (['harga_tertinggi', 'price_desc'].includes(sort)) matches.sort((a, b) => Number(b.dataset.price) - Number(a.dataset.price));
      if (['terbaru', 'newest'].includes(sort)) matches.sort((a, b) => Number(b.dataset.id || b.dataset.bookingId) - Number(a.dataset.id || a.dataset.bookingId));

      current = Math.max(1, Math.min(Number(requested) || 1, Math.ceil(matches.length / pageSize) || 1));
      applyState(scope, null);
      host.hidden = false;
      items.forEach(item => { item.hidden = true; });
      const startIndex = (current - 1) * pageSize;
      const endIndex = Math.min(startIndex + pageSize, matches.length);
      matches.forEach((item, index) => {
        host.append(item);
        item.hidden = index < startIndex || index >= startIndex + pageSize;
      });
      if (count) {
        count.textContent = matches.length
          ? `Menampilkan ${startIndex + 1}-${endIndex} dari ${matches.length} ${noun}`
          : `0 ${noun} ditemukan`;
      }
      if (empty) empty.hidden = matches.length > 0;
      buildPagination(pagination, matches.length, current, pageSize, page => {
        render(page, true, true);
        window.LapakBaeMotion ? window.LapakBaeMotion.scrollTo(host) : host.scrollIntoView({ behavior: 'auto', block: 'start' });
      });
      if (update) {
        const query = { [pageKey]: current > 1 ? current : '' };
        Object.entries(fields).forEach(([key, field]) => { if (field) query[field.name || key] = field.value; });
        updateQuery(query, push);
      }
      syncQuickTabs();
      applyState(scope);
    };

    quickTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        if (fields.category) fields.category.value = tab.dataset.filterTab || '';
        render(1, true, true);
      });
    });

    form?.addEventListener('submit', event => { event.preventDefault(); render(1, true, true); });
    form?.addEventListener('change', () => render(1, true, true));
    form?.addEventListener('reset', () => setTimeout(() => render(1, true, true), 0));
    if (fields.status && !form?.contains(fields.status)) fields.status.addEventListener('change', () => render(1, true, true));

    let timer;
    fields.q?.addEventListener('input', () => {
      clearTimeout(timer);
      timer = setTimeout(() => render(1, true), 180);
    });

    restoreFields();
    render();
    listControllers.push({ restore: () => { restoreFields(); render(); }, refresh: () => render() });
  };

  $$('form[data-filter]').forEach(form => {
    const scope = form.closest('[data-list-scope], [data-state-scope]') || form.closest('section') || form.parentElement;
    const explicit = $('[data-list], [data-catalog]', scope);
    const items = explicit ? $$('[data-item], .service-card[data-name], .store-card[data-name], .booking-card', explicit) : $$('.service-card[data-name], .store-card[data-name], .booking-card', scope);
    setupList(scope, items, form, explicit);
  });

  $$('[data-list]').forEach(host => {
    const scope = stateScope(host);
    setupList(scope, $$('[data-item]', host).length ? $$('[data-item]', host) : $$('.service-card, .store-card, .booking-card', host), null, host);
  });

  $$('.table-panel').forEach((panel, index) => {
    const search = $('[data-table-search]', panel);
    const pagination = $('[data-table-pagination]', panel);
    if (!search && !pagination) return;
    const scope = panel.closest('[data-state-scope]') || panel.parentElement;
    const pageKey = panel.dataset.pageParam || (index === 0 ? 'page' : `table_${index + 1}_page`);
    const searchKey = panel.dataset.searchParam || (index === 0 ? 'q' : `table_${index + 1}_q`);
    let current = 1;
    let empty = $('[data-table-empty]', panel);
    if (!empty) {
      empty = document.createElement('p');
      empty.dataset.tableEmpty = '';
      empty.className = 'data-list-empty';
      empty.textContent = 'Tidak ada data yang cocok dengan kata pencarian tersebut.';
      const table = $('.table-responsive', panel);
      (table || panel).insertAdjacentElement(table ? 'afterend' : 'beforeend', empty);
    }

    const render = (page = current, update = false, push = false) => {
      applyState(scope, null);
      const rows = $$('tbody tr', panel);
      const q = normalize(search?.value);
      const matches = rows.filter(row => (
        row.dataset.removed !== 'true'
        && (!q || normalize(row.textContent).includes(q))
        && (panel.dataset.history == null || (panel.dataset.history === 'true'
          ? terminalStatuses.includes(row.dataset.status)
          : !terminalStatuses.includes(row.dataset.status)))
      ));
      current = Math.max(1, Math.min(Number(page) || 1, Math.ceil(matches.length / 10) || 1));
      rows.forEach(row => { row.hidden = true; });
      const start = (current - 1) * 10;
      const end = Math.min(start + 10, matches.length);
      matches.slice(start, end).forEach(row => { row.hidden = false; });
      empty.hidden = matches.length > 0;
      const count = $('[data-table-count]', panel);
      if (count) count.textContent = matches.length ? `Menampilkan ${start + 1}-${end} dari ${matches.length} data` : '0 data';
      buildPagination(pagination, matches.length, current, 10, pageNumber => render(pageNumber, true, true));
      if (update) updateQuery({ [pageKey]: current > 1 ? current : '', [searchKey]: search?.value || '' }, push);
      applyState(scope);
    };

    const restore = () => {
      const query = new URLSearchParams(location.search);
      if (search) search.value = query.get(searchKey) || '';
      render(Number(query.get(pageKey)) || 1);
    };

    search?.addEventListener('input', () => render(1, true));
    tableControllers.push({ refresh: () => render(), restore });
    restore();
  });

  window.addEventListener('popstate', () => {
    listControllers.forEach(controller => controller.restore());
    tableControllers.forEach(controller => controller.restore());
    applyState($('main') || document);
  });
  applyState($('main') || document);

  // Interactive calendar date chips + time slot availability checker.
  const localToday = new Date();
  localToday.setMinutes(localToday.getMinutes() - localToday.getTimezoneOffset());
  const today = localToday.toISOString().slice(0, 10);

  const buildBookableDates = count => {
    const dates = [];
    const cursor = new Date(`${today}T12:00:00`);
    while (dates.length < count) {
      if (cursor.getDay() !== 0) {
        const iso = cursor.toISOString().slice(0, 10);
        const dayShort = new Intl.DateTimeFormat('id-ID', { weekday: 'short' }).format(cursor);
        const dateShort = new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short' }).format(cursor);
        dates.push({ iso, dayShort, dateShort });
      }
      cursor.setDate(cursor.getDate() + 1);
    }
    return dates;
  };

  $$('input[name=tanggal_booking]').forEach(field => {
    field.min = today;
    const initialDate = params.get('tanggal_booking');
    if (initialDate && /^\d{4}-\d{2}-\d{2}$/.test(initialDate) && initialDate >= today) field.value = initialDate;
  });

  $$('[data-booking-form], [data-slot-form], [data-booking-entry]').forEach(form => {
    const picker = $('[data-slot-picker]', form);
    const radios = $$('input[name=jam_mulai]', picker || form);
    const dateField = $('input[name=tanggal_booking]', form);
    const quickDateHost = $('[data-quick-dates]', form);
    const serviceId = $('input[name=service_id]', form)?.value;
    const servicesList = readData('services', []);
    const service = Array.isArray(servicesList) ? servicesList.find(item => String(item.id) === serviceId) : null;
    const storeId = form.dataset.storeId || service?.store_id || '1';
    let initialSlotApplied = false;

    const syncQuickDateChips = () => {
      if (!quickDateHost || !dateField) return;
      $$('.date-chip', quickDateHost).forEach(chip => {
        const selected = chip.dataset.dateIso === dateField.value;
        chip.classList.toggle('is-selected', selected);
        chip.setAttribute('aria-pressed', String(selected));
      });
    };

    if (quickDateHost && dateField) {
      const quickDates = buildBookableDates(6);
      quickDateHost.replaceChildren();
      quickDates.forEach(({ iso, dayShort, dateShort }) => {
        const chip = document.createElement('button');
        chip.type = 'button';
        chip.className = 'date-chip';
        chip.dataset.dateIso = iso;
        chip.setAttribute('aria-pressed', 'false');
        const small = document.createElement('small');
        small.textContent = dayShort;
        const strong = document.createElement('strong');
        strong.textContent = dateShort;
        chip.append(small, strong);
        chip.addEventListener('click', () => {
          dateField.value = iso;
          dateField.dispatchEvent(new Event('change', { bubbles: true }));
          if (dateField.getAttribute('aria-invalid') === 'true') setError(dateField, '');
        });
        quickDateHost.append(chip);
      });
    }

    const minutes = time => {
      const parts = String(time).split(':').map(Number);
      return parts[0] * 60 + parts[1];
    };

    const refreshSlots = () => {
      if (!picker) return;
      const date = dateField?.value || '';
      picker.dataset.awaitingDate = String(!date);
      const sunday = date ? new Date(`${date}T12:00:00`).getDay() === 0 : false;
      const duration = Number(form.dataset.duration || service?.durasi_menit) || 120;
      let available = 0;

      radios.forEach(radio => {
        const start = minutes(radio.value);
        const end = start + duration;
        const conflicts = Array.isArray(fixtureBookings) && fixtureBookings.some(booking => {
          const status = bookingStatus[String(booking.id)] || booking.status;
          if (!['menunggu_konfirmasi', 'dikonfirmasi', 'sedang_dikerjakan'].includes(status) || String(booking.store_id) !== String(storeId) || booking.tanggal_booking !== date) return false;
          const bookedStart = minutes(booking.jam_mulai);
          const bookedEnd = minutes(booking.jam_selesai);
          return start < bookedEnd && end > bookedStart;
        });
        radio.disabled = !date || date < today || sunday || start < 8 * 60 || end > 17 * 60 || conflicts;
        if (radio.disabled) radio.checked = false;
        else available++;
      });

      if (!initialSlotApplied && date) {
        const requested = params.get('jam_mulai');
        const matching = radios.find(radio => !radio.disabled && radio.value === requested);
        if (matching) matching.checked = true;
        initialSlotApplied = true;
      }

      const message = $('[data-slot-message]', form);
      if (message) {
        message.setAttribute('role', 'status');
        message.setAttribute('aria-live', 'polite');
        message.textContent = !date
          ? 'Pilih tanggal di atas untuk menampilkan jam mulai yang tersedia.'
          : date < today
            ? 'Pilih hari ini atau tanggal sesudahnya.'
            : sunday
              ? 'Hari Minggu usaha tutup. Pilih tanggal Senin sampai Sabtu.'
              : available
                ? `${available} jam mulai tersedia. Estimasi selesai mengikuti durasi ${duration} menit.`
                : 'Semua slot pada tanggal ini sudah terisi. Pilih tanggal lain.';
      }
      syncQuickDateChips();
    };

    const updateSummary = () => {
      const date = dateField?.value;
      const time = $('input[name=jam_mulai]:checked', form)?.value || $('select[name=jam_mulai]', form)?.value;
      const duration = Number(form.dataset.duration) || 120;
      const set = (selector, text) => $$(selector).forEach(node => { node.textContent = text; });
      set('#summary-date, [data-summary-date]', date ? new Intl.DateTimeFormat('id-ID', { dateStyle: 'long' }).format(new Date(`${date}T12:00:00`)) : 'Belum dipilih');
      set('#summary-time, [data-summary-time]', time ? time.replace(':', '.') : 'Belum dipilih');
      if (time) {
        const [hours, mins] = time.split(':').map(Number);
        const end = hours * 60 + mins + duration;
        set('#summary-end, [data-summary-end]', `${String(Math.floor(end / 60) % 24).padStart(2, '0')}.${String(end % 60).padStart(2, '0')}`);
      } else {
        set('#summary-end, [data-summary-end]', 'Belum dipilih');
      }
    };

    const refresh = () => {
      refreshSlots();
      updateSummary();
    };

    form.addEventListener('change', refresh);
    if (picker) slotControllers.push({ refresh });
    refresh();
  });

  $$('[data-print]').forEach(button => button.addEventListener('click', () => window.print()));

  window.LapakBae = {
    toast,
    confirmAction,
    validate,
    refreshTables,
    refreshLists: () => listControllers.forEach(controller => controller.refresh()),
    syncBookings,
    getRole: () => role,
    readData
  };
})();
