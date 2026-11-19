<?php
require 'config.php'; $title = 'Penilaian';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['hapus'])) {
            $pdo->prepare("DELETE FROM keanggotaan_nilai WHERE id_nilai=?")->execute([$_POST['hapus']]);
            flash('Penilaian dihapus.');
        } else {
            $d = [$_POST['nis'], $_POST['id_ekskul'], $_POST['tingkat_keaktifan'], $_POST['nilai_angka'], trim($_POST['keterangan'])];
            if ($_POST['id'] !== '') {
                $pdo->prepare("UPDATE keanggotaan_nilai SET nis=?, id_ekskul=?, tingkat_keaktifan=?, nilai_angka=?, keterangan=? WHERE id_nilai=?")->execute([...$d, $_POST['id']]);
                flash('Penilaian diperbarui.');
            } else {
                $pdo->prepare("INSERT INTO keanggotaan_nilai (nis, id_ekskul, tingkat_keaktifan, nilai_angka, keterangan) VALUES (?,?,?,?,?)")->execute($d);
                flash('Penilaian berhasil disimpan.');
            }
        }
    } catch (PDOException $x) { flash(errmsg($x), 'danger'); }
    header('Location: nilai.php'); exit;
}
$ed = null;
if (isset($_GET['edit'])) { $s = $pdo->prepare("SELECT * FROM keanggotaan_nilai WHERE id_nilai=?"); $s->execute([$_GET['edit']]); $ed = $s->fetch(); }
$siswa = $pdo->query("SELECT nis, nama_siswa, kelas FROM siswa ORDER BY nama_siswa")->fetchAll();
$ekskul = $pdo->query("SELECT id_ekskul, nama_ekskul FROM ekskul ORDER BY nama_ekskul")->fetchAll();
$rows = $pdo->query("SELECT kn.*, s.nama_siswa, s.kelas, e.nama_ekskul, e.pembina FROM keanggotaan_nilai kn
  JOIN siswa s ON kn.nis = s.nis JOIN ekskul e ON kn.id_ekskul = e.id_ekskul ORDER BY kn.tanggal_penilaian DESC, kn.id_nilai DESC")->fetchAll();
require 'header.php';
?>
<h4 class="fw-bold mb-3">Penilaian Ekstrakurikuler</h4>
<div class="row g-3">
 <div class="col-lg-4"><div class="card"><div class="card-body">
  <h6 class="fw-bold mb-3"><?= $ed ? 'Edit Penilaian' : 'Input Penilaian' ?></h6>
  <form method="post">
   <input type="hidden" name="id" value="<?= e($ed['id_nilai'] ?? '') ?>">
   <div class="mb-2"><label class="form-label">Siswa</label><select name="nis" class="form-select" required>
    <option value="">-- pilih siswa --</option>
    <?php foreach ($siswa as $s): ?><option value="<?= e($s['nis']) ?>" <?= ($ed['nis'] ?? '') == $s['nis'] ? 'selected' : '' ?>><?= e($s['nama_siswa']) ?> (<?= e($s['kelas']) ?>)</option><?php endforeach; ?>
   </select></div>
   <div class="mb-2"><label class="form-label">Ekskul</label><select name="id_ekskul" class="form-select" required>
    <option value="">-- pilih ekskul --</option>
    <?php foreach ($ekskul as $x): ?><option value="<?= $x['id_ekskul'] ?>" <?= ($ed['id_ekskul'] ?? '') == $x['id_ekskul'] ? 'selected' : '' ?>><?= e($x['nama_ekskul']) ?></option><?php endforeach; ?>
   </select></div>
   <div class="mb-2"><label class="form-label">Tingkat Keaktifan</label><select name="tingkat_keaktifan" class="form-select">
    <?php foreach (['Sangat Aktif', 'Aktif', 'Cukup', 'Kurang'] as $k): ?><option <?= ($ed['tingkat_keaktifan'] ?? '') == $k ? 'selected' : '' ?>><?= $k ?></option><?php endforeach; ?>
   </select></div>
   <div class="mb-2"><label class="form-label">Nilai (0 - 100)</label><input type="number" name="nilai_angka" class="form-control" min="0" max="100" step="0.01" required value="<?= e($ed['nilai_angka'] ?? '') ?>"></div>
   <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="3"><?= e($ed['keterangan'] ?? '') ?></textarea></div>
   <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
   <?php if ($ed): ?><a href="nilai.php" class="btn btn-light">Batal</a><?php endif; ?>
  </form></div></div></div>
 <div class="col-lg-8"><div class="card"><div class="card-body"><div class="table-responsive"><table class="table align-middle">
  <thead><tr><th>Siswa</th><th>Ekskul</th><th>Keaktifan</th><th>Nilai</th><th class="text-end">Aksi</th></tr></thead><tbody>
  <?php foreach ($rows as $r): ?>
   <tr><td><b><?= e($r['nama_siswa']) ?></b><br><small class="text-muted"><?= e($r['kelas']) ?></small></td>
    <td><?= e($r['nama_ekskul']) ?><br><small class="text-muted"><?= e($r['pembina']) ?></small></td>
    <td><?= badge($r['tingkat_keaktifan']) ?><br><small class="text-muted"><?= e($r['keterangan']) ?></small></td>
    <td><b><?= e($r['nilai_angka']) ?></b> <span class="badge text-bg-light"><?= predikat($r['nilai_angka']) ?></span></td>
    <td class="text-end text-nowrap"><a href="?edit=<?= $r['id_nilai'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
     <form method="post" class="d-inline" onsubmit="return confirm('Hapus penilaian ini?')"><input type="hidden" name="hapus" value="<?= $r['id_nilai'] ?>">
      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
  <?php endforeach; if (!$rows): ?><tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr><?php endif; ?>
 </tbody></table></div></div></div></div>
</div>
<?php require 'footer.php'; ?>
