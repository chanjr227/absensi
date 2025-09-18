<?php
require '../config/db.php';

$result = $koneksi->query("SELECT * FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Karyawan</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
</head>

<body>
    <div class="sidebar">
        <h2>Admin</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="data-karyawan.php" style="background:#1e293b;"> Data Karyawan</a>
        <a href="laporan.php">📑 Laporan</a>
    </div>

    <div class="main">
        <div class="navbar">
            <span>Halo, Admin</span>
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="content">
            <h1>👥 Data Karyawan</h1>
            <a href="tambah-karyawan.php"><button>➕ Tambah Karyawan</button></a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= $row['role'] ?></td>
                        <td>
                            <a href="edit-karyawan.php?id=<?= $row['id'] ?>"><button>✏ Edit</button></a>
                            <a href="hapus-karyawan.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus karyawan ini?')"><button>🗑 Hapus</button></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>

</html>