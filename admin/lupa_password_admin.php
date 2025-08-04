<?php
session_start();
include '../koneksi.php';

$success = '';
$error = '';

if (isset($_POST['reset'])) {
    $email = trim($_POST['email']);
    $password_baru = trim($_POST['password_baru']);

    // Cek apakah email ada di database admin
    $cek = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE admin SET password='$password_hash' WHERE email='$email'");

        if ($update) {
            $success = "Password berhasil direset. Silakan login kembali.";
        } else {
            $error = "Gagal memperbarui password.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            padding-top: 60px;
        }
        .form-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 form-box">
            <h4 class="text-center mb-4">Reset Password Admin</h4>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label>Email Terdaftar</label>
                    <input type="email" name="email" class="form-control" placeholder="Email admin" required>
                </div>
                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="password" name="password_baru" class="form-control" placeholder="Password baru" required>
                </div>
                <button name="reset" class="btn btn-warning w-100">Reset Password</button>
            </form>

            <p class="mt-3 text-center">
                <a href="login_admin.php">← Kembali ke Login</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
