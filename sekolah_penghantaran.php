<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['peranan'] !== 'sekolah/agensi') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM penghantaran WHERE user_id = ? AND peranan_penghantar = 'sekolah/agensi' ORDER BY tarikh_hantar DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <title>Senarai Penghantaran Sekolah</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f0fff0; padding: 20px; font-size: 20px; }
    h2 { color: #2e7d32; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
    th { background: #a5d6a7; color: #1b5e20; }
    tr:nth-child(even) { background: #f1f8e9; }
    a { text-decoration: none; color: #388e3c; }
    nav { text-align:center; margin-bottom: 20px; }
    nav a { margin: 0 10px; color: #2e7d32; font-weight: bold; }
  </style>
</head>
<body>
  <h2>Senarai Penghantaran oleh Sekolah</h2>

  <nav>
    <a href="hantar_kitar_semula.php">➕ Hantar Kitar Semula</a>
    <a href="sekolah_penghantaran.php">📦 Lihat Penghantaran</a>
    <a href="logout.php">🚪 Log Keluar</a>
  </nav>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <tr>
        <th>Tarikh Hantar</th>
        <th>Kategori</th>
        <th>Jenis</th>
        <th>Alamat</th>
        <th>Nama Sekolah</th>
        <th>Kelas</th>
        <th>Nama Pelajar</th>
        <th>Tarikh Kutipan</th>
        <th>Gambar</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['tarikh_hantar']) ?></td>
        <td><?= htmlspecialchars($row['kategori']) ?></td>
        <td><?= htmlspecialchars($row['jenis']) ?></td>
        <td><?= htmlspecialchars($row['alamat']) ?></td>
        <td><?= htmlspecialchars($row['nama_sekolah']) ?></td>
        <td><?= htmlspecialchars($row['kelas']) ?></td>
        <td><?= htmlspecialchars($row['nama_pelajar']) ?></td>
        <td><?= htmlspecialchars($row['cadangan_tarikh_kutipan']) ?></td>
        <td>
          <?php if ($row['gambar']): ?>
            <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" style="width:100px;">
          <?php else: ?>
            Tiada
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p style="text-align:center; color:#888;">Tiada penghantaran direkodkan.</p>
  <?php endif; ?>

  <?php $stmt->close(); $conn->close(); ?>
</body>
</html>
