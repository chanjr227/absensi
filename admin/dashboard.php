<?php
require '../config/db.php';

// Ambil semua data absensi
$result = $koneksi->query("SELECT absensi.*, users.username 
                           FROM absensi 
                           JOIN users ON absensi.user_id = users.id 
                           ORDER BY tanggal DESC, jam_masuk DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>📊 Admin</h2>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="data-karyawan.php">👥 Data Karyawan</a>
        <a href="laporan.php">📑 Laporan</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <!-- Navbar -->
        <div class="navbar">
            <span>Halo, Admin</span>
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </div>

        <!-- Content -->
        <div class="content">
            <h1>Rekap Absensi</h1>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Status</th>
                    <th>Foto</th>
                </tr>
                <?php
                $no = 1;
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= $row['tanggal'] ?></td>
                        <td><?= $row['jam_masuk'] ?></td>
                        <td><?= $row['jam_keluar'] ?: '-' ?></td>
                        <td><?= ucfirst($row['status']) ?></td>
                        <td><img src="../uploads/<?= $row['foto'] ?>" alt="foto"></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>

</html>