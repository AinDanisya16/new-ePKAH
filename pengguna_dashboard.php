<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pengguna</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h2 { color: green; }
        .menu { margin-top: 20px; }
        .menu a {
            display: block;
            padding: 10px;
            margin: 5px 0;
            width: 300px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #000;
        }
        .menu a:hover {
            background-color: #d0ffd0;
        }
    </style>
</head>
<body>

    <h2>Selamat Datang ke Dashboard Pengguna</h2>

    <div class="menu">
        <a href="hantar_kitar_semula.php">➕ Hantar Kitar Semula</a>
        <a href="pengguna_penghantaran.php">📦 Lihat Penghantaran Saya</a>
        <a href="logout.php">🚪 Log Keluar</a>
    </div>

</body>
</html>
