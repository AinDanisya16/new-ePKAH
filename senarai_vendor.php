<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Semak kalau bukan admin
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'admin') {
    echo "Akses ditolak!";
    exit;
}

$sql = "SELECT id, nama, telefon, status FROM users WHERE peranan = 'vendor'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Senarai Vendor</title>
    <style>
        table { border-collapse: collapse; width: 90%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h2>Senarai Vendor Berdaftar</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>No Telefon</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['telefon']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
