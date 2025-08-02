<?php
$koneksi = new mysqli("localhost", "root", "", "absensi_db");

if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
