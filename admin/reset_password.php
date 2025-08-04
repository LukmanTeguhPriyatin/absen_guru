<?php
include '../koneksi.php';

$success = '';
$error = '';

if (isset($_POST['reset'])) {
    $email = $_POST['email'];
    $newpass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $update = mysqli_query($conn, "UPDATE guru SET password='$newpass' WHERE email='$email'");
    if ($update && mysqli_affected_rows($conn) > 0) {
        $success = "Password berhasil direset!";
    } else {
        $error = "Gagal reset password! Email tidak ditemukan atau error database.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Reset Password Guru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #84fab0, #8fd3f4);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .reset-box {
      background: #fff;
      padding: 35px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
    }
    h3 {
      color: #0d6efd;
    }
  </style>
</head>
<body>

<div class="reset-box text-center">
  <h3 class="mb-4">Reset Password Guru</h3>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3 text-start">
      <label>Email</label>
      <input type="email" name="email" class="form-control" placeholder="Masukkan email guru" required>
    </div>
    <div class="mb-3 text-start">
      <label>Password Baru</label>
      <input type="password" name="password" class="form-control" placeholder="Masukkan password baru" required>
    </div>
    <button name="reset" class="btn btn-success w-100">Reset Password</button>
  </form>

  <p class="mt-3">
    <a href="../login.php">← Kembali ke Login</a>

</div>

</body>
</html>
