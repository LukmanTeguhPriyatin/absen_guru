<?php
session_start();
include '../koneksi.php'; // koneksi.php ada di luar folder admin

if (isset($_POST['register'])) {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // Validasi password
    if ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah email sudah terdaftar
        $cek = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Email sudah digunakan!";
        } else {
            // Simpan ke database
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $simpan = mysqli_query($conn, "INSERT INTO admin (nama, email, password) VALUES ('$nama', '$email', '$hash')");
            if ($simpan) {
                header("Location: admin_login.php?status=success");
                exit();
            } else {
                $error = "Gagal mendaftar. Silakan coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register Admin - Sakura Nihongo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .register-box {
      background: white;
      padding: 30px;
      margin-top: 80px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="register-box col-md-6 col-lg-4">
      <h4 class="text-center mb-4">Registrasi Admin</h4>

      <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

      <form method="post">
        <div class="mb-3">
          <label>Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Konfirmasi Password</label>
          <input type="password" name="konfirmasi" class="form-control" required>
        </div>
        <button name="register" class="btn btn-primary w-100">Daftar Sekarang</button>
      </form>
      <p class="mt-3 text-center">
        Sudah punya akun? <a href="admin_login.php">Login di sini</a>
      </p>
    </div>
  </div>
</body>
</html>
