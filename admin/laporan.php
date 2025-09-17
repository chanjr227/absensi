<?php
require '../config/db.php';



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
</head>

<body>
    <div class="sidebar">
        <h2>Admin</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="#">Data karyawan</a>
        <a href="laporan.php">Laporan</a>
    </div>

    <div class="main">
        <div class="navbar">
            <span>halo, admin</span>
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="content">
            <h1>Laporan Absensi</h1>
            <!-- Filter -->
            <form method="GET" style="margin-bottom: 15px;">
                <label>Dari:</label>
                <input type="date" name="mulai" value="<?= $tanggalMulai ?>">
                <label>Sampai:</label>
                <input type="date" name="selesai" value="<?= $tanggalSelesai ?>">
                <button type="submit">🔍 Filter</button>
                <a href="export-excel.php?mulai=<?= $tanggalMulai ?>&selesai=<?= $tanggalSelesai ?>">
                    <button type="button">📥 Export Excel</button>
                </a>
                <a href="export-pdf.php?mulai=<?= $tanggalMulai ?>&selesai=<?= $tanggalSelesai ?>">
                    <button type="button">🖨 Export PDF</button>
                </a>
            </form>
        </div>
    </div>
    <!-- Tabel -->
    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Status</th>
        </tr>
        <?php $no = 1;
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['tanggal'] ?></td>
                <td><?= $row['jam_masuk'] ?></td>
                <td><?= $row['jam_keluar'] ?: '-' ?></td>
                <td style="color:<?= $row['status'] === 'telat' ? 'red' : 'green' ?>">
                    <?= ucfirst($row['status']) ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    </div>
    </div>
</body>

</html>