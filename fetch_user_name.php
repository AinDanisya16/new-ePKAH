<?php
// fetch_user_name.php
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id'];
    $sql = "SELECT nama FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($nama);
    $stmt->fetch();
    echo $nama;
    $stmt->close();
}

$conn->close();
?>


