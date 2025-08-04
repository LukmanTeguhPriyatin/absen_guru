<?php
session_start();
include '../db/db.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Ambil semua data guru
$gurus = mysqli_query($conn, "SELECT * FROM guru");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Sakura Nihongo</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<h1>Admin Panel</h1>
<table border="1">
<tr><th>Nama</th><th>Gaji/Jam</th><th>Aksi</th></tr>
<?php while ($g = mysqli_fetch_assoc($gurus)) {
    echo "<tr><td>{$g['nama']}</td><td>{$g['gaji_per_jam']}</td><td>
    <a href='export_pdf.php?id={$g['id']}'>Export PDF</a> | 
    <a href='reset_gaji.php?id={$g['id']}'>Reset Gaji</a></td></tr>";
} ?>
</table>
</body>
</html>
