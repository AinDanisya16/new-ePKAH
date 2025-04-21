<?php
session_start();
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] != 'admin') {
    echo "Akses ditolak!";
    exit;
}

$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, nama, telefon, peranan FROM users WHERE status = 'pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Luluskan Pengguna</title>
</head>
<body>
    <h2>Senarai Pengguna Belum Diluluskan</h2>

    <?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>Nama</th>
            <th>No Telefon</th>
            <th>Peranan</th>
            <th>Tindakan</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['telefon']) ?></td>
            <td><?= htmlspecialchars($row['peranan']) ?></td>
            <td>
                <a href="proses_luluskan.php?id=<?= $row['id'] ?>&tindakan=lulus" onclick="return confirm('Luluskan pengguna ini?')">✅ Luluskan</a> |
                <a href="proses_luluskan.php?id=<?= $row['id'] ?>&tindakan=tolak" onclick="return confirm('Tolak pengguna ini?')">❌ Tolak</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>Tiada pengguna yang menunggu kelulusan.</p>
    <?php endif; ?>

    <br>
    <a href="admin_dashboard.php">⬅ Kembali ke Dashboard</a>
</body>
</html>

