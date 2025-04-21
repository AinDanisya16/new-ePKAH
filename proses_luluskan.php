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

if (isset($_GET['id']) && isset($_GET['tindakan'])) {
    $id = intval($_GET['id']);
    $tindakan = $_GET['tindakan'];

    if ($tindakan == 'lulus') {
        $sql = "UPDATE users SET status = 'approved' WHERE id = $id";
    } elseif ($tindakan == 'tolak') {
        $sql = "DELETE FROM users WHERE id = $id";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_luluskan_pengguna.php");
        exit;
    } else {
        echo "Ralat semasa memproses tindakan.";
    }
} else {
    echo "Parameter tidak lengkap.";
}
