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

if (isset($_GET['id']) && isset($_GET['tindakan']) && isset($_GET['peranan'])) {
    $id = intval($_GET['id']);
    $tindakan = $_GET['tindakan'];
    $peranan = $_GET['peranan']; // 'user' or 'vendor'

    // Admin approval or rejection logic untuk pengguna dan vendor
    if ($peranan == 'user') {
        if ($tindakan == 'lulus') {
            // Luluskan pengguna
            $sql = "UPDATE users SET status = 'approved' WHERE id = ? AND peranan = 'user'";
        } elseif ($tindakan == 'tolak') {
            // Buang pengguna
            $sql = "DELETE FROM users WHERE id = ? AND peranan = 'user'";
        }
    } elseif ($peranan == 'vendor') {
        if ($tindakan == 'lulus') {
            // Luluskan vendor
            $sql = "UPDATE users SET status = 'approved' WHERE id = ? AND peranan = 'vendor'";
        } elseif ($tindakan == 'tolak') {
            // Buang vendor
            $sql = "DELETE FROM users WHERE id = ? AND peranan = 'vendor'";
        }
    }

    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_luluskan_pengguna.php");
        exit;
    } else {
        echo "Ralat semasa memproses tindakan.";
    }
} else {
    echo "Parameter tidak lengkap.";
}
?>


