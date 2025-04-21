<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if (!isset($_SESSION['user_id']) || $_SESSION['peranan'] !== 'admin') {
    echo "Akses ditolak! Log masuk sebagai admin untuk akses dashboard ini.";
    exit;
}

// Kira jumlah notifikasi penghantaran baru
$sql = "SELECT COUNT(*) AS jumlah_baru FROM penghantaran WHERE notifikasi_admin = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$jumlahBaru = $row['jumlah_baru'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .menu a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background: #f0f0f0;
            text-decoration: none;
            color: black;
            border: 1px solid #ccc;
            width: 300px;
        }
        .menu a:hover { background: #d0e8ff; }
        .badge {
            background: red;
            color: white;
            padding: 2px 8px;
            border-radius: 50%;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <h2>Selamat Datang, Admin <?php echo $_SESSION['nama']; ?>!</h2>

    <div class="menu">
        <a href="senarai_pengguna.php">👥 Senarai Pengguna</a>
        <a href="senarai_vendor.php">🏭 Senarai Vendor</a>
        <a href="senarai_penghantaran.php">
            📦 Senarai Penghantaran 
            <?php if ($jumlahBaru > 0) echo "<span class='badge'>$jumlahBaru</span>"; ?>
        </a>
        <a href="logout.php">🚪 Log Keluar</a>
    </div>
</body>
</html>
