<?php
require 'config/db.php';
$tanggalHariIni = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Absensi Tanpa Login</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
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

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            70% {
                opacity: 1;
            }

            100% {
                opacity: 0;
                display: none;
            }
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
            <input type="hidden" name="image_data" id="image_data">
            <button type="submit">✅ Check In</button>
        </form>
    </div>

    <script>
        setInterval(() => {
            document.getElementById("jam").innerText = new Date().toLocaleTimeString();
        }, 1000);

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