<?php
require_once "dbconnection.php";
require_once "classes/jenis_hewan.php";

$conn = $db->get_connection();
$jenisObj = new Jenis_hewan();
$allData = $jenisObj->helper_fetch_all_with_ras_from_db($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Ras Hewan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
        include("menu.php");
    ?>
    <div class="container">
        <main>
            <div class="header-section">
                    <h2>Manajemen Ras Hewan</h2>
                    <div class="header-actions1">
                        <a href="dmaster.php" class="btn-back">Kembali</a>
                    </div>
                </div>
            <table border="1" cellpadding="8" cellspacing="0" style="margin:auto; width:90%;">
                <tr>
                    <th>Jenis Hewan</th>
                    <th>Ras Hewan</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($allData as $idjenis => $data) { ?>
                <tr>
                    <td><?= $data['jenis'] ?></td>
                    <td>
                        <?php 
                        if (!empty($data['ras'])) {
                            foreach ($data['ras'] as $r) {
                                if ($r['idras_hewan']) {
                                    echo "<div class='ras-item'>
                                            <span class='ras-nama'>{$r['nama_ras']}</span>
                                            <div class='ras-buttons'>
                                                <a href='update_ras.php?id={$r['idras_hewan']}' class='edit-btn'>Update</a>
                                                <a href='delete_ras.php?id={$r['idras_hewan']}' class='delete-btn' onclick='return confirm(\"Hapus ras ini?\")'>Delete</a>
                                            </div>
                                          </div>";
                                }
                            }
                        }
                        ?>
                    </td>

                    <td>
                        <a href="tambah_ras.php?idjenis=<?= $idjenis ?>" class="btn-add">Tambah Ras</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </main>
    </div>
</body>
</html>
