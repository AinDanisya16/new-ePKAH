<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

// Semak login & role
if (!isset($_SESSION['user_id']) || $_SESSION['peranan'] !== 'admin') {
    echo "Akses ditolak! Log masuk sebagai admin untuk akses dashboard ini.";
    exit;
}

// Kalau 'nama' tak wujud dalam session, elakkan warning
if (!isset($_SESSION['nama'])) {
    $_SESSION['nama'] = 'Admin'; // default nama kalau tak ada
}

// Query untuk ambil penghantaran
$query = "SELECT * FROM penghantaran WHERE status_penghantaran = 'dalam semakan' ORDER BY tarikh_hantar DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body { 
            font-family: 'Consolas', sans-serif;
            padding: 15px;
            background-color: #f7fdf5;
            font-size: 18px;
            color: #2e7d32;
        }

        h2 {
            text-align: center;
            color: #388e3c;
            padding-top: 30px;
            font-size: 32px;
        }

        .menu {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .menu a {
            display: block;
            margin: 12px 0;
            padding: 12px;
            background: #e1f5e1;
            text-decoration: none;
            color: black;
            border: 2px solid #388e3c;
            width: 75%;
            text-align: center;
            font-size: 20px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .menu a:hover { 
            background: #d0e8ff;
        }

        .logo {
            display: block;
            margin: 0 auto 16px;
            width: 120px;
            height: 100px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #66bb6a;
            text-align: center;
        }

        th {
            background-color: #a5d6a7;
        }
    </style>
</head>
<body>

    <img src="logo_epkah.png" alt="Logo ePKAH" class="logo">   
    <h2>Selamat Datang, Admin <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h2>

    <div class="menu">
        <a href="profil_admin.php"> 👤 Profil Saya </a>
        <a href="senarai_pengguna.php">👥 Senarai Pengguna</a>
        <a href="senarai_vendor.php">🏭 Senarai Vendor</a>
        <a href="admin_penghantaran.php">📦 Senarai Penghantaran</a>
        <a href="statistik.php">📊 Lihat Statistik</a>
        <a href="logout.php">🚪 Log Keluar</a>
    </div>

</body>
</html>
