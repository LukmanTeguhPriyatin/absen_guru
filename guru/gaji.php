<?php
session_start();
include '../koneksi.php'; // karena koneksi.php ada di luar folder guru

if (!isset($_SESSION['id'])) {
  header("Location: ../login.php");
  exit();
}

$id = $_SESSION['id'];
$nama = $_SESSION['nama'];

// Ambil total gaji dari database
$query = mysqli_query($conn, "SELECT SUM(jumlah_jam * gaji_per_jam) as total FROM absensi WHERE guru_id='$id'");
$data = mysqli_fetch_assoc($query);
$total = $data['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Total Gaji - Sakura Nihongo</title>
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
      <img src="../img/sanigooke2.png" class="logo-img me-2" alt="Sanigo"> <!-- ✅ perbaiki path gambar -->
      <span>Sakura Nihongo</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="../login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="../register.php">Register</a></li>
        <li class="nav-item"><a class="nav-link" href="absen.php">Absen</a></li>
        <li class="nav-item"><a class="nav-link active fw-bold" href="gaji.php">Total Gaji</a></li>
        <li class="nav-item"><a class="nav-link" href="../admin/index.php">Admin Panel</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="../logout.php">Logout</a></li> <!-- ✅ perbaiki path logout -->
      </ul>
    </div>
  </div>
</nav>

<!-- GAJI CONTENT -->
<div class="container page-title">
  <h2>Halo, <?php echo htmlspecialchars($nama); ?>!</h2>
  <div class="alert alert-info mt-3">
    Total Gaji Anda Saat Ini: <strong>Rp<?php echo number_format($total); ?></strong>
  </div>

  <div class="mt-4">
    <a href="absen.php" class="btn btn-outline-primary">Kembali ke Absen</a>
  </div>
</div>

<!-- FOOTER -->
<footer class="text-center mt-5 p-3 bg-light">
  <small>&copy; 2025 Sakura Nihongo - Absensi Guru</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
