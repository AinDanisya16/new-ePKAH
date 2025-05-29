<?php
$conn = new mysqli("localhost", "root", "", "ePKAH");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi jana ID mengikut peranan
function generateUserID($role, $conn) {
    $prefixes = [
        'pengguna' => 'PE',
        'sekolah/agensi' => 'SA',
        'vendor' => 'VE',
        'admin' => 'AD'
    ];

    $prefix = $prefixes[$role] ?? 'XX';

    // Kira bilangan user untuk peranan tersebut
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE peranan = ?");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = (int)$row['total'] + 1;

    return $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $peranan = $_POST['peranan'];
    $id_pengguna = generateUserID($peranan, $conn); // ID dijana di sini
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $telefon = filter_var(trim($_POST['telefon']), FILTER_SANITIZE_STRING);
    $alamat = $_POST['alamat'];
    $poskod = $_POST['poskod'];
    $jajahan = $_POST['jajahan'];
    $negeri = $_POST['negeri'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $id_kakitangan = $_POST['id_kakitangan'] ?? null;
    $nama_syarikat = $_POST['nama_syarikat'] ?? null;
    $no_syarikat = $_POST['no_syarikat'] ?? null;
    $lokasi_kutipan = $_POST['lokasi_kutipan'] ?? null;
    $jenis_barang = isset($_POST['jenis_barang']) ? implode(", ", $_POST['jenis_barang']) : null;
    $status = 'approved';

    $stmt = $conn->prepare("INSERT INTO users 
        (id_pengguna, nama, email, telefon, alamat, poskod, jajahan, negeri, peranan, password, nama_syarikat, no_syarikat, lokasi_kutipan, jenis_barang, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssssss", 
        $id_pengguna, $nama, $email, $telefon, $alamat, $poskod, $jajahan, $negeri, $peranan, $password, 
        $nama_syarikat, $no_syarikat, $lokasi_kutipan, $jenis_barang, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Pendaftaran berjaya! ID Pengguna anda: $id_pengguna'); window.location.href='index.php';</script>";
        exit;
    } else {
        echo "Ralat: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengguna Baru</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <img src="logo_epkah.png" alt="Logo ePKAH" class="logo">
    <h2>Daftar Akaun e-PKAH</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label>Peranan:</label>
        <select name="peranan" required onchange="toggleFields()">
            <option value="">--Pilih Peranan--</option>
            <option value="pengguna">Pengguna</option>
            <option value="sekolah/agensi">Sekolah/Agensi</option>
            <option value="vendor">Vendor</option>
            <option value="admin">Admin</option>
        </select>

        <div style="display:none;">
            <label>ID Pengguna:</label>
            <input type="text" id="generated-id" name="id_dummy" readonly>
        </div>

        <label>Nama:</label>
        <input type="text" name="nama" required>

        <div id="admin-field" style="display:none;">
            <label>No ID Kakitangan:</label>
            <input type="text" name="id_kakitangan">
        </div>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>No Telefon:</label>
        <input type="text" name="telefon" required>

        <label>Alamat:</label>
        <input type="text" name="alamat" required>

        <label>Poskod:</label>
        <input type="text" name="poskod" required>

        <label>Jajahan/Daerah:</label>
        <select name="jajahan" required>
            <option value="">--Pilih Jajahan--</option>
            <option value="Bachok">Bachok</option>
            <option value="Gua Musang">Gua Musang</option>
            <option value="Jeli">Jeli</option>
            <option value="Kota Bharu">Kota Bharu</option>
            <option value="Kuala Krai">Kuala Krai</option>
            <option value="Machang">Machang</option>
            <option value="Pasir Mas">Pasir Mas</option>
            <option value="Pasir Puteh">Pasir Puteh</option>
            <option value="Tanah Merah">Tanah Merah</option>
            <option value="Tumpat">Tumpat</option>
        </select>

        <label>Negeri:</label>
        <select name="negeri" required>
            <option value="Kelantan">Kelantan</option>
        </select>

        <label>Katalaluan:</label>
        <div style="position: relative;">
            <input type="password" name="password" id="passwordField" required
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
                title="Mesti ada sekurang-kurangnya 8 aksara, huruf besar, huruf kecil, nombor & simbol"
                style="width: 100%; padding-right: 40px;">
            
        </div>

        <div id="vendor-fields" style="display:none;">
            <label>Nama Syarikat:</label>
            <input type="text" name="nama_syarikat">

            <label>No Syarikat:</label>
            <input type="text" name="no_syarikat">

            <label>Lokasi Pengumpulan:</label>
            <input type="text" name="lokasi_kutipan">

            <div class="checkbox-group">
                <label>Jenis Barang:</label>
                <label><input type="checkbox" name="jenis_barang[]" value="UCO"> Minyak Masak Terpakai (UCO)</label>
                <label><input type="checkbox" name="jenis_barang[]" value="e-waste"> E-Waste</label>
                <label><input type="checkbox" name="jenis_barang[]" value="plastic"> Plastik</label>
                <label><input type="checkbox" name="jenis_barang[]" value="aluminum"> Aluminium</label>
                <label><input type="checkbox" name="jenis_barang[]" value="kertas"> Kertas</label>
            </div>
        </div>

           <div class="terms-box" id="default-terms">
            <strong>Terma & Syarat:</strong>
            <ul>
                <li>Barang yang dikutip perlu berada dalam keadaan bersih dan telah diasingkan mengikut kategori kitar semula.</li>
                <li>Jika barang tidak memenuhi kriteria kebersihan dan pengasingan, pihak kami berhak untuk tidak mengambil barang tersebut bagi memastikan proses kitar semula berjalan dengan lancar dan efektif.</li>
                <li>Hasil jualan sedekah akan disumbangkan kepada organisasi kebajikan seperti Masjid atau badan amal lain yang berkaitan.</li>
            </ul>
        </div>

        <div class="terms-box" id="sekolah/agensi-terms">
            <strong>Terma & Syarat:</strong>
            <ul>
                <li>Barang yang dikutip perlu berada dalam keadaan bersih dan telah diasingkan mengikut kategori kitar semula.</li>
                <li>Jika barang tidak memenuhi kriteria kebersihan dan pengasingan, pihak kami berhak untuk tidak mengambil barang tersebut bagi memastikan proses kitar semula berjalan dengan lancar dan efektif.</li>
            </ul>
        </div>

        <div class="terms-box" id="vendor-terms" style="display:none;">
            <strong>Terma & Syarat Vendor:</strong>
            <ul>
                <li>Kutipan perlu dibuat mengikut maklumat lesen dan lokasi yang telah diberikan semasa pendaftaran.</li>
                <li>Vendor bertanggungjawab memastikan proses kutipan berjalan mengikut jadual dan mematuhi garis panduan agensi berkaitan.</li>            
            </ul>
        </div>

        <div class="terms-box">
            <input type="checkbox" name="terms" required> Saya setuju dengan <a href="#">Terma dan Syarat</a>
        </div>

        <button type="submit">Daftar</button>
    </form>

    <nav>
        <a href="index.php">➕ Login</a>
    </nav>

</body>
</html> 
