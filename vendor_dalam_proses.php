<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Semak jika bukan vendor
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'vendor') {
    echo "Akses ditolak!";
    exit;
}

$sql = "SELECT p.*, u.nama FROM penghantaran p
        JOIN users u ON p.user_id = u.id
        WHERE p.status_penghantaran = 'diterima vendor' 
        ORDER BY p.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Penghantaran Dalam Proses</title>
    <style>
        table { border-collapse: collapse; width: 95%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        a.btn {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-tindakan { background-color: green; color: white; }

        .nav-btn {
            display: inline-block;
            margin-right: 10px;
            padding: 8px 15px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }
        .semakan { background-color: blue; }
        .proses { background-color: orange; }
        .ditolak { background-color: red; }
        .selesai-nav { background-color: green; }
    </style>
</head>
<body>
    <h2>Senarai Penghantaran Diterima Vendor (Dalam Proses)</h2>

    <div style="margin-bottom: 20px;">
        <a href="vendor_penghantaran.php" class="nav-btn semakan">📩 Dalam Semakan</a>
        <a href="vendor_dalam_proses.php" class="nav-btn proses">📦 Dalam Proses</a>
        <a href="vendor_ditolak.php" class="nav-btn ditolak">❌ Ditolak</a>
        <a href="vendor_selesai.php" class="nav-btn selesai-nav">✅ Selesai</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama Pengguna</th>
            <th>Kategori</th>
            <th>Jenis</th>
            <th>Alamat</th>
            <th>Tarikh</th>
            <th>Status</th>
            <th>Tindakan</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= $row['kategori'] ?></td>
            <td><?= $row['jenis'] ?></td>
            <td><?= $row['alamat'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td><?= $row['status_penghantaran'] ?></td>
            <td>
                <a class="btn btn-tindakan" href="vendor_proses.php?id=<?= $row['id'] ?>&aksi=selesai">✅ Tandakan Selesai</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
