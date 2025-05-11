<?php
// Kod proses penyimpanan data kutipan kat sini
// ...

// Kalau berjaya insert:
echo '
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Berjaya!</title>
    <meta http-equiv="refresh" content="3;url=laporan_data_kutipan.php">
    <style>
        body {
            background: #e8f5e9;
            color: #2e7d32;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 60px;
        }
        .box {
            background: #c8e6c9;
            padding: 30px;
            border-radius: 15px;
            display: inline-block;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .box h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }
        .box a {
            display: inline-block;
            margin-top: 20px;
            background: #66bb6a;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
        }
        .emoji {
            font-size: 48px;
            display: block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="emoji">✅📦</div>
        <h2>Penghantaran Berjaya!</h2>
        <p>Anda akan dialihkan ke laporan kutipan dalam beberapa saat...</p>
        <a href="laporan_data_kutipan.php">📄 Lihat Penghantaran Sekarang</a>
    </div>
</body>
</html>
';
exit;
?>
