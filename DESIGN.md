# DESIGN.md - LapakBae

## Identitas & Arah Desain
LapakBae adalah marketplace jasa UMKM lokal Indonesia dengan pendekatan editorial craft yang terinspirasi dari struktur tipografi dan ritme scroll `lenis.dev` serta standar `/design-taste-frontend` dan filter `anti-slop`.

- **Dial:** `ENERGY 3 / RHYTHM 3 / MOTION 3`
- **Tema:** Terang (siang hari, nyaman dibaca di layar HP maupun desktop).
- **Tujuan desain:** Menonjolkan proses booking (pilih jasa, pilih tanggal & jam, tunggu konfirmasi penyedia, bayar tunai setelah selesai) tanpa dekorasi promosi yang menutupi informasi transaksi.

## Aturan Proporsi Warna 60-30-10

| Proporsi | Token & Hex | Nama Warna | Penggunaan | Verifikasi Kontras WCAG AA |
| --- | --- | --- | --- | --- |
| **60% Dominan** | `#F4F6F0` / `#FFFFFF` | Soft Sage Mist / Pure White | `#F4F6F0` untuk latar belakang utama halaman (`body`), `#FFFFFF` untuk kartu jasa, modal booking, panel formulir, dan tabel dashboard sebagai pembeda elevasi. | `#1F2937` di atas `#F4F6F0` = **13.48:1** (PASS AAA); `#1F2937` di atas `#FFFFFF` = **14.68:1** (PASS AAA). |
| **30% Sekunder** | `#3A5A40` / `#2D4F1E` | Forest Sage / Deep Moss | Navbar utama, Sidebar akun (`admin`, `pemilik_toko`, `pembeli`), Footer, tab kategori aktif, dan judul bagian utama (`h1`, `h2`). | `#FFFFFF` di atas `#3A5A40` = **7.73:1** (PASS AAA); `#FFFFFF` di atas `#2D4F1E` = **9.34:1** (PASS AAA); `#3A5A40` di atas `#F4F6F0` = **7.10:1** (PASS AAA). |
| **10% Aksen** | `#E07A5F` / `#D06549` | Warm Terracotta / Deep Clay | Khusus CTA penarik perhatian ("Pesan Jasa", "Konfirmasi Pesanan", "Cari Jasa", "Kirim Ulasan", "Verifikasi Toko") serta tanggal dan slot jam terpilih pada kalender booking. | Teks `#111827` di atas `#E07A5F` = **6.01:1** (PASS AA); `#111827` di atas `#D06549` = **4.76:1** (PASS AA). |
| **Netral & Teks** | `#1F2937` / `#6B7280` / `#5B6370` / `#D1D5DB` / `#8B939E` | Charcoal / Muted Gray / Soft Border / Control Border | `#1F2937` teks utama, `#6B7280` teks sekunder di atas `#FFFFFF` (4.83:1), `#5B6370` teks sekunder di atas `#F4F6F0` (5.57:1), `#D1D5DB` garis kartu/pembatas, `#8B939E` batas input interaktif (3.10:1 WCAG 1.4.11). | Seluruh pasangan teks dan kontrol interaktif lolos batas minimum WCAG AA. |
| **Overlay Embun & Skip** | `#E6EDE0` / `#D9E2CE` / `#2D4F1E` / `#C94734` | Mist Fog / Plaid Cloth / Deep Moss | Kanvas embun/kabut semi-buram layar penuh di atas splash dengan kain lap kotak-kotak merah-krem (`#FBF6EB` / `#C94734`) dan tombol pojok `Lewati` (`#2D4F1E` di atas putih). | `#2D4F1E` di atas `#D9E2CE` = **6.99:1** (PASS AA); `#2D4F1E` di atas `#FFFFFF` = **9.34:1** (PASS AAA). |

## Tipografi (`/design-taste-frontend` Dealt Pairing)
- **Display & Headings (`h1`, `h2`, angka statistik):** `Newsreader` (serif editorial dengan optical sizing, bobot 400-600, tracking `-0.025em`) untuk memberikan karakter hangat khas usaha lokal dan hierarki editorial ala `lenis.dev`.
- **UI, Form, Kartu & Tabel:** `Hanken Grotesk` (neo-grotesk bersih, bobot 400-700, `tabular-nums` pada harga dan tabel) agar angka tarif, slot jam, dan tabel operasional mudah dipindai.
- Tidak menggunakan font generik default AI (`Inter`, `Plus Jakarta Sans`, `Poppins`, `Space Grotesk`).

## Ilustrasi & Fotografi
- **Ilustrasi editorial khusus (5 aset):** Bergaya gouache/risograph hangat dengan palet persis 60-30-10 (`illus-hero-marketplace.jpg`, `illus-step-schedule.jpg`, `illus-step-confirm.jpg`, `illus-step-done.jpg`, `illus-owner-partner.jpg`), tanpa teks di dalam gambar dan tanpa gaya generik Undraw/Storyset.
- **Foto katalog jasa:** 7 foto layanan nyata lokal (bersumber dari Unsplash) tampil tanpa caption di bawah gambar dan tanpa overlay stiker promosi.

## Motion, Smooth Scroll & Interaksi (Lenis + GSAP)
- **Lenis Smooth Scroll:** Terpasang pada seluruh halaman (`lerp: 0.1`, `smoothWheel: true`), disinkronkan langsung ke `gsap.ticker` dan `ScrollTrigger.update`, otomatis berhenti saat modal `<dialog>` terbuka (`data-lenis-prevent` pada container scroll bersarang) dan nonaktif sepenuhnya saat `prefers-reduced-motion: reduce`.
- **GSAP 3.12.5 + ScrollTrigger:**
  - Hero entrance sekali saat muat + efek `picture-develops` pada ilustrasi hero.
  - Koreografi `pinned-chapters` pada seksi `#cara-kerja` (scroll desktop & tombol tab interaktif untuk berpindah di antara 3 ilustrasi alur booking).
  - Transisi ringan kartu katalog saat berganti tab kategori beranda.
  - Hairline progres scroll `#E07A5F` di tepi atas navbar.
- **Intro beranda (`#splash-intro` + wipe layar penuh):**
  - **Splash layanan:** Logo `lapakbae.` di tengah dikelilingi 6 node kategori (Kebersihan, Perbaikan, Suruh & titip, Desain, Pendidikan, Otomotif) beserta nama usaha unggulan. Nama usaha diambil dari layanan aktif pertama per `category_slug`, bukan ID numerik hasil normalisasi data contoh.
  - **Desktop (`>=901px`):** Splash dipin selama `+=80%` scroll. Orbit berputar dan mengecil, node menyebar radial lalu konvergen ke pusat, cue gulir memudar. **Seluler (`<=900px`):** Splash tidak dipin; node bergerak halus di tempat dan memudar saat section keluar layar, dengan topbar ringkas dan detail nama usaha disembunyikan di bawah `360px`.
  - **Auto-play wipe layar penuh (2 detik):** Saat beranda dibuka, overlay fixed menutup viewport dengan kanvas efek embun/kabut (uap radial, mikro-kabut, dan tetesan embun) yang dibuat sekali pada kanvas offscreen (`buildMistTextureCanvas`). Tanpa perlu interaksi mouse manual, GSAP menggerakkan kain lap kotak-kotak merah-krem secara zig-zag menyapu layar selama 2 detik menggunakan brush stamp lembut `destination-out` (`buildBrushStamp`); logo pusat `lapakbae.` dan 6 kartu kategori di baliknya langsung terlihat tajam begitu tersapu. Pada akhir 2 detik, kain lap memudar, overlay fade-out (`450ms`), scroll dibuka, `ScrollTrigger.refresh()` dijalankan, dan kartu splash menjalankan animasi settle halus. Selama animasi berjalan, Lenis dihentikan dan `html.is-splash-wipe-locked` menahan scroll dengan kompensasi lebar scrollbar; tombol kecil `Lewati` di pojok kanan atas, `Escape`, dan kurungan fokus Tab menjadi jalan pintas. Fokus hanya berpindah ke `.splash-skip` bila pengguna berinteraksi lewat keyboard.
  - **Fallback:** `prefers-reduced-motion`, tanpa JavaScript, muat dengan hash `#beranda-utama`, atau tanpa GSAP menampilkan splash langsung tanpa overlay terkunci; tanpa Lenis auto-play 2 detik tetap berjalan dan scroll biasa dibuka. Failsafe inline `4.5` detik melepas overlay bila `animation.js` gagal dimuat.
- **Micro-interaction:** Kartu jasa naik `2px` dan foto zoom `1.03` di dalam bingkai saat hover; tombol step `-1px` hover dan `scale(0.985)` saat ditekan; konten tetap tampil penuh tanpa JS atau saat reduced motion aktif.

## Responsivitas & Pola Komponen
- Breakpoint reflow nyata di `1120px`, `900px`, `620px`, dan `359px` untuk intro beranda (diverifikasi sampai `360px` dan `320px`).
- Pada layar seluler, panel pemilih jadwal di `layanan/detail.php` langsung muncul setelah judul/harga, ringkasan pesanan di `pembeli/booking.php` muncul sebelum formulir, sidebar akun berubah menjadi tombol toggle Forest Sage dengan label halaman aktif, dan formulir autentikasi memakai inset `20px`.
- Seluruh target sentuh minimal `44px x 44px`, seluruh ikon menggunakan glif literal per fungsi, dan seluruh daftar memiliki state `empty`, `loading`, dan `error`.
