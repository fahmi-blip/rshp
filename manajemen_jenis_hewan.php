<?php
include_once("dbconnection.php");
include_once("classes/jenis_hewan.php");

$conn = $db->get_connection();
$jenisObj = new Jenis_hewan();
$allJenis = $jenisObj->helper_fetch_all_jenis_hewan_from_db($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Hewan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
        include("menu.php");
    ?>
    <div class="container">
        <main>
            <div class="header-section">
                    <h2>Manajemen Jenis Hewan</h2>
                    <div class="header-actions1">
                        <a href="dmaster.php" class="btn-back">Kembali</a>
                    </div>
                </div>
            <table border="1" cellpadding="8" cellspacing="0" style="margin:auto; width:90%;">
                <tr>
                    <th>ID</th>
                    <th>Nama Jenis</th>
                </tr>
                <?php foreach ($allJenis as $j) { ?>
                <tr>
                    <td><?= $j['idjenis_hewan'] ?></td>
                    <td><?= $j['nama_jenis_hewan'] ?></td>
                
                </tr>
                <?php } ?>
            </table>
        </main>
</div>
</body>
</html>