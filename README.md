# LapakBae

Frontend PHP Native, Bootstrap 5, CSS (60-30-10), JavaScript, Lenis smooth scroll, dan GSAP + ScrollTrigger. Tidak ada controller, model atau query database.

Jalankan `php -S localhost:8090 -t .`, lalu buka http://localhost:8090/app/views/home/index.php.

Data contoh ada di `app/views/partials/sample-data.php`. View menerima variabel PHP dan menampilkan data dengan escaping. Form memvalidasi dan menampilkan ringkasan di browser, tanpa penyimpanan server. Kata sandi tidak disimpan oleh JavaScript. Role pilihan, status booking, dan status aktivasi data bertahan selama sesi tab melalui `sessionStorage`.

Backend nantinya memasok data, token CSRF, autentikasi, endpoint, aturan status, dan ketersediaan jadwal. Pemisahan tampilan role dan pemilihan data pada view bukan pemeriksaan hak akses server. Validasi browser, termasuk benturan jadwal, harus diulangi oleh backend ketika integrasi dilakukan.

Halaman daftar mendukung `?state=empty`, `?state=error` dan `?state=loading` untuk pemeriksaan tampilan. Navigasi tiga role tersedia pada footer.

## Halaman utama

- Publik: beranda, tentang, katalog dan detail jasa, katalog dan detail usaha, masuk, daftar.
- Pembeli: dashboard, form booking, detail dan timeline, riwayat dengan filter status, profil.
- Pemilik toko: dashboard, profil usaha, layanan, tambah/edit layanan, jadwal mingguan dan tanggal libur, booking masuk, detail booking, riwayat, ulasan.
- Admin: dashboard, pengguna, toko, kategori, layanan, booking, detail booking, ulasan, laporan, dan form tambah/edit sesuai jenis data.

Beranda diawali animasi **auto-play 2 detik berbasis GSAP**: kain lap kotak-kotak merah-krem otomatis menyapu efek embun/kabut di layar secara zig-zag lalu fade-out masuk ke **splash 6 kategori layanan** (kartu tengah `lapakbae.`, orbit, dan 6 kartu kategori beserta usaha unggulan). Tombol kecil **Lewati** di pojok kanan atas atau `Escape` langsung membuka splash. Setelah itu koreografi splash berjalan seperti biasa: di desktop splash dipin oleh ScrollTrigger, di seluler mengalir normal.

Katalog menampilkan maksimal 12 item; tabel dan riwayat booking maksimal 10. Pencarian, filter, urutan, dan halaman dipertahankan dalam query string. Jadwal contoh memakai Senin–Sabtu 08.00–17.00. Field `hari` memakai 1=Senin hingga 7=Minggu.

Bootstrap 5.3.3, tujuh foto jasa (Unsplash), lima ilustrasi editorial khusus, dan favicon `favicon.svg` disimpan lokal di `public/assets/`. Lenis 1.1.18, GSAP 3.12.5, ScrollTrigger, dan Google Fonts (`Newsreader` + `Hanken Grotesk`) dimuat dari CDN dengan `defer`; konten tetap terlihat penuh tanpa GSAP/Lenis.

Rincian pemeriksaan dan batas integrasi ada di `FRONTEND_CHECKS.md`.
