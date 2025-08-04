<?php
session_start();
if (isset($_SESSION['log']) && isset($_SESSION['role'])) {
    header('Location: user/dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Selamat Datang | Sistem Absensi</title>
    <link rel="stylesheet" href="assets/css/style-index.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>Sistem Absensi</h1>
            <p>Silakan login untuk memulai absensi Anda.</p>
            <a href="auth/login.php" class="btn">🔐 Login</a>
        </div>
    </div>
</body>

</html>