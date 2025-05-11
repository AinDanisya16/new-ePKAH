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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $penghantaran_id = $_POST['penghantaran_id'];

    // Ubah status kutipan atau teruskan padamkan
    $sql = "UPDATE penghantaran SET status_kutipan = 'Selesai' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $penghantaran_id);
    
    if ($stmt->execute()) {
        // Redirect kembali ke senarai penghantaran vendor setelah berjaya kemaskini
        header("Location: vendor_penghantaran.php");
        exit;
    } else {
        echo "Ralat mengemaskini data!";
    }

    $stmt->close();
}

$conn->close();
?>
