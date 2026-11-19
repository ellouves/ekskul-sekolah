<?php
require 'config.php'; $title = 'Dashboard';
$stat = [
 ['Total Siswa',  $pdo->query("SELECT COUNT(*) FROM siswa")->fetchColumn(),  'bi-people',          '#4f46e5,#6366f1'],
 ['Total Ekskul', $pdo->query("SELECT COUNT(*) FROM ekskul")->fetchColumn(), 'bi-trophy',          '#0ea5e9,#38bdf8'],
 ['Data Penilaian', $pdo->query("SELECT COUNT(*) FROM keanggotaan_nilai")->fetchColumn(), 'bi-clipboard-check', '#10b981,#34d399'],
 ['Rata-rata Nilai', $pdo->query("SELECT ROUND(IFNULL(AVG(nilai_angka),0),2) FROM keanggotaan_nilai")->fetchColumn(), 'bi-graph-up', '#f59e0b,#fbbf24'],
];
$rekap = $pdo->query("SELECT s.nis, s.nama_siswa, s.kelas, e.nama_ekskul, e.pembina, kn.tingkat_keaktifan, kn.nilai_angka
  FROM keanggotaan_nilai kn JOIN siswa s ON kn.nis = s.nis JOIN ekskul e ON kn.id_ekskul = e.id_ekskul
  ORDER BY s.nama_siswa, e.nama_ekskul")->fetchAll();
$perEkskul = $pdo->query("SELECT e.nama_ekskul, COUNT(kn.id_nilai) jml, ROUND(IFNULL(AVG(kn.nilai_angka),0),2) rata
  FROM ekskul e LEFT JOIN keanggotaan_nilai kn ON e.id_ekskul = kn.id_ekskul GROUP BY e.id_ekskul, e.nama_ekskul ORDER BY rata DESC")->fetchAll();
require 'header.php';
?>
<h4 class="fw-bold mb-3">Dashboard</h4>
<div class="row g-3 mb-4">
<?php foreach ($stat as [$l, $v, $i, $g]): ?>
  <div class="col-6 col-lg-3"><div class="stat d-flex justify-content-between align-items-center" style="background:linear-gradient(135deg,<?= $g ?>)">
    <div><div class="small"><?= $l ?></div><div class="fs-3 fw-bold"><?= e($v) ?></div></div><i class="bi <?= $i ?>"></i></div></div>
<?php endforeach; ?>
</div>
<div class="row g-3">
 <div class="col-lg-8"><div class="card"><div class="card-body">
  <h6 class="fw-bold mb-3"><i class="bi bi-table me-1"></i>Rekapitulasi Nilai Siswa</h6>
  <div class="table-responsive"><table class="table align-middle">
   <thead><tr><th>Siswa</th><th>Kelas</th><th>Ekskul</th><th>Pembina</th><th>Keaktifan</th><th>Nilai</th></tr></thead><tbody>
   <?php foreach ($rekap as $r): ?>
    <tr><td><b><?= e($r['nama_siswa']) ?></b><br><small class="text-muted"><?= e($r['nis']) ?></small></td>
     <td><?= e($r['kelas']) ?></td><td><?= e($r['nama_ekskul']) ?></td><td><?= e($r['pembina']) ?></td>
     <td><?= badge($r['tingkat_keaktifan']) ?></td>
     <td><b><?= e($r['nilai_angka']) ?></b> <span class="badge text-bg-light"><?= predikat($r['nilai_angka']) ?></span></td></tr>
   <?php endforeach; if (!$rekap): ?><tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr><?php endif; ?>
  </tbody></table></div></div></div></div>
 <div class="col-lg-4"><div class="card"><div class="card-body">
  <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-1"></i>Rata-rata per Ekskul</h6>
  <?php foreach ($perEkskul as $p): ?>
   <div class="mb-3"><div class="d-flex justify-content-between small"><span><?= e($p['nama_ekskul']) ?> <span class="text-muted">(<?= $p['jml'] ?> nilai)</span></span><b><?= $p['rata'] ?></b></div>
   <div class="progress" style="height:8px"><div class="progress-bar" style="width:<?= $p['rata'] ?>%;background:#7c3aed"></div></div></div>
  <?php endforeach; ?>
 </div></div></div>
</div>
<?php require 'footer.php'; ?>
