# Pemeriksaan frontend LapakBae

Diperiksa 17 November 2026 dan 20 November 2026. Implementasi hanya view PHP, CSS, dan JavaScript; tidak ada controller, model, atau query database.

## Hasil

| Pemeriksaan | Hasil dan bukti |
|---|---|
| Sintaks PHP | PASS: 51 file view dan partial lolos `php -l`. |
| Render PHP | PASS: 33 halaman utama dirender melalui PHP CLI tanpa warning atau fatal error. |
| Sintaks JavaScript | PASS: `node --check` pada `main.js` dan `animation.js`. |
| Responsivitas | PASS: 11 halaman representatif pada masing-masing lebar 360, 768, dan 1280 px. Total 33 pemeriksaan DOM tanpa overflow halaman, gambar gagal, atau error PHP; setiap halaman memiliki satu `h1`. |
| Kontras token | PASS: `#1F2937`/`#F4F6F0` 13.48:1, `#FFFFFF`/`#3A5A40` 7.73:1, `#111827`/`#E07A5F` 6.01:1, tombol `Lewati` `#2D4F1E`/`#D9E2CE` 6.99:1 dan `#2D4F1E`/`#FFFFFF` 9.34:1, batas input `#8B939E`/`#FFFFFF` 3.10:1 (`contrast-check.py`). |
| Tampilan dan identitas | PASS: palet 60-30-10 Soft Sage Mist / Forest Sage / Warm Terracotta, dua keluarga font `Newsreader` + `Hanken Grotesk`, ENERGY 3 / RHYTHM 3 / MOTION 3 sesuai `DESIGN.md`. |
| Konten tanpa animasi | Diperiksa melalui kode: tidak ada CSS yang menyembunyikan konten sambil menunggu GSAP; animasi berhenti ketika preferensi reduced motion aktif. |

Halaman responsif yang diperiksa: beranda, katalog dan detail layanan, register, katalog usaha, form booking, detail booking pembeli, booking masuk, jadwal usaha, form edit layanan admin, dan laporan admin.

## Pemeriksaan intro beranda (20 November 2026)

Diuji pada Chrome headless melalui Playwright (`qa_autoplay_wipe.py`) dengan viewport `1440x900`, `1280x800`, `1024x768`, `768x1024`, `390x844`, `360x800`, `320x700`, dan lanskap pendek `844x390`.

| Pemeriksaan | Hasil dan bukti |
|---|---|
| Embun & kunci awal | PASS: overlay fixed tampil di 8 viewport; kanvas offscreen mengisi layar tepat sesuai viewport (`overflow=0`) dan menahan scroll (`is-splash-wipe-locked`). |
| Auto-play zig-zag 2 detik | PASS: tanpa input manual, kain lap kotak-kotak merah-krem (`clothOpacity=1`) bergerak zig-zag di `1440x900` (`clearedRatio` naik `0.304` -> `0.633`, `scrollY=0`) dan `390x844` (`clearedRatio=0.463`, `scrollY=0`). Kartu `lapakbae.` dan node di belakang langsung tampak (`centerOpacity=1`). |
| Fade-out & pin desktop | PASS: setelah zig-zag 2 detik, kain memudar, overlay fade-out dan `hidden=true`, lock scroll dilepas tanpa memunculkan fokus nyasar pada `.splash-skip`, pin desktop aktif (`top=76`, `hasPinSpacer=true`) hingga `#beranda-utama` (`scrollY=1269`), serta scroll seluler normal (`scrollY=450`). |
| Tombol `Lewati` & Escape | PASS: tombol pojok `Lewati` (`73x44` desktop/lanskap, `68x44` mobile) dan `Escape` langsung menghentikan animasi serta membuka scroll (`locked=false`). |
| Keyboard & fokus | PASS: fokus awal masuk dialog; Tab/Shift+Tab terkurung pada tombol `Lewati` dan berhenti saat `.is-done`; saat `Escape` fokus berpindah ke `.splash-skip` dan dipertahankan melampaui `ScrollTrigger.refresh()` (`refreshTriggersPreservingFocus`). |
| Fallback | PASS: `prefers-reduced-motion`, `<noscript>`, muat `#beranda-utama`, tanpa GSAP, tanpa Lenis (`scrollY=600`), serta blokir `animation.js` + inline `Lewati` lolos seluruhnya. |
| Konsol & aset | PASS: tidak ada error konsol, page error, maupun respons `>=400`; `favicon.svg` termuat bersih. |

## Interaksi yang dijalankan di browser

- Katalog: halaman berikutnya menampilkan sisa item; query halaman dan urutan dipertahankan. Pencarian tanpa hasil menampilkan state kosong.
- State `empty`, `error`, dan `loading`: hanya state yang dipilih tampil, konten daftar disembunyikan.
- Booking: tanggal 20 November pada layanan dua jam menonaktifkan slot yang beririsan dengan 09.00–11.00 serta slot yang selesai setelah 17.00. Minggu 22 November tidak bisa dipilih; form menolak melanjutkan tanpa slot.
- Alur detail layanan → masuk → form booking mempertahankan layanan, tanggal, dan jam. Jam 11.00 dengan durasi 120 menit menghasilkan ringkasan selesai 13.00.
- Form masuk kosong menunjukkan pesan error per field. Ringkasan booking menampilkan tanggal, kontak, alamat, serta pembayaran tunai.
- Pemilik menerima booking, memulai pengerjaan, dan menyelesaikannya. Tombol serta timeline mengikuti status; booking selesai muncul pada riwayat.
- Pembatalan mengubah badge dan pesan terminal serta menyembunyikan tombol batalkan.
- Ulasan hanya muncul untuk booking selesai; pemeriksaan form berulang tidak menggandakan ringkasan.
- Edit layanan `id=12` menampilkan nama sofa, harga Rp175.000, kategori, dan foto yang benar.
- Jam tutup sebelum jam buka ditolak; setelah diperbaiki form menampilkan feedback valid.
- File gambar lokal berhasil menampilkan pratinjau melalui object URL. Batas tipe dan ukuran file diperiksa dalam JavaScript.
- Toko menunggu verifikasi dapat disetujui. Aksi nonaktifkan dan aktifkan memperbarui status serta tombol yang tersedia.
- Dialog tertutup dengan Escape. Navigasi seluler membuka dan menutup dengan tombol serta Escape.
- Tidak ada pesan error JavaScript pada log browser yang diperiksa.

## Perbaikan dari versi awal

- Memperbaiki overflow dashboard pada HP dengan kolom grid yang dapat menyusut dan sidebar yang dibatasi lebarnya.
- Detail serta edit tidak lagi diam-diam mengambil data pertama jika ID berbeda atau tidak ditemukan.
- Daftar dan riwayat pemilik hanya menampilkan booking usahanya, dengan pemisahan status aktif dan terminal.
- Tombol booking, badge enam status, timeline, statistik, dan laporan membaca perubahan status yang sama selama sesi tab.
- Verifikasi toko memakai nilai field `menunggu`, sesuai dokumen planning. Kategori pada form layanan memakai ID numerik.
- Detail booking admin mengarah ke ID yang sesuai. Form tambah/edit pengguna, toko, layanan, dan kategori tersedia.
- Error form terhubung melalui `aria-describedby`; tabel dapat difokuskan dan digeser dengan keyboard.

## Batas integrasi

Form hanya memeriksa isian dan menampilkan feedback. Tidak mengirim atau menyimpan perubahan ke server. Status dan role pilihan menggunakan session storage untuk interaksi tampilan; ini bukan autentikasi atau otorisasi server.

Backend harus menyediakan data, token CSRF, endpoint, autentikasi, pemeriksaan kepemilikan, aturan status final, jadwal tiap toko, tanggal tutup, dan pemeriksaan benturan atomik. Jadwal yang dipakai saat ini adalah data tampilan Senin–Sabtu 08.00–17.00; perubahan form jadwal belum mengubah ketersediaan server.

Pemeriksaan ini mencakup pola interaksi di atas dan halaman representatif. Tidak mencakup transaksi backend, pengukuran Lighthouse, atau sertifikasi aksesibilitas menyeluruh.
