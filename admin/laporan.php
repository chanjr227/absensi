<?php
require '../config/db.php';

// Ambil tanggal filter (default awal bulan sampai hari ini)
$tanggalMulai = $_GET['mulai'] ?? date('Y-m-01');
$tanggalSelesai = $_GET['selesai'] ?? date('Y-m-d');

// Query ambil data absensi join dengan users
$stmt = $koneksi->prepare("
    SELECT a.*, u.nama, u.username 
    FROM absensi a 
    JOIN users u ON a.user_id = u.id
    WHERE a.tanggal BETWEEN ? AND ?
    ORDER BY a.tanggal ASC
");
$stmt->bind_param("ss", $tanggalMulai, $tanggalSelesai);
$stmt->execute();
$result = $stmt->get_result();
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
        <a href="data-karyawan.php">Data karyawan</a>
        <a href="laporan.php" class="active">Laporan</a>
    </div>

    <div class="main">
        <div class="navbar">
            <span>Halo, Admin</span>
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
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= $row['jam_masuk'] ?></td>
                            <td><?= $row['jam_keluar'] ?: '-' ?></td>
                            <td style="color:<?= $row['status'] === 'telat' ? 'red' : 'green' ?>">
                                <?= ucfirst($row['status']) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">Tidak ada data absensi</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>

</html>