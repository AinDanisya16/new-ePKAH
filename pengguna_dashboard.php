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
    <title>Dashboard Pengguna</title>
    <style>
        body {
            font-family: Consolas, monospace;
            background: #f0fff0;
            padding: 30px;
            font-size: 20px;
        }

        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 120px;
            height: auto;
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            font-size: 28px;
        }

        .menu {
            margin-top: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .menu a {
            display: block;
            padding: 15px 20px;
            margin: 10px 0;
            background-color: #e8f5e9;
            border: 2px solid #a5d6a7;
            border-radius: 8px;
            text-decoration: none;
            color: #1b5e20;
            font-size: 20px;
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: #c8e6c9;
        }
    </style>
</head>
<body>

    <img src="logo_epkah.png" alt="Logo ePKAH" class="logo">

    <h2>Selamat Datang ke Dashboard Pengguna</h2>

    <div class="menu">
        <a href="hantar_kitar_semula.php">➕ Hantar Kitar Semula</a>
        <a href="pengguna_penghantaran.php">📦 Lihat Penghantaran Saya</a>
        <a href="logout.php">🚪 Log Keluar</a>
    </div>

</body>
</html>

