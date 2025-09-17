<?php
require '../config/db.php';
require '../vendor/autoload.php'; // pastikan sudah install dompdf via composer

use Dompdf\Dompdf;

$tanggalMulai = $_GET['mulai'] ?? date('Y-m-01');
$tanggalSelesai = $_GET['selesai'] ?? date('Y-m-d');

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

$html = "<h2 style='text-align:center;'>Laporan Absensi</h2>";
$html .= "<p>Periode: $tanggalMulai s/d $tanggalSelesai</p>";
$html .= "<table border='1' cellpadding='5' cellspacing='0' width='100%'>
<tr><th>No</th><th>Nama</th><th>Tanggal</th><th>Jam Masuk</th><th>Jam Keluar</th><th>Status</th></tr>";
$no = 1;
while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
        <td>" . $no++ . "</td>
        <td>" . htmlspecialchars($row['username']) . "</td>
        <td>" . $row['tanggal'] . "</td>
        <td>" . $row['jam_masuk'] . "</td>
        <td>" . ($row['jam_keluar'] ?: '-') . "</td>
        <td>" . $row['status'] . "</td>
    </tr>";
}
$html .= "</table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("laporan_absensi.pdf", ["Attachment" => true]);
