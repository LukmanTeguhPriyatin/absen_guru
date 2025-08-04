<!-- reset_password.php -->
<?php
include 'koneksi.php';

if (!isset($_GET['email'])) {
    echo "Email tidak valid!";
    exit;
}

$email = $_GET['email'];

if (isset($_POST['reset'])) {
    $pass1 = $_POST['password1'];
    $pass2 = $_POST['password2'];

    if ($pass1 != $pass2) {
        echo "Password tidak cocok!";
    } else {
        $password_baru = password_hash($pass1, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE guru SET password='$password_baru' WHERE email='$email'");
        echo "Password berhasil direset. <a href='login.php'>Login di sini</a>";
    }
}
?>

<h3>Reset Password untuk: <?= htmlspecialchars($email) ?></h3>
<form method="post">
  <input type="password" name="password1" placeholder="Password baru" required><br>
  <input type="password" name="password2" placeholder="Ulangi password" required><br>
  <button name="reset">Reset Password</button>
</form>
