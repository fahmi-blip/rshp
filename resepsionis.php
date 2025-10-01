<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Resepsionis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("resepsionis_menu.php"); ?>

    <main style="text-align: center; padding-top: 80px;">
        <h1>Selamat Datang, Resepsionis <?php echo htmlspecialchars($_SESSION['user']['nama']);?>!</h1>
        <div class="menu-master">
            <a href="registrasi_pemilik.php" class="btn-master">Registrasi Pemilik Baru</a>
            <a href="registrasi_pet.php" class="btn-master">Registrasi Pet Baru</a>
            <a href="temu_dokter.php" class="btn-master">Pendaftaran Temu Dokter</a>
        </div>
    </main>
</body>
</html>