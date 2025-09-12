<?php
include_once("dbconnection.php");
include_once("classes/user.php");
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$user = new User();
$result = $user->reset_password($db, (int)$id);

if ($result['status'] === "success") {
    echo "<h3>Password baru user ID $id adalah: <b>{$result['password']}</b></h3>";
    echo "<p>Catat password ini, tidak akan ditampilkan lagi.</p>";
    echo "<a href='manajemen_user.php'>Kembali</a>";
} else {
    echo "<p>{$result['message']}</p>";
    echo "<a href='manajemen_user.php'>Kembali</a>";
}
?>
