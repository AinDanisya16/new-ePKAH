<?php
include 'config.php';

// Semak jika ada kelulusan
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    mysqli_query($conn, "UPDATE users SET Status='approved' WHERE id=$id");
    header("Location: admin.php");
}

// Dapatkan senarai pengguna belum lulus
$result = mysqli_query($conn, "SELECT * FROM users WHERE Status='pending'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Senarai Pengguna Baharu</title>
</head>
<body>
    <h2>Senarai Pengguna Baharu (Pending)</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No Telefon</th>
            <th>Peranan</th>
            <th>Tindakan</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['Nama'] ?></td>
            <td><?= $row['Email'] ?></td>
            <td><?= $row['Telefon'] ?></td>
            <td><?= $row['Peranan'] ?></td>
            <td><a href="admin.php?approve=<?= $row['id'] ?>" onclick="return confirm('Luluskan akaun ini?')">Luluskan</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
