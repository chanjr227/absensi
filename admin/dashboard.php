<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/db.php';

// Ambil data statistik
$total_karyawan = $koneksi->query("SELECT COUNT(*) as total FROM users WHERE role='karyawan'")->fetch_assoc()['total'];
$total_admin    = $koneksi->query("SELECT COUNT(*) as total FROM users WHERE role='admin'")->fetch_assoc()['total'];
$total_absen    = $koneksi->query("SELECT COUNT(*) as total FROM absensi WHERE DATE(tanggal)=CURDATE()")->fetch_assoc()['total'];

// Ambil data karyawan
$result = $koneksi->query("SELECT * FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
</head>

<body>
    <div class="sidebar">
        <h2>Admin</h2>
        <a href="dashboard.php" class="active">🏠 Dashboard</a>
        <a href="laporan.php">📑 Laporan</a>
        <a href="../auth/logout.php" class="logout-btn">🚪 Logout</a>
    </div>

    <div class="main">
        <div class="navbar">
            <span>👋 Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
        </div>

        <div class="content">
            <h1>📊 Dashboard</h1>

            <div class="stats">
                <div class="card">
                    <h3><?= $total_karyawan ?></h3>
                    <p>Total Karyawan</p>
                </div>
                <div class="card">
                    <h3><?= $total_admin ?></h3>
                    <p>Total Admin</p>
                </div>
                <div class="card">
                    <h3><?= $total_absen ?></h3>
                    <p>Absensi Hari Ini</p>
                </div>
            </div>

            <h2>👥 Daftar Karyawan</h2>
            <a href="tambah-karyawan.php"><button>➕ Tambah Karyawan</button></a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nama'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['role'] ?? '-') ?></td>
                            <td>
                                <a href="edit-karyawan.php?id=<?= $row['id'] ?>"><button>✏ Edit</button></a>
                                <a href="hapus-karyawan.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus karyawan ini?')">
                                    <button class="btn-delete">🗑 Hapus</button>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">Belum ada data karyawan</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>

</html>