<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class EventEvaluationModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByEvent($eventId) {
        $stmt = $this->db->prepare("
            SELECT ee.*, u.name as pic_name, u.username as pic_username, u.image as pic_image
            FROM event_evaluations ee
            LEFT JOIN users u ON ee.pic_user_id = u.id
            WHERE ee.event_id = :event_id
            ORDER BY ee.created_at DESC
        ");
        $stmt->execute(['event_id' => $eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO event_evaluations (event_id, pic_user_id, evaluation)
            VALUES (:event_id, :pic_user_id, :evaluation)
        ");
        return $stmt->execute([
            'event_id' => $data['event_id'],
            'pic_user_id' => $data['pic_user_id'] ?: null,
            'evaluation' => $data['evaluation']
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM event_evaluations WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
