<?php
session_start();
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'vendor') {
    echo "Akses ditolak!";
    exit;
}

$conn = new mysqli("localhost", "root", "", "ePKAH");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$vendor_id = $_SESSION['user_id'];

// Ambil filter dari GET
$kategori_filter = $_GET['kategori'] ?? '';
$tarikh_mula = $_GET['tarikh_mula'] ?? '';
$tarikh_akhir = $_GET['tarikh_akhir'] ?? '';

// Query asas
$sql = "SELECT u.nama AS nama_pengguna, p.jajahan_daerah, kv.kategori, kv.berat_kg, kv.nilai_rm, kv.tarikh_kutipan
        FROM kutipan_vendor kv
        JOIN penghantaran p ON kv.penghantaran_id = p.id
        JOIN users u ON p.user_id = u.id
        WHERE p.vendor_id = ?";

$params = [$vendor_id];
$types = "i";

// Tambah filter kategori jika ada
if (!empty($kategori_filter)) {
    $sql .= " AND kv.kategori = ?";
    $types .= "s";
    $params[] = $kategori_filter;
}

// Tambah filter tarikh jika ada
if (!empty($tarikh_mula) && !empty($tarikh_akhir)) {
    $sql .= " AND kv.tarikh_kutipan BETWEEN ? AND ?";
    $types .= "ss";
    $params[] = $tarikh_mula;
    $params[] = $tarikh_akhir;
}

$sql .= " ORDER BY kv.id DESC"; // Urutan mengikut id terbaru

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Kutipan</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3e5f5; padding: 20px; }
        h2 { text-align: center; color: #6a1b9a; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ba68c8; text-align: center; }
        th { background: #8e24aa; color: white; }
        td { background: #f3e5f5; }
        .menu { margin-top: 30px; text-align: center; }
        .menu a { padding: 10px 20px; background: #ab47bc; color: white; text-decoration: none; border-radius: 8px; margin: 0 5px; }
        form.filter { text-align: center; margin-bottom: 20px; background: #f8bbd0; padding: 15px; border-radius: 10px; }
        form.filter select, form.filter input { padding: 5px 10px; margin: 0 5px; border-radius: 5px; border: 1px solid #ce93d8; }
        form.filter button { background: #8e24aa; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

<h2>📄 Laporan Data Kutipan Vendor</h2>

<!-- Form Filter -->
<form method="GET" class="filter">
    <label for="kategori">♻️ Kategori:</label>
    <select name="kategori" id="kategori">
        <option value="">-- Semua --</option>
        <option value="Minyak Masak Terpakai" <?= $kategori_filter == 'Minyak Masak Terpakai' ? 'selected' : '' ?>>Minyak Masak Terpakai</option>
        <option value="E-Waste" <?= $kategori_filter == 'E-Waste' ? 'selected' : '' ?>>E-Waste</option>
        <option value="Plastik" <?= $kategori_filter == 'Plastik' ? 'selected' : '' ?>>Plastik</option>
        <option value="Besi/Tin" <?= $kategori_filter == 'Besi/Tin' ? 'selected' : '' ?>>Besi/Tin</option>
        <option value="Aluminium" <?= $kategori_filter == 'Aluminium' ? 'selected' : '' ?>>Aluminium</option>
        <option value="Kotak" <?= $kategori_filter == 'Kotak' ? 'selected' : '' ?>>Kotak</option>
        <option value="Kertas" <?= $kategori_filter == 'Kertas' ? 'selected' : '' ?>>Kertas</option>
    </select>

    <label for="tarikh_mula">📅 Tarikh Mula:</label>
    <input type="date" name="tarikh_mula" id="tarikh_mula" value="<?= htmlspecialchars($tarikh_mula) ?>">

    <label for="tarikh_akhir">📅 Tarikh Akhir:</label>
    <input type="date" name="tarikh_akhir" id="tarikh_akhir" value="<?= htmlspecialchars($tarikh_akhir) ?>">

    <button type="submit">🔍 Tapis</button>
</form>

<!-- Tabel Laporan -->
<table>
    <tr>
        <th>👤 Nama Pengguna</th>
        <th>📍 Jajahan/Daerah</th>
        <th>🗂️ Kategori</th>
        <th>⚖️ Berat (KG)</th>
        <th>💰 Nilai (RM)</th>
        <th>🗓️ Tarikh Kutipan</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
            <td><?= htmlspecialchars($row['jajahan_daerah']) ?></td>
            <td><?= htmlspecialchars($row['kategori']) ?></td>
            <td><?= number_format($row['berat_kg'], 2) ?></td>
            <td>RM <?= number_format($row['nilai_rm'], 2) ?></td>
            <td><?= htmlspecialchars($row['tarikh_kutipan']) ?></td>
        </tr>
        <?php } ?>
    <?php else: ?>
        <tr><td colspan="7">Tiada data kutipan untuk ditunjukkan berdasarkan filter yang diberikan.</td></tr>
    <?php endif; ?>
</table>

<!-- Menu Navigation -->
<div class="menu">
    <a href="vendor_dashboard.php">🏠 Dashboard</a>
    <a href="logout.php">🚪 Logout</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
