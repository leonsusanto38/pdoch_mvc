<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class NewsModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT n.*, u.name as creator_name 
            FROM news n
            LEFT JOIN users u ON n.created_by = u.id
            ORDER BY n.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatest($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT n.*, u.name as creator_name 
            FROM news n
            LEFT JOIN users u ON n.created_by = u.id
            ORDER BY n.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT n.*, u.name as creator_name 
            FROM news n
            LEFT JOIN users u ON n.created_by = u.id
            WHERE n.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO news (title, content, image, created_by)
            VALUES (:title, :content, :image, :created_by)
        ");
        return $stmt->execute([
            'title' => $data['title'],
            'content' => $data['content'],
            'image' => $data['image'] ?? null,
            'created_by' => $data['created_by']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE news 
            SET title = :title, content = :content, image = :image
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'content' => $data['content'],
            'image' => $data['image'] ?? null
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM news WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
