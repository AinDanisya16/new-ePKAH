<?php
session_start();

// Semak jika pengguna sudah log masuk dan berperanan 'sekolah/agensi'
if (!isset($_SESSION['user_id']) || $_SESSION['peranan'] !== 'sekolah/agensi') {
    header("Location: login.php");
    exit;
}

$nama = $_SESSION['nama'];
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Sekolah/Agensi</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f1f8e9;
            text-align: center;
            padding: 40px;
            font-size: 20px;
        }

        .dashboard {
            background: white;
            display: inline-block;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }

        h2 {
            color: #388e3c;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            margin: 12px;
            text-decoration: none;
            background: #66bb6a;
            color: white;
            padding: 12px 22px;
            border-radius: 8px;
            font-size: 18px;
        }

        a:hover {
            background: #558b2f;
        }

        .logout {
            background-color: #e53935;
        }

        .logout:hover {
            background-color: #c62828;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Selamat datang, <?php echo htmlspecialchars($nama); ?>!</h2>
        <p>Anda sedang menggunakan akaun Sekolah / Agensi.</p>

    <div class="menu">
        <a href="hantar_kitar_semula.php">➕ Hantar Kitar Semula</a>
        <a href="sekolah_penghantaran.php">📦 Lihat Penghantaran Saya</a>
        <a href="logout.php">🚪 Log Keluar</a>
    </div>

</body>
</html>
