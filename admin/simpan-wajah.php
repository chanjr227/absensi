<?php
require '../config/db.php';

if (!empty($_POST['user_id']) && !empty($_POST['image_data'])) {
    $userId = intval($_POST['user_id']);
    $imageData = $_POST['image_data'];
    $folder = "../uploads/wajah/";

    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    $filePath = $folder . $userId . ".png";
    file_put_contents($filePath, base64_decode(str_replace('data:image/png;base64,', '', $imageData)));

    echo "<script>alert('✅ Wajah berhasil disimpan!');window.location='registrasi-wajah.php';</script>";
} else {
    echo "<script>alert('❌ Data tidak lengkap');window.location='registrasi-wajah.php';</script>";
}
