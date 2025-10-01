<?php
include 'dbconnection.php';
date_default_timezone_set('Asia/Jakarta');
$message = '';
$tanggal_hari_ini = date('Y-m-d');

$conn = $db->get_connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idpet = $_POST['idpet'];
    $idrole_user_dokter = $_POST['idrole_user_dokter'];
    $tanggal = $_POST['tanggal'];
    $keluhan = $_POST['keluhan'];

    $stmt = $conn->prepare("INSERT INTO temu_dokter (idpet, idrole_user, tanggal, keluhan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $idpet, $idrole_user_dokter, $tanggal, $keluhan);

    if ($stmt->execute()) {
        $message = '<div class="alert success">Pendaftaran temu dokter berhasil!</div>';
    } else {
        $message = '<div class="alert error">Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

$pet_result = $conn->query("SELECT p.idpet, p.nama as nama_pet, u.nama as nama_pemilik 
                            FROM pet p 
                            JOIN pemilik o ON p.idpemilik = o.idpemilik
                            JOIN user u ON o.iduser = u.iduser
                            ORDER BY nama_pemilik, nama_pet");

$dokter_result = $conn->query("SELECT ru.idrole_user, u.nama 
                               FROM role_user ru
                               JOIN user u ON ru.iduser = u.iduser
                               WHERE ru.idrole = 2 AND ru.status = 1
                               ORDER BY u.nama");

$antrian_query = "SELECT 
                    td.idreservasi_dokter,
                    p.nama AS nama_pet,
                    u_pemilik.nama AS nama_pemilik,
                    u_dokter.nama AS nama_dokter,
                    td.keluhan
                  FROM temu_dokter td
                  JOIN pet p ON td.idpet = p.idpet
                  JOIN pemilik o ON p.idpemilik = o.idpemilik
                  JOIN user u_pemilik ON o.iduser = u_pemilik.iduser
                  JOIN role_user ru ON td.idrole_user = ru.idrole_user
                  JOIN user u_dokter ON ru.iduser = u_dokter.iduser
                  WHERE td.tanggal = '$tanggal_hari_ini'
                  ORDER BY td.idreservasi_dokter ASC";
$antrian_result = $conn->query($antrian_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran & Antrian Temu Dokter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php include("resepsionis_menu.php"); ?>

    <?php echo $message; ?>
    <main>
    <a href="resepsionis.php" class="back-link"> Kembali</a>
    <div class="content-split">
        <div class="form-section">
            <h2>Formulir Pendaftaran</h2>
            <form action="temu_dokter.php" method="post" class="styled-form">
                <div class="form-group">
                    <label for="tanggal">Tanggal Pertemuan:</label>
                    <input type="date" id="tanggal" name="tanggal" value="<?php echo $tanggal_hari_ini; ?>" required>
                </div>
                <div class="form-group">
                    <label for="idpet">Pilih Pet:</label>
                    <select id="idpet" name="idpet" required>
                        <option value="">-- Pilih Pet (Pemilik) --</option>
                        <?php while($row = $pet_result->fetch_assoc()): ?>
                            <option value="<?php echo $row['idpet']; ?>"><?php echo htmlspecialchars($row['nama_pet'] . ' (' . $row['nama_pemilik'] . ')'); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="idrole_user_dokter">Pilih Dokter:</label>
                    <select id="idrole_user_dokter" name="idrole_user_dokter" required>
                        <option value="">-- Pilih Dokter --</option>
                        <?php while($row = $dokter_result->fetch_assoc()): ?>
                            <option value="<?php echo $row['idrole_user']; ?>"><?php echo htmlspecialchars($row['nama']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="keluhan">Keluhan:</label>
                    <textarea id="keluhan" name="keluhan" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn">Daftarkan</button>
            </form>
        </div>

        <div class="table-section">
            <h2>Antrian Hari Ini (<?php echo date('d M Y'); ?>)</h2>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>No. Urut</th>
                        <th>Nama Pet</th>
                        <th>Pemilik</th>
                        <th>Dokter</th>
                        <th>Keluhan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($antrian_result->num_rows > 0): ?>
                        <?php $no_urut = 1; ?>
                        <?php while($row = $antrian_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no_urut++; ?></td>
                            <td><?php echo htmlspecialchars($row['nama_pet']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_pemilik']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_dokter']); ?></td>
                            <td><?php echo htmlspecialchars($row['keluhan']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Belum ada antrian untuk hari ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>
</body>
</html>
<?php $conn->close(); ?>