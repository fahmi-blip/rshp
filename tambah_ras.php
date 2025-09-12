<?php
require_once "dbconnection.php";
require_once "classes/ras_hewan.php";

$conn = $db->get_connection();
$rasObj = new Ras_hewan();

if (!isset($_GET['idjenis'])) {
    die("Jenis hewan tidak ditemukan!");
}
$idjenis = (int) $_GET['idjenis'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_ras = $_POST['nama_ras'];

    $rasBaru = new Ras_hewan(null, $nama_ras, $idjenis);
    if ($rasBaru->insert_db($conn)) {
        header("Location: manajemen_ras_hewan.php");
        exit;
    } else {
        echo "Gagal menambahkan ras!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Ras Hewan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container">
        <h3>Tambah Ras Baru</h3>
        <form method="POST">
            <label>Nama Ras:</label>
            <input type="text" name="nama_ras" required>
            <br><br>
            <button type="submit" class="btn-add">Simpan</button>
            <a href="manajemen_ras_hewan.php" class="btn-back">Batal</a>
        </form>
    </div>
</body>
</html>
