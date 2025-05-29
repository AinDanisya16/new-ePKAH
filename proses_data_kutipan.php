<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if (!isset($_SESSION['user_id']) || $_SESSION['peranan'] !== 'vendor') {
    die("❌ Akses tidak dibenarkan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['penghantaran_id'], $_POST['kutipan_vendor'])) {
    $penghantaran_id = intval($_POST['penghantaran_id']);
    $kutipanList = $_POST['kutipan_vendor'];

    $stmt = $conn->prepare("INSERT INTO kutipan_vendor (penghantaran_id, kategori, item_3r, berat, nilai) VALUES (?, ?, ?, ?, ?)");

foreach ($_POST['kutipan_vendor'] as $item) {
        $kategori = $conn->real_escape_string($item['kategori']);
        $item_3r = isset($item['item_3r']) ? $conn->real_escape_string($item['item_3r']) : null;
        $berat = floatval($item['berat']);
        $nilai = floatval($item['nilai']);

        $stmt->bind_param("issdd", $penghantaran_id, $kategori, $item_3r, $berat, $nilai);
        $stmt->execute();
    }
    $stmt->close();

// Ganti di proses_data_kutipan.php
    header("Location: laporan_data_kutipan.php?msg=Berjaya+tambah+kutipan");
exit();
    exit();
} else {
    die("❌ Data tidak lengkap.");
}
?>
