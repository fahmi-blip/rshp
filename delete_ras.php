<?php
require_once "dbconnection.php";
require_once "classes/ras_hewan.php";

$rasObj = new Ras_hewan();
$conn = $db->get_connection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($rasObj->delete($conn, $id)) {
        header("Location: manajemen_ras_hewan.php");
        exit;
    } else {
        echo "Gagal menghapus ras!";
    }
} else {
    echo "ID tidak ditemukan!";
}
