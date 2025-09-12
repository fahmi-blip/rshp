<?php
require_once "Ras_hewan.php";

class Jenis_hewan {
    public int $idjenis;
    public string $nama_jenis;
    public array $ras_hewan = [];

    public function __construct($idjenis = 0, $nama_jenis = "") {
        $this->idjenis = $idjenis;
        $this->nama_jenis = $nama_jenis;
    }

    public function set_data(int $idjenis, string $nama): void {
        $this->idjenis = $idjenis;
        $this->nama_jenis = $nama;
    }

    public function fetch_ras_from_db(mysqli $conn): void {
        $stmt = $conn->prepare("SELECT * FROM ras_hewan WHERE idjenis = ?");
        $stmt->bind_param("i", $this->idjenis);
        $stmt->execute();
        $result = $stmt->get_result();

        $this->ras_hewan = [];
        while ($row = $result->fetch_assoc()) {
            $this->ras_hewan[] = new Ras_hewan($row['idras_hewan'], $row['nama_ras']);
        }
    }

    public function helper_fetch_all_jenis_hewan_from_db(mysqli $conn): array {
        $result = $conn->query("SELECT * FROM jenis_hewan");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function helper_fetch_all_with_ras_from_db(mysqli $conn): array {
        $query = "SELECT j.idjenis_hewan, j.nama_jenis_hewan, r.idras_hewan, r.nama_ras 
                  FROM jenis_hewan j 
                  LEFT JOIN ras_hewan r ON j.idjenis_hewan = r.idjenis_hewan";
        $result = $conn->query($query);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[$row['idjenis_hewan']]['jenis'] = $row['nama_jenis_hewan'];
            $data[$row['idjenis_hewan']]['ras'][] = [
                "idras_hewan" => $row['idras_hewan'],
                "nama_ras" => $row['nama_ras']
            ];
        }
        return $data;
    }
}
?>
