<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefon = trim($_POST['telefon']);
    $password = $_POST['password'];

    // Cari pengguna ikut no telefon
    $sql = "SELECT * FROM users WHERE telefon = '$telefon'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Semak password betul atau tidak
        if (password_verify($password, $user['password'])) {

            // Semak status akaun
            if ($user['status'] == 'approved') {

                // Simpan dalam session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['peranan'] = strtolower($user['peranan']); // contoh: admin, vendor, pengguna

                // Redirect ikut peranan
                if ($_SESSION['peranan'] == 'admin') {
                    header("Location: admin_dashboard.php");
                    exit;
                } elseif ($_SESSION['peranan'] == 'vendor') {
                    header("Location: vendor_dashboard.php"); // ⬅️ Ini untuk vendor
                    exit;
                } else {
                    header("Location: pengguna_dashboard.php");
                    exit;
                }

            } else {
                $error = "Akaun anda belum diluluskan oleh admin.";
            }

        } else {
            $error = "Katalaluan salah.";
        }

    } else {
        $error = "No telefon tidak wujud.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Pengguna</title>
</head>
<body>
    <h2>Login</h2>

    <?php if ($error != "") echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="">
        <label>No Telefon:</label><br>
        <input type="text" name="telefon" required><br><br>

        <label>Katalaluan:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>

        <p><a href="forgot_password.php">Lupa kata laluan?</a></p>
    </form>
</body>
</html>
