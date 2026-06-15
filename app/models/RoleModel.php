<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class RoleModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO roles (name, id_user_created) VALUES (?, ?)");
        return $stmt->execute([$data['name'], $data['id_user_created']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE roles SET name = ?, id_user_updated = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['id_user_updated'], $id]);
    }

    public function delete($id) {
        // First, check if this role is in use
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM user_role WHERE id_role = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Role is in use, cannot delete directly
        }

        $stmt = $this->db->prepare("DELETE FROM roles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
