<?php
require '../config/db.php'; // karena file ada di folder user

$tanggal = date('Y-m-d');
$jam = date('H:i:s');

$userId = $_POST['user_id'] ?? null;
if (!$userId) {
    header("Location: ../index.php?error=User ID wajib diisi");
    exit;
}

// Cek apakah user sudah absen hari ini
$stmt = $koneksi->prepare("SELECT id FROM absensi WHERE user_id = ? AND tanggal = ?");
$stmt->bind_param("is", $userId, $tanggal);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Sudah ada absen hari ini
    header("Location: ../index.php?error=Anda sudah absen hari ini");
    exit;
}

// Tentukan status (telat jika lewat jam 08:00)
$status = ($jam > '08:00:00') ? 'telat' : 'ontime';

// Simpan foto
$namaFile = null;
if (!empty($_POST['image_data'])) {
    $fotoBase64 = $_POST['image_data'];
    $namaFile = 'foto_' . time() . '.png';

    if (!is_dir("../uploads")) {
        mkdir("../uploads", 0777, true);
    }

    file_put_contents("../uploads/$namaFile", base64_decode(str_replace('data:image/png;base64,', '', $fotoBase64)));
}

// Simpan data absen
$stmt = $koneksi->prepare("INSERT INTO absensi (user_id, tanggal, jam_masuk, status, foto) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $userId, $tanggal, $jam, $status, $namaFile);
$stmt->execute();

header("Location: ../index.php?success=Absen berhasil disimpan");
exit;
