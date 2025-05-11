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

$vendor_id = $_SESSION['user_id'];
$penghantaran_id = intval($_POST['penghantaran_id']);

// Semak penghantaran belum selesai dan milik vendor
$sql_check = "SELECT * FROM penghantaran WHERE id = ? AND vendor_id = ? AND status_kutipan != 'Selesai'";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $penghantaran_id, $vendor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Padam kutipan dulu kalau ada
    $sql_delete_kutipan = "DELETE FROM kutipan WHERE penghantaran_id = ?";
    $stmt_delete_kutipan = $conn->prepare($sql_delete_kutipan);
    $stmt_delete_kutipan->bind_param("i", $penghantaran_id);
    $stmt_delete_kutipan->execute();

    // Padam penghantaran
    $sql_delete = "DELETE FROM penghantaran WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $penghantaran_id);

    if ($stmt_delete->execute()) {
        header("Location: vendor_penghantaran.php?msg=deleted");
    } else {
        header("Location: vendor_penghantaran.php?msg=failed");
    }
} else {
    header("Location: vendor_penghantaran.php?msg=not_allowed");
}

$conn->close();
exit;
?>
