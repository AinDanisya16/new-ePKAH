<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pastikan hanya admin boleh proses
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'admin') {
    echo "Akses ditolak!";
    exit;
}

// Ambil ID penghantaran & tindakan
$id = $_GET['id'] ?? null;
$aksi = $_GET['aksi'] ?? null;

if ($id && in_array($aksi, ['terima', 'tolak', 'selesai'])) {
    // Tentukan status baru
    if ($aksi == 'terima') {
        $status_baru = 'diterima admin';
    } elseif ($aksi == 'tolak') {
        $status_baru = 'ditolak admin';
    } else {
        $status_baru = 'selesai';
    }

    $stmt = $conn->prepare("UPDATE penghantaran SET status_penghantaran = ? WHERE id = ?");
    $stmt->bind_param("si", $status_baru, $id);

    if ($stmt->execute()) {
        // Redirect terus balik ke senarai admin_penghantaran.php
        header("Location: admin_penghantaran.php?status=updated");
        exit;
    } else {
        echo "Gagal kemaskini status.";
    }
} else {
    echo "Permintaan tidak sah.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Proses Penghantaran</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7fdf5;
            color: #2e7d32;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #388e3c;
            padding-top: 50px;
            font-size: 36px;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 40px;
        }

        .button {
            display: inline-block;
            margin: 20px 10px;
            padding: 12px 20px;
            background-color: #66bb6a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 20px;
            font-weight: bold;
        }

        .button:hover {
            background-color: #388e3c;
            transition: 0.3s ease;
        }

        .back-btn {
            background-color: #81c784;
        }

        .back-btn:hover {
            background-color: #66bb6a;
        }

        .status-msg {
            text-align: center;
            margin-top: 20px;
            font-size: 24px;
            color: #388e3c;
        }

        .error-msg {
            color: red;
            font-size: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>Proses Penghantaran</h2>

    <div class="container">
        <div class="status-msg">
            <?php if (isset($status_baru)): ?>
                <p>Status Penghantaran Berjaya Dikemaskini ke: <strong><?= ucfirst($status_baru) ?></strong></p>
            <?php else: ?>
                <p class="error-msg">Permintaan tidak sah atau berlaku kesilapan.</p>
            <?php endif; ?>
        </div>

        <div style="text-align: center;">
            <a href="admin_penghantaran.php" class="button back-btn">Kembali ke Senarai Penghantaran</a>
        </div>
    </div>

</body>
</html>
