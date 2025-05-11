<?php
require __DIR__ . '/vendor/autoload.php';
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = new mysqli("localhost", "root", "", "ePKAH");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$vendor_id = $_SESSION['user_id'];

$sql = "SELECT p.*, u.nama AS nama_pengguna FROM penghantaran p 
        JOIN users u ON p.user_id = u.id
        WHERE p.vendor_id = ?";

$kategori = $_POST['kategori'] ?? '';
$status = $_POST['status'] ?? '';
$nama_pengguna = $_POST['nama_pengguna'] ?? '';
$tarikh_dari = $_POST['tarikh_dari'] ?? '';
$tarikh_hingga = $_POST['tarikh_hingga'] ?? '';

$params = [$vendor_id];
$types = "i";

if (!empty($kategori)) { $sql .= " AND p.kategori = ?"; $params[] = $kategori; $types .= "s"; }
if (!empty($status)) { $sql .= " AND p.status_kutipan = ?"; $params[] = $status; $types .= "s"; }
if (!empty($nama_pengguna)) { $sql .= " AND u.nama LIKE ?"; $params[] = "%$nama_pengguna%"; $types .= "s"; }
if (!empty($tarikh_dari)) { $sql .= " AND p.tarikh_hantar >= ?"; $params[] = $tarikh_dari; $types .= "s"; }
if (!empty($tarikh_hingga)) { $sql .= " AND p.tarikh_hantar <= ?"; $params[] = $tarikh_hingga; $types .= "s"; }

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headers = ['ID', 'Nama Pengguna', 'No Telefon', 'Kategori', 'Jenis', 'Alamat', 'Poskod', 'Daerah', 'Negeri', 'Tarikh Hantar', 'Status Kutipan'];
$sheet->fromArray($headers, NULL, 'A1');

$rowIndex = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->fromArray([
        $row['id'],
        $row['nama_pengguna'],
        $row['no_telefon_untuk_dihubungi'],
        $row['kategori'],
        $row['jenis'],
        $row['alamat'],
        $row['poskod'],
        $row['jajahan_daerah'],
        $row['negeri'],
        $row['tarikh_hantar'],
        $row['status_kutipan']
    ], NULL, 'A' . $rowIndex++);
}

$filename = "senarai_penghantaran_" . date("Ymd_His") . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
