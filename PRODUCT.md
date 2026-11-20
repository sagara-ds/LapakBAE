# LapakBae

Marketplace jasa UMKM Indonesia yang menghubungkan kebutuhan rumah tangga serta usaha lokal dengan penyedia jasa di sekitar.

## Alur Utama Produk
1. **Cari & bandingkan jasa:** Pelanggan mencari jasa berdasarkan kata kunci, kategori (Kebersihan, Perbaikan, Suruh & titip, Desain, Pendidikan, Otomotif), area layanan, dan urutan harga. Setiap kartu menampilkan penyedia, durasi pengerjaan, area, rating, dan tarif satuan yang jelas.
2. **Pilih tanggal dan jam:** Pelanggan memilih tanggal melalui pilihan cepat kalender (otomatis melewati hari Minggu) atau input tanggal, lalu memilih jam mulai yang tersedia tanpa benturan dengan jadwal booking aktif.
3. **Isi kontak & konfirmasi pesanan:** Pelanggan melengkapi nama, nomor telepon, alamat layanan, dan catatan kebutuhan, lalu meninjau rincian lengkap (jasa, penyedia, tanggal, rentang jam, total biaya) sebelum melanjutkan.
4. **Konfirmasi penyedia & pengerjaan:** Penyedia meninjau pesanan masuk dan mengubah status melalui siklus `menunggu_konfirmasi` -> `dikonfirmasi` -> `sedang_dikerjakan` -> `selesai` (atau `ditolak` / `dibatalkan`).
5. **Bayar tunai & beri ulasan:** Pembayaran dilakukan tunai langsung ke penyedia setelah layanan selesai dikerjakan. Setelah status selesai, pelanggan dapat memberikan nilai 1 sampai 5 bintang beserta ulasan tertulis.

## Peran Pengguna
- **Publik (Tamu):** Beranda dengan animasi pembuka otomatis 2 detik (kain lap kotak-kotak merah-krem membersihkan embun/kabut menuju splash 6 kategori layanan, tombol pojok `Lewati`/`Escape` tersedia), filter kategori langsung, dan alur kerja interaktif; katalog & detail jasa, daftar & profil usaha, halaman tentang, masuk, dan daftar.
- **Pembeli:** Dashboard prioritas jadwal aktif, formulir booking dengan ringkasan waktu selesai otomatis, detail & timeline booking, riwayat dengan filter status, form ulasan pada booking selesai, dan profil.
- **Pemilik Toko:** Dashboard dengan sorotan antrean booking yang butuh keputusan, manajemen booking masuk & riwayat, pengelolaan layanan (tambah/edit/nonaktifkan), editor jadwal operasional mingguan 7 hari dan tanggal libur khusus, profil usaha, serta ulasan pelanggan.
- **Admin:** Dashboard dengan prioritas antrean verifikasi usaha baru, pengelolaan pengguna, toko (dengan verifikasi/tolak), kategori, layanan, seluruh booking & detail relasi, moderasi ulasan (tampilkan/sembunyikan), serta laporan distribusi status booking siap cetak.

## Arsitektur Teknis Frontend
- **Stack:** PHP Native Views (tanpa controller, model, atau query database), Bootstrap 5.3.3 lokal, CSS kustom 60-30-10 (`Newsreader` + `Hanken Grotesk`), Vanilla JavaScript, Lenis smooth scroll (`1.1.18`), dan GSAP (`3.12.5`) + ScrollTrigger.
- **Data & Persistensi Sesi:** Data contoh disimpan di `app/views/partials/sample-data.php` tanpa penanda demo untuk kebutuhan tugas. Perubahan peran, status booking, dan status aktivasi data dipertahankan selama sesi tab melalui `sessionStorage`.
- **State Pemeriksaan:** Halaman katalog dan tabel mendukung `?state=empty`, `?state=error`, dan `?state=loading` dengan bentuk skeleton yang sesuai konteks (kartu untuk katalog, baris untuk tabel).
- **Motion Beranda:** Splash layanan memakai GSAP ScrollTrigger dengan pin dan scrub; splash dipin di desktop dan mengalir di seluler. Saat halaman dibuka, kain lap kotak-kotak merah-krem otomatis menyapu efek embun/kabut secara zig-zag selama 2 detik (tekstur offscreen + brush stamp lembut `destination-out`), lalu otomatis fade-out masuk ke kartu tengah `lapakbae.` dan 6 kartu kategori di sekitarnya. Tombol kecil `Lewati` di pojok kanan atas dan `Escape` langsung membuka splash; reduced motion, tanpa JavaScript, atau tanpa GSAP menampilkan splash langsung.
