# CHANGELOG

Seluruh perubahan desain, antarmuka, kode, dan dokumentasi LapakBae dicatat secara kronologis di file ini.

## [2.2.0] - 2026-11-20

### Intro Beranda: Auto-Play Kain Lap Kotak-Kotak Merah-Krem (2 Detik)
- Mengubah overlay pembuka di beranda (`app/views/home/index.php`) dari usapan manual ("Usap layar untuk membuka" + ikon tangan) menjadi **animasi otomatis (auto-play) 2 detik berbasis GSAP**.
- Menambahkan elemen **kain lap kotak-kotak merah-krem** (`[data-splash-wipe-cloth]`) berbahan SVG kustom bertesktil plaid (`#FBF6EB` / `#C94734`) dengan kontur kain melengkung, sudut lipatan, dan jahitan tepi putus-putus (`#8F281E`).
- Mengganti tekstur latar belakang kanvas menjadi **efek embun dan kabut** (`buildMistTextureCanvas` di `public/assets/js/animation.js`): gradasi sage-putih semi-buram, gumpalan uap radial, butiran kabut mikroskopis, titik kondensasi embun, dan jejak tetesan halus pada kanvas offscreen.
- Menggerakkan kain lap secara zig-zag 4 lintasan selama **2.00 detik** (`gsap.timeline`) sambil menghapus kabut mengikuti jalur kain lap memakai brush stamp lembut `destination-out` (`buildBrushStamp`), lalu memudarkan kain dan overlay (`450ms` fade-out) masuk ke kartu tengah `lapakbae.` dan 6 kartu kategori `#splash-intro` (animasi settle halus).
- Memperbarui tombol pintasan menjadi tombol kecil **`Lewati`** di pojok kanan atas (`.splash-wipe-skip`, tinggi `44px`, `#2D4F1E` di atas putih = `9.34:1` dan di atas `#D9E2CE` = `6.99:1`) serta memangkas timeout failsafe inline menjadi `4500ms`.
- Menambahkan `refreshTriggersPreservingFocus()` di `public/assets/js/animation.js` agar `ScrollTrigger.refresh()` pada `#splash-intro` yang di-pin tidak melepas fokus tombol `.splash-skip` saat halaman masih memuat font, dan mencegah kotak fokus nyasar pada `.splash-skip` ketika auto-play selesai tanpa input keyboard.

### Pemeriksaan (`qa_autoplay_wipe.py`)
- **8 viewport** (`1440x900`, `1280x800`, `1024x768`, `768x1024`, `390x844`, `360x800`, `320x700`, dan `844x390`): overflow horizontal `0`, tombol `Lewati` di pojok kanan atas (`73x44` desktop/tablet/lanskap, `68x44` mobile), 0 error konsol dan 0 error jaringan.
- **Auto-play desktop (`1440x900`) & seluler (`390x844`):** sapuan berjalan otomatis tanpa interaksi manual (`clearedRatio` naik `0.304` -> `0.633` di desktop, `0.463` di seluler), splash terlihat tajam (`centerOpacity='1'`), scroll terkunci (`scrollY=0`) hingga fade-out otomatis selesai lalu membuka scroll (desktop pin `top=76`, `hasPinSpacer=true` hingga `heroY=1269`; seluler `scrollY=450`).
- **Aksesibilitas & fallback:** Tab trap, `Escape` (fokus ke `.splash-skip`), klik `Lewati`, `prefers-reduced-motion`, `<noscript>`, muat hash `#beranda-utama`, tanpa GSAP, tanpa Lenis (`scrollY=600`), dan blokir `animation.js` lolos seluruhnya.

## [2.1.0] - 2026-11-20

### Intro Beranda: Splash Layanan & Usap Layar
- Menambahkan **splash pengantar** `#splash-intro` di beranda: logo `lapakbae.`, orbit dekoratif, dan 6 node kategori layanan beserta nama usaha unggulan dari layanan aktif pertama per `category_slug` (`app/views/home/index.php`).
- Menambahkan **wipe layar penuh** di atas splash (`[data-splash-wipe]`): kanvas fixed menutupi viewport dengan tekstur kaca berdebu (gradasi sage `#E9EBDD` ke `#D6DCCB`, noda radial, debu, streak, dan sidik jari) yang dibuat sekali pada kanvas offscreen (`buildTextureCanvas`). Pointer atau sentuhan menghapus lapisan dengan brush stamp lembut (`buildBrushStamp`, `destination-out`); logo pusat dan node layanan tetap terlihat penuh di balik sapuan sejak awal.
- **Progres, sinkronisasi & pembukaan:** progres dihitung dari sampler `64x64`; pada `>=55%` bersih, sisa lapisan memudar otomatis, overlay disembunyikan, `ScrollTrigger.refresh()` dijalankan, dan splash memainkan transisi settle ringan. Selama wipe, scroll dikunci (`html.is-splash-wipe-locked` + `Lenis.stop()`) dengan kompensasi lebar scrollbar.
- **Aksesibilitas & kontras:** overlay memakai `role="dialog"` + `aria-modal`, fokus awal masuk ke dialog, kurungan Tab pada tombol skip berhenti seketika saat `.is-done` aktif, dan fokus dikembalikan ke `.splash-skip` setelah overlay tertutup. Keterangan `.splash-wipe-sub` memakai Charcoal Gelap `#3F4752` di atas `#D6DCCB` (**6.69:1**, PASS WCAG AAA). Tersedia tombol `Buka tanpa mengusap`, pintasan `Escape`, serta status `aria-live` pada 22%/42%/selesai.
- **GSAP ScrollTrigger** (`public/assets/js/animation.js`):
  - Desktop `>=901px`: splash dipin (`top=76`) selama `+=80%`; orbit berputar, node menyebar lalu konvergen ke pusat.
  - Seluler `<=900px`: splash tidak dipin; node bergerak dan memudar saat section keluar layar.
- **Responsif & fallback:**
  - Prompt wipe muat sampai `320px` termasuk lanskap pendek (`844x390`); kanvas mengikuti viewport dan DPR (maks. 2x); ukuran brush menyesuaikan layar.
  - `prefers-reduced-motion`, tanpa JavaScript (`<noscript>`), muat dengan hash `#beranda-utama`, atau tanpa GSAP menampilkan splash langsung tanpa mengunci scroll; tanpa Lenis wipe tetap berfungsi dan halaman tetap dapat digulir.
  - Failsafe inline 6 detik melepas overlay bila `animation.js` gagal dimuat.

### Aset & Identitas
- Menambahkan favicon merek `public/assets/images/favicon.svg` dan memasangnya pada `app/views/layouts/header.php`, menghilangkan 404 favicon.

### Pemeriksaan
- QA Playwright (Chrome headless) pada `1440x900`, `1280x800`, `1024x768`, `768x1024`, `390x844`, `360x800`, `320x700`, dan `844x390`: overflow horizontal `0` di semua ukuran, 0 error konsol/jaringan, tombol skip `>=44px` berada di dalam viewport.
- Simulasi mouse drag di `1440x900` dan touch drag di `390x844`: usapan sebagian (`<55%`) memperlihatkan splash di balik sapuan dan tetap mengunci scroll (`scrollY=0`); usapan penuh (`>=55%`) memudarkan overlay, melepas lock scroll, mengaktifkan pin desktop (`top=76`, `hasPinSpacer=true`), dan menggulir normal hingga `#beranda-utama` (`scrollY=1269`) maupun seluler (`scrollY=450`).
- Uji fallback skip, `Escape`, Tab trap, reduced motion, tanpa JavaScript, tanpa GSAP, tanpa Lenis (`scrollY=600`), hash `#beranda-utama`, dan skrip inline failsafe lolos seluruhnya.

## [2.0.0] - 2026-11-20

### Sistem Desain & Komposisi Warna 60-30-10
- Menerapkan aturan proporsi warna **60-30-10** di seluruh 33+ view melalui `public/assets/css/style.css`:
  - **60% Dominan:** Soft Sage Mist (`#F4F6F0`) sebagai latar halaman (`body`) dan Pure White (`#FFFFFF`) untuk kartu jasa, panel, modal booking, serta tabel dashboard.
  - **30% Sekunder:** Forest Sage (`#3A5A40`) dan Deep Moss (`#2D4F1E`) pada Navbar, Sidebar akun (`admin`, `pemilik_toko`, `pembeli`), Footer, tab kategori aktif, dan judul utama (`h1`, `h2`).
  - **10% Aksen:** Warm Terracotta (`#E07A5F`) dan Deep Clay (`#D06549`) khusus tombol CTA utama ("Pesan Jasa", "Konfirmasi Pesanan", "Cari Jasa", "Kirim Ulasan", "Verifikasi Toko") serta tanggal dan slot terpilih pada kalender booking, dipasangkan dengan teks Charcoal gelap (`#111827`) agar lolos rasio kontras WCAG AA (`6.01:1` dan `4.76:1`).
  - **Netral & Kontras Kontrol:** Charcoal (`#1F2937`), Muted Gray (`#6B7280` di atas putih, `#5B6370` di atas `#F4F6F0`), Soft Border (`#D1D5DB`), dan batas kontrol input `#8B939E` (`3.10:1` lolos WCAG 1.4.11).
- Mengganti pasangan font lama (`Plus Jakarta Sans` + `DM Sans`) ke pasangan hasil deal `/design-taste-frontend`: **`Newsreader`** (editorial display) dan **`Hanken Grotesk`** (UI, form, tabel).

### Smooth Scroll (Lenis), Animasi GSAP & Ilustrasi Editorial
- Menambahkan **Lenis** (`lenis@1.1.18`) pada `app/views/layouts/footer.php` dan menyinkronkannya dengan `gsap.ticker` serta `ScrollTrigger.update` pada `public/assets/js/animation.js` (nonaktif otomatis saat `prefers-reduced-motion: reduce`, jeda otomatis saat dialog terbuka).
- Membangun seksi interaktif **`#cara-kerja` (`pinned-chapters`)** dengan GSAP ScrollTrigger dan tab keyboard/klik untuk 3 tahap alur booking.
- Menambahkan **5 ilustrasi editorial khusus** bergaya gouache/risograph sesuai palet 60-30-10 di `public/assets/images/`:
  - `illus-hero-marketplace.jpg` (Hero beranda)
  - `illus-step-schedule.jpg` (Tahap 1: Pilih jadwal)
  - `illus-step-confirm.jpg` (Tahap 2: Konfirmasi penyedia)
  - `illus-step-done.jpg` (Tahap 3: Selesai & bayar tunai, serta halaman Tentang)
  - `illus-owner-partner.jpg` (Banner pendaftaran pemilik usaha)

### Pembaruan Halaman & Perbaikan Temuan Audit (`anti-slop`)
- **Shell Bersama (`header.php`, `navbar.php`, `footer.php`, `sidebar.php`, `states.php`):**
  - Menambahkan ikon SVG literal khusus per menu (`grid`, `inbox`, `briefcase`, `calendar`, `history`, `star`, `store`, `users`, `tag`, `chart`, `logout`) menggantikan ikon toko yang sebelumnya dipakai berulang.
  - Memperbaiki sidebar seluler menjadi tombol toggle berlabel halaman aktif dengan dukungan tombol `Escape`.
  - Memberi label teks "Menu" pada tombol menu seluler dan menambahkan indikator progres scroll di tepi atas navbar.
  - Memisahkan tautan pratinjau peran pada footer dengan label eksplisit `Pratinjau peran:`.
  - Menyediakan skeleton baris tabel khusus (`skeleton-table`) pada halaman tabel selain skeleton kartu katalog.
- **Publik (`home/index.php`, `home/tentang.php`, `layanan/*`, `toko/*`, `auth/*`):**
  - Menghapus stiker miring, kartu overlay promosi, dan klaim wilayah tetap pada hero beranda.
  - Menambahkan tab kategori interaktif langsung di beranda dan katalog yang menyaring kartu dengan animasi GSAP.
  - Menghapus tombol hati favorit di luar ruang lingkup pada `partials/service-card.php`, menampilkan durasi menit secara jelas, mengganti teks harga "Mulai" dengan harga satuan eksplisit, dan menambahkan CTA langsung `Pesan Jasa`.
  - Menambahkan pilihan tanggal cepat kalender (`calendar-quick-dates`) yang menyorot tanggal terpilih dengan Warm Terracotta dan menunda tampilan slot dicoret sampai tanggal dipilih (`partials/slots.php`).
  - Memindahkan urutan panel jadwal ke posisi atas pada layar seluler di `layanan/detail.php`.
  - Menambahkan nama pemilik, alamat, telepon, jam operasional, dan jumlah layanan aktif pada `toko/index.php` dan `toko/detail.php`.
  - Memperbaiki halaman `auth/login.php` dan `auth/register.php` menjadi kartu terpusat dengan inset seluler `20px`, judul literal, dan CTA pendaftaran dinamis sesuai pilihan akun.
- **Pembeli (`pembeli/*`, `partials/booking-detail.php`, `partials/booking-card.php`):**
  - Memprioritaskan booking aktif pada dashboard pembeli dan menambahkan tautan `Kirim Ulasan` langsung pada kartu booking selesai.
  - Menampilkan ringkasan layanan dan harga sebelum formulir di layar seluler pada `pembeli/booking.php`, serta menampilkan total harga di samping CTA `Konfirmasi Pesanan`.
  - Melengkapi modal konfirmasi booking dengan rincian jasa, penyedia, jadwal, estimasi jam selesai, pemesan, alamat, dan total tunai.
  - Menaikkan panel `Form Ulasan` ke bagian atas pada detail booking selesai dengan 5 pilihan nilai dalam satu baris dan CTA `Kirim Ulasan`.
  - Menambahkan kotak `Tindakan berikutnya` spesifik per peran dan status.
- **Pemilik Toko (`pemilik_toko/*`):**
  - Menambahkan banner prioritas jumlah booking menunggu keputusan di dashboard pemilik dan memperbaiki label statistik menjadi `Perlu keputusanmu sekarang`.
  - Mengurutkan booking `menunggu_konfirmasi` ke baris paling atas tabel dan memprioritaskan kolom jadwal/status/tindakan.
  - Menggabungkan editor jadwal 7 hari menjadi satu formulir mingguan dengan satu CTA (`Periksa jadwal mingguan`) dan tautan cepat ke bagian `Tanggal libur`.
  - Mengelompokkan formulir tambah/edit layanan ke dalam 3 fieldset dan menambahkan contoh satuan permanen.
- **Admin (`admin/*`):**
  - Memprioritaskan antrean toko menunggu verifikasi di `admin/dashboard.php` dan mengurutkan toko berstatus `menunggu` ke baris pertama pada `admin/toko.php` dengan modal konfirmasi yang menyebut nama usaha.
  - Memperjelas label kolom `Peran` pada `admin/pengguna.php`, status toko pada `admin/layanan.php`, status `Ditampilkan/Disembunyikan` pada `admin/ulasan.php`, contoh format slug pada `admin/edit.php`, serta cakupan data laporan (`Seluruh data booking tercatat`) pada `admin/laporan.php` termasuk mode cetak.

### Kebersihan Kode, Copywriting & Audit
- Memasang 6 skill `miqdadbadjuber/anti-slop` secara global di `~/.gemini/config/skills/` (`antislop`, `antislop-code`, `antislop-copywriting`, `antislop-human`, `antislop-layoutmobile`, `antislop-ui`).
- Membersihkan seluruh kode PHP, CSS, dan JS dari komentar generik/dekoratif (`antislop-code`), buzzword AI, dan karakter em-dash (U+2014 = 0 kemunculan).
- Memperbarui `DESIGN.md`, `PRODUCT.md`, dan menambahkan laporan audit tahap 2 di `anti-slop/audit-002-2026-11-20.md`.
