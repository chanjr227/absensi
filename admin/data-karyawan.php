<?php
require '../config/db.php';

// Ambil semua data karyawan
$result = $koneksi->query("SELECT * FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Karyawan</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <div class="container">
        <h1>Data Karyawan</h1>
        <a href="tambah-karyawan.php" class="btn btn-tambah">+ Tambah Karyawan</a>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td>
                            <a href="edit-karyawan.php?id=<?= $row['id'] ?>" class="btn btn-edit">✏ Edit</a>
                            <a href="hapus-karyawan.php?id=<?= $row['id'] ?>" class="btn btn-hapus" onclick="return confirm('Hapus karyawan ini?')">🗑 Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <a href="dashboard.php" class="btn btn-back">⬅ Kembali</a>
    </div>
</body>

</html>