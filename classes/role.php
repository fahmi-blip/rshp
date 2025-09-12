<?php
class Role {
    private int $idrole;
    private string $nama_role;
    private bool $status;

    public function set_role(int $idrole, string $nama_role, bool $status): void {
        $this->idrole = $idrole;
        $this->nama_role = $nama_role;
        $this->status = $status;
    }
    
    public function get_data(): array {
        return [
            'idrole'    => $this->idrole,
            'nama_role' => $this->nama_role,
            'status'    => $this->status,
        ];
    }
    public function set_status(bool $newstatus): void {
        $this->status = $newstatus;
    }

    public function get_status(): bool {
        return $this->status;
    }
    public function get_all_users($db): array {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit();
        }

        $query = "SELECT * FROM user";
        $result = $db->send_query($query);
        $users = [];

        if ($result['status'] === "success") {
            $users = $result['data'];
        }

        return $users;
    }
    public function get_all_roles($db): array {
        $conn = $db->get_connection();
        $roles = [];

        $roleQuery = $conn->query("SELECT * FROM role");
        while ($row = $roleQuery->fetch_assoc()) {
            $roles[] = $row;
        }

        return $roles;
    } 
    public function assign_role_to_user($db, int $iduser, int $idrole, int $status): array {
        $conn = $db->get_connection();

        $check = $conn->prepare("SELECT * FROM role_user WHERE iduser = ? AND idrole = ?");
        $check->bind_param("ii", $iduser, $idrole);
        $check->execute();
        $checkRes = $check->get_result();

        if ($checkRes->num_rows > 0) {
            return [
                "status"  => "error",
                "message" => "Role ini sudah pernah ditambahkan untuk user ini."
            ];
        }

        $stmt = $conn->prepare("INSERT INTO role_user (iduser, idrole, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $iduser, $idrole, $status);

        if ($stmt->execute()) {
            return [
                "status"  => "success",
                "message" => "Role berhasil ditambahkan"
            ];
        } else {
            return [
                "status"  => "error",
                "message" => "Gagal menambahkan role: " . $conn->error
            ];
        }
    }public function edit_role_user($db, int $iduser, int $old_idrole, int $new_idrole, int $status): array {
        $conn = $db->get_connection();

        $check = $conn->prepare("SELECT * FROM role_user WHERE iduser = ? AND idrole = ?");
        $check->bind_param("ii", $iduser, $new_idrole);
        $check->execute();
        $checkRes = $check->get_result();

        if ($checkRes->num_rows > 0) {
            return [
                "status" => "error",
                "message" => "User sudah memiliki role ini."
            ];
        }

        $stmt = $conn->prepare("UPDATE role_user SET idrole = ?, status = ? WHERE iduser = ? AND idrole = ?");
        $stmt->bind_param("iiii", $new_idrole, $status, $iduser, $old_idrole);

        if ($stmt->execute()) {
            return [
                "status" => "success",
                "message" => "Role user berhasil diubah."
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Gagal mengubah role: " . $conn->error
            ];
        }
    }

    public function delete_user($db, $id) {
        $conn = $db->get_connection();
        $stmt1 = $conn->prepare("DELETE FROM role_user WHERE iduser = ?");
        $stmt1->bind_param("i", $id);
        $stmt1->execute();

        $stmt2 = $conn->prepare("DELETE FROM user WHERE iduser = ?");
        $stmt2->bind_param("i", $id);
        return $stmt2->execute();
    }
}
?>