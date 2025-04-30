<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'admin') {
    echo "Akses ditolak!";
    exit;
}

$search = $_GET['search'] ?? '';
$filter_vendor = $_GET['vendor'] ?? '';

// Senarai semua vendor untuk dropdown
$vendorList = $conn->query("SELECT id, nama FROM users WHERE peranan = 'vendor'");

// Query asas
$sql = "SELECT p.*, u.nama AS nama_pengguna, v.nama AS nama_vendor 
        FROM penghantaran p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN users v ON p.vendor_id = v.id
        WHERE p.status_penghantaran = 'selesai'";

// Tambah filter carian
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (
        u.nama LIKE '%$search%' OR 
        v.nama LIKE '%$search%' OR 
        p.alamat LIKE '%$search%'
    )";
}

// Tambah filter vendor
if (!empty($filter_vendor)) {
    $filter_vendor = (int)$filter_vendor;
    $sql .= " AND p.vendor_id = $filter_vendor";
}

$sql .= " ORDER BY p.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Senarai Penghantaran Selesai</title>
    <style>
        body { font-family: "Arial", monospace; font-size: 18px; background-color: #f0fdf4; color: #388e3c; margin: 0; padding: 0; }
        h2 { text-align: center; padding-top: 20px; }
        table { border-collapse: collapse; width: 95%; margin: 20px auto; background: #fff; border-radius: 8px; box-shadow: 0 0 8px rgba(0, 0, 0, 0.1); }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #66bb6a; color: white; }
        td { background-color: #f9f9f9; }
        .btn { padding: 5px 10px; border-radius: 5px; }
        .selesai { background-color: #43a047; color: white; }
        .nav-btn { margin-right: 10px; padding: 6px 10px; text-decoration: none; border-radius: 5px; color: white; font-weight: bold; }
        .semakan { background-color: blue; } .ditolak { background-color: red; } .selesai-nav { background-color: green; }
        .menu { text-align: center; margin: 20px 0; }
        .menu a { display: inline-block; margin: 8px; padding: 8px 16px; background: #a5d6a7; text-decoration: none; color: #1b5e20; border-radius: 50px; font-weight: bold; }
        .menu a:hover { background-color: #81c784; }
        .filter-form { text-align: center; margin-top: 10px; }
        .filter-form input, .filter-form select { padding: 6px 10px; font-size: 16px; margin: 5px; border-radius: 5px; border: 1px solid #ccc; }
        .filter-form button { padding: 6px 12px; background-color: #66bb6a; color: white; border: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
<h2>Senarai Penghantaran Selesai</h2>

<div style="text-align: center; margin-bottom: 10px;">
    <a href="admin_penghantaran.php" class="nav-btn semakan">📩 Dalam Semakan</a>
    <a href="admin_ditolak.php" class="nav-btn ditolak">❌ Ditolak</a>
    <a href="admin_selesai.php" class="nav-btn selesai-nav">✅ Selesai</a>
</div>

<div class="filter-form">
    <form method="GET">
        <input type="text" name="search" placeholder="Cari nama / alamat..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <select name="vendor">
            <option value="">-- Tapis Vendor --</option>
            <?php while ($vendor = $vendorList->fetch_assoc()) { ?>
                <option value="<?= $vendor['id'] ?>" <?= ($filter_vendor == $vendor['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($vendor['nama']) ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit">Cari</button>
    </form>
</div>

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
        <th>Nama Vendor</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
            <td><?= $row['kategori'] ?></td>
            <td><?= $row['jenis'] ?></td>
            <td><?= $row['alamat'] ?></td>
            <td><?= $row['poskod'] ?></td>
            <td><?= $row['jajahan/daerah'] ?></td>
            <td><?= $row['negeri'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td><span class="btn selesai">📦 <?= $row['status_kutipan'] ?></span></td>
            <td><?= htmlspecialchars($row['nama_vendor'] ?? '-') ?></td>
        </tr>
    <?php } ?>
</table>

<div class="menu">
    <a href="admin_dashboard.php">🏠 Kembali ke Dashboard</a>
    <a href="logout.php">🚪 Log Keluar</a>
</div>
</body>
</html>





