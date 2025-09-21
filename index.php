<?php
require 'config/db.php';
$tanggalHariIni = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Absensi Dengan Face Detection</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <!-- Pastikan face-api.js dimuat lebih dulu -->
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            padding: 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
            text-align: center;
            width: 350px;
            position: relative;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: none;
            outline: none;
        }

        button {
            background: #38bdf8;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            background: #0ea5e9;
            transform: translateY(-2px);
        }

        canvas {
            position: absolute;
            top: 90px;
            left: 50%;
            transform: translateX(-50%);
        }

        .alert {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            animation: fadeOut 3s forwards;
        }

        .success {
            background: #22c55e;
        }

        .error {
            background: #ef4444;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Absensi Hari Ini</h2>
        <p><?= date('d-m-Y') ?> | Jam: <span id="jam"></span></p>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">✅ <?= htmlspecialchars($_GET['success']) ?></div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert error">⚠️ <?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form action="user/checkin.php" method="POST" onsubmit="return ambilFoto()">
            <input type="number" name="user_id" placeholder="Masukkan User ID / NIK" required>
            <div id="kamera"></div>
            <canvas id="overlay"></canvas>
            <input type="hidden" name="image_data" id="image_data">
            <button type="submit" id="btnAbsen" disabled>✅ Check In</button>
        </form>
    </div>

    <script>
        // Jam real-time
        setInterval(() => {
            document.getElementById("jam").innerText = new Date().toLocaleTimeString();
        }, 1000);

        // Setup webcam
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'png',
            png_quality: 90
        });
        Webcam.attach('#kamera');

        const overlay = document.getElementById('overlay');
        const ctx = overlay.getContext('2d');

        console.log("⏳ Memuat model face-api.js...");
        Promise.all([
                faceapi.nets.ssdMobilenetv1.loadFromUri("https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@0.22.2/weights"),
                faceapi.nets.faceLandmark68Net.loadFromUri("https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@0.22.2/weights"),
                faceapi.nets.faceRecognitionNet.loadFromUri("https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@0.22.2/weights")
            ])
            .then(() => {
                console.log("✅ Model berhasil dimuat! Menunggu video siap...");
                // Tambahkan delay 1.5 detik supaya webcam benar-benar siap
                setTimeout(startFaceDetection, 1500);
            })
            .catch(err => console.error("❌ Gagal memuat model:", err));

        async function startFaceDetection() {
            console.log("▶ Mulai deteksi wajah...");
            const videoElement = document.querySelector('#kamera video');
            if (!videoElement) {
                console.error("❌ Video element tidak ditemukan!");
                return;
            }

            overlay.width = 320;
            overlay.height = 240;

            setInterval(async () => {
                const detections = await faceapi.detectAllFaces(videoElement).withFaceLandmarks();
                ctx.clearRect(0, 0, overlay.width, overlay.height);
                faceapi.draw.drawDetections(overlay, detections);

                if (detections.length > 0) {
                    console.log("✅ Wajah terdeteksi");
                }
                document.getElementById('btnAbsen').disabled = detections.length === 0;
            }, 500);
        }

        function ambilFoto() {
            Webcam.snap(function(data_uri) {
                document.getElementById('image_data').value = data_uri;
            });
            return true;
        }
    </script>
</body>

</html>