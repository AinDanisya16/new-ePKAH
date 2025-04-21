<?php
require 'db.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];
} else {
    echo "Kod reset tidak sah.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        echo "Kata laluan tidak sepadan!";
    } else {
        // Cari pengguna berdasarkan kod reset
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            // Hash dan simpan password baru
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE id = ?");
            $update->bind_param("si", $hashed, $user_id);
            $update->execute();

            echo "Kata laluan berjaya ditukar. <a href='login.php'>Log Masuk</a>";
        } else {
            echo "Kod tidak sah!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tetapkan Semula Kata Laluan</title>
</head>
<body>
    <h2>Tetapkan Kata Laluan Baharu</h2>
    <form method="post">
        <label>Kata Laluan Baru:</label><br>
        <input type="password" name="password" required><br><br>
        <label>Sahkan Kata Laluan:</label><br>
        <input type="password" name="confirm" required><br><br>
        <input type="submit" value="Reset Kata Laluan">
    </form>
</body>
</html>
