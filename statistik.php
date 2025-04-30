<?php
include 'db.php'; // sambung ke database

// Query data kutipan
$sql = "SELECT u.nama AS nama_pengguna, k.kategori, k.berat, k.nilai, k.tarikh_kutipan 
        FROM kutipan_vendor k 
        JOIN penghantaran p ON k.penghantaran_id = p.id 
        JOIN users u ON p.user_id = u.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>📊 Statistik Kutipan Vendor</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4fff4;
            margin: 20px;
            color: #2e4e2e;
        }

        h2 {
            text-align: center;
            color: #3c763d;
            margin-bottom: 30px;
            font-size: 26px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0, 128, 0, 0.1);
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 18px;
            text-align: center;
            font-size: 15px;
        }

        th {
            background-color: #b5e7b5;
            color: #1d3b1d;
        }

        tr:nth-child(even) {
            background-color: #e9f9e9;
        }

        tr:hover {
            background-color: #d0f0d0;
        }

        .total-row {
            font-weight: bold;
            background-color: #c3e6cb;
        }

        .back-button {
    display: block;
    width: 200px; /* tetapkan lebar untuk keseragaman */
    margin: 30px auto; /* centrekan butang */
    padding: 12px 25px;
    background-color: #66bb6a;
    color: white;
    border: none;
    border-radius: 10px;
    text-decoration: none;
    font-size: 18px;
    text-align: center; /* teks di tengah */
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: background-color 0.3s ease;
    
    }
        .back-button:hover {
            background-color: #58a05b;
        }

        .emoji {
            margin-right: 6px;
        }
    </style>
</head>
<body>
    <h2>📈 Statistik Kutipan Vendor 🌿</h2>

    <table>
        <tr>
            <th>👤 Nama Pengguna</th>
            <th>📦 Kategori</th>
            <th>⚖️ Berat (kg)</th>
            <th>💰 Nilai (RM)</th>
            <th>🗓️ Tarikh Kutipan</th>
        </tr>

        <?php
        $total_berat = 0;
        $total_nilai = 0;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tarikh = date("d/m/Y", strtotime($row['tarikh_kutipan']));
                echo "<tr>";
                echo "<td>{$row['nama_pengguna']}</td>";
                echo "<td>{$row['kategori']}</td>";
                echo "<td>{$row['berat']}</td>";
                echo "<td>RM " . number_format($row['nilai'], 2) . "</td>";
                echo "<td>{$tarikh}</td>";
                echo "</tr>";

                $total_berat += $row['berat'];
                $total_nilai += $row['nilai'];
            }

            // Jumlah akhir
            echo "<tr class='total-row'>";
            echo "<td colspan='2'>🔢 Jumlah Keseluruhan</td>";
            echo "<td>{$total_berat}</td>";
            echo "<td>RM " . number_format($total_nilai, 2) . "</td>";
            echo "<td>-</td>";
            echo "</tr>";
        } else {
            echo "<tr><td colspan='5'>❗ Tiada data kutipan.</td></tr>";
        }

        $conn->close();
        ?>
    </table>

    <a href="admin_dashboard.php" class="back-button">🏠 Kembali ke Dashboard</a>
</body>
</html>
