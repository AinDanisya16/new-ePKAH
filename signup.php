<?php
$conn = new mysqli("localhost", "root", "", "ePKAH");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $peranan = $_POST['peranan'];
    $sub_peranan = $_POST['sub_peranan'] ?? null;
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
        (nama, email, telefon, alamat, poskod, jajahan, negeri, peranan, password, nama_syarikat, no_syarikat, lokasi_kutipan, jenis_barang, status, id_kakitangan, sub_peranan) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssss", 
        $nama, $email, $telefon, $alamat, $poskod, $jajahan, $negeri, $peranan, $password, 
        $nama_syarikat, $no_syarikat, $lokasi_kutipan, $jenis_barang, $status, $id_kakitangan, $sub_peranan);

    if ($stmt->execute()) {
        echo "<script>alert('🌟 Pendaftaran berjaya! Anda boleh log masuk sekarang.'); window.location.href='index.php';</script>";
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
    <title>Daftar Akaun 🌱 e-PKAH</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f1fff1;
            margin: 0;
            padding: 20px;
        }

        form {
            background: #d0f0c0;
            border-radius: 16px;
            padding: 30px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 0 12px rgba(0, 80, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            font-size: 26px;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 14px;
            color: #388e3c;
        }

        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            margin-bottom: 18px;
            border: 1px solid #a5d6a7;
            border-radius: 8px;
            background-color: #fafffa;
            font-size: 16px;
        }

        .checkbox-group label {
            font-weight: normal;
            color: #2e7d32;
            display: block;
            margin-top: 6px;
        }

        button {
            background-color: #66bb6a;
            color: white;
            border: none;
            padding: 14px;
            font-size: 20px;
            width: 100%;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #43a047;
        }

        .logo {
            display: block;
            margin: auto;
            width: 90px;
            height: auto;
        }

        .terms-box {
            background-color: #e8f5e9;
            border: 1px solid #c5e1a5;
            padding: 16px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .terms-box ul {
            padding-left: 20px;
        }

        .emoji {
            font-size: 1.2em;
            margin-right: 6px;
        }

        .sub-peranan-group {
            margin-top: -10px;
        }
    </style>

    <script>
        function toggleFields() {
            const role = document.querySelector('select[name="peranan"]').value;
            document.getElementById('vendor-fields').style.display = role === 'vendor' ? 'block' : 'none';
            document.getElementById('admin-field').style.display = role === 'admin' ? 'block' : 'none';
            document.getElementById('pengguna-subrole').style.display = role === 'pengguna' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <img src="logo_epkah.png" alt="Logo ePKAH" class="logo">
    <h2>🌿 Daftar Akaun Baru e-PKAH</h2>

    <form method="POST" action="" enctype="multipart/form-data">
        <label><span class="emoji">👥</span>Peranan:</label>
        <select name="peranan" required onchange="toggleFields()">
            <option value="">-- Pilih Peranan --</option>
            <option value="pengguna">Pengguna</option>
            <option value="vendor">Vendor</option>
            <option value="admin">Admin</option>
        </select>

        <div id="pengguna-subrole" class="sub-peranan-group" style="display:none;">
            <label><span class="emoji">🏷️</span>Jenis Pengguna:</label>
            <select name="sub_peranan">
                <option value="">-- Pilih Jenis --</option>
                <option value="individu">Individu</option>
                <option value="organisasi">Organisasi</option>
                <option value="agensi">Agensi</option>
            </select>
        </div>

        <label><span class="emoji">📛</span>Nama:</label>
        <input type="text" name="nama" required>

        <div id="admin-field" style="display:none;">
            <label><span class="emoji">🆔</span>No ID Kakitangan:</label>
            <input type="text" name="id_kakitangan">
        </div>

        <label><span class="emoji">📧</span>Email:</label>
        <input type="email" name="email" required>

        <label><span class="emoji">📞</span>No Telefon:</label>
        <input type="text" name="telefon" required>

        <label><span class="emoji">🏡</span>Alamat:</label>
        <input type="text" name="alamat" required>

        <label><span class="emoji">📮</span>Poskod:</label>
        <input type="text" name="poskod" required>

        <label><span class="emoji">📍</span>Jajahan/Daerah:</label>
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

        <label><span class="emoji">🗺️</span>Negeri:</label>
        <select name="negeri" required>
            <option value="Kelantan">Kelantan</option>
        </select>

        <label><span class="emoji">🔐</span>Katalaluan:</label>
        <input type="password" name="password" required 
               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
               title="Mesti ada sekurang-kurangnya 8 aksara, huruf besar, huruf kecil, nombor & simbol">

        <div id="vendor-fields" style="display:none;">
            <label>🏢 Nama Syarikat:</label>
            <input type="text" name="nama_syarikat">
            <label>📜 No Syarikat:</label>
            <input type="text" name="no_syarikat">
            <label>📍 Lokasi Kutipan:</label>
            <input type="text" name="lokasi_kutipan">

            <label>📦 Jenis Barang Dikutip:</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="jenis_barang[]" value="Minyak Masak Terpakai (UCO)"> 🌻 Minyak Masak Terpakai (UCO)</label>
                <label><input type="checkbox" name="jenis_barang[]" value="Barangan Kitar Semula (3R)"> ♻️ Barangan Kitar Semula (3R)</label>
                <label><input type="checkbox" name="jenis_barang[]" value="Barang Elektrik & Elektronik (E-waste)"> 🔌 E-Waste</label>
                <label><input type="checkbox" name="jenis_barang[]" value="Lain-lain"> 🧺 Lain-lain</label>
            </div>
        </div>

        <div class="terms-box">
            <input type="checkbox" name="terms" required>
            Saya setuju dengan <a href="#">Terma dan Syarat</a>
        </div>

        <button type="submit">🌸 Daftar Sekarang</button>
    </form>
</body>
</html>
