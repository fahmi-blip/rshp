<?php
include_once("dbconnection.php");

$conn = $db->get_connection();
$query = "SELECT idkategori, nama_kategori FROM kategori";
$result = $conn->query($query);
$kategori_data = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Kategori</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container">
        <main>
            <div class="header-section">
                <h2>Data Kategori</h2>
                <div class="header-actions1">
                    <a href="dmaster.php" class="btn-back">Kembali</a>
                </div>
            </div>
            <table border="1" cellpadding="8" cellspacing="0" style="margin:auto; width:90%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kategori_data)) { ?>
                        <?php foreach ($kategori_data as $kategori) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($kategori['idkategori']); ?></td>
                                <td><?php echo htmlspecialchars($kategori['nama_kategori']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="2" style="text-align:center;">Tidak ada data kategori.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
