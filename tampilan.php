<?php error_reporting(0) ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pantauan Keluhan Sosial - Purbalingga</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="tampilan.css">
</head>
<body>
  <div class="container py-4">
    <h2 class="text-center mb-4">
      <i class="fa-solid fa-comments text-success"></i> Pantauan Keluhan Masyarakat
    </h2>

    <!-- 🔍 Filter dan Search -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-4 mb-2">
        <label class="fw-semibold mb-1">Pilih Kategori:</label>
        <select id="categorySelect" class="form-select" onchange="goToCategory(this.value)">
          <option value="semua">Semua kategori</option>
          <option value="infrastruktur">Infrastruktur</option>
          <option value="pelayananpublik">Pelayanan Publik</option>
          <option value="lingkungan">Lingkungan</option>
          <option value="ketertiban">Ketertiban</option>
          <option value="pendidikan">Pendidikan</option>
        </select>
      </div>

      <div class="col-md-5 mb-2">
        <label class="fw-semibold mb-1">Cari Aduan:</label>
        <div class="input-group">
          <span class="input-group-text bg-success text-white"><i class="fa-solid fa-search"></i></span>
          <input type="text" id="searchInput" class="form-control" placeholder="Ketik untuk mencari aduan...">
        </div>
      </div>

      <div class="col-md-3 mt-3 mt-md-0 text-end">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
          <i class="fa-solid fa-plus"></i> Tambah Aduan
        </button>
      </div>
    </div>

    <?php require_once "route.php" ?>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" id="saveStatusBtn" class="btn btn-warning text-dark">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="tampilan.js"></script> -->
   <script>
function goToCategory(value) {
  if (value === "semua") {
    window.location.href = "./?p=semua" ;
  } else {
    window.location.href = "./?p=" + value;
  }
}
</script>
</body>
</html>
