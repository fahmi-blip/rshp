<?php
include_once("dbconnection.php");
include_once("classes/user.php");
include_once("classes/role.php");

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$userObj = new User();
$roleObj = new Role();

$users = $userObj->get_users_with_roles($db);

$all_roles = $roleObj->get_all_roles($db);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Role User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container">
    <main>
       <div class="header-section">
            <h2>Manajemen Role User</h2>
            <div class="header-actions1">
                <a href="dmaster.php" class="btn-back">Kembali</a>
            </div>
        </div>

        <?php
        if (isset($_GET['msg'])) {
            echo "<p style='color:green; text-align:center;'>{$_GET['msg']}</p>";
        }
        ?>

        <table border="1" cellpadding="8" cellspacing="0" style="margin:auto; width:90%;">
            <thead>
                <tr>
                    <th>ID User</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)) { ?>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo $user['iduser']; ?></td>
                            <td><?php echo $user['nama']; ?></td>
                            <td>
                                <?php
                                if (!empty($user['roles'])) {
                                    foreach ($user['roles'] as $role) {
                                        echo $role['nama_role']." (".($role['status'] ? "Aktif" : "Nonaktif").")<br>";
                                    }
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                            <td style="text-align: center;">
                                <a href="tambah_role.php?id=<?php echo $user['iduser']; ?>" class="btn-add">Tambah Role</a><br>
                                <a href="edit_role.php?id=<?php echo $user['iduser']; ?>" class="edit-btn">Edit Role</a><br>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">Belum ada data user/role</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
    </div>
</body>
</html>
