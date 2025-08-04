<?php
include '../koneksi.php';

if (!isset($_GET['guru_id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$guru_id = $_GET['guru_id'];

// Ambil data guru & total gaji
$query = "
    SELECT g.nama, g.rekening, 
           SUM(a.jumlah_jam) AS total_jam, 
           a.gaji_per_jam, 
           SUM(a.jumlah_jam * a.gaji_per_jam) AS total_gaji
    FROM absensi a
    JOIN guru g ON a.guru_id = g.id
    WHERE g.id = '$guru_id'
    GROUP BY g.id, a.gaji_per_jam
";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Detail absensi
$detail_result = mysqli_query($conn, "
    SELECT tanggal, kelas, jumlah_jam 
    FROM absensi 
    WHERE guru_id = '$guru_id'
    ORDER BY tanggal ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Slip Gaji Guru</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }
    .slip {
      background: white;
      max-width: 700px;
      margin: auto;
      padding: 25px 35px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.15);
    }
    .logo {
      text-align: center;
      margin-bottom: 15px;
    }
    .logo img {
      height: 60px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    .info p {
      margin: 6px 0;
      font-size: 16px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 25px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px 12px;
    }
    th {
      background-color: #f8f8f8;
    }
    td:last-child, th:last-child {
      text-align: right;
    }
    .total {
      font-size: 18px;
      font-weight: bold;
      text-align: right;
      margin-top: 20px;
    }
  </style>
</head>
<body>

<div class="slip">
  <div class="logo">
    <img src="../img/sanigooke2.png" alt="Logo">
  </div>

  <h2>SLIP GAJI GURU</h2>

  <?php if ($data): ?>
  <div class="info">
    <p><strong>Nama Guru:</strong> <?= htmlspecialchars($data['nama']) ?></p>
    <p><strong>No. Rekening:</strong> <?= htmlspecialchars($data['rekening']) ?></p>
    <p><strong>Gaji per Jam:</strong> Rp <?= number_format($data['gaji_per_jam'], 0, ',', '.') ?></p>
    <p><strong>Total Jam Mengajar:</strong> <?= $data['total_jam'] ?> jam</p>
  </div>

  <h3>Rincian Absensi</h3>
  <table>
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Kelas</th>
        <th>Jumlah Jam</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($detail_result)): ?>
      <tr>
        <td><?= $row['tanggal'] ?></td>
        <td><?= htmlspecialchars($row['kelas']) ?></td>
        <td style="text-align: right;"><?= $row['jumlah_jam'] ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <p class="total">Total Gaji: Rp <?= number_format($data['total_gaji'], 0, ',', '.') ?></p>

  <?php else: ?>
    <p class="text-danger">Data tidak ditemukan.</p>
  <?php endif; ?>
</div>

</body>
</html>
