<?php
session_start();
include("db.php");

if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] != 'admin') {
    echo "Akses ditolak!";
    exit;
}

$admin_id = $_SESSION['user_id'];

$sql = "SELECT p.*, u.nama 
        FROM penghantaran p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.status = 'diterima_admin' AND p.admin_id = $admin_id 
        ORDER BY p.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Senarai Penghantaran Diterima</title>
    <style>
        body {
            font-family: 'Consolas', sans-serif;
            background-color: #f7fdf5;
            color: #2e7d32;
            margin: 0;
            padding: 0;
            font-size: 20px; /* Increase body font size */
        }

        h2 {
            text-align: center;
            color: #388e3c;
            padding-top: 50px;
            font-size: 42px; /* Increase heading font size */
        }

        table {
            border-collapse: collapse;
            width: 90%;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 15px; /* Increase padding for better spacing */
            text-align: center;
            font-size: 20px; /* Increase font size in table cells */
        }

        th {
            background-color: #66bb6a;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        img {
            width: 100px; /* Increase image size */
            height: auto;
        }
    </style>
</head>
<body>

<h2>Senarai Penghantaran Diterima</h2>

<table>
    <tr>
        <th>Nama Pengguna</th>
        <th>Kategori</th>
        <th>Jenis</th>
        <th>Alamat</th>
        <th>Gambar</th>
        <th>Status</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= htmlspecialchars($row['nama']); ?></td>
        <td><?= htmlspecialchars($row['kategori']); ?></td>
        <td><?= htmlspecialchars($row['jenis']); ?></td>
        <td><?= htmlspecialchars($row['alamat']); ?></td>
        <td><img src="uploads/<?= htmlspecialchars($row['gambar']); ?>" alt="Gambar Penghantaran"></td>
        <td><?= htmlspecialchars($row['status']); ?></td>
    </tr>
    <?php } ?>
</table>

</body>
</html>

