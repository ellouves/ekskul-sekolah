<?php
require 'config.php'; $title = 'Data Siswa';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['hapus'])) {
            $pdo->prepare("DELETE FROM siswa WHERE nis=?")->execute([$_POST['hapus']]);
            flash('Data siswa dihapus.');
        } else {
            $d = [trim($_POST['nis']), trim($_POST['nama_siswa']), trim($_POST['kelas']), $_POST['jenis_kelamin']];
            if ($_POST['lama'] !== '') {
                $pdo->prepare("UPDATE siswa SET nis=?, nama_siswa=?, kelas=?, jenis_kelamin=? WHERE nis=?")->execute([...$d, $_POST['lama']]);
                flash('Data siswa diperbarui.');
            } else {
                $pdo->prepare("INSERT INTO siswa (nis, nama_siswa, kelas, jenis_kelamin) VALUES (?,?,?,?)")->execute($d);
                flash('Siswa berhasil ditambahkan.');
            }
        }
    } catch (PDOException $x) { flash(errmsg($x), 'danger'); }
    header('Location: siswa.php'); exit;
}
$ed = null;
if (isset($_GET['edit'])) { $s = $pdo->prepare("SELECT * FROM siswa WHERE nis=?"); $s->execute([$_GET['edit']]); $ed = $s->fetch(); }
$rows = $pdo->query("SELECT * FROM siswa ORDER BY nama_siswa")->fetchAll();
require 'header.php';
?>
<h4 class="fw-bold mb-3">Data Siswa</h4>
<div class="row g-3">
 <div class="col-lg-4"><div class="card"><div class="card-body">
  <h6 class="fw-bold mb-3"><?= $ed ? 'Edit Siswa' : 'Tambah Siswa' ?></h6>
  <form method="post">
   <input type="hidden" name="lama" value="<?= e($ed['nis'] ?? '') ?>">
   <div class="mb-2"><label class="form-label">NIS</label><input name="nis" class="form-control" required maxlength="20" value="<?= e($ed['nis'] ?? '') ?>"></div>
   <div class="mb-2"><label class="form-label">Nama Siswa</label><input name="nama_siswa" class="form-control" required maxlength="100" value="<?= e($ed['nama_siswa'] ?? '') ?>"></div>
   <div class="mb-2"><label class="form-label">Kelas</label><input name="kelas" class="form-control" required maxlength="20" placeholder="X RPL" value="<?= e($ed['kelas'] ?? '') ?>"></div>
   <div class="mb-3"><label class="form-label">Jenis Kelamin</label><select name="jenis_kelamin" class="form-select">
     <option value="L" <?= ($ed['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
     <option value="P" <?= ($ed['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' ?>>Perempuan</option></select></div>
   <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
   <?php if ($ed): ?><a href="siswa.php" class="btn btn-light">Batal</a><?php endif; ?>
  </form></div></div></div>
 <div class="col-lg-8"><div class="card"><div class="card-body"><div class="table-responsive"><table class="table align-middle">
  <thead><tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>JK</th><th class="text-end">Aksi</th></tr></thead><tbody>
  <?php foreach ($rows as $r): ?>
   <tr><td><?= e($r['nis']) ?></td><td><?= e($r['nama_siswa']) ?></td><td><?= e($r['kelas']) ?></td><td><?= e($r['jenis_kelamin']) ?></td>
    <td class="text-end text-nowrap"><a href="?edit=<?= urlencode($r['nis']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
     <form method="post" class="d-inline" onsubmit="return confirm('Hapus siswa ini?')"><input type="hidden" name="hapus" value="<?= e($r['nis']) ?>">
      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
  <?php endforeach; ?>
 </tbody></table></div></div></div></div>
</div>
<?php require 'footer.php'; ?>
