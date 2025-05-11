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

// Ambil nilai filter dari borang (jika ada)
$jajahan_daerah_filter = isset($_POST['jajahan_daerah']) ? $_POST['jajahan_daerah'] : '';
$jenis_filter = isset($_POST['jenis']) ? $_POST['jenis'] : '';
$status_filter = isset($_POST['status']) ? $_POST['status'] : '';

// Bangunkan SQL query berdasarkan filter
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
        WHERE p.status_penghantaran = 'dalam semakan'";

if ($jajahan_daerah_filter) {
    $sql .= " AND p.jajahan_daerah = '" . $conn->real_escape_string($jajahan_daerah_filter) . "'";
}

if ($jenis_filter) {
    $sql .= " AND p.jenis = '" . $conn->real_escape_string($jenis_filter) . "'";
}

if ($status_filter) {
    $sql .= " AND p.status_kutipan = '" . $conn->real_escape_string($status_filter) . "'";
}

$sql .= " ORDER BY p.id DESC";
$result = $conn->query($sql);

// Senarai vendor untuk dropdown
$vendors = [];
$vendorQuery = $conn->query("SELECT id, nama FROM users WHERE peranan = 'vendor'");
while ($v = $vendorQuery->fetch_assoc()) {
    $vendors[] = $v;
}

// Senarai pilihan untuk filter
$jajahanQuery = $conn->query("SELECT DISTINCT jajahan_daerah FROM penghantaran WHERE jajahan_daerah != '' AND jajahan_daerah IS NOT NULL");
$jenisQuery = $conn->query("SELECT DISTINCT jenis FROM penghantaran");
$statusQuery = $conn->query("SELECT DISTINCT status_kutipan FROM penghantaran");
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

<!-- Filter Form -->
<form method="post" action="">
    <div style="display: flex; justify-content: center; gap: 15px;">
        <select name="jajahan_daerah">
            <option value="">Pilih Jajahan/Daerah</option>
            <?php while ($row = $jajahanQuery->fetch_assoc()) { ?>
                <option value="<?= $row['jajahan_daerah'] ?>" <?= $row['jajahan_daerah'] == $jajahan_daerah_filter ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['jajahan_daerah']) ?>
                </option>
            <?php } ?>
        </select>
        
        <select name="jenis">
            <option value="">Pilih Jenis</option>
            <?php while ($row = $jenisQuery->fetch_assoc()) { ?>
                <option value="<?= $row['jenis'] ?>" <?= $row['jenis'] == $jenis_filter ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['jenis']) ?>
                </option>
            <?php } ?>
        </select>
        
        <select name="status">
            <option value="">Pilih Status</option>
            <?php while ($row = $statusQuery->fetch_assoc()) { ?>
                <option value="<?= $row['status_kutipan'] ?>" <?= $row['status_kutipan'] == $status_filter ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['status_kutipan']) ?>
                </option>
            <?php } ?>
        </select>
        
        <button type="submit" style="background-color: #66bb6a; color: white; border: none; border-radius: 4px; padding: 6px 12px;">
            🔍 Cari
        </button>
    </div>
</form>

<!-- Data Table -->
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
