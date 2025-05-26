<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if (!isset($_SESSION['user_id']) || $_SESSION['peranan'] !== 'vendor') {
    die("❌ Akses tidak dibenarkan.");
}

if (isset($_POST['penghantaran_id']) && isset($_POST['kutipan'])) {
    $penghantaran_id = intval($_POST['penghantaran_id']);
    $kutipan = $_POST['kutipan'];

    foreach ($kutipan as $item) {
        $kategori = $conn->real_escape_string($item['kategori']);
        $berat = floatval($item['berat']);
        $nilai = floatval($item['nilai']);
        $jenis = isset($item['item']) ? $conn->real_escape_string($item['item']) : '-';
        $tarikh_kutipan = date("Y-m-d"); // hari ini

        $sql = "INSERT INTO kutipan_vendor (penghantaran_id, kategori, jenis, berat_kg, nilai_rm, tarikh_kutipan) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdds", $penghantaran_id, $kategori, $jenis, $berat, $nilai, $tarikh_kutipan);
        $stmt->execute();
    }

    header("Location: laporan_data_kutipan.php");
    exit();
} else {
    echo "❌ Data tidak lengkap.";
}
?>
