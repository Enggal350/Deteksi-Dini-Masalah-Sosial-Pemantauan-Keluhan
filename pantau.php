<?php
// pantau.php
$jsonFile = __DIR__ . '/hasil_aduan.json';

// Baca data JSON
$data = [];
if (file_exists($jsonFile)) {
    $raw = file_get_contents($jsonFile);
    $data = json_decode($raw, true);
    if (!is_array($data)) $data = [];
}

$filterKategori = $_GET['kategori'] ?? '';
$search = $_GET['search'] ?? '';

$kategoriList = array_values(array_unique(array_column($data, 'kategori')));
$totalAduan = count($data);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pansosan - Pantauan Aduan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { font-family:'Poppins',sans-serif; background:#f8fff8; }
    .navbar { background:linear-gradient(135deg,#28a745,#20c997); }
    .navbar-brand { color:#fff !important; font-weight:bold; }
    .hero { background:linear-gradient(135deg,#28a745cc,#20c997cc); color:#fff; text-align:center; padding:60px 0; }
    .aduan-card { background:#fff; border-left:5px solid #28a745; border-radius:10px; padding:15px; margin-bottom:15px; box-shadow:0 2px 10px rgba(0,0,0,0.08); }
    footer { background:#212529; color:#fff; padding:15px; text-align:center; margin-top:40px; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#">Pansosan - Pantauan Aduan</a>
  </div>
</nav>

<section class="hero">
  <div class="container">
    <h1 class="fw-bold">Pantauan Aduan Masyarakat</h1>
    <p>Total Aduan: <?= $totalAduan ?></p>
  </div>
</section>

<div class="container my-5">
  <!-- Filter -->
  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
      <select name="kategori" class="form-select" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        <?php foreach ($kategoriList as $kat): ?>
          <option value="<?= htmlspecialchars($kat) ?>" <?= $kat == $filterKategori ? 'selected' : '' ?>><?= htmlspecialchars($kat) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-6">
      <input type="text" name="search" class="form-control" placeholder="Cari teks aduan..." value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-2">
      <button class="btn btn-success w-100"><i class="fa-solid fa-search"></i> Cari</button>
    </div>
  </form>

  <!-- Daftar Aduan -->
  <?php
  $filtered = array_filter($data, function($item) use ($filterKategori, $search) {
    $matchKat = !$filterKategori || ($item['kategori'] ?? '') === $filterKategori;
    $matchSearch = !$search || stripos($item['text'], $search) !== false;
    return $matchKat && $matchSearch;
  });
  foreach ($filtered as $idx => $item): ?>
    <div class="aduan-card">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-success mb-1"><?= htmlspecialchars($item['kategori'] ?? '-') ?></h6>
          <p class="mb-1"><?= htmlspecialchars($item['text']) ?></p>
          <small class="text-muted"><?= htmlspecialchars($item['nama'] ?? 'Anonim') ?> - <?= htmlspecialchars($item['tanggal'] ?? '-') ?></small>
        </div>
        <button class="btn btn-outline-success btn-sm editBtn"
                data-idx="<?= $idx ?>"
                data-text="<?= htmlspecialchars($item['text'], ENT_QUOTES) ?>">
          <i class="fa-solid fa-pen"></i>
        </button>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="text-end mt-4">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Tambah Aduan</button>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="editForm" class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Aduan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <textarea id="editText" class="form-control" rows="5" required></textarea>
        <input type="hidden" id="editIndex">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="addForm" class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="fa-solid fa-plus me-2"></i>Tambah Aduan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input id="addNama" class="form-control mb-2" placeholder="Nama">
        <select id="addKategori" class="form-select mb-2">
          <?php foreach ($kategoriList as $kat): ?>
            <option value="<?= htmlspecialchars($kat) ?>"><?= htmlspecialchars($kat) ?></option>
          <?php endforeach; ?>
          <option value="Lainnya">Lainnya</option>
        </select>
        <textarea id="addText" class="form-control" rows="4" placeholder="Tulis aduan..." required></textarea>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>
      <?php
      require_once "route.php";
      ?>

<footer>&copy; <?= date('Y') ?> Pansosan - Sistem Aduan</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.editBtn').forEach(btn=>{
  btn.addEventListener('click',()=>{
    document.getElementById('editIndex').value = btn.dataset.idx;
    document.getElementById('editText').value = btn.dataset.text;
    new bootstrap.Modal(document.getElementById('editModal')).show();
  });
});

// Simpan hasil edit
document.getElementById('editForm').addEventListener('submit',e=>{
  e.preventDefault();
  const idx = document.getElementById('editIndex').value;
  const text = document.getElementById('editText').value.trim();
  const fd = new FormData();
  fd.append('action','update');
  fd.append('index',idx);
  fd.append('text',text);
  fetch('aduan.php',{method:'POST',body:fd})
    .then(r=>r.json()).then(res=>{
      if(res.success) location.reload();
      else alert(res.message);
    });
});

// Tambah aduan baru
document.getElementById('addForm').addEventListener('submit',e=>{
  e.preventDefault();
  const fd = new FormData();
  fd.append('action','create');
  fd.append('nama',document.getElementById('addNama').value);
  fd.append('kategori',document.getElementById('addKategori').value);
  fd.append('text',document.getElementById('addText').value);
  fetch('aduan.php',{method:'POST',body:fd})
    .then(r=>r.json()).then(res=>{
      if(res.success) location.reload();
      else alert(res.message);
    });
});
</script>
</body>
</html>
