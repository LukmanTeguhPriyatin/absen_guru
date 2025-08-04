<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $data = mysqli_query($conn, "SELECT * FROM guru WHERE email='$email'");
    if ($row = mysqli_fetch_assoc($data)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Akun tidak ditemukan!";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Guru - Sakura Nihongo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('img/bagrond.png') no-repeat center center fixed;
      background-size: cover;
    }
    .login-box {
      background-color: rgba(255,255,255,0.9);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
      margin-top: 80px;
    }
    .logo {
      width: 120px;
      display: block;
      margin: 0 auto 20px auto;
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="login-box col-md-6 col-lg-4">
      <img src="img/sanigooke2.png" alt="Logo" class="logo">
      <h4 class="text-center mb-4">Login Guru</h4>
      
      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
      <?php endif; ?>
      
      <form method="post">
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button name="login" class="btn btn-primary w-100">Login</button>
      </form>

      <p class="mt-3 text-center">
        <a href="lupa_password.php">Lupa password?</a>
      </p>

      <p class="text-center mt-2">
        Belum punya akun? <a href="register.php">Daftar di sini</a>
      </p>
    </div>
  </div>
</body>
</html>
