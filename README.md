# SiEkskul - Sistem Penilaian Ekstrakurikuler Sekolah
Aplikasi web PHP + MySQL (CRUD siswa, ekskul, penilaian + dashboard rekapitulasi).

## Cara menjalankan (XAMPP / Laragon)
1. Nyalakan Apache dan MySQL.
2. Buka phpMyAdmin > tab Import > pilih `database/db_ekskul_sekolah.sql` > Go.
3. Salin folder `ekskul_app` ke `htdocs` (XAMPP) atau `www` (Laragon).
4. Buka http://localhost/ekskul_app/
5. Jika user/password MySQL berbeda, ubah bagian atas file `config.php`.

Catatan: butuh PHP 8.1+ dan internet (Bootstrap dimuat dari CDN agar tampilan bagus).

## Struktur
- database/db_ekskul_sekolah.sql : database + data contoh
- config.php : koneksi & fungsi bantu
- header.php, footer.php : tampilan bersama
- index.php : dashboard & rekap JOIN
- siswa.php, ekskul.php, nilai.php : halaman CRUD
