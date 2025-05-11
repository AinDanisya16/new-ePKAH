<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pastikan admin
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'admin') {
    echo "Akses ditolak!";
    exit;
}

// Proses lulus/tolak
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE users SET status = 'approved' WHERE id = $id");
    header('Location: senarai_pengguna.php');
    exit;
} elseif (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $conn->query("UPDATE users SET status = 'rejected' WHERE id = $id");
    header('Location: senarai_pengguna.php');
    exit;
}

// Ambil pengguna sahaja
$sql = "SELECT id, nama, telefon, peranan, status, alamat, poskod
        FROM users
        WHERE peranan = 'pengguna'
        ORDER BY id DESC";
$result = $conn->query($sql);
if (!$result) {
    die("Query error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Pengguna</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Palet hijau kitar semula + font lebih mesra dan saiz lebih besar */
        :root {
            --bg-light: #e6f4ea;
            --accent: #4c8c50;
            --accent-light: #a8d5ba;
            --white: #ffffff;
            --font-base: 18px;
            --font-lg: 20px;
            --font-xl: 24px;
            --font-xxl: 32px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--accent);
            padding: 30px;
            font-size: var(--font-base);
            line-height: 1.6;
        }
        h2 {
            text-align: center;
            font-size: var(--font-xxl);
            margin-bottom: 30px;
            color: var(--accent);
        }
        table {
            width: 100%;
            margin: auto;
            border-collapse: collapse;
            border: 2px solid var(--accent-light);
            border-radius: 12px;
            overflow: hidden;
            background-color: var(--white);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 16px 12px;
            text-align: center;
            font-size: var(--font-lg);
        }
        th {
            background-color: var(--accent);
            color: var(--white);
            font-weight: 600;
        }
        tr:nth-child(even) td {
            background-color: var(--accent-light);
        }
        td {
            background-color: var(--white);
        }
        .btn {
            padding: 10px 18px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            font-size: var(--font-base);
            transition: transform 0.2s;
            display: inline-block;
            margin: 4px;
            border: 2px solid var(--accent);
        }
        .btn-approve {
            background-color: var(--accent-light);
            color: var(--accent);
        }
        .btn-approve:hover {
            background-color: var(--white);
            transform: scale(1.1);
        }
        .btn-reject {
            background-color: #ffdede;
            color: #b71c1c;
        }
        .btn-reject:hover {
            background-color: var(--white);
            transform: scale(1.1);
        }
        .menu {
            text-align: center;
            margin-top: 40px;
        }
        .menu a {
            padding: 14px 26px;
            background-color: var(--accent);
            color: var(--white);
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: var(--font-lg);
            margin: 0 12px;
            display: inline-block;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: background-color 0.3s, transform 0.2s;
        }
        .menu a:hover {
            background-color: var(--accent-light);
            transform: translateY(-3px);
        }
        .no-action {
            font-style: italic;
            color: var(--accent-light);
            font-size: var(--font-base);
        }
    </style>
</head>
<body>
    <h2>♻️ Senarai Pengguna e-PKAH ♻️</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Telefon</th>
                <th>Peranan</th>
                <th>Status</th>
                <th>Alamat</th>
                <th>Poskod</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['telefon']) ?></td>
                <td><?= htmlspecialchars($row['peranan']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td><?= htmlspecialchars($row['poskod']) ?></td>
                <td>
                    <?php if ($row['status'] === 'pending'): ?>
                        <a class="btn btn-approve" href="?approve=<?= $row['id'] ?>">✅ Lulus</a>
                        <a class="btn btn-reject" href="?reject=<?= $row['id'] ?>">❌ Tolak</a>
                    <?php else: ?>
                        <span class="no-action">Tiada tindakan</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">Tiada rekod pengguna.</td></tr>
        <?php endif; ?>
    </tbody>

    </table>

    <div class="menu">
        <a href="admin_dashboard.php">🏠 Dashboard</a>
        <a href="logout.php">🚪 Log Keluar</a>
    </div>

<?php $conn->close(); ?>
</body>
</html>
