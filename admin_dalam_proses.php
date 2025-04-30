<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Semak jika bukan admin
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'admin') {
    echo "Akses ditolak!";
    exit;
}

$sql = "SELECT p.*, u.nama FROM penghantaran p
        JOIN users u ON p.user_id = u.id
        WHERE p.status_penghantaran = 'diterima admin' 
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
            font-size: 18px; /* Kurangkan font size */
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: #81c784;
        }
    </style>
</head>
<body>
    <h2>Senarai Penghantaran Diterima Admin (Dalam Proses)</h2>

    <div style="margin-bottom: 20px;">
        <a href="admin_penghantaran.php" class="nav-btn semakan">📩 Dalam Semakan</a>
        <a href="admin_dalam_proses.php" class="nav-btn proses">📦 Dalam Proses</a>
        <a href="admin_ditolak.php" class="nav-btn ditolak">❌ Ditolak</a>
        <a href="admin_selesai.php" class="nav-btn selesai-nav">✅ Selesai</a>
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
                <a class="btn btn-tindakan" href="admin_proses.php?id=<?= $row['id'] ?>&aksi=selesai">✅ Tandakan Selesai</a>
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
