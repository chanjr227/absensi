<?php
require '../config/db.php';

$id = $_GET['id'];
$koneksi->query("DELETE FROM users WHERE id=$id");
header("Location: data-karyawan.php");
exit;
