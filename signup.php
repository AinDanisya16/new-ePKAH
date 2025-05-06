<?php
$conn = new mysqli("localhost", "root", "", "ePKAH");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $peranan = $_POST['peranan'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $telefon = filter_var(trim($_POST['telefon']), FILTER_SANITIZE_STRING); // Sanitize phone number
    $alamat = $_POST['alamat'];
    $poskod = $_POST['poskod'];
    $jajahan = $_POST['jajahan'];
    $negeri = $_POST['negeri'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $id_kakitangan = $_POST['id_kakitangan'] ?? null;
    $nama_syarikat = $_POST['nama_syarikat'] ?? null;
    $no_syarikat = $_POST['no_syarikat'] ?? null;
    $lokasi_kutipan = $_POST['lokasi_kutipan'] ?? null;
    $jenis_barang = isset($_POST['jenis_barang']) ? implode(", ", $_POST['jenis_barang']) : null; // Ubah sini

    $status = 'approved';  // Set status to 'approved' for vendors to bypass admin approval

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users 
        (nama, email, telefon, alamat, poskod, jajahan, negeri, peranan, password, nama_syarikat, no_syarikat, lokasi_kutipan, jenis_barang, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssss", 
        $nama, $email, $telefon, $alamat, $poskod, $jajahan, $negeri, $peranan, $password, 
        $nama_syarikat, $no_syarikat, $lokasi_kutipan, $jenis_barang, $status);

    if ($stmt->execute()) {
    echo "<script>alert('Pendaftaran berjaya! Anda boleh log masuk sekarang.'); window.location.href='index.php';</script>";
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
    <style>
        body {
            font-family: Arial, monospace;
            background: #f0fff0;
            padding: 20px;
            font-size: 20px;
        }
        form {
            max-width: 650px;
            margin: auto;
            padding: 25px;
            background: #e8f5e9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2e7d32;
            font-size: 24px;
        }
        label {
            font-weight: bold;
            color: #388e3c;
            display: block;
            margin-bottom: 6px;
        }
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            margin: 6px 0 18px;
            border: 1px solid #a5d6a7;
            border-radius: 6px;
            font-size: 16px;
            font-family: Arial, monospace;
        }
        button {
            background-color: #43a047;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 22px;
        }
        button:hover {
            background-color: #2e7d32;
        }
        .terms-box {
            background: #f1f8e9;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #c5e1a5;
            margin-bottom: 18px;
        }
        .terms-box ul {
            padding-left: 20px;
            margin-top: 10px;
            color: #33691e;
        }
        .checkbox-group {
            display: flex;
            flex-direction: column;
            margin-top: 6px;
            margin-bottom: 18px;
        }
        .checkbox-group label {
            font-weight: normal;
            color: #2e7d32;
            margin-bottom: 8px;
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 90px;
            height: auto;
        }
    </style>
    <script>
        function toggleFields() {
            const role = document.querySelector('select[name="peranan"]').value;
            document.getElementById('vendor-fields').style.display = role === 'vendor' ? 'block' : 'none';
            document.getElementById('admin-field').style.display = role === 'admin' ? 'block' : 'none';
            document.getElementById('default-terms').style.display = (role === 'pengguna') ? 'block' : 'none';
            document.getElementById('sekolah/agensi-terms').style.display = (role === 'sekolah/agensi') ? 'block' : 'none';
            document.getElementById('vendor-terms').style.display = (role === 'vendor') ? 'block' : 'none';
            document.getElementById('terms-label').style.display = (role !== 'admin') ? 'block' : 'none';
        }
    </script>
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
        <input type="password" name="password" required
               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
               title="Mesti ada sekurang-kurangnya 8 aksara, huruf besar, huruf kecil, nombor & simbol">

        <div id="vendor-fields" style="display:none;">
            <label>Nama Syarikat:</label>
            <input type="text" name="nama_syarikat">
            <label>No Syarikat:</label>
            <input type="text" name="no_syarikat">
            <label>Lesen PBT:</label>
            <input type="file" name="lesen_pbt">
            <label>Lesen JAS:</label>
            <input type="file" name="lesen_jas">
            <label>Lesen SWCorp:</label>
            <input type="file" name="lesen_swcorp">
            <label>Lokasi Kutipan:</label>
            <input type="text" name="lokasi_kutipan">
            
            <label>Jenis Barang yang Dikutip:</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="jenis_barang[]" value="Minyak Masak Terpakai (UCO)"> Minyak Masak Terpakai (UCO)</label>
                <label><input type="checkbox" name="jenis_barang[]" value="Barangan Kitar Semula (3R)"> Barangan Kitar Semula (3R)</label>
                <label><input type="checkbox" name="jenis_barang[]" value="Barang Elektrik & Elektronik (E-waste)"> Barang Elektrik & Elektronik (E-waste)</label>
                <label><input type="checkbox" name="jenis_barang[]" value="Lain-lain"> Lain-lain</label>
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
</body>
</html> 