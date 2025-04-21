<?php
session_start();
include("db.php");

if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] != 'vendor') {
    echo "Akses ditolak!";
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $vendor_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE penghantaran SET status = 'diterima_vendor', vendor_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $vendor_id, $id);
    $stmt->execute();

    header("Location: vendor_penghantaran.php");
    exit;
}
?>
