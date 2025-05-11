<?php
include 'db.php'; // sambung ke database

// Menangani filter jika ada
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$berat_filter = isset($_GET['berat']) ? $_GET['berat'] : '';
$nilai_filter = isset($_GET['nilai']) ? $_GET['nilai'] : '';
$tarikh_filter = isset($_GET['tarikh']) ? $_GET['tarikh'] : '';

// Query data kutipan dengan filter
$sql = "SELECT u.nama AS nama_pengguna, dk.kategori, dk.berat_kg AS berat, dk.nilai_rm AS nilai, dk.tarikh_kutipan 
        FROM data_kutipan dk
        JOIN penghantaran p ON dk.penghantaran_id = p.id 
        JOIN users u ON p.user_id = u.id
        WHERE 1=1";

// Menambah filter ke query jika ada
if ($kategori_filter) {
    $sql .= " AND dk.kategori = '$kategori_filter'";
}
if ($berat_filter) {
    $sql .= " AND dk.berat_kg >= '$berat_filter'";
}
if ($nilai_filter) {
    $sql .= " AND dk.nilai_rm >= '$nilai_filter'";
}
if ($tarikh_filter) {
    $sql .= " AND dk.tarikh_kutipan >= '$tarikh_filter'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>📊 Statistik</title>
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
            width: 200px;
            margin: 30px auto;
            padding: 12px 25px;
            background-color: #66bb6a;
            color: white;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-size: 18px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #58a05b;
        }

        .emoji {
            margin-right: 6px;
        }

        /* Styling untuk filter form */
        .filter-form {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-form select,
        .filter-form input {
            padding: 8px;
            margin: 5px;
            font-size: 16px;
        }

        .filter-form button {
            padding: 10px 20px;
            background-color: #66bb6a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .filter-form button:hover {
            background-color: #58a05b;
        }
    </style>
</head>
<body>
    <h2>📈 Statistik 🌿</h2>

    <!-- Filter Form -->
    <div class="filter-form">
        <form method="get">
            <select name="kategori">
                <option value="">Pilih Kategori</option>
                <option value="UCO" <?= $kategori_filter == 'UCO' ? 'selected' : ''; ?>>UCO</option>
                <option value="3R" <?= $kategori_filter == '3R' ? 'selected' : ''; ?>>3R</option>
                <option value="E-waste" <?= $kategori_filter == 'E-waste' ? 'selected' : ''; ?>>E-waste</option>
            </select>
            <input type="number" name="berat" placeholder="Min Berat (kg)" value="<?= htmlspecialchars($berat_filter); ?>">
            <input type="number" name="nilai" placeholder="Min Nilai (RM)" value="<?= htmlspecialchars($nilai_filter); ?>">
            <input type="date" name="tarikh" value="<?= htmlspecialchars($tarikh_filter); ?>">
            <button type="submit">🔍 Cari</button>
        </form>
    </div>

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
                echo "<td>" . htmlspecialchars($row['nama_pengguna']) . "</td>";
                echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                echo "<td>" . number_format($row['berat'], 2) . "</td>";
                echo "<td>RM " . number_format($row['nilai'], 2) . "</td>";
                echo "<td>$tarikh</td>";
                echo "</tr>";

                $total_berat += $row['berat'];
                $total_nilai += $row['nilai'];
            }

            // Jumlah keseluruhan
            echo "<tr class='total-row'>";
            echo "<td colspan='2'>🔢 Jumlah Keseluruhan</td>";
            echo "<td>" . number_format($total_berat, 2) . "</td>";
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
