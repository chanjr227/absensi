<?php
require '../auth/session_check.php';
require '../config/db.php';

$userId = $_SESSION['user_id'];
$tanggal = date('Y-m-d');
$jamKeluar = date('H:i:s');

$stmt = $koneksi->prepare("UPDATE absensi SET jam_keluar = ? WHERE user_id = ? AND tanggal = ?");
$stmt->bind_param("sis", $jamKeluar, $userId, $tanggal);
$stmt->execute();

header("Location: dashboard.php");
exit;
