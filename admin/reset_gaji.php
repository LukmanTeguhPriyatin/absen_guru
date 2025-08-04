<?php
include '../koneksi.php';

if (isset($_GET['guru_id'])) {
    $guru_id = intval($_GET['guru_id']);

    // GUNAKAN $conn (bukan $koneksi)
    $query = "DELETE FROM absensi WHERE guru_id = $guru_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: lihat_gaji.php?reset_success=1");
        exit();
    } else {
        echo "Gagal mereset gaji.";
    }
} else {
    echo "ID guru tidak ditemukan.";
}
?>
