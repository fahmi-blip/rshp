<?php
require_once "role.php";

class User {
    private int $iduser;
    private string $nama;
    private string $email;
    private string $password;
    private array $role = []; 

    public function set_user(int $iduser, string $nama, string $email, string $password): void {
        $this->iduser = $iduser;
        $this->nama = $nama;
        $this->email = $email;
        $this->password = $password;
    }

    public function get_user(): array {
        return [
            'iduser'   => $this->iduser,
            'nama'     => $this->nama,
            'email'    => $this->email,
            'password' => $this->password,
            'role'     => $this->get_role_aktif(),
        ];
    }

    public function set_role(Role $role): void {
        if ($role->get_data()['status'] == true) {
            foreach ($this->role as $r) {
                $r->set_status(false);
            }
        }
        $this->role[] = $role;
    }

    public function get_role_aktif(): Role {
        $role_aktif = new Role();
        foreach ($this->role as $r) {
            if ($r->get_status() == true) {
                $role_aktif = $r;
            }
        }
        return $role_aktif;
    }
     public function login(mysqli $conn, string $email, string $password): array {

        $stmt = $conn->prepare("SELECT iduser, nama, password FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ["status" => "error", "message" => "Email tidak ditemukan."];
        }

        $userData = $result->fetch_assoc();

        if (!password_verify($password, $userData['password'])) {
            return ["status" => "error", "message" => "Password salah."];
        }

        $stmt_role = $conn->prepare(
            "SELECT r.nama_role 
             FROM role_user ru 
             JOIN role r ON ru.idrole = r.idrole 
             WHERE ru.iduser = ? AND ru.status = 1"
        );
        $stmt_role->bind_param("i", $userData['iduser']);
        $stmt_role->execute();
        $role_result = $stmt_role->get_result();

        if ($role_result->num_rows === 0) {
            return ["status" => "error", "message" => "Anda tidak memiliki peran aktif."];
        }
        
        $roleData = $role_result->fetch_assoc();
        $active_role = $roleData['nama_role'];

        session_start();
        $_SESSION["user"] = [
            'id'        => $userData['iduser'],
            'nama'      => $userData['nama'],
            'email'     => $email,
            'role'      => $active_role,
            'logged_in' => true
        ];

        return ["status" => "success", "role" => $active_role];
    }

    public function register_user($db, string $nama, string $email, string $password): array {
        $conn = $db->get_connection();

        $check = $conn->prepare("SELECT iduser FROM user WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $checkRes = $check->get_result();

        if ($checkRes->num_rows > 0) {
            return [
                "status"  => "error",
                "message" => "Email sudah terdaftar, gunakan email lain."
            ];
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO user (nama, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $email, $hash);

        if ($stmt->execute()) {
            $iduser = $stmt->insert_id;
            $this->set_user($iduser, $nama, $email, $hash);

            return [
                "status"  => "success",
                "message" => "User berhasil ditambahkan",
                "data"    => $this->get_user()
            ];
        } else {
            return [
                "status"  => "error",
                "message" => "Gagal menambahkan user: " . $conn->error
            ];
        }
    }
    public function edit_user($db, int $iduser, string $nama): array {
        $conn = $db->get_connection();

        $stmt = $conn->prepare("UPDATE user SET nama = ? WHERE iduser = ?");
        $stmt->bind_param("si", $nama, $iduser);

        if ($stmt->execute()) {
            if (isset($this->iduser) && $this->iduser === $iduser) {
                $this->nama = $nama;
            }

            return [
                "status"  => "success",
                "message" => "User berhasil diupdate"
            ];
        } else {
            return [
                "status"  => "error",
                "message" => "Gagal update user: " . $conn->error
            ];
        }
    }
    public function reset_password($db, int $iduser): array {
        $conn = $db->get_connection();

        $newPass = substr(md5(time()), 0, 8);

        $hash = password_hash($newPass, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE iduser = ?");
        $stmt->bind_param("si", $hash, $iduser);

        if ($stmt->execute()) {
            if (isset($this->iduser) && $this->iduser === $iduser) {
                $this->password = $hash;
            }

            return [
                "status"   => "success",
                "message"  => "Password berhasil direset",
                "password" => $newPass 
            ];
        } else {
            return [
                "status"  => "error",
                "message" => "Gagal reset password: " . $conn->error
            ];
        }
    }
    public function get_users_with_roles($db): array {
        $conn = $db->get_connection();

        $query = "SELECT u.iduser, u.nama, r.nama_role, ru.status 
                  FROM user AS u
                  LEFT JOIN role_user AS ru ON u.iduser = ru.iduser
                  LEFT JOIN role AS r ON ru.idrole = r.idrole
                  ORDER BY u.iduser";

        $result = $db->send_query($query);

        $users = [];
        if ($result['status'] === "success") {
            $result_array = $result['data'];

            foreach ($result_array as $row) {
                $id = $row['iduser'];

                if (!array_key_exists($id, $users)) {
                    $users[$id] = [
                        'iduser' => $row['iduser'],
                        'nama'   => $row['nama'],
                        'roles'  => []
                    ];
                }

                if (!empty($row['nama_role'])) {
                    $statusText = ($row['status'] == 1) ? "Aktif" : "Non-Aktif";
                    $users[$id]['roles'][] = [
                        'nama_role' => $row['nama_role'],
                        'status'    => $statusText
                    ];
                }
            }
        }
        return $users;
    }

    
}
?>
