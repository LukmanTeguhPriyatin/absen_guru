<?php
include '../koneksi.php';
session_start();

// Cek apakah admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$guru_id = $_GET['id'];

// Proses update data guru
if (isset($_POST['update_guru'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $rekening = mysqli_real_escape_string($conn, $_POST['rekening']);

    mysqli_query($conn, "UPDATE guru SET nama='$nama', rekening='$rekening' WHERE id='$guru_id'");
    $pesan = "✅ Data guru berhasil diperbarui.";
}

// Proses update absensi
if (isset($_POST['update_absen'])) {
    foreach ($_POST['absen_id'] as $key => $absen_id) {
        $tanggal = $_POST['tanggal'][$key];
        $kelas = mysqli_real_escape_string($conn, $_POST['kelas'][$key]);
        $jumlah_jam = floatval($_POST['jumlah_jam'][$key]); // pastikan desimal

        mysqli_query($conn, "UPDATE absensi SET tanggal='$tanggal', kelas='$kelas', jumlah_jam='$jumlah_jam' WHERE id='$absen_id'");
    }
    $pesan = "✅ Data absensi berhasil diperbarui.";
}

// Ambil data guru
$guru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM guru WHERE id='$guru_id'"));

// Ambil data absensi
$absen_result = mysqli_query($conn, "SELECT * FROM absensi WHERE guru_id='$guru_id' ORDER BY tanggal ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Data Guru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h3>Edit Data Guru</h3>

  <?php if (isset($pesan)): ?>
    <div class="alert alert-success"><?= $pesan ?></div>
  <?php endif; ?>

  <!-- Form Edit Nama & Rekening -->
  <form method="post" class="mb-4 p-3 bg-white shadow-sm rounded">
    <h5>Data Pribadi</h5>
    <div class="mb-3">
      <label>Nama</label>
      <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($guru['nama']) ?>" required>
    </div>
    <div class="mb-3">
      <label>No Rekening</label>
      <input type="text" name="rekening" class="form-control" value="<?= htmlspecialchars($guru['rekening']) ?>" required>
    </div>
    <button type="submit" name="update_guru" class="btn btn-primary">Simpan Perubahan</button>
    <a href="admin_dashboard.php" class="btn btn-secondary">Kembali</a>
  </form>

  <!-- Form Edit Absensi -->
  <form method="post" class="p-3 bg-white shadow-sm rounded">
    <h5>Riwayat Absensi</h5>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Tanggal</th>
          <th>Kelas</th>
          <th>Jumlah Jam</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($absen_result)): ?>
          <tr>
            <td>
              <input type="hidden" name="absen_id[]" value="<?= $row['id'] ?>">
              <input type="date" name="tanggal[]" class="form-control" value="<?= $row['tanggal'] ?>">
            </td>
            <td>
              <input type="text" name="kelas[]" class="form-control" value="<?= htmlspecialchars($row['kelas']) ?>">
            </td>
            <td>
              <input type="number" name="jumlah_jam[]" class="form-control" step="0.01" value="<?= $row['jumlah_jam'] ?>">
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <button type="submit" name="update_absen" class="btn btn-success">Simpan Perubahan Absensi</button>
  </form>
</div>
</body>
</html>
