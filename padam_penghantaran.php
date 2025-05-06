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

// Semak dahulu sama ada status adalah 'Selesai' dan vendor memang empunya penghantaran itu
$sql_check = "SELECT * FROM penghantaran WHERE id = ? AND vendor_id = ? AND status_kutipan = 'Selesai'";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $penghantaran_id, $vendor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Padam rekod
    $sql_delete = "DELETE FROM penghantaran WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $penghantaran_id);
    if ($stmt_delete->execute()) {
        header("Location: vendor_penghantaran.php?msg=deleted");
        exit;
    } else {
        echo "Gagal padam penghantaran.";
    }
} else {
    echo "Tidak dibenarkan. Sama ada penghantaran bukan milik anda atau belum selesai.";
}

$conn->close();
?>
