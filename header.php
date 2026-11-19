<?php
$nav = ['index.php' => ['Dashboard', 'bi-speedometer2'], 'siswa.php' => ['Siswa', 'bi-people'],
        'ekskul.php' => ['Ekskul', 'bi-trophy'], 'nilai.php' => ['Penilaian', 'bi-clipboard-check']];
$cur = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="id"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> - SiEkskul</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
 body{background:#f4f6fb}
 .navbar{background:linear-gradient(90deg,#4f46e5,#7c3aed)}
 .nav-link.active{background:rgba(255,255,255,.2);border-radius:8px}
 .card{border:0;border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,.06)}
 .stat{color:#fff;border-radius:14px;padding:20px}
 .stat i{font-size:2rem;opacity:.8}
 .table thead th{background:#eef0ff;border:0}
</style></head>
<body>
<nav class="navbar navbar-expand-md navbar-dark mb-4 shadow-sm"><div class="container">
  <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-mortarboard-fill me-2"></i>SiEkskul</a>
  <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#m"><span class="navbar-toggler-icon"></span></button>
  <div class="collapse navbar-collapse" id="m"><ul class="navbar-nav ms-auto gap-1">
    <?php foreach ($nav as $f => [$n, $i]): ?>
      <li class="nav-item"><a class="nav-link <?= $cur == $f ? 'active' : '' ?>" href="<?= $f ?>"><i class="bi <?= $i ?> me-1"></i><?= $n ?></a></li>
    <?php endforeach; ?>
  </ul></div>
</div></nav>
<div class="container pb-5">
<?php if (!empty($_SESSION['flash'])): [$m, $t] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
  <div class="alert alert-<?= $t ?> alert-dismissible fade show"><?= e($m) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
