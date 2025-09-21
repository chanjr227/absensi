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

// Ambil data absensi per bulan (untuk grafik batang)
$absensi_per_bulan = $koneksi->query("
    SELECT MONTH(tanggal) as bulan,
           SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
           SUM(CASE WHEN status='izin' THEN 1 ELSE 0 END) as izin,
           SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) as sakit
    FROM absensi
    WHERE YEAR(tanggal)=YEAR(CURDATE())
    GROUP BY MONTH(tanggal)
    ORDER BY bulan ASC
");

$bulan = [];
$hadir = [];
$izin = [];
$sakit = [];

while ($row = $absensi_per_bulan->fetch_assoc()) {
    $bulan[] = date("M", mktime(0, 0, 0, $row['bulan'], 1));
    $hadir[] = $row['hadir'];
    $izin[] = $row['izin'];
    $sakit[] = $row['sakit'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            <!-- Grafik absensi -->
            <h2>📈 Statistik Absensi Per Bulan</h2>
            <canvas id="chartAbsensi" style="max-height:300px;"></canvas>

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

    <script>
        // Grafik batang
        const ctx = document.getElementById('chartAbsensi');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($bulan) ?>,
                datasets: [{
                        label: 'Hadir',
                        data: <?= json_encode($hadir) ?>,
                        backgroundColor: '#22c55e'
                    },
                    {
                        label: 'Izin',
                        data: <?= json_encode($izin) ?>,
                        backgroundColor: '#facc15'
                    },
                    {
                        label: 'Sakit',
                        data: <?= json_encode($sakit) ?>,
                        backgroundColor: '#ef4444'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>