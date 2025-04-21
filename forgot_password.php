<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];

    // Cari pengguna
    $stmt = $conn->prepare("SELECT id FROM users WHERE nama = ?");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Jana kod reset
        $reset_code = bin2hex(random_bytes(16));

        // Simpan kod ke DB
        $update = $conn->prepare("UPDATE users SET reset_code = ? WHERE id = ?");
        $update->bind_param("si", $reset_code, $user_id);
        $update->execute();

        // Redirect ke reset_password.php dengan kod
        header("Location: reset_password.php?code=" . $reset_code);
        exit;
    } else {
        echo "Nama tidak dijumpai!";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Lupa Kata Laluan</title></head>
<body>
    <h2>Lupa Kata Laluan</h2>
    <form method="POST">
        <label>Masukkan Nama:</label>
        <input type="text" name="nama" required>
        <button type="submit">Hantar</button>
    </form>
</body>
</html>

