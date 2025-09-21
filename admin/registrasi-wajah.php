<?php
require '../config/db.php';

// Ambil semua karyawan dari tabel users
$result = $koneksi->query("SELECT id, username FROM users ORDER BY username ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Registrasi Wajah</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        select,
        button {
            margin: 10px 0;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: 90%;
        }

        button {
            background: #38bdf8;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #0ea5e9;
        }

        #kamera {
            margin: 10px auto;
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Registrasi Wajah</h2>
        <form action="simpan-wajah.php" method="POST" onsubmit="return ambilFoto()">
            <select name="user_id" required>
                <option value="">-- Pilih Karyawan --</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['username']) ?></option>
                <?php endwhile; ?>
            </select>
            <div id="kamera"></div>
            <input type="hidden" name="image_data" id="image_data">
            <button type="submit">📸 Simpan Wajah</button>
        </form>
    </div>

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