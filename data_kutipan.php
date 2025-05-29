<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ePKAH");

if (!isset($_SESSION['user_id']) || $_SESSION['peranan'] !== 'vendor') {
    die("❌ Akses tidak dibenarkan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['penghantaran_id'])) {
    $penghantaran_id = intval($_POST['penghantaran_id']);

    $sql = "SELECT p.*, u.nama AS nama_pengguna 
            FROM penghantaran p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $penghantaran_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $penghantaran = $result->fetch_assoc();

    if (!$penghantaran) {
        die("❌ Penghantaran tidak ditemui.");
    }
} else {
    die("❌ Data tidak lengkap.");
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>🌿 Tambah Data Kutipan Vendor</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0fdf4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 750px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2e7d32;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #388e3c;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #c8e6c9;
            border-radius: 8px;
            background-color: #f9fff9;
        }
        .section {
            margin-bottom: 20px;
        }
        .button-submit {
            background-color: #66bb6a;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
            transition: 0.3s;
        }
        .button-submit:hover {
            background-color: #43a047;
        }
        .info-box {
            background: #e8f5e9;
            border-left: 5px solid #4caf50;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            color: #2e7d32;
        }
        #senarai-kutipan ul {
            list-style: none;
            padding-left: 0;
        }
        #senarai-kutipan li {
            background: #e0f2f1;
            margin-bottom: 8px;
            padding: 10px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #senarai-kutipan button {
            background: #e53935;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 5px 12px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }
        #senarai-kutipan button:hover {
            background: #c62828;
        }
    </style>

    <script>
    let kutipanList = [];

    function updateForm() {
        var kategori = document.getElementById('kategori').value;
        document.getElementById('uco-fields').style.display = (kategori === 'UCO') ? 'block' : 'none';
        document.getElementById('ewaste-fields').style.display = (kategori === 'E-Waste') ? 'block' : 'none';
        document.getElementById('3r-fields').style.display = (kategori === '3R') ? 'block' : 'none';
    }

    function update3RFields() {
        var item3R = document.getElementById('item_3r').value;
        document.getElementById('3r-weight-value').style.display = (item3R !== '') ? 'block' : 'none';
    }

    function tambahKutipan() {
        var kategori = document.getElementById('kategori').value;
        if (kategori === "") {
            alert("Sila pilih kategori.");
            return;
        }

        let data = { kategori: kategori };
        if (kategori === 'UCO') {
            data.berat = document.getElementsByName('berat_uco')[0].value;
            data.nilai = document.getElementsByName('nilai_uco')[0].value;
        } else if (kategori === '3R') {
            data.item = document.getElementsByName('item_3r')[0].value;
            data.berat = document.getElementsByName('berat_3r')[0].value;
            data.nilai = document.getElementsByName('nilai_3r')[0].value;
        } else if (kategori === 'E-Waste') {
            data.berat = document.getElementsByName('berat_ewaste')[0].value;
            data.nilai = document.getElementsByName('nilai_ewaste')[0].value;
        }

        kutipanList.push(data);
        renderKutipanList();
        document.getElementById('form-kutipan').reset();
        updateForm(); 
    }

    function renderKutipanList() {
        var html = "<ul>";
        kutipanList.forEach(function(item, index) {
            html += "<li>[" + item.kategori + "] " +
                    (item.item ? item.item + " - " : "") +
                    "Berat: " + item.berat + "kg, Nilai: RM" + item.nilai + 
                    " <button onclick='padamKutipan("+index+")'>Padam</button></li>";
        });
        html += "</ul>";
        document.getElementById('senarai-kutipan').innerHTML = html;
    }

    function padamKutipan(index) {
        kutipanList.splice(index, 1);
        renderKutipanList();
    }

    function submitSemua() {
        if (kutipanList.length === 0) {
            alert("Tiada kutipan untuk dihantar.");
            return;
        }

        var form = document.createElement('form');
        form.method = 'post';
        form.action = 'proses_data_kutipan.php'; // tukar ke proses simpan kutipan

        var penghantaran_id = document.createElement('input');
        penghantaran_id.type = 'hidden';
        penghantaran_id.name = 'penghantaran_id';
        penghantaran_id.value = <?= $penghantaran['id'] ?>;
        form.appendChild(penghantaran_id);

        kutipanList.forEach(function(item, index) {
            for (const key in item) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'kutipan_vendor['+index+']['+key+']'; // nama array tukar jadi kutipan_vendor
                input.value = item[key];
                form.appendChild(input);
            }
        });

        document.body.appendChild(form);
        form.submit();
    }
    </script>
</head>

<body>

<div class="container">
    <h2>🌿 Tambah Data Kutipan Vendor</h2>

    <div class="info-box">
        📋 Sila tambah kutipan satu per satu, kemudian klik "🚛 Hantar Semua Kutipan".
    </div>

    <form id="form-kutipan" onsubmit="event.preventDefault(); tambahKutipan();">
        <div class="section">
            <label>👤 Nama Pengguna</label>
            <input type="text" value="<?= htmlspecialchars($penghantaran['nama_pengguna']) ?>" readonly>

            <label>📍 Alamat Penghantaran</label>
            <input type="text" value="<?= htmlspecialchars($penghantaran['alamat']) ?>" readonly>

            <label> 🌍 Jajahan/Daerah</label>
            <input type="text" value="<?= htmlspecialchars($penghantaran['jajahan_daerah']) ?>" readonly>

            <label> 📞 No Telefon Untuk Dihubungi</label>
            <input type="text" value="<?= htmlspecialchars($penghantaran['no_telefon_untuk_dihubungi']) ?>" readonly>
        </div>

        <div class="section">
            <label>📦 Pilih Kategori Kutipan</label>
            <select name="kategori" id="kategori" onchange="updateForm()" required>
                <option value="">-- Sila Pilih Kategori --</option>
                <option value="UCO">🌻 Minyak Masak Terpakai (UCO)</option>
                <option value="3R">♻️ Barangan Kitar Semula (3R)</option>
                <option value="E-Waste">🖥️ Barang Elektrik & Elektronik (E-Waste)</option>
            </select>
        </div>

        <div id="uco-fields" style="display:none;">
            <label>⚖️ Berat (kg)</label>
            <input type="number" step="0.01" name="berat_uco">
            <label>💰 Nilai (RM)</label>
            <input type="number" step="0.01" name="nilai_uco">
        </div>

        <div id="3r-fields" style="display:none;">
            <label>📋 Pilih Item 3R</label>
            <select name="item_3r" id="item_3r" onchange="update3RFields()">
                <option value="">-- Sila Pilih --</option>
                <option value="Plastik">Plastik</option>
                <option value="Kertas">Kertas</option>
                <option value="Kotak">Kotak</option>
                <option value="Tin_Aluminiun">Tin Aluminium</option>
                <option value="Besi">Besi</option>
            </select>

            <div id="3r-weight-value" style="display:none;">
                <label>⚖️ Berat (kg)</label>
                <input type="number" step="0.01" name="berat_3r">
                <label>💰 Nilai (RM)</label>
                <input type="number" step="0.01" name="nilai_3r">
            </div>
        </div>

        <div id="ewaste-fields" style="display:none;">
            <label>⚖️ Berat (kg)</label>
            <input type="number" step="0.01" name="berat_ewaste">
            <label>💰 Nilai (RM)</label>
            <input type="number" step="0.01" name="nilai_ewaste">
        </div>

        <button type="submit" class="button-submit">➕ Tambah Kutipan</button>
    </form>

    <div id="senarai-kutipan" style="margin-top: 25px;"></div>

    <button onclick="submitSemua()" class="button-submit">🚛 Hantar Semua Kutipan</button>
</div>

</body>
</html>
