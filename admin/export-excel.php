<?php
require '../config/db.php';

$tanggalMulai = $_GET['mulai'] ?? date('Y-m-01');
$tanggalSelesai = $_GET['selesai'] ?? date('Y-m-d');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_absensi.xls");

$query = $koneksi->prepare("
    SELECT absensi.*, users.username 
    FROM absensi 
    JOIN users ON absensi.user_id = users.id
    WHERE tanggal BETWEEN ? AND ?
    ORDER BY tanggal DESC, jam_masuk DESC
");
$query->bind_param("ss", $tanggalMulai, $tanggalSelesai);
$query->execute();
$result = $query->get_result();

echo "<table border='1'>";
echo "<tr><th>No</th><th>Nama</th><th>Tanggal</th><th>Jam Masuk</th><th>Jam Keluar</th><th>Status</th></tr>";
$no = 1;
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . $no++ . "</td>
            <td>" . htmlspecialchars($row['username']) . "</td>
            <td>" . $row['tanggal'] . "</td>
            <td>" . $row['jam_masuk'] . "</td>
            <td>" . ($row['jam_keluar'] ?: '-') . "</td>
            <td>" . $row['status'] . "</td>
        </tr>";
}
echo "</table>";
