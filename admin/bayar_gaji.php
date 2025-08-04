<?php
include '../koneksi.php'; // atau sesuaikan dengan lokasi koneksi Anda
date_default_timezone_set("Asia/Jakarta");

if (isset($_GET['nama'])) {
    $nama = $_GET['nama'];

    // Ambil total jam dan gaji/jam guru
    $query = mysqli_query($koneksi, "SELECT SUM(jumlah_jam) AS total_jam, gaji_per_jam 
                                      FROM absensi 
                                      INNER JOIN guru ON absensi.nama = guru.nama 
                                      WHERE absensi.nama = '$nama'");
    $data = mysqli_fetch_assoc($query);
    
    $total_jam = $data['total_jam'];
    $gaji_per_jam = $data['gaji_per_jam'];
    $total_gaji = $total_jam * $gaji_per_jam;
    $tanggal = date('Y-m-d');

    // Simpan ke riwayat pembayaran
    mysqli_query($koneksi, "INSERT INTO riwayat_pembayaran (nama_guru, total_gaji, tanggal_bayar) 
                            VALUES ('$nama', '$total_gaji', '$tanggal')");

    // Hapus data absensi setelah pembayaran
    mysqli_query($koneksi, "DELETE FROM absensi WHERE nama = '$nama'");

    header("Location: lihat_gaji.php");
    exit;
}
?>
