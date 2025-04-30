<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Semak peranan admin
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'admin') {
    echo "Akses ditolak!";
    exit;
}
// Ambil semua penghantaran dalam semakan
$sql = "SELECT 
            p.id, 
            p.kategori, 
            p.jenis, 
            p.alamat, 
            p.poskod, 
            p.jajahan_daerah, 
            p.negeri, 
            p.gambar, 
            p.nama_pelajar, 
            p.nama_sekolah, 
            p.kelas, 
            p.cadangan_tarikh_kutipan, 
            p.tarikh_hantar, 
            p.status_kutipan, 
            u.nama, 
            p.no_telefon_untuk_dihubungi
        FROM penghantaran p
        JOIN users u ON p.user_id = u.id
        WHERE p.status_penghantaran = 'dalam semakan'
        ORDER BY p.id DESC";
$result = $conn->query($sql);
// Senarai vendor untuk dropdown
$vendors = [];
$vendorQuery = $conn->query("SELECT id, nama FROM users WHERE peranan = 'vendor'");
while ($v = $vendorQuery->fetch_assoc()) {
    $vendors[] = $v;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Senarai Penghantaran</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0fff0;
            padding: 25px;
            font-size: 18px;
        }
        h2 {
            text-align: center;
            color: #2e7d32;
            font-size: 28px;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            border: 1px solid #a5d6a7;
        }
        th, td {
            border: 1px solid #a5d6a7;
            padding: 8px 12px;
            text-align: center;
            font-size: 16px;
        }
        th {
            background-color: #43a047;
            color: white;
        }
        .menu {
            text-align: center;
            margin-top: 30px;
        }
        .menu a {
            display: inline-block;
            margin: 8px;
            padding: 8px 20px;
            background-color: #a5d6a7;
            text-decoration: none;
            color: #1b5e20;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
        }
        .menu a:hover {
            background-color: #81c784;
        }
        select, button {
            font-size: 14px;
            padding: 5px;
        }
    </style>
</head>
<body>

<h2>Senarai Penghantaran Pengguna (Menunggu Semakan)</h2>

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
        <th>No Telefon Untuk Dihubungi</th>
        <th>Tarikh Hantar</th>
        <th>Status Kutipan</th>
        <th>Assign Vendor</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['kategori']) ?></td>
        <td><?= htmlspecialchars($row['jenis']) ?></td>
        <td><?= htmlspecialchars($row['alamat']) ?></td>
        <td><?= htmlspecialchars($row['poskod']) ?></td>
        <td><?= htmlspecialchars($row['jajahan_daerah']) ?></td>
        <td><?= htmlspecialchars($row['negeri']) ?></td>
        <td><?= htmlspecialchars($row['no_telefon_untuk_dihubungi']) ?></td>
        <td><?= htmlspecialchars($row['tarikh_hantar']) ?></td>
        <td><?= htmlspecialchars($row['status_kutipan']) ?></td>
        <td>
            <form action="admin_assign_vendor.php" method="post">
                <input type="hidden" name="penghantaran_id" value="<?= $row['id'] ?>" />
                <select name="vendor_id">
                    <option value="">Pilih Vendor</option>
                    <?php foreach ($vendors as $vendor) { ?>
                        <option value="<?= $vendor['id'] ?>"><?= htmlspecialchars($vendor['nama']) ?></option>
                    <?php } ?>
                </select>
                <button type="submit" style="background-color: #ff9800; color: white; border: none; border-radius: 4px; padding: 6px 12px;">
                    🧾 Pilih
                </button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>

<div class="menu">
    <a href="admin_dashboard.php">🏠 Kembali ke Dashboard</a>
    <a href="logout.php">🚪 Log Keluar</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
