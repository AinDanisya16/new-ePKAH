<?php
session_start();

// Semak jika pengguna adalah vendor
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'vendor') {
    echo "Akses ditolak!";
    exit;
}

// Sambung ke database
$conn = new mysqli("localhost", "root", "", "ePKAH");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form
$kategori = $_POST['kategori'];
$jenis = $_POST['jenis'];
$berat_kg = $_POST['berat_kg'];
$nilai_rm = $_POST['nilai_rm'];
$tarikh_kutipan = $_POST['tarikh_kutipan'];
$penghantaran_id = $_POST['penghantaran_id'];  // Id penghantaran dari form

// Masukkan data ke dalam tabel data_kutipan
$sql = "INSERT INTO data_kutipan (penghantaran_id, kategori, jenis, berat_kg, nilai_rm, tarikh_kutipan) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issdss", $penghantaran_id, $kategori, $jenis, $berat_kg, $nilai_rm, $tarikh_kutipan);

if ($stmt->execute()) {
    // Berjaya insert data, redirect ke laporan data kutipan
    header("Location: laporan_data_kutipan.php");
    exit();
} else {
    echo "Gagal memasukkan data kutipan: " . $stmt->error;
}

$conn->close();
?>
