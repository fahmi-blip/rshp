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
    public function login(string $email, string $password): bool {
        session_start();

        $admin_email = "admin@mail.com";
        $admin_password = "123456";

        if ($email === $admin_email && $password === $admin_password) {
            
            $this->set_user(1, "administrator", $email, $password);

            $role_admin = new Role();
            $role_admin->set_role(1,"administrator",true);
            $this->set_role($role_admin);

            
            $_SESSION["user"] = [
                'id'        => $this->iduser,
                'nama'      => $this->nama,
                'email'     => $this->email,
                'role_aktif'=> $this->get_role_aktif()->get_data(),
                'logged_in' => true
            ];

            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION["flash_msg"] = "Email atau Password salah!";
            header("Location: login.php");
            exit();
        }
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
