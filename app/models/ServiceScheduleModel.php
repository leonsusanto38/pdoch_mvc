<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class ServiceScheduleModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT s.*, u.name as user_name, u.username as user_username
            FROM service_schedules s
            JOIN users u ON s.user_id = u.id
            ORDER BY s.date ASC, s.type DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.name as user_name, u.username as user_username
            FROM service_schedules s
            JOIN users u ON s.user_id = u.id
            WHERE s.user_id = :user_id
            ORDER BY s.date ASC, s.type DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOutstandingByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.name as user_name, u.username as user_username
            FROM service_schedules s
            JOIN users u ON s.user_id = u.id
            WHERE s.user_id = :user_id AND s.date >= CURDATE()
            ORDER BY s.date ASC, s.type DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.name as user_name, u.username as user_username
            FROM service_schedules s
            JOIN users u ON s.user_id = u.id
            WHERE s.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO service_schedules (user_id, role, date, type, location, notes, description)
            VALUES (:user_id, :role, :date, :type, :location, :notes, :description)
        ");
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'role' => $data['role'],
            'date' => $data['date'],
            'type' => $data['type'],
            'location' => $data['location'] ?? null,
            'notes' => $data['notes'] ?? null,
            'description' => $data['description'] ?? null
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE service_schedules 
            SET user_id = :user_id, role = :role, date = :date, type = :type, 
                location = :location, notes = :notes, description = :description
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'user_id' => $data['user_id'],
            'role' => $data['role'],
            'date' => $data['date'],
            'type' => $data['type'],
            'location' => $data['location'] ?? null,
            'notes' => $data['notes'] ?? null,
            'description' => $data['description'] ?? null
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM service_schedules WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
