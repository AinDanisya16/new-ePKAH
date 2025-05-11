<?php
$conn = new mysqli("localhost", "root", "", "ePKAH");
if ($conn->connect_error) {
    die("Connection failed");
}

$peranan = $_GET['role'] ?? '';

$prefixes = [
    'pengguna' => 'PE',
    'sekolah/agensi' => 'SA',
    'vendor' => 'VE',
    'admin' => 'AD'
];

if (!isset($prefixes[$peranan])) {
    echo '';
    exit;
}

$prefix = $prefixes[$peranan];

$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE peranan = ?");
$stmt->bind_param("s", $peranan);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

$next_number = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
echo $prefix . '/' . $next_number;
