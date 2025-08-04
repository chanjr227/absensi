<?php
require '../auth/session_check.php';
require '../config/db.php';

$userId = $_SESSION['user_id'];
$tanggal = date('Y-m-d');
$jam = date('H:i:s');

// Status telat kalau masuk > jam 08:00
$status = ($jam > '08:00:00') ? 'telat' : 'ontime';

// Ambil foto base64
$fotoBase64 = $_POST['image_data'];
$namaFile = 'foto_' . time() . '.png';
file_put_contents("../uploads/$namaFile", base64_decode(str_replace('data:image/png;base64,', '', $fotoBase64)));

$stmt = $koneksi->prepare("INSERT INTO absensi (user_id, tanggal, jam_masuk, status, foto) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $userId, $tanggal, $jam, $status, $namaFile);
$stmt->execute();

header("Location: dashboard.php");
exit;
