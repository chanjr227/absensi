<?php
require '../config/db.php';

$id = $_GET['id'];
$result = $koneksi->query("SELECT * FROM users WHERE id=$id");
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $koneksi->prepare("UPDATE users SET nama=?, username=?, password=?, role=? WHERE id=?");
        $stmt->bind_param("ssssi", $nama, $username, $password, $role, $id);
    } else {
        $stmt = $koneksi->prepare("UPDATE users SET nama=?, username=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $nama, $username, $role, $id);
    }
    $stmt->execute();
    header("Location: data-karyawan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Karyawan</title>
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
                <h2>Edit Karyawan</h2>
                <form method="POST">
                    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                    <input type="password" name="password" placeholder="Ganti Password (Opsional)">
                    <select name="role" required>
                        <option value="karyawan" <?= $user['role'] === 'karyawan' ? 'selected' : '' ?>>Karyawan</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>