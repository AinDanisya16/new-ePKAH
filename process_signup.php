<?php
// Sambung ke pangkalan data
$dsn = 'mysql:host=localhost;dbname=epkah';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Ambil data dari borang
$peranan = $_POST['peranan'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$telefon = $_POST['telefon'];
$alamat = $_POST['alamat'];
$poskod = $_POST['poskod'];
$jajahan = $_POST['jajahan'];
$negeri = $_POST['negeri'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$nama_syarikat = isset($_POST['nama_syarikat']) ? $_POST['nama_syarikat'] : null;
$no_syarikat = isset($_POST['no_syarikat']) ? $_POST['no_syarikat'] : null;
$lokasi_kutipan = isset($_POST['lokasi_kutipan']) ? $_POST['lokasi_kutipan'] : null;
$jenis_barang = isset($_POST['jenis_barang']) ? implode(', ', $_POST['jenis_barang']) : null;

// Query untuk memasukkan data ke dalam jadual `users` (sebelum ni `pengguna`)
$sql = "INSERT INTO users (peranan, nama, email, telefon, alamat, poskod, jajahan, negeri, password, nama_syarikat, no_syarikat, lokasi_kutipan, jenis_barang)
        VALUES (:peranan, :nama, :email, :telefon, :alamat, :poskod, :jajahan, :negeri, :password, :nama_syarikat, :no_syarikat, :lokasi_kutipan, :jenis_barang)";

$stmt = $pdo->prepare($sql);

// Bind parameter kepada query
$stmt->bindParam(':peranan', $peranan);
$stmt->bindParam(':nama', $nama);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':telefon', $telefon);
$stmt->bindParam(':alamat', $alamat);
$stmt->bindParam(':poskod', $poskod);
$stmt->bindParam(':jajahan', $jajahan);
$stmt->bindParam(':negeri', $negeri);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':nama_syarikat', $nama_syarikat);
$stmt->bindParam(':no_syarikat', $no_syarikat);
$stmt->bindParam(':lokasi_kutipan', $lokasi_kutipan);
$stmt->bindParam(':jenis_barang', $jenis_barang);

// Jalankan query untuk memasukkan data
if ($stmt->execute()) {
    echo "Pendaftaran berjaya!";
} else {
    echo "Ralat semasa pendaftaran.";
}
?>
