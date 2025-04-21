<?php
// Sambung ke database
$conn = new mysqli("localhost", "root", "", "ePKAH");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Bila form dihantar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $negeri = $_POST['negeri'];
    $daerah = $_POST['daerah'];
    $peranan = $_POST['peranan'];
    $password = $_POST['password'];
    
    // Hash password untuk keselamatan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Masukkan ke table users dengan status 'pending'
    $sql = "INSERT INTO users (nama, email, telefon, negeri, daerah, peranan, password, status)
            VALUES ('$nama', '$email', '$telefon', '$negeri', '$daerah', '$peranan', '$hashed_password', 'pending')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pendaftaran berjaya! Tunggu kelulusan admin.');</script>";
    } else {
        echo "Ralat: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pengguna Baru</title>
</head>
<body>
    <h2>Daftar Akaun</h2>
    <form method="POST" action="">
        <label>Nama:</label><br>
        <input type="text" name="nama" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>No Telefon:</label><br>
        <input type="text" name="telefon" required><br><br>

        <label>Negeri:</label><br>
        <input type="text" name="negeri" required><br><br>

        <label>Daerah:</label><br>
        <input type="text" name="daerah" required><br><br>

        <label>Peranan:</label><br>
        <select name="peranan" required>
            <option value="">--Pilih Peranan--</option>
            <option value="pengguna">Pengguna</option>
            <option value="vendor">Vendor</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <label>Katalaluan:</label><br>
        <input type="password" name="password" required
               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
               title="Mesti ada sekurang-kurangnya 8 aksara, huruf besar, huruf kecil, nombor & simbol"><br><br>

        <input type="checkbox" required> Saya setuju dengan Terma & Syarat<br><br>

        <button type="submit">Daftar</button>
    </form>
</body>
</html>
