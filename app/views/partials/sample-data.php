<?php
$base = $base ?? '/app/views';
$asset = $asset ?? '/public/assets';
$csrf_token = $csrf_token ?? '';
$errors = $errors ?? [];
$categories = $categories ?? [
['slug'=>'kebersihan','nama'=>'Kebersihan','teks'=>'Rumah bersih, pikiran lebih tenang.','icon'=>'clean'],
['slug'=>'perbaikan','nama'=>'Perbaikan','teks'=>'Bereskan yang rusak di rumah.','icon'=>'tool'],
['slug'=>'suruh','nama'=>'Suruh & titip','teks'=>'Minta bantuan untuk urusan harian.','icon'=>'bag'],
['slug'=>'desain','nama'=>'Desain','teks'=>'Ide usahamu jadi lebih terlihat.','icon'=>'pen'],
['slug'=>'pendidikan','nama'=>'Pendidikan','teks'=>'Belajar ditemani yang paham.','icon'=>'book'],
['slug'=>'otomotif','nama'=>'Otomotif','teks'=>'Rawat kendaraan untuk aktivitas harian.','icon'=>'car']];
$services = $services ?? [
['id'=>1,'nama_jasa'=>'Bersih rumah, sampai ke sudutnya','deskripsi'=>'Membersihkan lantai, kamar mandi, dapur, dan ruang keluarga. Peralatan kebersihan dibawa oleh penyedia.','harga'=>150000,'satuan_harga'=>'kunjungan','durasi_menit'=>120,'area_layanan'=>'Purwokerto Utara','gambar'=>'cleaning.jpg','category_id'=>'kebersihan','toko'=>'Resik Apik','inisial'=>'RA','rating'=>'4,9','ulasan'=>24],
['id'=>2,'nama_jasa'=>'Servis AC biar adem lagi','deskripsi'=>'Pembersihan filter dan evaporator, pemeriksaan suhu serta pengecekan kondisi AC.','harga'=>75000,'satuan_harga'=>'unit','durasi_menit'=>60,'area_layanan'=>'Purwokerto Timur','gambar'=>'repair.jpg','category_id'=>'perbaikan','toko'=>'Bengkel Adem','inisial'=>'BA','rating'=>'4,8','ulasan'=>18],
['id'=>3,'nama_jasa'=>'Desain logo untuk usaha kamu','deskripsi'=>'Logo sesuai karakter usaha, termasuk dua konsep dan dua kali revisi.','harga'=>250000,'satuan_harga'=>'pekerjaan','durasi_menit'=>180,'area_layanan'=>'Purwokerto','gambar'=>'design.jpg','category_id'=>'desain','toko'=>'Ruang Rupa','inisial'=>'RR','rating'=>'4,9','ulasan'=>12],
['id'=>4,'nama_jasa'=>'Les privat, belajar lebih paham','deskripsi'=>'Pendampingan matematika dan pelajaran sekolah di rumah.','harga'=>60000,'satuan_harga'=>'jam','durasi_menit'=>60,'area_layanan'=>'Purwokerto Selatan','gambar'=>'education.jpg','category_id'=>'pendidikan','toko'=>'Kawan Belajar','inisial'=>'KB','rating'=>'4,8','ulasan'=>16],
['id'=>5,'nama_jasa'=>'Cuci motor di rumah','deskripsi'=>'Pencucian motor dengan peralatan yang dibawa ke rumah.','harga'=>30000,'satuan_harga'=>'motor','durasi_menit'=>45,'area_layanan'=>'Purwokerto Barat','gambar'=>'automotive.jpg','category_id'=>'otomotif','toko'=>'Apik Motor','inisial'=>'AM','rating'=>'4,7','ulasan'=>9],
['id'=>6,'nama_jasa'=>'Titip belanja kebutuhan harian','deskripsi'=>'Bantuan belanja dari pasar atau toko sekitar. Biaya belanja dibayar terpisah.','harga'=>20000,'satuan_harga'=>'perjalanan','durasi_menit'=>60,'area_layanan'=>'Purwokerto Utara','gambar'=>'errands.jpg','category_id'=>'suruh','toko'=>'Titip Bae','inisial'=>'TB','rating'=>'4,9','ulasan'=>21],
['id'=>7,'nama_jasa'=>'Rapikan halaman dan taman','deskripsi'=>'Membersihkan halaman dan merapikan tanaman rumah.','harga'=>120000,'satuan_harga'=>'kunjungan','durasi_menit'=>90,'area_layanan'=>'Purwokerto Utara','gambar'=>'garden.jpg','category_id'=>'kebersihan','toko'=>'Kebon Apik','inisial'=>'KA','rating'=>'4,8','ulasan'=>8],
['id'=>8,'nama_jasa'=>'Perbaikan keran dan pipa bocor','deskripsi'=>'Pemeriksaan kebocoran dan perbaikan pipa. Material disepakati terpisah.','harga'=>85000,'satuan_harga'=>'kunjungan','durasi_menit'=>60,'area_layanan'=>'Purwokerto Timur','gambar'=>'repair.jpg','category_id'=>'perbaikan','toko'=>'Beres Rumah','inisial'=>'BR','rating'=>'4,8','ulasan'=>14],
['id'=>9,'nama_jasa'=>'Desain menu untuk warung','deskripsi'=>'Menu usaha yang rapi dan mudah dibaca pelanggan.','harga'=>100000,'satuan_harga'=>'pekerjaan','durasi_menit'=>120,'area_layanan'=>'Purwokerto','gambar'=>'design.jpg','category_id'=>'desain','toko'=>'Ruang Rupa','inisial'=>'RR','rating'=>'4,9','ulasan'=>12],
['id'=>10,'nama_jasa'=>'Belajar membaca di rumah','deskripsi'=>'Pendampingan membaca sesuai usia anak.','harga'=>55000,'satuan_harga'=>'jam','durasi_menit'=>60,'area_layanan'=>'Purwokerto Selatan','gambar'=>'education.jpg','category_id'=>'pendidikan','toko'=>'Kawan Belajar','inisial'=>'KB','rating'=>'4,8','ulasan'=>16],
['id'=>11,'nama_jasa'=>'Antar dokumen dalam kota','deskripsi'=>'Pengantaran dokumen dalam wilayah layanan.','harga'=>25000,'satuan_harga'=>'perjalanan','durasi_menit'=>45,'area_layanan'=>'Purwokerto','gambar'=>'errands.jpg','category_id'=>'suruh','toko'=>'Titip Bae','inisial'=>'TB','rating'=>'4,9','ulasan'=>21],
['id'=>12,'nama_jasa'=>'Bersih sofa dan kursi','deskripsi'=>'Pembersihan sofa dan kursi kain di rumah.','harga'=>175000,'satuan_harga'=>'kunjungan','durasi_menit'=>120,'area_layanan'=>'Purwokerto Barat','gambar'=>'cleaning.jpg','category_id'=>'kebersihan','toko'=>'Resik Apik','inisial'=>'RA','rating'=>'4,9','ulasan'=>24],
['id'=>13,'nama_jasa'=>'Pemasangan rak dan furnitur','deskripsi'=>'Bantuan merakit furnitur dan memasang rak.','harga'=>95000,'satuan_harga'=>'pekerjaan','durasi_menit'=>90,'area_layanan'=>'Purwokerto Timur','gambar'=>'repair.jpg','category_id'=>'perbaikan','toko'=>'Beres Rumah','inisial'=>'BR','rating'=>'4,8','ulasan'=>14],
['id'=>14,'nama_jasa'=>'Bersih dapur dan kamar mandi','deskripsi'=>'Membersihkan permukaan dapur, kompor, wastafel, dan kamar mandi.','harga'=>95000,'satuan_harga'=>'kunjungan','durasi_menit'=>90,'area_layanan'=>'Purwokerto Utara','gambar'=>'cleaning.jpg','category_id'=>'kebersihan','toko'=>'Resik Apik','inisial'=>'RA','rating'=>'4,9','ulasan'=>24]];
$statuses = ['menunggu_konfirmasi'=>'Menunggu konfirmasi','dikonfirmasi'=>'Dikonfirmasi','ditolak'=>'Ditolak','sedang_dikerjakan'=>'Sedang dikerjakan','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'];
$statusIcons = ['menunggu_konfirmasi'=>'clock','dikonfirmasi'=>'calendar','ditolak'=>'close','sedang_dikerjakan'=>'tool','selesai'=>'check','dibatalkan'=>'cancel'];
$bookings = $bookings ?? [
['id'=>1,'kode_booking'=>'LB-171126-001','nama_jasa'=>'Bersih rumah, sampai ke sudutnya','toko'=>'Resik Apik','tanggal_booking'=>'2026-11-20','tanggal_label'=>'20 Nov 2026','jam_mulai'=>'09:00','jam_selesai'=>'11:00','harga_booking'=>150000,'status'=>'dikonfirmasi','nama_pemesan'=>'Nadia Putri','no_telepon'=>'081234567890','alamat_layanan'=>'Jl. Kenanga No. 12, Purwokerto Utara','catatan'=>'Tolong fokus pada dapur dan kamar mandi.'],
['id'=>2,'kode_booking'=>'LB-171126-002','nama_jasa'=>'Servis AC biar adem lagi','toko'=>'Bengkel Adem','tanggal_booking'=>'2026-11-21','tanggal_label'=>'21 Nov 2026','jam_mulai'=>'13:00','jam_selesai'=>'14:00','harga_booking'=>75000,'status'=>'menunggu_konfirmasi','nama_pemesan'=>'Nadia Putri','no_telepon'=>'081234567890','alamat_layanan'=>'Jl. Kenanga No. 12, Purwokerto Utara','catatan'=>'AC kurang dingin.'],
['id'=>3,'kode_booking'=>'LB-141126-003','nama_jasa'=>'Desain logo untuk usaha kamu','toko'=>'Ruang Rupa','tanggal_booking'=>'2026-11-14','tanggal_label'=>'14 Nov 2026','jam_mulai'=>'10:00','jam_selesai'=>'13:00','harga_booking'=>250000,'status'=>'selesai','nama_pemesan'=>'Nadia Putri','no_telepon'=>'081234567890','alamat_layanan'=>'Purwokerto','catatan'=>'Logo untuk usaha makanan.']];

$stores = $stores ?? [
    ['id'=>1,'nama_toko'=>'Resik Apik','pemilik'=>'Raka Prasetya','inisial'=>'RA','area_layanan'=>'Purwokerto Utara','alamat'=>'Jl. Melati No. 8, Purwokerto Utara','no_telepon'=>'081234567891','deskripsi'=>'Jasa kebersihan rumah untuk keluarga di Purwokerto. Peralatan dibawa sendiri dan pekerjaan disesuaikan dengan kebutuhan rumahmu.','foto_toko'=>'cleaning.jpg','kategori'=>'Kebersihan','status'=>'aktif'],
    ['id'=>2,'nama_toko'=>'Bengkel Adem','pemilik'=>'Bima Aditya','inisial'=>'BA','area_layanan'=>'Purwokerto Timur','alamat'=>'Jl. Merdeka No. 21, Purwokerto Timur','no_telepon'=>'081234567892','deskripsi'=>'Perawatan dan servis AC rumah. Rincian pengerjaan dibicarakan sebelum pekerjaan dimulai.','foto_toko'=>'repair.jpg','kategori'=>'Perbaikan','status'=>'aktif'],
    ['id'=>3,'nama_toko'=>'Ruang Rupa','pemilik'=>'Rani Putri','inisial'=>'RR','area_layanan'=>'Purwokerto','alamat'=>'Jl. Jenderal Sudirman No. 15, Purwokerto','no_telepon'=>'081234567893','deskripsi'=>'Studio desain untuk usaha lokal, dari logo sampai menu warung.','foto_toko'=>'design.jpg','kategori'=>'Desain','status'=>'aktif'],
    ['id'=>4,'nama_toko'=>'Kawan Belajar','pemilik'=>'Dimas Putra','inisial'=>'KB','area_layanan'=>'Purwokerto Selatan','alamat'=>'Jl. Dahlia No. 4, Purwokerto Selatan','no_telepon'=>'081234567894','deskripsi'=>'Pendampingan belajar di rumah sesuai kebutuhan anak.','foto_toko'=>'education.jpg','kategori'=>'Pendidikan','status'=>'aktif'],
    ['id'=>5,'nama_toko'=>'Apik Motor','pemilik'=>'Adi Nugroho','inisial'=>'AM','area_layanan'=>'Purwokerto Barat','alamat'=>'Jl. Mawar No. 10, Purwokerto Barat','no_telepon'=>'081234567895','deskripsi'=>'Perawatan dan pencucian kendaraan di rumah.','foto_toko'=>'automotive.jpg','kategori'=>'Otomotif','status'=>'aktif'],
    ['id'=>6,'nama_toko'=>'Titip Bae','pemilik'=>'Tika Sari','inisial'=>'TB','area_layanan'=>'Purwokerto Utara','alamat'=>'Jl. Soka No. 5, Purwokerto Utara','no_telepon'=>'081234567896','deskripsi'=>'Bantuan titip belanja dan antar dokumen dalam kota.','foto_toko'=>'errands.jpg','kategori'=>'Suruh & titip','status'=>'aktif'],
    ['id'=>7,'nama_toko'=>'Kebon Apik','pemilik'=>'Agus Wibowo','inisial'=>'KA','area_layanan'=>'Purwokerto Utara','alamat'=>'Jl. Anggrek No. 7, Purwokerto Utara','no_telepon'=>'081234567897','deskripsi'=>'Perawatan halaman dan tanaman untuk rumah yang lebih nyaman.','foto_toko'=>'garden.jpg','kategori'=>'Kebersihan','status'=>'menunggu'],
    ['id'=>8,'nama_toko'=>'Beres Rumah','pemilik'=>'Bayu Saputra','inisial'=>'BR','area_layanan'=>'Purwokerto Timur','alamat'=>'Jl. Sawo No. 3, Purwokerto Timur','no_telepon'=>'081234567898','deskripsi'=>'Bantuan perbaikan pipa dan pemasangan furnitur di rumah.','foto_toko'=>'repair.jpg','kategori'=>'Perbaikan','status'=>'aktif'],
];
$categoryIds = ['kebersihan'=>1,'perbaikan'=>2,'suruh'=>3,'desain'=>4,'pendidikan'=>5,'otomotif'=>6];
foreach ($categories as $index=>$category) $categories[$index]['id'] = $category['id'] ?? ($index+1);
foreach ($services as $index=>$service) {
    $services[$index]['category_slug'] = $service['category_slug'] ?? (is_string($service['category_id']) ? $service['category_id'] : '');
    $services[$index]['category_id'] = $categoryIds[$services[$index]['category_slug']] ?? $service['category_id'];
    $services[$index]['status'] = $service['status'] ?? 'aktif';
    foreach ($stores as $store) if ($store['nama_toko']===$service['toko']) { $services[$index]['store_id']=$store['id']; $services[$index]['store_status']=$store['status']; }
}
foreach ($bookings as $index=>$booking) {
    foreach ($services as $service) if ($service['nama_jasa']===$booking['nama_jasa']) {
        $bookings[$index]['service_id']=$service['id'];
        $bookings[$index]['store_id']=$service['store_id'];
    }
    $bookings[$index]['user_id']=$booking['user_id'] ?? 1;
}
$ownerBookings = $ownerBookings ?? [
    ['id'=>4,'user_id'=>2,'store_id'=>1,'service_id'=>12,'kode_booking'=>'LB-171126-004','nama_jasa'=>'Bersih sofa dan kursi','toko'=>'Resik Apik','tanggal_booking'=>'2026-11-23','tanggal_label'=>'23 Nov 2026','jam_mulai'=>'13:00','jam_selesai'=>'15:00','harga_booking'=>175000,'status'=>'menunggu_konfirmasi','nama_pemesan'=>'Dewi Pratiwi','no_telepon'=>'081234567899','alamat_layanan'=>'Jl. Seroja No. 6, Purwokerto Utara','catatan'=>'Sofa tiga dudukan, bahan kain.'],
    ['id'=>5,'user_id'=>2,'store_id'=>1,'service_id'=>1,'kode_booking'=>'LB-171126-005','nama_jasa'=>'Bersih rumah, sampai ke sudutnya','toko'=>'Resik Apik','tanggal_booking'=>'2026-11-17','tanggal_label'=>'17 Nov 2026','jam_mulai'=>'13:00','jam_selesai'=>'15:00','harga_booking'=>150000,'status'=>'sedang_dikerjakan','nama_pemesan'=>'Dewi Pratiwi','no_telepon'=>'081234567899','alamat_layanan'=>'Jl. Seroja No. 6, Purwokerto Utara','catatan'=>'Bersihkan lantai dan ruang keluarga.'],
    ['id'=>6,'user_id'=>2,'store_id'=>1,'service_id'=>1,'kode_booking'=>'LB-121126-006','nama_jasa'=>'Bersih rumah, sampai ke sudutnya','toko'=>'Resik Apik','tanggal_booking'=>'2026-11-12','tanggal_label'=>'12 Nov 2026','jam_mulai'=>'09:00','jam_selesai'=>'11:00','harga_booking'=>150000,'status'=>'selesai','nama_pemesan'=>'Dewi Pratiwi','no_telepon'=>'081234567899','alamat_layanan'=>'Jl. Seroja No. 6, Purwokerto Utara','catatan'=>'Peralatan dibawa penyedia.'],
    ['id'=>7,'user_id'=>1,'store_id'=>1,'service_id'=>12,'kode_booking'=>'LB-101126-007','nama_jasa'=>'Bersih sofa dan kursi','toko'=>'Resik Apik','tanggal_booking'=>'2026-11-10','tanggal_label'=>'10 Nov 2026','jam_mulai'=>'13:00','jam_selesai'=>'15:00','harga_booking'=>175000,'status'=>'dibatalkan','nama_pemesan'=>'Nadia Putri','no_telepon'=>'081234567890','alamat_layanan'=>'Jl. Kenanga No. 12, Purwokerto Utara','catatan'=>'Pelanggan menjadwalkan ulang.'],
    ['id'=>8,'user_id'=>2,'store_id'=>1,'service_id'=>1,'kode_booking'=>'LB-091126-008','nama_jasa'=>'Bersih rumah, sampai ke sudutnya','toko'=>'Resik Apik','tanggal_booking'=>'2026-11-09','tanggal_label'=>'9 Nov 2026','jam_mulai'=>'15:00','jam_selesai'=>'17:00','harga_booking'=>150000,'status'=>'ditolak','nama_pemesan'=>'Dewi Pratiwi','no_telepon'=>'081234567899','alamat_layanan'=>'Jl. Seroja No. 6, Purwokerto Utara','catatan'=>'Penyedia berhalangan.'],
];
$allBookings = [...$bookings, ...$ownerBookings];
$recordStatuses = ['aktif'=>'Aktif','nonaktif'=>'Nonaktif','menunggu'=>'Menunggu verifikasi','ditolak'=>'Ditolak'];
$users = $users ?? [
    ['id'=>1,'nama'=>'Nadia Putri','email'=>'nadia@email.com','role'=>'pembeli','no_telepon'=>'081234567890','alamat'=>'Jl. Kenanga No. 12, Purwokerto Utara','status'=>'aktif'],
    ['id'=>2,'nama'=>'Dewi Pratiwi','email'=>'dewi@email.com','role'=>'pembeli','no_telepon'=>'081234567899','alamat'=>'Jl. Seroja No. 6, Purwokerto Utara','status'=>'aktif'],
    ['id'=>3,'nama'=>'Raka Prasetya','email'=>'resik@email.com','role'=>'pemilik_toko','no_telepon'=>'081234567891','alamat'=>'Jl. Melati No. 8, Purwokerto Utara','status'=>'aktif'],
    ['id'=>4,'nama'=>'Bima Aditya','email'=>'adem@email.com','role'=>'pemilik_toko','no_telepon'=>'081234567892','alamat'=>'Purwokerto Timur','status'=>'aktif'],
];
