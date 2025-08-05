<?php
require '../auth/session_check.php';
require '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Hitung jumlah user per role
$roles = ['admin', 'dosen', 'mahasiswa', 'karyawan'];
$jumlahPerRole = [];

foreach ($roles as $role) {
    $stmt = $koneksi->prepare("SELECT COUNT(*) AS total FROM users WHERE role = ?");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $jumlahPerRole[$role] = $result['total'];
}

// Filter tanggal
$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$query = "SELECT a.*, u.username, u.role FROM absensi a 
          JOIN users u ON a.user_id = u.id";

if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $query .= " WHERE a.tanggal BETWEEN ? AND ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ss", $tanggal_awal, $tanggal_akhir);
} else {
    $stmt = $koneksi->prepare($query);
}

$stmt->execute();
$dataAbsensi = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
</head>

<body>
    <h1>Dashboard Admin</h1>
    <p>Halo, <?= htmlspecialchars($_SESSION['username']) ?>!</p>

    <div class="card-container">
        <?php foreach ($jumlahPerRole as $role => $jumlah): ?>
            <div class="card">
                <h3><?= ucfirst($role) ?></h3>
                <p><?= $jumlah ?> user</p>
            </div>
        <?php endforeach; ?>
    </div>

    <hr>

    <h2>Rekap Absensi</h2>

    <form method="GET">
        <label>Tanggal Awal:</label>
        <input type="date" name="tanggal_awal" value="<?= htmlspecialchars($tanggal_awal) ?>">
        <label>Tanggal Akhir:</label>
        <input type="date" name="tanggal_akhir" value="<?= htmlspecialchars($tanggal_akhir) ?>">
        <button type="submit">Filter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Role</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $dataAbsensi->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['role'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= $row['jam_masuk'] ?></td>
                    <td><?= $row['jam_keluar'] ?: '-' ?></td>
                    <td><?= $row['status'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>
    <a href="../auth/logout.php">🔒 Logout</a>
</body>

</html>