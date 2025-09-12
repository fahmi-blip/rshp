<?php
include_once("dbconnection.php");
include_once("classes/user.php");

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM user WHERE iduser = $id";
$result = $db->send_query($query);
$userData = !empty($result['data']) ? $result['data'][0] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];

    $user = new User();
    $res = $user->edit_user($db, $id, $nama);

    if ($res['status'] === "success") {
        header("Location: manajemen_user.php");
        exit();
    } else {
        $error = $res['message'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h3>Edit User</h3>
    <form method="POST">
        Nama: <input type="text" name="nama" value="<?= $userData['nama']; ?>" required><br><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
