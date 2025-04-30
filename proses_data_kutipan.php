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
        $item_3r = isset($item['item']) ? $conn->real_escape_string($item['item']) : NULL;

        $sql = "INSERT INTO kutipan_vendor (penghantaran_id, kategori, berat, nilai, item_3r) 
                VALUES ('$penghantaran_id', '$kategori', '$berat', '$nilai', " . ($item_3r ? "'$item_3r'" : "NULL") . ")";

        if (!$conn->query($sql)) {
            die("❌ Gagal simpan data: " . $conn->error);
        }
    }

    echo "<script>alert('✅ Semua data kutipan berjaya disimpan!'); window.location.href='vendor_dashboard.php';</script>";
} else {
    die("❌ Data tidak lengkap.");
}
?>
