<?php
session_start();
include("db.php"); // Pastikan fail db.php wujud dan betul

if (!isset($_SESSION['user_id'])) {
    die("Sila log masuk dahulu.");
}

// Ambil data dari borang
$user_id = $_SESSION['user_id'];
$kategori = $_POST['kategori'];
$jenis = $_POST['jenis'];
$poskod = $_POST['poskod'];
$jajahan = $_POST['jajahan'];
$negeri = $_POST['negeri'];
$nama_pelajar = $_POST['nama_pelajar'] ?? '';
$nama_sekolah = $_POST['nama_sekolah'] ?? '';
$kelas = $_POST['kelas'] ?? '';
$cadangan_tarikh_kutipan = $_POST['cadangan_tarikh_kutipan'] ?? '';

// Handle gambar upload
$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$gambar = $_FILES["gambar"]["name"];
$targetFile = $targetDir . basename($gambar);
move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile);

// Masukkan ke database
$stmt = $conn->prepare("INSERT INTO penghantaran (user_id, kategori, jenis, poskod, jajahan, negeri, gambar, nama_pelajar, nama_sekolah, kelas, cadangan_tarikh_kutipan, tarikh_hantar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

$stmt->bind_param("issssssssss", $user_id, $kategori, $jenis, $poskod, $jajahan, $negeri, $gambar, $nama_pelajar, $nama_sekolah, $kelas, $cadangan_tarikh_kutipan);

if ($stmt->execute()) {
    echo "Penghantaran berjaya dihantar!";
    echo "<br><a href='pengguna_dashboard.php'>Kembali ke Dashboard</a>";
} else {
    echo "Ralat: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>