<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM penghantaran WHERE user_id = '$user_id' ORDER BY tarikh_hantar DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Senarai Penghantaran Anda</title>
</head>
<body>
    <h2>Senarai Penghantaran Kitar Semula Anda</h2>
    <a href="hantar_kitar_semula.php">+ Hantar Baru</a><br><br>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Jenis</th>
                <th>Poskod</th>
                <th>Jajahan</th>
                <th>Negeri</th>
                <th>Nama Pelajar</th>
                <th>Sekolah</th>
                <th>Kelas</th>
                <th>Tarikh Hantar</th>
                <th>Gambar</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['kategori'] ?></td>
                    <td><?= $row['jenis'] ?></td>
                    <td><?= $row['poskod'] ?></td>
                    <td><?= $row['jajahan'] ?></td>
                    <td><?= $row['negeri'] ?></td>
                    <td><?= $row['nama_pelajar'] ?></td>
                    <td><?= $row['nama_sekolah'] ?></td>
                    <td><?= $row['kelas'] ?></td>
                    <td><?= $row['tarikh_hantar'] ?></td>
                    <td>
                        <?php if ($row['gambar']): ?>
                            <img src="uploads/<?= $row['gambar'] ?>" width="100">
                        <?php else: ?>
                            Tiada
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Tiada penghantaran direkodkan.</p>
    <?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
