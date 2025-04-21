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
</head>
<body>
    <!-- Navigasi Menu -->
    <nav>
        <a href="hantar_kitar_semula.php">Hantar Kitar Semula</a> |
        <a href="pengguna_penghantaran.php">Lihat Penghantaran Saya</a> |
        <a href="logout.php">Log Keluar</a>
    </nav>
    <hr>

    <h2>Hantar Kitar Semula</h2>
    <form action="proses_penghantaran.php" method="POST" enctype="multipart/form-data">
        <label>Kategori:</label><br>
        <select name="kategori" required>
            <option value="">--Pilih Kategori--</option>
            <option value="UCO">UCO</option>
            <option value="3R">3R</option>
            <option value="E-waste">E-waste</option>
        </select><br><br>

        <label>Jenis:</label><br>
        <select name="jenis" required>
            <option value="">--Pilih Jenis--</option>
            <option value="Sedekah">Sedekah</option>
            <option value="Jual">Jual</option>
        </select><br><br>

        <label>Alamat Kutipan / Penghantaran:</label><br>
        <input type="text" id="poskod" name="poskod" placeholder="Poskod"><br><br>
        <input type="text" id="jajahan" name="jajahan" placeholder="Jajahan"><br><br>
        <input type="text" id="negeri" name="negeri" placeholder="Negeri"><br><br>

        <label>Gambar Barang:</label><br>
        <input type="file" name="gambar" accept="image/*" required><br><br>

        <label>Nama Pelajar (jika berkaitan):</label><br>
        <input type="text" name="nama_pelajar"><br><br>

        <label>Nama Sekolah </label><br>
        <input type="text" name="nama_sekolah"><br><br>

        <label>Kelas</label><br>
        <input type="text" name="kelas"><br><br>

        <label>Cadangan Tarikh Kutipan</label><br>
        <input type="text" name="cadangan_tarikh_kutipan"><br><br>

        <button type="submit">Hantar</button>
    </form>
</body>
</html>
