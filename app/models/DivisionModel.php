<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class DivisionModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT d.*, u.name as coordinator_name 
                FROM divisions d 
                LEFT JOIN users u ON d.coordinator_user_id = u.id 
                ORDER BY d.name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM divisions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO divisions (coordinator_user_id, name, description, id_user_created) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['coordinator_user_id'] ?: null,
            $data['name'],
            $data['description'] ?: null,
            $data['id_user_created']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE divisions SET coordinator_user_id = ?, name = ?, description = ?, id_user_updated = ? WHERE id = ?");
        return $stmt->execute([
            $data['coordinator_user_id'] ?: null,
            $data['name'],
            $data['description'] ?: null,
            $data['id_user_updated'],
            $id
        ]);
    }

    public function delete($id) {
        // Delete all members in this division first
        $stmt = $this->db->prepare("DELETE FROM division_member WHERE division_id = ?");
        $stmt->execute([$id]);

        // Delete the division
        $stmt = $this->db->prepare("DELETE FROM divisions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // --- Division Member Management ---

    public function getMembers($divisionId) {
        $sql = "SELECT dm.*, u.name as user_name, u.username as user_username 
                FROM division_member dm
                JOIN users u ON dm.user_id = u.id
                WHERE dm.division_id = ?
                ORDER BY u.name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$divisionId]);
        return $stmt->fetchAll();
    }

    public function addMember($divisionId, $userId, $role, $idUserCreated) {
        // Check if already a member
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM division_member WHERE division_id = ? AND user_id = ?");
        $stmt->execute([$divisionId, $userId]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Already a member
        }

        $stmt = $this->db->prepare("INSERT INTO division_member (division_id, user_id, role, id_user_created) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$divisionId, $userId, $role, $idUserCreated]);
    }

    public function removeMember($divisionId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM division_member WHERE division_id = ? AND user_id = ?");
        return $stmt->execute([$divisionId, $userId]);
    }

    public function updateMemberRole($divisionId, $userId, $role, $idUserUpdated) {
        $stmt = $this->db->prepare("UPDATE division_member SET role = ?, id_user_updated = ? WHERE division_id = ? AND user_id = ?");
        return $stmt->execute([$role, $idUserUpdated, $divisionId, $userId]);
    }
}
