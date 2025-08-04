<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$guru_id = $_SESSION['id'];
$tanggal = $_POST['tanggal'];
$kelas = $_POST['kelas'];
$jumlah_jam = floatval($_POST['jumlah_jam']); // mendukung jam seperti 1.5

// Ambil tarif gaji dari database
$result = mysqli_query($conn, "SELECT gaji_per_jam FROM guru WHERE id = '$guru_id'");
$data = mysqli_fetch_assoc($result);
$gaji_per_jam = $data['gaji_per_jam'] ?? 50000;

// Simpan ke tabel absensi
$query = "INSERT INTO absensi (guru_id, tanggal, kelas, jumlah_jam, gaji_per_jam) 
          VALUES ('$guru_id', '$tanggal', '$kelas', '$jumlah_jam', '$gaji_per_jam')";

if (mysqli_query($conn, $query)) {
    header("Location: absen.php?pesan=sukses");
    exit();
} else {
    echo "Gagal menyimpan absen: " . mysqli_error($conn);
}
