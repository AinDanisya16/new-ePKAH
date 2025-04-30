<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Semak jika admin
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'admin') {
    echo "Akses ditolak!";
    exit;
}

// Papar hanya penghantaran ditolak
$sql = "SELECT p.*, u.nama FROM penghantaran p
        JOIN users u ON p.user_id = u.id
        WHERE p.status_kutipan = 'ditolak'
        ORDER BY p.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Senarai Penghantaran Ditolak</title>
    <style>
        body {
            font-family: 'Consolas', sans-serif;
            background-color: #f7fdf5;
            color: #2e7d32;
            margin: 0;
            padding: 0;
            font-size: 20px;
        }

        h2 {
            text-align: center;
            color: #388e3c;
            padding-top: 30px;
            font-size: 24px;
        }

        table {
            border-collapse: collapse;
            width: 95%;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            font-size: 18px;
        }

        th {
            background-color: #66bb6a;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
        }

        .ditolak {
            background-color: red;
            color: white;
        }

        .ditolak:hover {
            background-color: #c62828;
            transition: 0.3s ease;
        }

        .nav-btn {
            display: inline-block;
            margin-right: 10px;
            padding: 8px 12px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            font-size: 18px;
        }

        .semakan {
            background-color: blue;
        }

        .semakan:hover {
            background-color: #1e88e5;
        }

        .selesai {
            background-color: green;
        }

        .selesai:hover {
            background-color: #2e7d32;
        }

        .menu {
            text-align: center;
            margin-top: 30px;
        }

        .menu a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background-color: #a5d6a7;
            text-decoration: none;
            color: #1b5e20;
            border-radius: 50px;
            font-weight: bold;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: #81c784;
        }
    </style>
</head>
<body>
    <h2>Senarai Penghantaran Ditolak</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama Pengguna</th>
            <th>Kategori</th>
            <th>Jenis</th>
            <th>Alamat</th>
            <th>Poskod</th>
            <th>Jajahan/Daerah</th>
            <th>Negeri</th>
            <th>Tarikh</th>
            <th>Status Kutipan</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= $row['kategori'] ?></td>
            <td><?= $row['jenis'] ?></td>
            <td><?= $row['alamat'] ?></td>
            <td><?= $row['poskod'] ?></td>
            <td><?= $row['jajahan/daerah'] ?></td>
            <td><?= $row['negeri'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td><span class="btn ditolak">❌ <?= $row['status_kutipan'] ?></span></td>
        </tr>
        <?php } ?>
    </table>

    <div style="text-align: center; margin-top: 20px;">
        <a href="admin_penghantaran.php" class="nav-btn semakan">📩 Dalam Semakan</a>
        <a href="admin_ditolak.php" class="nav-btn ditolak">❌ Ditolak</a>
        <a href="admin_selesai.php" class="nav-btn selesai">✅ Selesai</a>
    </div>

    <div class="menu">
        <a href="admin_dashboard.php">🏠 Kembali ke Dashboard</a>
        <a href="logout.php">🚪 Log Keluar</a>
    </div>

</body>
</html>


