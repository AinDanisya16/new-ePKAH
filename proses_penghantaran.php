<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    die("❌ Sila log masuk terlebih dahulu untuk menghantar permintaan.");
}

$user_id = $_SESSION['user_id'];
$peranan = $_SESSION['peranan'] ?? 'pengguna';

// Ambil data dari borang
$kategori = $_POST['kategori'] ?? '';
$jenis = $_POST['jenis'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$poskod = $_POST['poskod'] ?? '';
$jajahan_daerah = $_POST['jajahan_daerah'] ?? '';
$negeri = $_POST['negeri'] ?? '';
$no_telefon = $_POST['no_telefon_untuk_dihubungi'] ?? '';
$nama_pelajar = $_POST['nama_pelajar'] ?? '';
$nama_sekolah = $_POST['nama_sekolah'] ?? '';
$kelas = $_POST['kelas'] ?? '';
$cadangan_tarikh_kutipan = $_POST['cadangan_tarikh_kutipan'] ?? '';

// Semak input wajib
if (empty($kategori) || empty($jenis) || empty($alamat) || empty($poskod) || empty($jajahan_daerah) || empty($negeri)) {
    die("❌ Sila lengkapkan semua maklumat wajib.");
}

// Uruskan muat naik gambar
$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$gambar = $_FILES["gambar"]["name"] ?? '';
if ($gambar) {
    $targetFile = $targetDir . basename($gambar);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if ($check === false) {
        die("❌ Fail bukan gambar yang sah.");
    }

    if ($_FILES["gambar"]["size"] > 2000000) {
        die("❌ Gambar melebihi saiz maksimum 2MB.");
    }

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        die("❌ Hanya fail JPG, JPEG, PNG & GIF dibenarkan.");
    }

    if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
        die("❌ Maaf, berlaku ralat semasa muat naik gambar.");
    }
} else {
    $gambar = NULL;
}

// Masukkan data ke dalam jadual penghantaran
$stmt = $conn->prepare("INSERT INTO penghantaran 
(user_id, peranan_penghantar, kategori, jenis, alamat, poskod, jajahan_daerah, negeri, no_telefon_untuk_dihubungi, gambar, nama_pelajar, nama_sekolah, kelas, cadangan_tarikh_kutipan, tarikh_hantar) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

$stmt->bind_param("isssssssssssss", 
    $user_id, $peranan, $kategori, $jenis, $alamat, $poskod, $jajahan_daerah, $negeri, $no_telefon, $gambar, $nama_pelajar, $nama_sekolah, $kelas, $cadangan_tarikh_kutipan);

if ($stmt->execute()) {
    // ✅ Simpan log aktiviti
    $tindakan = ($peranan === 'sekolah/agensi') 
        ? "Sekolah/Agensi menghantar borang penghantaran kategori $kategori ($jenis)"
        : "Pengguna menghantar borang penghantaran kategori $kategori ($jenis)";

    $log_stmt = $conn->prepare("INSERT INTO aktiviti_log (user_id, peranan, tindakan) VALUES (?, ?, ?)");
    $log_stmt->bind_param("iss", $user_id, $peranan, $tindakan);
    $log_stmt->execute();
    $log_stmt->close();

    // ✅ Tentukan link dinamik ikut peranan
    $link_papar = ($peranan === 'sekolah/agensi') ? 'sekolah_penghantaran.php' : 'pengguna_penghantaran.php';

    // Papar notis berjaya
    echo "
    <div style='
        background-color: #e8f5e9;
        color: #2e7d32;
        padding: 20px;
        margin: 50px auto;
        width: 90%;
        max-width: 600px;
        text-align: center;
        font-family: Arial, sans-serif;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        font-size: 20px;
    '>
        ✅ Penghantaran anda telah berjaya dihantar!<br><br>
        <a href='$link_papar' style='
            display: inline-block;
            margin-top: 15px;
            background-color: #43a047;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
        '>Lihat Penghantaran Saya</a>
    </div>
    ";
} else {
    echo "❌ Maaf, berlaku ralat: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
