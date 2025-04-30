<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Semak peranan vendor
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'vendor') {
    echo "Akses ditolak!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['penghantaran_id'])) {
    $penghantaran_id = intval($_POST['penghantaran_id']);

    // Kemaskini status kutipan dan penghantaran kepada selesai
    $sql = "UPDATE penghantaran 
            SET status_kutipan = 'selesai', status_penghantaran = 'selesai' 
            WHERE id = ? AND vendor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $penghantaran_id, $_SESSION['user_id']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: senarai_kutipan_vendor.php?berjaya=1");
        exit;
    } else {
        echo "❌ Gagal sahkan kutipan. Sila cuba lagi.";
    }

    $stmt->close();
} else {
    echo "❌ Permintaan tidak sah.";
}
?>
