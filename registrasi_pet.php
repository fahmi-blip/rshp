<?php
require_once "dbconnection.php";
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$conn = $db->get_connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pet = $_POST['nama_pet'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $warna_tanda = $_POST['warna_tanda'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $idpemilik = $_POST['idpemilik'];
    $idras_hewan = $_POST['idras_hewan'];

    $stmt = $conn->prepare("INSERT INTO pet (nama, tanggal_lahir, warna_tanda, jenis_kelamin, idpemilik, idras_hewan) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $nama_pet, $tanggal_lahir, $warna_tanda, $jenis_kelamin, $idpemilik, $idras_hewan);

    if ($stmt->execute()) {
        $message = '<p style="color:green; text-align:center;">Registrasi pet baru berhasil!</p>';
    } else {
        $message = '<p style="color:red; text-align:center;">Error: ' . $stmt->error . '</p>';
    }
    $stmt->close();
}

$pemilik_result = $conn->query("SELECT p.idpemilik, u.nama FROM pemilik p JOIN user u ON p.iduser = u.iduser ORDER BY u.nama ASC");

$ras_result = $conn->query("SELECT rh.idras_hewan, rh.nama_ras, jh.nama_jenis_hewan FROM ras_hewan rh JOIN jenis_hewan jh ON rh.idjenis_hewan = jh.idjenis_hewan ORDER BY jh.nama_jenis_hewan, rh.nama_ras ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("resepsionis_menu.php"); ?>
    <div class="form-container" style="margin-top: 80px;">
        <h3>Formulir Registrasi Pet</h3>
        <?php echo $message; ?>
        <form action="registrasi_pet.php" method="post" class="form-user">
            <label for="idpemilik">Pemilik:</label>
            <select id="idpemilik" name="idpemilik" required>
                <option value="">-- Pilih Pemilik --</option>
                <?php while($row = $pemilik_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['idpemilik']; ?>"><?php echo htmlspecialchars($row['nama']); ?></option>
                <?php endwhile; ?>
            </select>

            <label for="nama_pet">Nama Pet:</label>
            <input type="text" id="nama_pet" name="nama_pet" required>

            <label for="idras_hewan">Jenis & Ras Hewan:</label>
            <select id="idras_hewan" name="idras_hewan" required>
                <option value="">-- Pilih Ras Hewan --</option>
                <?php while($row = $ras_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['idras_hewan']; ?>">
                        <?php echo htmlspecialchars($row['nama_jenis_hewan'] . ' - ' . $row['nama_ras']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="tanggal_lahir">Tanggal Lahir:</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>

            <label for="jenis_kelamin">Jenis Kelamin:</label>
            <select id="jenis_kelamin" name="jenis_kelamin" required>
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="J">Jantan</option>
                <option value="B">Betina</option>
            </select>

            <label for="warna_tanda">Warna / Tanda:</label>
            <input type="text" id="warna_tanda" name="warna_tanda">

            <button type="submit">Daftarkan Pet</button>
        </form>
        <a href="resepsionis.php" class="back-link"> Kembali</a>
    </div>
</body>
</html>