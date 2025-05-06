<?php
session_start();
include 'config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $kategori = $_POST['kategori'];
    $berat = $_POST['berat'];
    $nilai = $_POST['nilai'];

    $_SESSION['cart'][] = [
        'kategori' => $kategori,
        'berat' => $berat,
        'nilai' => $nilai
    ];
}

// Submit cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_cart'])) {
    $vendor_id = $_SESSION['vendor_id'];
    $nama_pengguna = $_SESSION['nama_pengguna'];

    foreach ($_SESSION['cart'] as $item) {
        $kategori = $item['kategori'];
        $berat = $item['berat'];
        $nilai = $item['nilai'];

        $stmt = $conn->prepare("INSERT INTO kutipan (vendor_id, nama_pengguna, kategori, berat, nilai) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssd", $vendor_id, $nama_pengguna, $kategori, $berat, $nilai);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['cart'] = []; // Kosongkan cart ✅
    $success = true; // Tunjuk message cute
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Data Kutipan</title>
    <style>
        body {
            background-color: #eaffea;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            padding: 20px;
        }
        .container {
            background: #f0fff0;
            border: 2px solid #b2fab2;
            border-radius: 15px;
            padding: 20px;
            max-width: 700px;
            margin: auto;
            box-shadow: 2px 2px 10px #c9eac9;
        }
        h1 {
            color: #66bb6a;
            text-align: center;
        }
        form {
            margin-bottom: 20px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #a5d6a7;
            border-radius: 10px;
        }
        button {
            background-color: #81c784;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #66bb6a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #f9fff9;
        }
        th, td {
            border: 1px solid #c8e6c9;
            padding: 10px;
            text-align: center;
        }
        .success-message {
            background: #d0f0c0;
            padding: 15px;
            border: 2px solid #a5d6a7;
            border-radius: 10px;
            color: #2e7d32;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🌱 Data Kutipan Vendor 🌿</h1>

    <?php if (!empty($success)): ?>
        <div class="success-message">
            ✅ Berjaya hantar kutipan! Terima kasih! 🥰
        </div>
    <?php endif; ?>

    <form method="post">
        <label for="kategori">Kategori Sisa ♻️:</label>
        <select id="kategori" name="kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Minyak Masak Terpakai">Minyak Masak Terpakai 🛢️</option>
            <option value="E-Waste">E-Waste 🖥️</option>
            <option value="Plastik">Plastik 🛍️</option>
            <option value="Besi/Tin">Besi/Tin ⚙️</option>
            <option value="Tin Aluminium">Tin Aluminium 🥫</option>
            <option value="Kotak">Kotak 📦</option>
            <option value="Kertas">Kertas 📄</option>
        </select>

        <label for="berat">Berat (kg) ⚖️:</label>
        <input type="number" step="0.01" id="berat" name="berat" required>

        <label for="nilai">Nilai (RM) 💵:</label>
        <input type="number" step="0.01" id="nilai" name="nilai" required>

        <button type="submit" name="add_item">Tambah ke Cart 🛒</button>
    </form>

    <?php if (!empty($_SESSION['cart'])): ?>
        <h2>🛒 Cart Semasa</h2>
        <table>
            <tr>
                <th>Kategori</th>
                <th>Berat (kg)</th>
                <th>Nilai (RM)</th>
            </tr>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['kategori']) ?></td>
                    <td><?= htmlspecialchars($item['berat']) ?></td>
                    <td><?= htmlspecialchars($item['nilai']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <form method="post">
            <button type="submit" name="submit_cart">Hantar Kutipan 📤</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
