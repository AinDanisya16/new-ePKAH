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

if (isset($_GET['padam'])) {
    $id = intval($_GET['padam']);
    $conn->query("DELETE FROM penghantaran WHERE id = $id");
    header("Location: senarai_penghantaran.php");
    exit;
}

// Query yang dikemaskini untuk tarik semua data berkaitan
$sql = "SELECT p.id, u.nama, u.telefon, p.alamat, p.poskod, p.daerah_jajahan, p.negeri, p.kategori, p.jenis, p.maklumat_pelajar, p.gambar, p.created_at
        FROM penghantaran p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.id DESC";

$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html>
<head>
    <title>Senarai Penghantaran</title>
    <style>
        body {
            font-family: Arial, monospace;
            background-color: #f0fff5;
            color: #2e7d32;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            color: #1b5e20;
            font-size: 28px;
            background-color: #c8e6c9;
            padding: 15px;
            border-radius: 12px;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 4px 10px rgba(0, 128, 0, 0.2);
        }

        table {
    width: 90%;
    margin: 30px auto;
    border-collapse: collapse;
    box-shadow: 0 4px 8px rgba(0, 128, 0, 0.1);
    border-radius: 15px;
    overflow: hidden;
    border: 2px solid #66bb6a; /* Border luar table */
}

th {
    background-color: #81c784;
    color: white;
    padding: 12px;
    font-size: 16px;
    border: 1px solid #4caf50; /* Border antara column */
}

td {
    background-color: #e8f5e9;
    padding: 10px;
    font-size: 14px;
    border: 1px solid #a5d6a7; /* Border antara column */
}

        tr:nth-child(even) td {
            background-color: #d0f0c0;
        }

        img {
            border-radius: 8px;
            border: 2px solid #66bb6a;
        }

    .menu {
            text-align: center;
            margin-top: 30px;
        }

        .menu a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px; /* Kurangkan padding */
            background-color: #a5d6a7;
            text-decoration: none;
            color: #1b5e20;
            border-radius: 50px;
            font-weight: bold;
            font-size: 24px; /* Kurangkan font size */
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: #81c784;
        }
    </style>
</head>
<body>
    <h2>Senarai Penghantaran</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Pengguna</th>
            <th>Telefon</th>
            <th>Kategori</th>
            <th>Jenis</th>
            <th>Alamat</th>
            <th>Poskod</th>
            <th>Daerah/Jajahan</th>
            <th>Negeri</th>
            <th>Maklumat Pelajar</th>
            <th>Gambar</th>
            <th>Tarikh</th>
            <th>Tindakan</th>
            ...
            <td>
            <a href='admin_kemaskini_penghantaran.php?id=<?= $row['id'] ?>'>✏️</a> |
            <a href='?padam=<?= $row['id'] ?>' onclick="return confirm('Padam rekod ini?');">🗑️</a>
            </td>
        </tr>

        <?php
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telefon']) . "</td>";
            echo "<td>" . ($row['kategori']) . "</td>";
            echo "<td>" . htmlspecialchars($row['jenis']) . "</td>";
            echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
            echo "<td>" . (isset($row['poskod']) ? htmlspecialchars($row['poskod']) : 'N/A') . "</td>";
            echo "<td>" . (isset($row['daerah_jajahan']) ? htmlspecialchars($row['daerah_jajahan']) : 'N/A') . "</td>";
            echo "<td>" . (isset($row['negeri']) ? htmlspecialchars($row['negeri']) : 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['maklumat_pelajar']) . "</td>";
            echo "<td><img src='uploads/" . htmlspecialchars($row['gambar']) . "' width='100'></td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

<div class="menu">
    <a href="admin_dashboard.php">🏠 Kembali ke Dashboard</a>
    <a href="logout.php">🚪 Log Keluar</a>
</div>

</body>
</html>




