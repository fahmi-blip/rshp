<?php
include_once("dbconnection.php");
include_once("classes/user.php");

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $user = new User();
    $result = $user->register_user($db, $nama, $email, $pass);

    if ($result['status'] === "success") {
        header("Location: manajemen_user.php");
        exit();
    } else {
        $error = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <div class="form-container">
            <h3>Tambah User Baru</h3>
            
        <?php if (!empty($error)) { ?>
            <p style="color: red; text-align:center;"><?php echo $error; ?></p>
        <?php } ?>

            <form method="POST" class="form-user">
                <label>Nama:</label>
                <input type="text" name="nama" required>

                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Password:</label>
                <input type="password" name="password" required>

                <button type="submit">Simpan</button>
            </form>
            <a href="manajemen_user.php" class="back-link"> Kembali</a>
</div>

</body>
</html>
