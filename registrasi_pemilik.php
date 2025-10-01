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
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $no_wa = $_POST['no_wa'];
    $alamat = $_POST['alamat'];

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $conn->begin_transaction();

    try {
        $check_stmt = $conn->prepare("SELECT iduser FROM user WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        if ($check_result->num_rows > 0) {
            throw new Exception("Email sudah terdaftar. Silakan gunakan email lain.");
        }
        $check_stmt->close();

        $stmt_user = $conn->prepare("INSERT INTO user (nama, email, password) VALUES (?, ?, ?)");
        $stmt_user->bind_param("sss", $nama, $email, $hashed_password);
        $stmt_user->execute();
        
        $iduser_baru = $conn->insert_id;

        $stmt_pemilik = $conn->prepare("INSERT INTO pemilik (idpemilik, no_wa, alamat, iduser) VALUES (?, ?, ?, ?)");
        $stmt_pemilik->bind_param("isss", $iduser_baru, $no_wa, $alamat, $iduser_baru);
        $stmt_pemilik->execute();

        $conn->commit();
        $message = '<p style="color:green; text-align:center;">Registrasi pemilik baru berhasil!</p>';

    } catch (Exception $e) {
        $conn->rollback();
        $message = '<p style="color:red; text-align:center;">Error: ' . $e->getMessage() . '</p>';
    }

    if(isset($stmt_user)) $stmt_user->close();
    if(isset($stmt_pemilik)) $stmt_pemilik->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pemilik</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("resepsionis_menu.php"); ?>
    
    <div class="form-container" style="margin-top: 80px;">
        <h3>Formulir Registrasi Pemilik</h3>
        <?php echo $message; ?>
        <form action="registrasi_pemilik.php" method="post" class="form-user">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" required>
            
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Password (default):</label>
            <input type="password" name="password" required>
            
            <label>Nomor WhatsApp:</label>
            <input type="text" name="no_wa" required>
            
            <label>Alamat:</label>
            <textarea name="alamat" rows="3" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;" required></textarea>
            
            <button type="submit" style="margin-top:15px;">Daftarkan Pemilik</button>
        </form>
        <a href="resepsionis.php" class="back-link"> Kembali</a>
    </div>
</body>
</html>