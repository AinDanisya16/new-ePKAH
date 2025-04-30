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

// Dapatkan maklumat vendor
$sql = "SELECT id, nama_syarikat, no_syarikat, alamat, poskod, negeri, lokasi_kutipan, jenis_barang FROM users WHERE peranan = 'vendor'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Senarai Vendor Berdaftar</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #e8f5e9, #c8e6c9);
            padding: 30px;
            color: #2e7d32;
            font-size: 18px;
        }

        h2 {
            text-align: center;
            font-size: 32px;
            color: #1b5e20;
            margin-bottom: 40px;
        }

        table {
            width: 95%;
            margin: 0 auto;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 14px 20px;
            text-align: center;
            font-size: 16px;
        }

        th {
            background-color: #66bb6a;
            color: white;
        }

        td {
            background-color: #f1f8e9;
            color: #33691e;
        }

        tr:nth-child(even) td {
            background-color: #dcedc8;
        }

        .menu {
            text-align: center;
            margin-top: 40px;
        }

        .menu a {
            background: #81c784;
            padding: 12px 25px;
            margin: 10px;
            border-radius: 30px;
            text-decoration: none;
            color: #1b5e20;
            font-weight: bold;
            transition: background 0.3s;
            font-size: 18px;
            display: inline-block;
        }

        .menu a:hover {
            background: #66bb6a;
        }
    </style>
</head>
<body>

<h2>🌿 Senarai Vendor Berdaftar e-PKAH 🌿</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Syarikat</th>
            <th>No. Syarikat</th>
            <th>Alamat</th>
            <th>Poskod</th>
            <th>Negeri</th>
            <th>Lokasi Kutipan</th>
            <th>Jenis Barang Dikutip</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['nama_syarikat']) ?></td>
            <td><?= htmlspecialchars($row['no_syarikat']) ?></td>
            <td><?= htmlspecialchars($row['alamat']) ?></td>
            <td><?= htmlspecialchars($row['poskod']) ?></td>
            <td><?= htmlspecialchars($row['negeri']) ?></td>
            <td><?= htmlspecialchars($row['lokasi_kutipan']) ?></td>
            <td><?= htmlspecialchars($row['jenis_barang']) ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<div class="menu">
    <a href="admin_dashboard.php">🏠 Dashboard</a>
    <a href="logout.php">🚪 Log Keluar</a>
</div>

</body>
</html>
