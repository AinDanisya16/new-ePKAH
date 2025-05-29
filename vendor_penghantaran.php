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

$sql = "SELECT p.*, u.nama AS nama_pengguna FROM penghantaran p 
        JOIN users u ON p.user_id = u.id
        WHERE p.vendor_id = ?";

$kategori = $_GET['kategori'] ?? '';
$status = $_GET['status'] ?? '';
$nama_pengguna = $_GET['nama_pengguna'] ?? '';
$tarikh_dari = $_GET['tarikh_dari'] ?? '';
$tarikh_hingga = $_GET['tarikh_hingga'] ?? '';

if (!empty($kategori)) $sql .= " AND p.kategori = ?";
if (!empty($status)) $sql .= " AND p.status_kutipan = ?";
if (!empty($nama_pengguna)) $sql .= " AND u.nama LIKE ?";
if (!empty($tarikh_dari)) $sql .= " AND p.tarikh_hantar >= ?";
if (!empty($tarikh_hingga)) $sql .= " AND p.tarikh_hantar <= ?";

$sql .= " ORDER BY p.id DESC";

$stmt = $conn->prepare($sql);

$paramTypes = "i";
$params = [$vendor_id];

if (!empty($kategori)) { $paramTypes .= "s"; $params[] = $kategori; }
if (!empty($status)) { $paramTypes .= "s"; $params[] = $status; }
if (!empty($nama_pengguna)) { $paramTypes .= "s"; $params[] = "%$nama_pengguna%"; }
if (!empty($tarikh_dari)) { $paramTypes .= "s"; $params[] = $tarikh_dari; }
if (!empty($tarikh_hingga)) { $paramTypes .= "s"; $params[] = $tarikh_hingga; }

$stmt->bind_param($paramTypes, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Senarai Penghantaran Vendor</title>
    <style>
        body { font-family: Arial, sans-serif; background: #e8f5e9; padding: 20px; }
        h2 { text-align: center; color: #2e7d32; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #81c784; text-align: center; }
        th { background: #388e3c; color: white; }
        td { background: #c8e6c9; }
        .menu { margin-top: 30px; text-align: center; }
        .menu a, .menu form button { padding: 10px 20px; background: #66bb6a; color: white; text-decoration: none; border: none; border-radius: 8px; margin: 0 5px; cursor: pointer; }
        .btn-tambah { padding: 8px 15px; background-color: #43a047; color: white; border: none; border-radius: 5px; cursor: pointer; }
        form.filter { margin-top: 20px; text-align: center; }
        form.filter select, form.filter input[type="text"], form.filter input[type="date"] { padding: 6px; margin: 0 5px; }
        form.filter button { padding: 6px 12px; background-color: #388e3c; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

<h2>Senarai Penghantaran Diberikan kepada Anda</h2>

<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] === 'deleted'): ?>
        <div style="background-color: #c8e6c9; color: #2e7d32; padding: 10px; text-align: center; border-radius: 5px; margin-top: 10px;">
            ✅ Penghantaran berjaya dipadam.
        </div>
    <?php elseif ($_GET['msg'] === 'failed'): ?>
        <div style="background-color: #ffcdd2; color: #c62828; padding: 10px; text-align: center; border-radius: 5px; margin-top: 10px;">
            ❌ Gagal padam penghantaran.
        </div>
    <?php elseif ($_GET['msg'] === 'not_allowed'): ?>
        <div style="background-color: #fff3cd; color: #856404; padding: 10px; text-align: center; border-radius: 5px; margin-top: 10px;">
            ⚠️ Tidak dibenarkan padam penghantaran ini.
        </div>
    <?php endif; ?>
<?php endif; ?>

<form method="get" class="filter">
    <select name="status">
        <option value="">Status: Semua</option>
        <option value="Belum Diambil" <?= ($status == 'Belum Diambil') ? 'selected' : '' ?>>Belum Diambil</option>
        <option value="Selesai" <?= ($status == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
    </select>
    <select name="kategori">
        <option value="">Kategori: Semua</option>
        <option value="UCO" <?= ($kategori == 'UCO') ? 'selected' : '' ?>>UCO</option>
        <option value="E-Waste" <?= ($kategori == 'E-Waste') ? 'selected' : '' ?>>E-Waste</option>
        <option value="3R" <?= ($kategori == '3R') ? 'selected' : '' ?>>3R</option>
    </select>
    <input type="date" name="tarikh_dari" value="<?= $tarikh_dari ?>">
    <input type="date" name="tarikh_hingga" value="<?= $tarikh_hingga ?>">
    <button type="submit">Tapis</button>
    <a href="vendor_penghantaran.php" style="background-color:#ef5350;">Reset</a>
</form>

<form method="post" action="export_excel.php">
    <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
    <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori) ?>">
    <input type="hidden" name="nama_pengguna" value="<?= htmlspecialchars($nama_pengguna) ?>">
    <input type="hidden" name="tarikh_dari" value="<?= htmlspecialchars($tarikh_dari) ?>">
    <input type="hidden" name="tarikh_hingga" value="<?= htmlspecialchars($tarikh_hingga) ?>">
    <button type="submit">📥 Export Excel</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Nama Pengguna</th>
        <th>No Telefon</th>
        <th>Kategori</th>
        <th>Jenis</th>
        <th>Alamat</th>
        <th>Poskod</th>
        <th>Jajahan/Daerah</th>
        <th>Negeri</th>
        <th>Tarikh Hantar</th>
        <th>Status</th>
        <th>Tindakan</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
        <td><?= htmlspecialchars($row['no_telefon_untuk_dihubungi']) ?></td>
        <td><?= htmlspecialchars($row['kategori']) ?></td>
        <td><?= htmlspecialchars($row['jenis']) ?></td>
        <td><?= htmlspecialchars($row['alamat']) ?></td>
        <td><?= htmlspecialchars($row['poskod']) ?></td>
        <td><?= htmlspecialchars($row['jajahan_daerah']) ?></td>
        <td><?= htmlspecialchars($row['negeri']) ?></td>
        <td><?= $row['tarikh_hantar'] ?></td>
        <td><?= $row['status_kutipan'] ?></td>
        <td>
            <?php if ($row['status_kutipan'] !== 'Selesai') { ?>
                <form action="data_kutipan.php" method="post" style="display:inline;">
                    <input type="hidden" name="penghantaran_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn-tambah">Tambah Kutipan</button>
                </form>
            <?php } else { ?>
                ✅ Selesai
            <?php } ?>

            <form action="padam_penghantaran.php" method="post" onsubmit="return confirm('Padam penghantaran ini?');" style="display:inline;">
                <input type="hidden" name="penghantaran_id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn-tambah" style="background-color:#d32f2f;">🗑️ Padam</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>

<div class="menu">
    <a href="vendor_dashboard.php">🏠 Dashboard</a>
    <a href="logout.php">🚪 Logout</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
