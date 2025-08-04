<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

$admin_nama = $_SESSION['admin_nama'];

// Reset semua gaji
if (isset($_POST['reset_gaji'])) {
    mysqli_query($conn, "DELETE FROM absensi");
    $pesan_reset = "✅ Semua data gaji berhasil direset.";
}

// Gaji tertinggi
$query_max = mysqli_query($conn, "
    SELECT g.nama, SUM(a.jumlah_jam * a.gaji_per_jam) AS total_gaji
    FROM absensi a
    JOIN guru g ON a.guru_id = g.id
    GROUP BY g.id
    ORDER BY total_gaji DESC
    LIMIT 1
");
$guru_max = mysqli_fetch_assoc($query_max);

// Gaji terendah
$query_min = mysqli_query($conn, "
    SELECT g.nama, SUM(a.jumlah_jam * a.gaji_per_jam) AS total_gaji
    FROM absensi a
    JOIN guru g ON a.guru_id = g.id
    GROUP BY g.id
    ORDER BY total_gaji ASC
    LIMIT 1
");
$guru_min = mysqli_fetch_assoc($query_min);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Sakura Nihongo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f8ff;
      font-family: 'Segoe UI', sans-serif;
    }
    .logo {
      height: 50px;
      margin-right: 15px;
    }
    .card a {
      text-decoration: none;
      color: white;
    }
    .card:hover {
      transform: scale(1.02);
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="../img/sanigooke2.png" class="logo" alt="Logo">
      <strong>Sakura Nihongo Admin</strong>
    </a>
    <span class="navbar-text text-white ms-auto">
      Halo, <?= htmlspecialchars($admin_nama); ?>
    </span>
  </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container mt-5">
  <h3 class="mb-4">Dashboard Admin</h3>

  <?php if (isset($pesan_reset)): ?>
    <div class="alert alert-success"><?= $pesan_reset ?></div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card bg-primary text-white shadow p-4 text-center">
        <h4>Lihat & Rekap Gaji Guru</h4>
        <a href="#daftar-guru" class="btn btn-light mt-3">Buka</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-info text-white shadow p-4 text-center">
        <h4>Reset Password Guru</h4>
        <a href="reset_password.php" class="btn btn-light mt-3">Buka</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-danger text-white shadow p-4 text-center">
        <h4>Logout</h4>
        <a href="../logout.php" class="btn btn-light mt-3">Keluar</a>
      </div>
    </div>
  </div>

  <!-- Gaji Terbesar & Terkecil -->
  <div class="row mt-5">
    <div class="col-md-6">
      <div class="card bg-success text-white shadow p-3">
        <h5 class="text-center">📈 Gaji Tertinggi</h5>
        <?php if ($guru_max): ?>
          <p class="text-center mb-0"><?= $guru_max['nama'] ?> <br><strong>Rp<?= number_format($guru_max['total_gaji'], 0, ',', '.') ?></strong></p>
        <?php else: ?>
          <p class="text-center mb-0">Belum ada data gaji.</p>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card bg-warning text-dark shadow p-3">
        <h5 class="text-center">📉 Gaji Terendah</h5>
        <?php if ($guru_min): ?>
          <p class="text-center mb-0"><?= $guru_min['nama'] ?> <br><strong>Rp<?= number_format($guru_min['total_gaji'], 0, ',', '.') ?></strong></p>
        <?php else: ?>
          <p class="text-center mb-0">Belum ada data gaji.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Reset Semua Gaji -->
  <div class="text-center mt-5">
    <form method="post" onsubmit="return confirm('Yakin ingin mereset semua rekap gaji?');">
      <button type="submit" name="reset_gaji" class="btn btn-outline-danger">Reset Semua Gaji</button>
    </form>
  </div>

  <!-- Daftar Guru dan Aksi -->
  <div class="mt-5" id="daftar-guru">
    <h4 class="mb-3">Daftar Guru</h4>
    <table class="table table-bordered table-hover">
      <thead class="table-light">
        <tr>
          <th>Nama</th>
          <th>Rekening</th>
          <th style="width: 300px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $guru_query = mysqli_query($conn, "SELECT * FROM guru");
        while ($guru = mysqli_fetch_assoc($guru_query)):
        ?>
          <tr>
            <td><?= htmlspecialchars($guru['nama']) ?></td>
            <td><?= htmlspecialchars($guru['rekening']) ?></td>
            <td>
              <a href="lihat_gaji.php?guru_id=<?= $guru['id'] ?>" class="btn btn-sm btn-success">Lihat Gaji</a>
              <a href="cetak_pdf.php?guru_id=<?= $guru['id'] ?>" class="btn btn-sm btn-secondary" target="_blank">Download PDF</a>
              <a href="edit_guru.php?id=<?= $guru['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="reset_gaji.php?guru_id=<?= $guru['id'] ?>"
                 onclick="return confirm('Yakin ingin reset gaji guru ini?')"
                 class="btn btn-sm btn-danger">Reset Gaji</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- FOOTER -->
<footer class="text-center mt-5 p-3 bg-light">
  <small>&copy; 2025 Sakura Nihongo - Admin Sistem</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
