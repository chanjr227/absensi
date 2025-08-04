<?php
$koneksi = new mysqli("localhost", "root", "", "absensi");

if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
