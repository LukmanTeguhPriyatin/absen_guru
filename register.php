<?php
include 'koneksi.php';
if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $gaji = (int)$_POST['gaji'];
    $rekening = $_POST['rekening'];

    $cek = mysqli_query($conn, "SELECT * FROM guru WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "Email sudah digunakan!";
    } else {
        mysqli_query($conn, "INSERT INTO guru (nama, email, password, gaji_per_jam, rekening) 
                             VALUES ('$nama', '$email', '$password', '$gaji', '$rekening')");
        header("Location: login.php");
    }
}
?>

<!-- FORM REGISTER -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register Guru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h3>Form Registrasi Guru</h3>
  <form method="post" class="mt-3">
    <div class="mb-3">
      <label>Nama</label>
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
      <label>Gaji per Jam (Rp)</label>
      <input type="number" name="gaji" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Nomor Rekening</label>
      <input type="text" name="rekening" class="form-control" required>
    </div>
    <button name="register" class="btn btn-primary">Daftar</button>
  </form>
</div>
</body>
</html>
