<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>epkah - Kitar Semula</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #d4fc79, #96e6a1);
            font-family: 'Arial', sans-serif;
            text-align: center;
        }
        .logo {
            margin-top: 30px;
        }
        .logo img {
            width: 120px;
            height: auto;
        }
        h1 {
            color: #2e7d32;
            margin-top: 10px;
            font-size: 40px;
            letter-spacing: 1px;
        }
        .container {
            display: flex;
            justify-content: center;
            margin-top: 50px;
            gap: 40px;
            flex-wrap: wrap;
        }
        .card {
            background: #ffffff;
            border-radius: 30px;
            padding: 30px 20px;
            width: 220px;
            height: 260px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transition: transform 0.4s, box-shadow 0.4s;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .card:hover {
            transform: translateY(-12px) scale(1.05);
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
            background: #e8f5e9;
        }
        .card img {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 20px;
            color: #2e7d32;
            font-weight: bold;
            margin-top: 10px;
        }
        footer {
            margin-top: 60px;
            color: #4caf50;
            font-size: 14px;
            padding-bottom: 30px;
        }
    </style>
</head>
<body>

    <div class="logo">
        <img src="logo_epkah.png" alt="Logo epkah">
    </div>

    <h1>Selamat Datang ke ePKAH</h1>

    <div class="container">
        <div class="card" onclick="location.href='lokasi.php'">
            <img src="https://cdn-icons-png.flaticon.com/512/854/854878.png" alt="Lokasi">
            <div class="card-title">Lokasi Pusat Kitar Semula</div>
        </div>

        <div class="card" onclick="location.href='program.php'">
            <img src="https://cdn-icons-png.flaticon.com/512/3534/3534388.png" alt="Program">
            <div class="card-title">Program Kitar Semula</div>
        </div>

        <div class="card" onclick="location.href='index.php'">
            <img src="https://cdn-icons-png.flaticon.com/512/1828/1828506.png" alt="Log Masuk">
            <div class="card-title"> Daftar Pengguna Baharu & Log Masuk Pengguna</div>
        </div>
    </div>

    <footer>
        © 2025 e-PKAH | Alam Sekitar Tanggungjawab Kita Bersama 🌱
    </footer>

</body>
</html>
