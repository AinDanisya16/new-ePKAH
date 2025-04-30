<?php
session_start();
include("db.php");

if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] != 'admin') {
    echo "Akses ditolak!";
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $admin_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE penghantaran SET status = 'diterima_admin', admin_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $admin_id, $id);
    $stmt->execute();

    header("Location: admin_penghantaran.php");
    exit;
}
?>
