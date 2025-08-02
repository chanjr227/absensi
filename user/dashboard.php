<?php
require '../auth/session_check.php';
require '../config/db.php';

//cek waktu kehadiran
$userId = $_SESSION['user_id'];
$tanggalHariIni = date('Y-m-d');

//ambil data hari ini
$stmt = $koneksi->prepare("SELECT *  FROM absensi WHERE user_id = ?  AND tanggal = ?");
$stmt->bind_param("is", $userId, $tanggalHariIni);
$stmt->execute();
$absenHariIni = $stmt->get_result()->fetch_assoc();
