<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id'])) {
  header("Location: ../login.php");
  exit();
}

$id = $_SESSION['id'];
$nama = $_SESSION['nama'];

// Ambil total gaji
$query = mysqli_query($conn, "SELECT SUM(jumlah_jam * gaji_per_jam) as total FROM absensi WHERE guru_id='$id'");
$data = mysqli_fetch_assoc($query);
$total = $data['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Sakura Nihongo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .logo-img { height: 40px; }
    .page-title { margin-top: 2rem; }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="../img/sanigooke2.png" class="logo-img me-2" alt="Sanigo">
      <span>Sakura Nihongo</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="absen.php">Absen</a></li>
        <li class="nav-item"><a class="nav-link" href="gaji.php">Total Gaji</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- DASHBOARD CONTENT -->
<div class="container page-title">
  <h2>Halo, <?php echo htmlspecialchars($nama); ?>!</h2>

  <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses'): ?>
    <div class="alert alert-success mt-3">Absen berhasil disimpan!</div>
  <?php endif; ?>

  <div class="alert alert-info mt-3">
    Total Gaji Anda: <strong>Rp<?php echo number_format($total); ?></strong>
  </div>

  <!-- Form Absen -->
  <div class="card mt-4">
    <div class="card-header bg-primary text-white">Form Absen Hari Ini</div>
    <div class="card-body">
      <form method="POST" action="proses_absen.php">
        <div class="mb-3">
          <label for="tanggal" class="form-label">Tanggal</label>
          <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="kelas" class="form-label">Kelas</label>
          <input type="text" name="kelas" id="kelas" class="form-control" placeholder="Misal: N5, A2" required>
        </div>
        <div class="mb-3">
          <label for="jumlah_jam" class="form-label">Jumlah Jam</label>
          <input type="number" name="jumlah_jam" id="jumlah_jam" class="form-control" min="0.5" step="0.5" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan Absen</button>
      </form>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="text-center mt-5 p-3 bg-light">
  <small>&copy; 2025 Sakura Nihongo - Absensi Guru</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
