<?php
session_start();
include '../koneksi.php'; // koneksi di luar folder admin, pakai $conn

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    // Validasi sederhana
    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi!";
    } else {
        // Cari admin berdasarkan email
        $query = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email'");
        if ($row = mysqli_fetch_assoc($query)) {
            if (password_verify($password, $row['password'])) {
                // Simpan ke session
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_nama'] = $row['nama'];
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Email tidak ditemukan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin - Sakura Nihongo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('halamanlogin.png') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 0;
      height: 100vh;
    }
    .overlay {
      background: rgba(255, 255, 255, 0.85);
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-6 col-lg-4 overlay">
      <h4 class="text-center mb-4">Login Admin</h4>

      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
      <?php if (isset($_GET['status']) && $_GET['status'] === "success") echo "<div class='alert alert-success'>Pendaftaran berhasil, silakan login!</div>"; ?>

      <form method="post">
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button name="login" class="btn btn-primary w-100">Login</button>
      </form>

      <p class="mt-3 text-center">
        <a href="lupa_password_admin.php">Lupa password?</a>
      </p>

      <p class="text-center">
        Belum punya akun? <a href="admin_register.php">Daftar di sini</a>
      </p>
    </div>
  </div>
</body>
</html>
