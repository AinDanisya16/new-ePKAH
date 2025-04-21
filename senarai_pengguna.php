<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Semak jika admin
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'admin') {
    echo "Akses ditolak!";
    exit;
}

// Proses luluskan/tolak
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE users SET status = 'approved' WHERE id = $id");
} elseif (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $conn->query("UPDATE users SET status = 'rejected' WHERE id = $id");
}

// Ambil semua pengguna
$sql = "SELECT id, nama, telefon, peranan, status FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Senarai Pengguna</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .btn-approve {
            color: green;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-reject {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Senarai Pengguna</h2>
    <table>
        <tr>
            <th>Nama</th>
            <th>Telefon</th>
            <th>Peranan</th>
            <th>Status</th>
            <th>Tindakan</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['telefon']) ?></td>
                <td><?= htmlspecialchars($row['peranan']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <?php if ($row['status'] == 'pending') { ?>
                        <a class="btn-approve" href="?approve=<?= $row['id'] ?>">✅ Luluskan</a> |
                        <a class="btn-reject" href="?reject=<?= $row['id'] ?>">❌ Tolak</a>
                    <?php } else { ?>
                        Tiada tindakan
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

