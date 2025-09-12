<?php
require_once "dbconnection.php";
require_once "classes/role.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $userObj = new Role();

    if ($userObj->delete_user($db, $id)) {
        header("Location: manajemen_user.php?msg=deleted");
        exit();
    } else {
        echo "Gagal menghapus user.";
    }
} else {
    header("Location: manajemen_user.php");
    exit();
}
