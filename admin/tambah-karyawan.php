<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $koneksi->prepare("INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $username, $password, $role);
    $stmt->execute();

    header("Location: data-karyawan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Karyawan</title>
    <link rel="stylesheet" href="../assets/css/dashboard-admin.css">
</head>

<body>
    <div class="sidebar">
        <h2>📊 Admin</h2>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="data-karyawan.php">👥 Data Karyawan</a>
        <a href="laporan.php">📑 Laporan</a>
    </div>

    <div class="main">
        <div class="navbar">
            <span>Halo, Admin</span>
            <a href="data-karyawan.php" class="logout-btn">Kembali</a>
        </div>

        <div class="content">
            <div class="form-card">
                <h2>Tambah Karyawan</h2>
                <form method="POST">
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="role" required>
                        <option value="karyawan">Karyawan</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>