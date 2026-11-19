<?php
require 'config.php'; $title = 'Data Ekskul';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['hapus'])) {
            $pdo->prepare("DELETE FROM ekskul WHERE id_ekskul=?")->execute([$_POST['hapus']]);
            flash('Ekskul dihapus.');
        } else {
            $d = [trim($_POST['nama_ekskul']), trim($_POST['pembina']), trim($_POST['hari_kegiatan'])];
            if ($_POST['id'] !== '') {
                $pdo->prepare("UPDATE ekskul SET nama_ekskul=?, pembina=?, hari_kegiatan=? WHERE id_ekskul=?")->execute([...$d, $_POST['id']]);
                flash('Ekskul diperbarui.');
            } else {
                $pdo->prepare("INSERT INTO ekskul (nama_ekskul, pembina, hari_kegiatan) VALUES (?,?,?)")->execute($d);
                flash('Ekskul berhasil ditambahkan.');
            }
        }
    } catch (PDOException $x) { flash(errmsg($x), 'danger'); }
    header('Location: ekskul.php'); exit;
}
$ed = null;
if (isset($_GET['edit'])) { $s = $pdo->prepare("SELECT * FROM ekskul WHERE id_ekskul=?"); $s->execute([$_GET['edit']]); $ed = $s->fetch(); }
$rows = $pdo->query("SELECT * FROM ekskul ORDER BY nama_ekskul")->fetchAll();
require 'header.php';
?>
<h4 class="fw-bold mb-3">Data Ekstrakurikuler</h4>
<div class="row g-3">
 <div class="col-lg-4"><div class="card"><div class="card-body">
  <h6 class="fw-bold mb-3"><?= $ed ? 'Edit Ekskul' : 'Tambah Ekskul' ?></h6>
  <form method="post">
   <input type="hidden" name="id" value="<?= e($ed['id_ekskul'] ?? '') ?>">
   <div class="mb-2"><label class="form-label">Nama Ekskul</label><input name="nama_ekskul" class="form-control" required maxlength="50" value="<?= e($ed['nama_ekskul'] ?? '') ?>"></div>
   <div class="mb-2"><label class="form-label">Pembina</label><input name="pembina" class="form-control" required maxlength="100" value="<?= e($ed['pembina'] ?? '') ?>"></div>
   <div class="mb-3"><label class="form-label">Hari Kegiatan</label><input name="hari_kegiatan" class="form-control" required maxlength="20" placeholder="Jumat" value="<?= e($ed['hari_kegiatan'] ?? '') ?>"></div>
   <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
   <?php if ($ed): ?><a href="ekskul.php" class="btn btn-light">Batal</a><?php endif; ?>
  </form></div></div></div>
 <div class="col-lg-8"><div class="card"><div class="card-body"><div class="table-responsive"><table class="table align-middle">
  <thead><tr><th>#</th><th>Ekskul</th><th>Pembina</th><th>Hari</th><th class="text-end">Aksi</th></tr></thead><tbody>
  <?php foreach ($rows as $r): ?>
   <tr><td><?= $r['id_ekskul'] ?></td><td><b><?= e($r['nama_ekskul']) ?></b></td><td><?= e($r['pembina']) ?></td><td><?= e($r['hari_kegiatan']) ?></td>
    <td class="text-end text-nowrap"><a href="?edit=<?= $r['id_ekskul'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
     <form method="post" class="d-inline" onsubmit="return confirm('Hapus ekskul ini?')"><input type="hidden" name="hapus" value="<?= $r['id_ekskul'] ?>">
      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
  <?php endforeach; ?>
 </tbody></table></div></div></div></div>
</div>
<?php require 'footer.php'; ?>
