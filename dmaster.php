<?php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Menu Data Master</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
       include("menu.php");
    ?>
<div class="container">
        <main>
            <h2>Menu Data Master</h2>
            <div class="menu-master">
                <a href="manajemen_user.php" class="btn-master">Data User</a>
                <a href="manajemen_role.php" class="btn-master">Manajemen Role</a>
                <a href="manajemen_jenis_hewan.php" class="btn-master">Jenis Hewan</a>
                <a href="manajemen_ras_hewan.php" class="btn-master">Ras Hewan</a>
                <a href="data_pemilik.php" class="btn-master">Data Pemilik</a>
                <a href="data_pet.php" class="btn-master">Data Pet</a>
                <a href="data_kategori.php" class="btn-master">Data Kategori</a>
                <a href="data_klinis.php" class="btn-master">Data Kategori Klinis</a>
                <a href="data_terapi.php" class="btn-master">Data Kode Tindakan Terapi</a>
                <a href="dashboard.php" class="btn-master btn-back">Kembali</a>
            </div>
        </main>
    </div>

</body>
</html>
