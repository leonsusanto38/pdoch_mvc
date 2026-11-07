<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class PilarLegendsRoleModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM pl_roles ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function getById($id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM pl_roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO pl_roles (name, base_attack, base_hp, base_movement, range_cells, description, created_by)
            VALUES (:name, :base_attack, :base_hp, :base_movement, :range_cells, :description, :created_by)
        ");
        $stmt->execute([
            'name'          => $data['name'],
            'base_attack'   => (int)$data['base_attack'],
            'base_hp'       => (int)$data['base_hp'],
            'base_movement' => (int)$data['base_movement'],
            'range_cells'   => (int)$data['range_cells'],
            'description'   => $data['description'] ?? null,
            'created_by'    => $data['created_by'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update($id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE pl_roles SET
                name          = :name,
                base_attack   = :base_attack,
                base_hp       = :base_hp,
                base_movement = :base_movement,
                range_cells   = :range_cells,
                description   = :description
            WHERE id = :id
        ");
        return $stmt->execute([
            'name'          => $data['name'],
            'base_attack'   => (int)$data['base_attack'],
            'base_hp'       => (int)$data['base_hp'],
            'base_movement' => (int)$data['base_movement'],
            'range_cells'   => (int)$data['range_cells'],
            'description'   => $data['description'] ?? null,
            'id'            => $id,
        ]);
    }

    public function delete($id): bool
    {
        // Check if role is used in any active game
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM pl_player_sessions ps
            JOIN pl_game_sessions gs ON ps.game_session_id = gs.id
            WHERE ps.pl_role_id = ? AND gs.status != 'finished'
        ");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM pl_roles WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            // Role masih direferensikan pl_player_sessions (pernah dipakai di game)
            return false;
        }
    }
}
