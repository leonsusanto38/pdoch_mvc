<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class DenominationModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM denominations ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM denominations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO denominations (name, id_user_created) VALUES (?, ?)");
        return $stmt->execute([$data['name'], $data['id_user_created']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE denominations SET name = ?, id_user_updated = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['id_user_updated'], $id]);
    }

    public function delete($id) {
        // Check if there are churches referencing this denomination
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM churchs WHERE id_denomination = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Denomination is in use, cannot delete
        }

        $stmt = $this->db->prepare("DELETE FROM denominations WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
