<?php
require_once "user.php";

class Pemilik extends User {
    private ?string $no_wa;
    private ?string $alamat;
    private int $iduser;
    private string $nama;
    private string $email;
    public function set_data_pemilik(string $nama, string $email, string $password, ?string $no_wa, ?string $alamat): void {
 
        parent::set_user(0, $nama, $email, $password); // iduser 0 karena belum dibuat di DB
        $this->no_wa = $no_wa;
        $this->alamat = $alamat;
    }
    public function set_data_user(int $iduser, string $nama, string $email): void {
        parent::set_user($iduser, $nama, $email, "");
    }

    public function create(mysqli $conn, string $nama, string $email, string $password, string $no_wa, string $alamat): array {

        $check_stmt = $conn->prepare("SELECT iduser FROM user WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        if ($result->num_rows > 0) {
            return ["status" => "error", "message" => "Email sudah terdaftar. Silakan gunakan email lain."];
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $user_stmt = $conn->prepare("INSERT INTO user (nama, email, password) VALUES (?, ?, ?)");
        $user_stmt->bind_param("sss", $nama, $email, $hashed_password);

        if (!$user_stmt->execute()) {
            return ["status" => "error", "message" => "Gagal membuat akun user: " . $conn->error];
        }

        $new_iduser = $conn->insert_id;

        $pemilik_stmt = $conn->prepare("INSERT INTO pemilik (nama,email, no_wa, alamat, iduser) VALUES (?,?, ?, ?)");
        $pemilik_stmt->bind_param("ssi", $no_wa, $alamat, $new_iduser);
        
        if ($pemilik_stmt->execute()) {
            return ["status" => "success", "message" => "Registrasi pemilik baru berhasil!"];
        } else {
            return ["status" => "error", "message" => "Gagal menyimpan data detail pemilik: " . $conn->error];
        }
    }

    public function get_user_by_id(): array {
        $dbconn = new DBconnection();
        $query = "SELECT * FROM pemilik WHERE iduser = " . $this->iduser;
        $result = $dbconn->send_query($query);
        $dbconn->close_connection();

        if ($result['status'] === "success" && !empty($result['data'])) {
            $user_data = $result['data'][0];
            $this->set_data_user($user_data['iduser'], $user_data['nama'], $user_data['email']);
            return ["status" => true, "message" => "User found"];
        } else {
            return ["status" => false, "message" => "User not found"];
        }
    }
}
?>