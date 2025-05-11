<?php
session_start();

// Ensure only logged-in vendors can access the dashboard
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'vendor') {
    echo "Akses ditolak!";
    exit;
}

// Ensure the vendor's account is approved
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // Assuming the user ID is stored in session
$sql = "SELECT status FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['status'] !== 'approved') {
    echo "Akaun anda belum diluluskan oleh admin.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vendor Dashboard</title>
    <style>
        body {
            font-family: Consolas, monospace;
            padding: 30px;
            background-color: #f0fff0;
            font-size: 18px;
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            font-size: 32px;
        }

        a {
            display: inline-block;
            margin: 15px 0;
            padding: 12px 25px;
            background-color: #43a047;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 20px;
            text-align: center;
        }

        a:hover {
            background-color: #2e7d32;
        }

        .dashboard-container {
            text-align: center;
        }

        .logo {
            display: block;
            margin: 0 auto 16px;
            width: 120px; /* Saiz logo lebih besar */
            height: 100px;
        }

        .header {
            height: 100px; /* Set height to 100px */
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="logo_epkah.png" alt="Logo ePKAH" class="logo">
    </div>

    <div class="dashboard-container">
        <h2>Selamat Datang Vendor: <?= $_SESSION['nama']; ?></h2>

        <a href="vendor_penghantaran.php">📦 Senarai Penghantaran Masuk</a><br>
        <a href="laporan_data_kutipan.php">📊 Laporan Data Kutipan</a><br>        
        <a href="logout.php">🚪 Logout</a>
    </div>
</body>
</html>


