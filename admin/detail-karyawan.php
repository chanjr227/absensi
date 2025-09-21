<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil karyawan</title>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../auth/login.php");
        exit;
    }

    require '../config/db.php';

    if (!isset($_GET['id'])) {
        header("Location: data-karyawan.php");
        exit;
    }

    $id = intval($_GET['id']);

    // Ambil data karyawan
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        header("Location: data-karyawan.php");
        exit;
    }

    // Ambil riwayat absensi
    $absensi = $koneksi->query("SELECT * FROM absensi WHERE user_id = $id ORDER BY tanggal DESC, jam_masuk DESC");
    ?>

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Profil Karyawan</title>
        <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
        <style>
            .profile-card {
                background: #f8fafc;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
            }

            .profile-card h2 {
                margin-top: 0;
                color: #1e293b;
            }

            .absensi-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
            }

            .absensi-table th,
            .absensi-table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: center;
            }

            .absensi-table th {
                background: #1e293b;
                color: white;
            }
        </style>
    </head>

    <body>
        <div class="sidebar">
            <h2>Admin</h2>
            <a href="dashboard.php">🏠 Dashboard</a>
            <a href="data-karyawan.php" class="active">👥 Data Karyawan</a>
            <a href="laporan.php">📑 Laporan</a>
            <a href="../auth/logout.php" class="logout-btn">🚪 Logout</a>
        </div>

        <div class="main">
            <div class="navbar">
                <span>👤 Detail Profil - <?= htmlspecialchars($user['nama']) ?></span>
            </div>

            <div class="content">
                <div class="profile-card">
                    <h2><?= htmlspecialchars($user['nama']) ?></h2>
                    <p><b>Username:</b> <?= htmlspecialchars($user['username']) ?></p>
                    <p><b>Role:</b> <?= htmlspecialchars($user['role']) ?></p>
                    <p><b>Dibuat pada:</b> <?= htmlspecialchars($user['created_at']) ?></p>
                </div>

                <h3>📅 Riwayat Absensi</h3>
                <table class="absensi-table">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                    </tr>
                    <?php if ($absensi && $absensi->num_rows > 0): ?>
                        <?php while ($row = $absensi->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                <td><?= htmlspecialchars($row['jam_masuk']) ?></td>
                                <td><?= htmlspecialchars($row['jam_keluar'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['status'] ?? '-') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Belum ada riwayat absensi</td>
                        </tr>
                    <?php endif; ?>
                </table>

                <br>
                <a href="data-karyawan.php"><button>⬅ Kembali</button></a>
            </div>
        </div>
    </body>

    </html>

</body>

</html>