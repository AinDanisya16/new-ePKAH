<?php
session_start();
include("db.php");

// Semak jika admin sudah login
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] != 'admin') {
    echo "Akses ditolak! Log masuk sebagai admin untuk akses dashboard ini.";
    exit;
}

// Ambil data penghantaran bersama nama pengguna (JOIN)
$sql = "SELECT penghantaran.*, users.nama 
        FROM penghantaran 
        JOIN users ON penghantaran.user_id = users.id 
        ORDER BY penghantaran.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Senarai Penghantaran</title>
    <style>
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #888;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2 align="center">Senarai Penghantaran</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Pengguna</th>
            <th>Kategori</th>
            <th>Jenis</th>
            <th>Alamat</th>
            <th>Maklumat Pelajar</th>
            <th>Gambar</th>
            <th>Tarikh</th>
        </tr>
        <?php
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
            echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
            echo "<td>" . htmlspecialchars($row['jenis']) . "</td>";
            echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
            echo "<td>" . htmlspecialchars($row['maklumat_pelajar']) . "</td>";
            echo "<td><img src='uploads/" . $row['gambar'] . "' width='100'></td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>