<!-- lupa_password.php -->
<?php
include 'koneksi.php';

if (isset($_POST['kirim'])) {
    $email = $_POST['email'];
    $cek = mysqli_query($conn, "SELECT * FROM guru WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        // Redirect ke halaman reset password
        header("Location: reset_password.php?email=$email");
    } else {
        echo "Email tidak ditemukan!";
    }
}
?>

<h3>Lupa Password</h3>
<form method="post">
  <input type="email" name="email" placeholder="Masukkan email Anda" required><br>
  <button name="kirim">Kirim</button>
</form>
