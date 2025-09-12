<?php
class DBconnection {
    private string $servername = "localhost";
    private string $username   = "root";
    private string $password   = "";
    private string $dbname     = "dbrshp";
    private mysqli $dbconn;

    public function init_connect(): void {
        $this->dbconn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->dbconn->connect_error) {
            die("Koneksi gagal". $this->dbconn->connect_error);
        }
    }

    public function send_query(string $query): array {
        $result = $this->dbconn->query($query);

        if ($this->dbconn->error) {
            return array(
                "status"  => "error",
                "message" => $this->dbconn->error,
                "data"    => []
            );
        } else if ($result === true) {
            return array(
                "status"  => "success",
                "message" => "Query executed successfully",
                "data"    => []
            );
        } else {
            return array(
                "status"  => "success",
                "message" => "Query executed successfully",
                "data"    => $result->fetch_all(MYSQLI_ASSOC)
            );
        }
    }
    public function get_connection(): mysqli {
        return $this->dbconn;
    }    

    public function close_connection(): void {
        if ($this->dbconn) {
            $this->dbconn->close();
        }
    }
}
$db = new DBconnection();
$db->init_connect();
?>
