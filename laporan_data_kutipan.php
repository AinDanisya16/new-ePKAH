<?php
session_start();
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'vendor') {
    echo "Akses ditolak!";
    exit;
}

$conn = new mysqli("localhost", "root", "", "ePKAH");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$vendor_id = $_SESSION['user_id'];

$sql = "SELECT k.*, p.*, u.nama AS nama_pengguna 
        FROM kutipan_vendor k 
        JOIN penghantaran p ON k.penghantaran_id = p.id 
        JOIN users u ON p.user_id = u.id
        WHERE p.vendor_id = ? 
        ORDER BY k.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Kutipan Vendor</title>
    <style>
        body { font-family: Arial, sans-serif; background: #e8f5e9; padding: 20px; }
        h2 { text-align: center; color: #2e7d32; margin-top: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #81c784; text-align: center; }
        th { background: #388e3c; color: white; }
        td { background: #c8e6c9; }
        .menu { margin-top: 30px; text-align: center; }
        .menu a { padding: 10px 20px; background: #66bb6a; color: white; text-decoration: none; border-radius: 8px; margin: 0 5px; }
    </style>
</head>
<body>

<h2>Laporan Data Kutipan Vendor</h2>

<table>
    <tr>
        <th>ID Kutipan</th>
        <th>Nama Pengguna</th>
        <th>Jajahan/Daerah</th>
        <th>Kategori</th>
        <th>Jenis</th>
        <th>Item 3R</th>
        <th>Berat (kg)</th>
        <th>Nilai (RM)</th>
        <th>Tarikh</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
        <td><?= htmlspecialchars($row['jajahan_daerah']) ?></td>
        <td><?= htmlspecialchars($row['kategori']) ?></td>
        <td><?= htmlspecialchars($row['jenis']) ?></td>
        <td><?= $row['item_3r'] ? htmlspecialchars($row['item_3r']) : '-' ?></td>
        <td>
            <?php
                if (isset($row['berat']) && !empty($row['berat'])) {
                    echo htmlspecialchars($row['berat']) . " kg";
                } else {
                    echo "Tiada Data";
                }
            ?>
        </td>
        <td>RM <?= number_format($row['nilai'], 2) ?></td>
        <td><?= $row['tarikh_kutipan'] ?></td>
    </tr>
    <?php } ?>
</table>

<?php
// Dapatkan jumlah berat mengikut kategori dan item_3r
$sqlJumlah = "SELECT p.kategori, k.item_3r, SUM(k.berat) AS jumlah_berat
              FROM kutipan_vendor k
              JOIN penghantaran p ON k.penghantaran_id = p.id
              WHERE p.vendor_id = ?
              GROUP BY p.kategori, k.item_3r
              ORDER BY p.kategori, k.item_3r";
$stmtJumlah = $conn->prepare($sqlJumlah);
$stmtJumlah->bind_param("i", $vendor_id);
$stmtJumlah->execute();
$resultJumlah = $stmtJumlah->get_result();
?>

<h2>Jumlah Berat (kg) Mengikut Kategori & Item 3R</h2>
<table>
    <tr>
        <th>Kategori</th>
        <th>Item 3R</th>
        <th>Jumlah Berat (kg)</th>
    </tr>
    <?php while ($rowJ = $resultJumlah->fetch_assoc()) { ?>
    <tr>
        <td><?= htmlspecialchars($rowJ['kategori']) ?></td>
        <td><?= $rowJ['item_3r'] ? htmlspecialchars($rowJ['item_3r']) : '-' ?></td>
        <td><?= number_format($rowJ['jumlah_berat'], 2) ?> kg</td>
    </tr>
    <?php } ?>
</table>

<div class="menu">
    <a href="vendor_dashboard.php">🏠 Dashboard</a>
    <a href="logout.php">🚪 Logout</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
