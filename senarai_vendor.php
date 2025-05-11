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

// Ambil nilai filter jika ada
$lokasi_kutipan_filter = isset($_POST['lokasi_kutipan']) ? $_POST['lokasi_kutipan'] : '';
$jenis_barang_filter = isset($_POST['jenis_barang']) ? $_POST['jenis_barang'] : '';

// Dapatkan lokasi kutipan yang unik dan tidak kosong dari database
$lokasi_sql = "SELECT DISTINCT lokasi_kutipan FROM users WHERE peranan = 'vendor' AND lokasi_kutipan IS NOT NULL AND lokasi_kutipan != '' ORDER BY lokasi_kutipan";
$lokasi_result = $conn->query($lokasi_sql);

// Membina SQL berdasarkan filter
$sql = "SELECT id, nama_syarikat, no_syarikat, alamat, poskod, negeri, lokasi_kutipan, jenis_barang FROM users WHERE peranan = 'vendor'";

if ($lokasi_kutipan_filter || $jenis_barang_filter) {
    $sql .= " AND 1=1";
    if ($lokasi_kutipan_filter) {
        $sql .= " AND lokasi_kutipan LIKE '%" . $conn->real_escape_string($lokasi_kutipan_filter) . "%'";
    }
    if ($jenis_barang_filter) {
        $sql .= " AND jenis_barang LIKE '%" . $conn->real_escape_string($jenis_barang_filter) . "%'";
    }
}

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

        .filter-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .filter-container select, .filter-container button {
            padding: 10px;
            font-size: 16px;
            margin: 5px;
        }
    </style>
</head>
<body>

<h2>🌿 Senarai Vendor Berdaftar e-PKAH 🌿</h2>

<div class="filter-container">
    <form method="POST" action="">
        <select name="lokasi_kutipan">
            <option value="">Pilih Lokasi Kutipan</option>
            <?php while ($lokasi_row = $lokasi_result->fetch_assoc()) { ?>
                <option value="<?= htmlspecialchars($lokasi_row['lokasi_kutipan']) ?>" <?= $lokasi_kutipan_filter == $lokasi_row['lokasi_kutipan'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($lokasi_row['lokasi_kutipan']) ?>
                </option>
            <?php } ?>
        </select>
        <select name="jenis_barang">
            <option value="">Pilih Jenis Barang Dikutip</option>
            <option value="Minyak Masak Terpakai (UCO)" <?= $jenis_barang_filter == 'Minyak Masak Terpakai (UCO)' ? 'selected' : ''; ?>>Minyak Masak Terpakai (UCO)</option>
            <option value="Barangan Kitar Semula (3R)" <?= $jenis_barang_filter == 'Barangan Kitar Semula (3R)' ? 'selected' : ''; ?>>Barangan Kitar Semula (3R)</option>
            <option value="E-waste" <?= $jenis_barang_filter == 'E-waste' ? 'selected' : ''; ?>>E-waste</option>
        </select>
        <button type="submit">Tapis</button>
    </form>
</div>

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
