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

$sql = "SELECT p.*, u.nama AS nama_pengguna FROM penghantaran p 
        JOIN users u ON p.user_id = u.id
        WHERE p.vendor_id = ? 
        ORDER BY p.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Senarai Penghantaran Vendor</title>
    <style>
        body { font-family: Arial, sans-serif; background: #e8f5e9; padding: 20px; }
        h2 { text-align: center; color: #2e7d32; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #81c784; text-align: center; }
        th { background: #388e3c; color: white; }
        td { background: #c8e6c9; }
        .menu { margin-top: 30px; text-align: center; }
        .menu a { padding: 10px 20px; background: #66bb6a; color: white; text-decoration: none; border-radius: 8px; margin: 0 5px; }
        .btn-tambah { padding: 8px 15px; background-color: #43a047; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') { ?>
    <p style="color: green; text-align:center;">Penghantaran berjaya dipadam.</p>
<?php } ?>

<body>

<h2>Senarai Penghantaran Diberikan kepada Anda</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Nama Pengguna</th>
        <th>No Telefon Untuk Dihubungi</th>
        <th>Kategori</th>
        <th>Jenis</th>
        <th>Alamat</th>
        <th>Poskod</th>
        <th>Jajahan/Daerah</th>
        <th>Negeri</th>
        <th>Tarikh Hantar</th>
        <th>Status Kutipan</th>
        <th>Tindakan</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
        <td><?= htmlspecialchars($row['no_telefon_untuk_dihubungi']) ?></td>
        <td><?= htmlspecialchars($row['kategori']) ?></td>
        <td><?= htmlspecialchars($row['jenis']) ?></td>
        <td><?= htmlspecialchars($row['alamat']) ?></td>
        <td><?= htmlspecialchars($row['poskod']) ?></td>
        <td><?= htmlspecialchars($row['jajahan_daerah']) ?></td>
        <td><?= htmlspecialchars($row['negeri']) ?></td>
        <td><?= $row['tarikh_hantar'] ?></td>
        <td><?= $row['status_kutipan'] ?></td>
        <td>
        <?php if ($row['status_kutipan'] !== 'Selesai') { ?>
        <form action="data_kutipan.php" method="post">
            <input type="hidden" name="penghantaran_id" value="<?= $row['id'] ?>">
            <button type="submit" class="btn-tambah">Tambah Kutipan</button>
        </form>
        <?php } else { ?>
        ✅ Selesai
        <form action="padam_penghantaran.php" method="post" onsubmit="return confirm('Padam penghantaran ini?');">
            <input type="hidden" name="penghantaran_id" value="<?= $row['id'] ?>">
            <button type="submit" class="btn-tambah" style="background-color:#d32f2f;">🗑️ Padam</button>
        </form>
        <?php } ?>
        </td>
        <td>
    </tr>
    <?php } ?>
</table>

<div class="menu">
    <a href="vendor_dashboard.php">🏠 Dashboard</a>
    <a href="logout.php">🚪 Logout</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
