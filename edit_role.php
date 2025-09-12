<?php
include_once("dbconnection.php");
include_once("classes/role.php");

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$roleObj = new Role();
$iduser = $_GET['id'];
$all_roles = $roleObj->get_all_roles($db);

if (isset($_POST['update'])) {
    $old_role = intval($_POST['old_role']);
    $new_role = intval($_POST['new_role']);
    $status   = isset($_POST['status']) ? 1 : 0;

    $result = $roleObj->edit_role_user($db, $iduser, $old_role, $new_role, $status);

    echo "<p>{$result['message']}</p>";
    if ($result['status'] === "success") {
        echo "<a href='manajemen_role.php'>Kembali ke Manajemen Role</a>";
        exit();
    }
}

$conn = $db->get_connection();
$userRoles = $conn->query("SELECT ru.idrole, r.nama_role, ru.status FROM role_user ru JOIN role r ON ru.idrole = r.idrole WHERE ru.iduser = $iduser")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Role User</title>
</head>
<body>
<h2>Edit Role User</h2>

<form method="POST">
    <label>Role Lama:</label>
    <select name="old_role" required>
        <?php foreach ($userRoles as $ur) { ?>
            <option value="<?php echo $ur['idrole']; ?>"><?php echo $ur['nama_role']; ?> (<?php echo $ur['status'] ? 'Aktif' : 'Nonaktif'; ?>)</option>
        <?php } ?>
    </select><br><br>

    <label>Role Baru:</label>
    <select name="new_role" required>
        <?php foreach ($all_roles as $role) { ?>
            <option value="<?php echo $role['idrole']; ?>"><?php echo $role['nama_role']; ?></option>
        <?php } ?>
    </select><br><br>

    <label>Status Aktif:</label>
    <input type="checkbox" name="status" value="1" checked><br><br>

    <button type="submit" name="update">Update Role</button>
</form>

<a href="manajemen_role.php">Kembali</a>
</body>
</html>
