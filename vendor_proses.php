<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pastikan hanya vendor boleh proses
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'vendor') {
    echo "Akses ditolak!";
    exit;
}

// Ambil ID penghantaran & tindakan
$id = $_GET['id'] ?? null;
$aksi = $_GET['aksi'] ?? null;

if ($id && in_array($aksi, ['terima', 'tolak', 'selesai'])) {
    // Tentukan status baru
    if ($aksi == 'terima') {
        $status_baru = 'diterima vendor';
    } elseif ($aksi == 'tolak') {
        $status_baru = 'ditolak vendor';
    } else {
        $status_baru = 'selesai';
    }

    $stmt = $conn->prepare("UPDATE penghantaran SET status_penghantaran = ? WHERE id = ?");
    $stmt->bind_param("si", $status_baru, $id);

    if ($stmt->execute()) {
        // Redirect terus balik ke senarai vendor_penghantaran.php
        header("Location: vendor_penghantaran.php?status=updated");
        exit;
    } else {
        echo "Gagal kemaskini status.";
    }
} else {
    echo "Permintaan tidak sah.";
}
?>
