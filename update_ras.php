<?php
require_once "dbconnection.php";
require_once "classes/ras_hewan.php";

$rasObj = new Ras_hewan();
$conn = $db->get_connection();

$ras = null;

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $ras = $rasObj->get_by_id($conn, $id);

    if (!$ras) {
        die("Data ras tidak ditemukan!");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['idras_hewan'];
    $nama_ras = $_POST['nama_ras'];

    if ($rasObj->update($conn, $id, $nama_ras)) {
        header("Location: manajemen_ras_hewan.php");
        exit;
    } else {
        echo "Gagal update data!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Ras Hewan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container">
        <main>
        <h3>Update Ras Hewan</h3>
        <?php if ($ras): ?>
        <form method="POST">
            <input type="hidden" name="idras_hewan" value="<?= $ras['idras_hewan'] ?>">
            
            <label>Nama Ras:</label>
            <input type="text" name="nama_ras" 
                   value="<?= htmlspecialchars($ras['nama_ras'], ENT_QUOTES) ?>" required>
            <br><br>
            <button type="submit" class="btn-add">Simpan</button>
        </form>
        <?php endif; ?>
        </main>
    </div>
</body>
</html>
