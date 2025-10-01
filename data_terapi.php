<?php
include_once("dbconnection.php");

$conn = $db->get_connection();
$query = "SELECT ktt.kode, ktt.deskripsi_tindakan_terapi, k.nama_kategori, kk.nama_kategori_klinis 
          FROM kode_tindakan_terapi ktt 
          JOIN kategori k ON ktt.idkategori = k.idkategori 
          JOIN kategori_klinis kk ON ktt.idkategori_klinis = kk.idkategori_klinis
          ORDER BY ktt.kode";
$result = $conn->query($query);
$terapi_data = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Kode Tindakan Terapi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container">
        <main>
            <div class="header-section">
                <h2>Data Kode Tindakan Terapi</h2>
                <div class="header-actions1">
                    <a href="dmaster.php" class="btn-back">Kembali</a>
                </div>
            </div>
            <table border="1" cellpadding="8" cellspacing="0" style="margin:auto; width:90%;">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                        <th>Kategori Klinis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($terapi_data)) { ?>
                        <?php foreach ($terapi_data as $terapi) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($terapi['kode']); ?></td>
                                <td><?php echo htmlspecialchars($terapi['deskripsi_tindakan_terapi']); ?></td>
                                <td><?php echo htmlspecialchars($terapi['nama_kategori']); ?></td>
                                <td><?php echo htmlspecialchars($terapi['nama_kategori_klinis']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4" style="text-align:center;">Tidak ada data tindakan terapi.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
