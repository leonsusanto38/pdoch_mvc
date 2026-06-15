<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class ChurchModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT c.*, d.name as denomination_name 
                FROM churchs c 
                LEFT JOIN denominations d ON c.id_denomination = d.id 
                ORDER BY c.name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM churchs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO churchs (id_denomination, name, address, id_user_created) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['id_denomination'] ?: null,
            $data['name'],
            $data['address'] ?: null,
            $data['id_user_created']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE churchs SET id_denomination = ?, name = ?, address = ?, id_user_updated = ? WHERE id = ?");
        return $stmt->execute([
            $data['id_denomination'] ?: null,
            $data['name'],
            $data['address'] ?: null,
            $data['id_user_updated'],
            $id
        ]);
    }

    public function delete($id) {
        // Check if there are users referencing this church
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE id_church = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Church is in use, cannot delete
        }

        $stmt = $this->db->prepare("DELETE FROM churchs WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
