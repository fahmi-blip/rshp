<?php
class Ras_hewan {
    private $idras;
    private $nama_ras;
    private $idjenis;

    public function __construct($idras = null, $nama_ras = null, $idjenis = null) {
        $this->idras = $idras;
        $this->nama_ras = $nama_ras;
        $this->idjenis = $idjenis;
    }

    public function insert_db($db) {
        $stmt = $db->prepare("INSERT INTO ras_hewan (nama_ras, idjenis_hewan) VALUES (?, ?)");
        $stmt->bind_param("si", $this->nama_ras, $this->idjenis);
        return $stmt->execute();
    }

    public function update($db, $id, $nama_ras) {
        $query = "UPDATE ras_hewan SET nama_ras = ? WHERE idras_hewan = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("si", $nama_ras, $id);
        return $stmt->execute();
    }

    public function delete($db, $id) {
        $query = "DELETE FROM ras_hewan WHERE idras_hewan = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public static function fetch_by_jenis($db, $idjenis) {
        $stmt = $db->prepare("SELECT * FROM ras_hewan WHERE idjenis=?");
        $stmt->bind_param("i", $idjenis);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function get_by_id($db, $idras) {
        $stmt = $db->prepare("SELECT * FROM ras_hewan WHERE idras_hewan=?");
        $stmt->bind_param("i", $idras);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }
}
?>