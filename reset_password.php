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
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE id = ?");
            $update->bind_param("si", $hashed, $user_id);
            $update->execute();

            echo "<p style='font-size: 18px;'>Kata laluan berjaya ditukar. <a href='index.php'>Log Masuk</a></p>";
        } else {
            echo "<p style='font-size: 18px;'>Kod tidak sah!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tetapkan Semula Kata Laluan</title>
    <style>
        body {
            font-family: Consolas, sans-serif;
            background-color: #e8f5e9;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #2e7d32;
            font-size: 32px;
        }
        form {
            max-width: 350px;
            margin: auto;
            background: #c8e6c9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            font-size: 18px;
            border: 1px solid #81c784;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #2e7d32;
        }
        input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 2px;
            margin-bottom: 15px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #81c784;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
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
    <h2>Tetapkan Kata Laluan Baharu</h2>
    <form method="post">
        <label>Kata Laluan Baru:</label>
        <input type="password" name="password" required>

        <label>Sahkan Kata Laluan:</label>
        <input type="password" name="confirm" required>

        <input type="submit" value="Reset Kata Laluan">
    </form>
</body>
</html>







