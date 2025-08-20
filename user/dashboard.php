<?php
require '../auth/session_check.php';
require '../config/db.php';

$userId = $_SESSION['user_id'];
$tanggalHariIni = date('Y-m-d');

// Ambil data absensi hari ini
$stmt = $koneksi->prepare("SELECT * FROM absensi WHERE user_id = ? AND tanggal = ?");
$stmt->bind_param("is", $userId, $tanggalHariIni);
$stmt->execute();
$absenHariIni = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard user</title>
    <script src="webcam.js"></script>
</head>

<body>
    <h2>Halo, <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>)</h2>
    <p>Tanggal: <?= date('d-m-Y') ?> | Jam: <span id="jam"></span></p>

    <?php if (!$absenHariIni): ?>
        <form action="checkin.php" method="POST" enctype="multipart/form-data" onsubmit="return ambilFoto()">
            <div id="kamera"></div>
            <input type="hidden" name="image_data" id="image_data">
            <button type="submit">✅ Check In</button>
        </form>
    <?php elseif ($absenHariIni && !$absenHariIni['jam_keluar']): ?>
        <form action="checkout.php" method="POST" enctype="multipart/form-data" onsubmit="return ambilFoto()">
            <div id="kamera"></div>
            <input type="hidden" name="image_data" id="image_data">
            <button type="submit">⏹️ Check out</button>
        </form>

    <?php else: ?>
        <p>✅ Kamu sudah check-in & check-out hari ini.</p>
    <?php endif; ?>

    <a href="../auth/logout.php">Logout</a>

    <script>
        // Jam realtime
        setInterval(() => {
            const now = new Date();
            document.getElementById("jam").innerText = now.toLocaleTimeString();
        }, 1000);
    </script>

    <!-- Library WebcamJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

    <!-- Inisialisasi Kamera -->
    <script>
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'png',
            png_quality: 90
        });
        Webcam.attach('#kamera');

        function ambilFoto() {
            Webcam.snap(function(data_uri) {
                document.getElementById('image_data').value = data_uri;
            });
            return true;
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script>
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'png',
            png_quality: 90
        });
        Webcam.attach('#kamera');

        function ambilFoto() {
            Webcam.snap(function(data_uri) {
                document.getElementById('image_data').value = data_uri;
            });
            return true;
        }
    </script>

</body>

</html>