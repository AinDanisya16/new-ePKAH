<?php
// hantar_kitar_semula.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$peranan = $_SESSION['peranan'];
require "db.php";
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Borang Penghantaran Kitar Semula</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #e8f5e9;
            padding: 20px;
            font-size: 20px;
            color: #2e7d32;
        }
        .logo {
            display: block;
            margin: 0 auto 15px;
            width: 120px;
            height: 100px;
        }
        h2 {
            text-align: center;
            color: #2e7d32;
            font-size: 30px;
            margin-bottom: 20px;
        }
        nav {
            text-align: center;
            margin-bottom: 20px;
        }
        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #43a047;
            font-weight: bold;
            font-size: 22px;
        }
        nav a:hover {
            color: #2e7d32;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            background: #f1f8e9;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #388e3c;
            font-weight: bold;
            font-size: 18px;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #81c784;
            border-radius: 12px;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        input:focus, select:focus {
            border-color: #43a047;
            box-shadow: 0 0 5px rgba(67, 160, 71, 0.5);
        }
        button {
            background: #43a047;
            color: #fff;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 12px;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background: #2e7d32;
        }
        #desc-3r, #desc-ewaste {
            margin-top: 20px;
            padding: 12px;
            background: #dcedc8;
            border: 1px solid #a5d6a7;
            border-radius: 12px;
            font-size: 16px;
            color: #2e7d32;
            display: none;
        }
        .emoji {
            font-size: 30px;
        }
    </style>
    <script>
        function updateDescription(){
            var kategori = document.getElementById("kategori").value;
            document.getElementById("desc-3r").style.display = kategori === "3R" ? "block" : "none";
            document.getElementById("desc-ewaste").style.display = kategori === "E-waste" ? "block" : "none";
        }
    </script>
</head>
<body>
    <h2>💚 Borang Penghantaran Kitar Semula 🍃</h2>
    <p style="text-align:center; font-size:22px; color:#388e3c;">
        ID Pengguna Anda: <strong><?php echo htmlspecialchars($user_id); ?></strong> 🌱
    </p>
    <nav>
        <a href="hantar_kitar_semula.php">➕ Hantar Kitar Semula</a>
        <?php if ($peranan == 'sekolah/agensi'): ?>
            <a href="sekolah_penghantaran.php">📦 Lihat Penghantaran Saya</a>
        <?php else: ?>
            <a href="pengguna_penghantaran.php">📦 Lihat Penghantaran Saya</a>
        <?php endif; ?>
        <a href="logout.php">🚪 Log Keluar</a>
    </nav>

    <form action="proses_penghantaran.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <input type="hidden" name="peranan" value="<?php echo htmlspecialchars($peranan); ?>">

        <label>Kategori:</label>
        <select id="kategori" name="kategori" required onchange="updateDescription()">
            <option value="">--Pilih Kategori--</option>
            <option value="UCO">🛢️ Minyak Masak Terpakai (UCO)</option>
            <option value="3R">🔄 Barangan Kitar Semula (3R)</option>
            <option value="E-waste">💻 Barang Elektrik & Elektronik (E-waste)</option>
        </select>

        <div id="desc-3r">
            <strong>Penerangan Kitar Semula (3R):</strong>
            <p>Barang kitar semula yang diterima ialah plastik, kotak, kertas, tin aluminium, dan besi.</p>
        </div>

        <div id="desc-ewaste">
            <strong>Penerangan Kitar Semula (E-waste):</strong>
            <p>Barang yang berkaitan elektrik & elektronik, contohnya : powerbank, wayar, telefon bimbit, TV, aircond, cerek, dan lain-lain. 🔌📱💻</p>
        </div>

        <label>Jenis:</label>
        <select name="jenis" required>
            <option value="">--Pilih Jenis--</option>
            <option value="sedekah">🎁 Sedekah</option>
            <option value="jual">💰 Jual</option>
        </select>

        <label>Alamat:</label>
        <input type="text" name="alamat" required>

        <label>Poskod:</label>
        <input type="text" name="poskod" required>

        <label>Jajahan/Daerah:</label>
        <select name="jajahan_daerah" required>
            <option value="">--Pilih Jajahan/Daerah--</option>
            <option>Bachok</option><option>Gua Musang</option><option>Jeli</option>
            <option>Kota Bharu</option><option>Kuala Krai</option><option>Machang</option>
            <option>Pasir Mas</option><option>Pasir Puteh</option><option>Tanah Merah</option>
            <option>Tumpat</option>
        </select>

        <label>Negeri:</label>
        <select name="negeri" required>
            <option>Kelantan</option>
        </select>

        <label>No Telefon Untuk Dihubungi:</label>
        <input type="text" name="no_telefon_untuk_dihubungi">

        <label>Gambar Barang:</label>
        <input type="file" name="gambar" accept="image/*" required>

        <?php if ($peranan == 'sekolah/agensi'): ?>
            <label>Nama Pelajar:</label>
            <input type="text" name="nama_pelajar" required>

            <label>Nama Sekolah:</label>
            <input type="text" name="nama_sekolah" required>

            <label>Kelas:</label>
            <input type="text" name="kelas" required>
        <?php endif; ?>

        <label>Cadangan Tarikh Kutipan:</label>
        <input type="date" name="cadangan_tarikh_kutipan" required>

        <button type="submit">Hantar 🌍</button>
    </form>
</body>
</html>
