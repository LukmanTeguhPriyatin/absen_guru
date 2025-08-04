<?php
session_start();

// Simpan role sebelum hancurkan session
$redirect = 'index.php'; // Default redirect

if (isset($_SESSION['admin_id'])) {
    $redirect = 'admin/login_admin.php'; // Ini OK karena posisi file di luar /admin
} elseif (isset($_SESSION['guru_id'])) {
    $redirect = 'login.php';
}

// Hapus session
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

header("Location: $redirect");
exit;
