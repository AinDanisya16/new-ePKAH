<?php
session_start();
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'vendor') {
    echo "Akses ditolak!";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vendor Dashboard</title>
    <style>
        body { font-family: Arial; padding: 20px; background-color: #f4f4f4; }
        h2 { color: #333; }
        a { display: inline-block; margin: 10px 0; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        a:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h2>Selamat Datang Vendor: <?= $_SESSION['nama']; ?></h2>

    <a href="vendor_penghantaran.php">📦 Senarai Penghantaran Masuk</a><br>
    <a href="logout.php">🚪 Logout</a>
</body>
</html>
