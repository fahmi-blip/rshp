<?php
include_once("dbconnection.php");
include_once("./classes/role.php");

$userObj = new Role();
$users = $userObj->get_all_users($db);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("menu.php");?>
    <div class="container">
    <main>
        <div class="header-section">
        <h2>Manajemen User</h2>
            <div class="header-actions">
                <a href="tambah_user.php" class="btn-add">Tambah User</a>
                <a href="dmaster.php" class="btn-back">Kembali</a>
            </div>
        </div>
  
        <table border="1" cellpadding="8" cellspacing="0" style="margin:auto; width:90%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                     <?php if (!empty($users)) { ?>
                        <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?php echo $user['iduser']; ?></td>
                        <td><?php echo $user['nama']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td style="text-align: center;">
                            <a href="edit_user.php?id=<?php echo $user['iduser'] ?>" class="edit-btn">Edit</a><br>
                            <a href="reset_password.php?id=<?php echo $user['iduser'] ?>" class="reset-btn">Reset Password</a><br>
                            <a href="delete_user.php?id=<?php echo $user['iduser'] ?>" 
                               class="delete-btn" 
                               onclick="return confirm('Yakin ingin menghapus akun ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
    </main>
    </div>
</body>
</html>
