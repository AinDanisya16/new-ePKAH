<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Semak jika pengguna ialah admin
if (!isset($_SESSION['peranan']) || $_SESSION['peranan'] !== 'admin') {
    echo "Akses ditolak!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $penghantaran_id = htmlspecialchars($_POST['penghantaran_id']);
    $vendor_id = htmlspecialchars($_POST['vendor_id']);

    if (!empty($vendor_id)) {
        // Semak jika penghantaran wujud
        $check = $conn->prepare("SELECT id FROM penghantaran WHERE id = ?");
        $check->bind_param("i", $penghantaran_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            // Kemaskini vendor
            $stmt = $conn->prepare("UPDATE penghantaran SET vendor_id = ?, status_penghantaran = 'diproses' WHERE id = ?");
            $stmt->bind_param("ii", $vendor_id, $penghantaran_id);

            if ($stmt->execute()) {
                header("Location: admin_penghantaran.php");
                exit;
            } else {
                echo "Terjadi ralat semasa menetapkan vendor!";
                exit;
            }
        } else {
            echo "Penghantaran tidak dijumpai!";
            exit;
        }
    } else {
        echo "Sila pilih vendor untuk penghantaran ini.";
        exit;
    }
}
?>
