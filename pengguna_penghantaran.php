<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM penghantaran WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Penghantaran Saya</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #d0f0c0, #e6f5d0);
            padding: 30px;
            margin: 0;
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            font-size: 34px;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        nav {
            text-align: center;
            margin-bottom: 25px;
        }

        nav a {
            background-color: #81c784;
            color: white;
            padding: 12px 22px;
            border-radius: 30px;
            text-decoration: none;
            margin: 0 8px;
            font-size: 18px;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        nav a:hover {
            background-color: #66bb6a;
        }

        .container {
            max-width: 100%;
            overflow-x: auto;
            background: #f1f8e9;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
            margin-top: 15px;
            font-size: 14px;
            min-width: 1000px;
        }

        th, td {
            background-color: #ffffff;
            padding: 14px 16px;
            text-align: center;
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            vertical-align: middle;
        }

        th {
            background-color: #c5e1a5;
            color: #33691e;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr:hover td {
            background-color: #dcedc8;
            transition: 0.3s;
        }

        td:last-child {
            font-weight: bold;
            color: #2e7d32;
        }

        @media screen and (max-width: 768px) {
            table {
                font-size: 13px;
                min-width: 1000px;
            }

            nav a {
                display: inline-block;
                margin: 8px 5px;
                font-size: 16px;
                padding: 10px 18px;
            }

            h2 {
                font-size: 28px;
            }
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>🌿 Senarai Penghantaran Saya 🌿</h2>

    <nav>
        <a href="hantar_kitar_semula.php">➕ Hantar Baru</a>
        <a href="pengguna_dashboard.php">🏠 Dashboard</a>
        <a href="logout.php">🚪 Log Keluar</a>
    </nav>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Jenis</th>
                <th>Alamat</th>
                <th>Poskod</th>
                <th>Jajahan/Daerah</th>
                <th>Negeri</th>
                <th>No Telefon</th>
                <th>Nama Pelajar</th>
                <th>Nama Sekolah</th>
                <th>Kelas</th>
                <th>Tarikh Hantar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['jenis']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['poskod']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['jajahan_daerah']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['negeri']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['no_telefon_untuk_dihubungi']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_pelajar']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_sekolah']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
                    echo "<td>" . date('d-m-Y', strtotime($row['tarikh_hantar'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status_penghantaran']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='13'>Tiada penghantaran dijumpai.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
