<?php
include_once("dbconnection.php");
include_once("classes/role.php");

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("ID User tidak ditemukan.");
}

$iduser = intval($_GET['id']);
$roleObj = new Role();

$roles = $roleObj->get_all_roles($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idrole = intval($_POST['idrole']);
    $status = intval($_POST['status']);

    $result = $roleObj->assign_role_to_user($db, $iduser, $idrole, $status);

    if ($result['status'] === "success") {
        header("Location: manajemen_role.php");
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
    <title>Tambah Role</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
    <h3>Tambah Role untuk User ID: <?php echo $iduser; ?></h3>

    <?php if (!empty($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">
        <label>Pilih Role:</label>
        <select name="idrole" required>
            <option value="">-- Pilih Role --</option>
            <?php foreach ($roles as $r) { ?>
                <option value="<?php echo $r['idrole']; ?>">
                    <?php echo $r['nama_role']; ?>
                </option>
            <?php } ?>
        </select>

        <label>Status:</label>
        <select name="status" required>
            <option value="1">Aktif</option>
            <option value="0">Non-Aktif</option>
        </select>

        <button type="submit">Simpan</button>
    </form>

    <a href="manajemen_role.php" class="back-link"> Kembali</a>
</div>

</body>
</html>
