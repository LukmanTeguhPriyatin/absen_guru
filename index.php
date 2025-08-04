<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$guru_id = $_SESSION['id'];
$nama = $_SESSION['nama'];

// Ambil data guru (termasuk gaji per jam)
$query_guru = mysqli_query($conn, "SELECT * FROM guru WHERE id='$guru_id'");
$guru = mysqli_fetch_assoc($query_guru);
$gaji_per_jam = $guru['gaji_per_jam'] ?? 50000;

// Hitung total gaji dari absensi
$result = mysqli_query($conn, "SELECT * FROM absensi WHERE guru_id='$guru_id'");
$total = 0;
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $total += $gaji_per_jam * $row['jumlah_jam'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Sakura Nihongo</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
    header { background: #fff; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
    main { padding: 20px; }
    h1, h2 { color: #333; }
  </style>
</head>
<body>
  <header>
    <img src="img/sakura_logo.png" alt="Sakura Nihongo" height="60">
    <h1>Selamat Datang di Sakura Nihongo, <?php echo htmlspecialchars($nama); ?>!</h1>
    <nav>
      <a href="guru/absen.php">Absen</a>
      <a href="guru/gaji.php">Total Gaji</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>
  <main>
    <h2>Rekap Gaji</h2>
    <p>Total Gaji Anda: <strong>Rp<?php echo number_format($total); ?></strong></p>
  </main>
</body>
</html>
