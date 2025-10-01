<?php
include_once("dbconnection.php");

$conn = $db->get_connection();
$query = "SELECT idkategori_klinis, nama_kategori_klinis FROM kategori_klinis";
$result = $conn->query($query);
$klinis_data = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Kategori Klinis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container">
        <main>
            <div class="header-section">
                <h2>Data Kategori Klinis</h2>
                <div class="header-actions1">
                    <a href="dmaster.php" class="btn-back">Kembali</a>
                </div>
            </div>
             <table border="1" cellpadding="8" cellspacing="0" style="margin:auto; width:90%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori Klinis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($klinis_data)) { ?>
                        <?php foreach ($klinis_data as $klinis) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($klinis['idkategori_klinis']); ?></td>
                                <td><?php echo htmlspecialchars($klinis['nama_kategori_klinis']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="2" style="text-align:center;">Tidak ada data kategori klinis.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
