<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borang Penghantaran Kitar Semula</title>
    <style>
        body {
            font-family: Arial, monospace;
            background: #f0fff0;
            padding: 20px;
            font-size: 24px;
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
            font-size: 24px;
        }

        nav {
            text-align: center;
            margin-bottom: 15px;
        }

        nav a {
            margin: 0 8px;
            text-decoration: none;
            color: #1b5e20;
            font-weight: bold;
            font-size: 24px;
        }

        nav a:hover {
            color: #43a047;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #e8f5e9;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #2e7d32;
            font-weight: bold;
            font-size: 20px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #a5d6a7;
            border-radius: 6px;
            font-size: 20px;
        }

        button {
            background-color: #43a047;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-size: 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2e7d32;
        }

        #desc-3r {
            margin-top: 20px;
            padding: 10px;
            background-color: #dcedc8;
            border: 1px solid #a5d6a7;
            border-radius: 6px;
            font-size: 16px;
            color: #2e7d32;
            display: none;
        }
    </style>

    <script>
        function updateDescription() {
            var kategori = document.getElementById("kategori").value;
            var desc3r = document.getElementById("desc-3r");

            if (kategori === "3R") {
                desc3r.style.display = "block";
            } else {
                desc3r.style.display = "none";
            }
        }
    </script>
</head>
<body>

    <h2>Borang Penghantaran Kitar Semula</h2>

    <nav>
        <a href="hantar_kitar_semula.php">➕ Hantar Kitar Semula</a>
        <a href="pengguna_penghantaran.php">📦 Lihat Penghantaran Saya</a>
        <a href="logout.php">🚪 Log Keluar</a>
    </nav>

    <form action="proses_penghantaran.php" method="POST" enctype="multipart/form-data">
        <label>Kategori:</label>
        <select id="kategori" name="kategori" required onchange="updateDescription()">
            <option value="">--Pilih Kategori--</option>
            <option value="UCO">Minyak Masak Terpakai (UCO)</option>
            <option value="3R">Barangan Kitar Semula (3R)</option>
            <option value="E-waste">Barang Elektrik & Elektronik (E-waste)</option>
        </select>

        <div id="desc-3r">
            <strong>Penerangan Kitar Semula (3R):</strong>
            <p>Barang kitar semula yang diterima ialah plastik, kotak, kertas, tin aluminium, dan besi.</p>
        </div>

        <label>Jenis:</label>
        <select name="jenis" required>
            <option value="">--Pilih Jenis--</option>
            <option value="sedekah">Sedekah</option>
            <option value="jual">Jual</option>
        </select>

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

        <label>No Telefon Untuk Dihubungi:</label>
        <input type="text" name="No_Telefon_Untuk_Dihubungi">

        <label>Gambar Barang:</label>
        <input type="file" name="gambar" accept="image/*" required>

        <label>Nama Pelajar (jika berkaitan):</label>
        <input type="text" name="nama_pelajar">

        <label>Nama Sekolah:</label>
        <input type="text" name="nama_sekolah">

        <label>Kelas:</label>
        <input type="text" name="kelas">

        <label>Cadangan Tarikh Kutipan:</label>
        <input type="date" name="cadangan_tarikh_kutipan" required>

        <button type="submit">Hantar</button>
    </form>

</body>
</html>
