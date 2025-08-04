<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id'])) {
    header("Location: login.php"); // arahkan ke login
    exit();
}

$id = intval($_SESSION['id']); // pastikan $id berupa integer
$nama = htmlspecialchars($_SESSION['nama']); // hindari XSS

// Proses absen jika tombol ditekan
if (isset($_POST['absen'])) {
    $tanggal = date('Y-m-d');
    $jumlah_jam = 1;
    $gaji_per_jam = 50000;

    // Cek apakah sudah absen hari ini
    $cek = mysqli_query($conn, "SELECT * FROM absensi WHERE guru_id='$id' AND tanggal='$tanggal'");
    if (mysqli_num_rows($cek) == 0) {
        mysqli_query($conn, "INSERT INTO absensi (guru_id, tanggal, jumlah_jam, gaji_per_jam)
                             VALUES ('$id', '$tanggal', '$jumlah_jam', '$gaji_per_jam')");
        $pesan = "Absen berhasil!";
    } else {
        $pesan = "Anda sudah absen hari ini.";
    }
}

// Hitung total gaji
$query = mysqli_query($conn, "SELECT SUM(jumlah_jam * gaji_per_jam) AS total FROM absensi WHERE guru_id='$id'");
$data = mysqli_fetch_assoc($query);
$total = $data['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Guru - Sakura Nihongo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background-color: #f8f9fa; }
    .logo-img { height: 40px; }
    .page-title { margin-top: 2rem; }
    footer { margin-top: 4rem; padding: 1rem 0; background: #e9ecef; text-align: center; }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="img/sanigooke2.png" class="logo-img me-2" alt="Sanigo" />
      <span>Sakura Nihongo</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="guru/absen.php">Absen</a></li>
        <li class="nav-item"><a class="nav-link" href="guru/gaji.php">Total Gaji</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- DASHBOARD CONTENT -->
<div class="container page-title">
  <h2>Halo Selamat Datang Kembali, <?php echo $nama; ?>!</h2>

  <?php if (isset($pesan)): ?>
    <div class="alert alert-info mt-3"><?php echo $pesan; ?></div>
  <?php endif; ?>

  <div class="alert alert-success mt-3">
    Total Gaji Anda: <strong>Rp<?php echo number_format($total, 0, ',', '.'); ?></strong>
  </div>

  <form method="post">
    <button type="submit" name="absen" class="btn btn-primary">Absen Sekarang</button>
  </form>
</div>

<!-- FOOTER -->
<footer>
  &copy; <?php echo date('Y'); ?> Sakura Nihongo. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
