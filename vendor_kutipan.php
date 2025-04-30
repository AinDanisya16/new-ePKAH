<?php
session_start();
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'vendor') {
    echo "Akses ditolak!";
    exit;
}

$conn = new mysqli("localhost", "root", "", "ePKAH");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$vendor_id = $_SESSION['user_id'];

// Get assigned delivery requests for this vendor
$sql = "SELECT p.*, u.nama AS nama_pengguna FROM penghantaran p 
        JOIN users u ON p.user_id = u.id
        WHERE p.vendor_id = ? 
        ORDER BY p.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle kutipan confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['penghantaran_id'])) {
    $penghantaran_id = $_POST['penghantaran_id'];
    $status_kutipan = 'Selesai';

    // Update status kutipan to 'Selesai'
    $update_sql = "UPDATE penghantaran SET status_kutipan = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $status_kutipan, $penghantaran_id);
    if ($update_stmt->execute()) {
        echo "Status kutipan telah dikemas kini!";
    } else {
        echo "Ralat mengemas kini status kutipan!";
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Kutipan Vendor</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e8f5e9;
            padding: 25px;
            font-size: 18px;
        }

        h2 { text-align: center; color: #2e7d32; }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 10px;
            border: 1px solid #81c784;
            text-align: center;
        }

        th { background-color: #388e3c; color: white; }
        td { background-color: #c8e6c9; }

        .menu {
            margin-top: 30px;
            text-align: center;
        }

        .menu a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background-color: #66bb6a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Senarai Penghantaran Diberikan kepada Anda</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Nama Pengguna</th>
        <th>Alamat</th>
        <th>Poskod</th>
        <th>Jajahan/Daerah</th>
        <th>Negeri</th>
        <th>Tarikh</th>
        <th>Status Kutipan</th>
        <th>Tindakan</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
        <td><?= htmlspecialchars($row['alamat']) ?></td>
        <td><?= $row['poskod'] ?></td>
        <td><?= $row['jajahan/daerah'] ?></td>
        <td><?= $row['negeri'] ?></td>
        <td><?= $row['created_at'] ?></td>
        <td><?= $row['status_kutipan'] ?></td>
        <td>
            <?php if ($row['status_kutipan'] !== 'Selesai') { ?>
            <form method="post">
                <input type="hidden" name="penghantaran_id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn-sahkan">Sahkan Kutipan</button>
            </form>
            <?php } else { echo "✅ Selesai"; } ?>
        </td>
    </tr>
    <?php } ?>
</table>

<div class="menu">
    <a href="vendor_dashboard.php">🏠 Kembali ke Dashboard</a>
    <a href="logout.php">🚪 Logout</a>
</div>

</body>
</html>
