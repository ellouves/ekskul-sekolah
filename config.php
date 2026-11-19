<?php
// === Pengaturan koneksi database (sesuaikan jika perlu) ===
$DB_HOST = 'localhost'; $DB_NAME = 'db_ekskul_sekolah'; $DB_USER = 'root'; $DB_PASS = '';

session_start();
try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $x) {
    die('<h3>Koneksi database gagal.</h3><p>Pastikan MySQL menyala dan file database/db_ekskul_sekolah.sql sudah di-import.</p>');
}
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function flash($m, $t = 'success') { $_SESSION['flash'] = [$m, $t]; }
function errmsg($x) {
    $c = $x->errorInfo[1] ?? 0;
    if ($c == 1451) return 'Data tidak bisa dihapus karena masih dipakai di data lain.';
    if ($c == 1062) return 'Data sudah ada (duplikat).';
    if ($c == 3819) return 'Nilai harus di antara 0 sampai 100.';
    if ($c == 1452) return 'Siswa atau ekskul yang dipilih tidak valid.';
    return 'Terjadi kesalahan: ' . $x->getMessage();
}
function predikat($n) { return $n >= 90 ? 'A' : ($n >= 80 ? 'B' : ($n >= 70 ? 'C' : 'D')); }
function badge($k) {
    $c = ['Sangat Aktif' => 'success', 'Aktif' => 'primary', 'Cukup' => 'warning', 'Kurang' => 'danger'][$k] ?? 'secondary';
    return "<span class='badge rounded-pill text-bg-$c'>" . e($k) . "</span>";
}
