<?php
include_once("dbconnection.php");

$conn = $db->get_connection();
$query = "SELECT p.nama AS nama_pet, p.tanggal_lahir, p.warna_tanda, 
                 CASE p.jenis_kelamin WHEN 'J' THEN 'Jantan' WHEN 'B' THEN 'Betina' ELSE 'N/A' END AS jenis_kelamin, 
                 u.nama AS nama_pemilik, rh.nama_ras 
          FROM pet p 
          JOIN pemilik pm ON p.idpemilik = pm.idpemilik 
          JOIN user u ON pm.iduser = u.iduser 
          JOIN ras_hewan rh ON p.idras_hewan = rh.idras_hewan";
$result = $conn->query($query);
$pet_data = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container">
        <main>
            <div class="header-section">
                <h2>Data Pet</h2>
                <div class="header-actions1">
                    <a href="dmaster.php" class="btn-back">Kembali</a>
                </div>
            </div>
            <table border="1" cellpadding="8" cellspacing="0" style="margin:auto; width:90%;">
                <thead>
                    <tr>
                        <th>Nama Pet</th>
                        <th>Tanggal Lahir</th>
                        <th>Warna/Tanda</th>
                        <th>Jenis Kelamin</th>
                        <th>Pemilik</th>
                        <th>Ras</th>
                    </tr>
                </thead>
                <tbody>
                     <?php if (!empty($pet_data)) { ?>
                        <?php foreach ($pet_data as $pet) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pet['nama_pet']); ?></td>
                                <td><?php echo htmlspecialchars($pet['tanggal_lahir']); ?></td>
                                <td><?php echo htmlspecialchars($pet['warna_tanda']); ?></td>
                                <td><?php echo htmlspecialchars($pet['jenis_kelamin']); ?></td>
                                <td><?php echo htmlspecialchars($pet['nama_pemilik']); ?></td>
                                <td><?php echo htmlspecialchars($pet['nama_ras']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">Tidak ada data pet.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
