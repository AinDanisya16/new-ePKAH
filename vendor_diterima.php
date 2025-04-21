<?php
session_start();
include("db.php");

if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] != 'vendor') {
    echo "Akses ditolak!";
    exit;
}

$vendor_id = $_SESSION['user_id'];

$sql = "SELECT p.*, u.nama 
        FROM penghantaran p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.status = 'diterima_vendor' AND p.vendor_id = $vendor_id 
        ORDER BY p.id DESC";

$result = $conn->query($sql);
?>

<h2>Senarai Penghantaran Diterima</h2>

<table border="1" cellpadding="8" cellspacing="0">
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
        <td><?= $row['nama']; ?></td>
        <td><?= $row['kategori']; ?></td>
        <td><?= $row['jenis']; ?></td>
        <td><?= $row['alamat']; ?></td>
        <td><img src="uploads/<?= $row['gambar']; ?>" width="80"></td>
        <td><?= $row['status']; ?></td>
    </tr>
    <?php } ?>
</table>
