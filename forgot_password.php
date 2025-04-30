<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE nama = ?");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        $reset_code = bin2hex(random_bytes(16));
        $update = $conn->prepare("UPDATE users SET reset_code = ? WHERE id = ?");
        $update->bind_param("si", $reset_code, $user_id);
        $update->execute();

        header("Location: reset_password.php?code=" . $reset_code);
        exit;
    } else {
        echo "<p style='font-size: 20px;'>Nama tidak dijumpai!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lupa Kata Laluan</title>
    <style>
        body {
            font-family: Consolas, sans-serif;
            background-color: #e8f5e9;
            padding: 20px;
            margin: 0;
        }
        h2 {
            text-align: center;
            color: #2e7d32;
            font-size: 32px;
        }
        form {
            max-width: 400px;
            margin: auto;
            background: #c8e6c9;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-size: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #2e7d32;
        }
        input[type="text"], button {
            width: 100%;
            padding: 2px;
            margin-bottom: 15px;
            font-size: 18px;
            border-radius: 8px;
            border: 1px solid #a5d6a7;
        }
        button {
            background-color: #4caf50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #388e3c;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 100px;
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="logo_ePKAH.png" alt="logo_ePKAH">
    </div>
    <h2>Lupa Kata Laluan</h2>
    <form method="POST">
        <label>Masukkan Nama:</label>
        <input type="text" name="nama" required>
        <button type="submit">Hantar</button>
    </form>
</body>
</html>


