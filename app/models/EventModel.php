<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class EventModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT e.*, u.name as coordinator_name 
                FROM events e 
                LEFT JOIN users u ON e.coordinator_user_id = u.id 
                ORDER BY e.time_start DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getUpcomingEvents($limit = 10) {
        $sql = "SELECT e.*, u.name as coordinator_name 
                FROM events e 
                LEFT JOIN users u ON e.coordinator_user_id = u.id 
                WHERE e.time_start >= NOW()
                ORDER BY e.time_start ASC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPastEvents($limit = 10) {
        $sql = "SELECT e.*, u.name as coordinator_name 
                FROM events e 
                LEFT JOIN users u ON e.coordinator_user_id = u.id 
                WHERE e.time_start < NOW()
                ORDER BY e.time_start DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO events (coordinator_user_id, name, time_start, time_end, description, id_user_created) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['coordinator_user_id'] ?: null,
            $data['name'],
            $data['time_start'],
            $data['time_end'],
            $data['description'] ?: null,
            $data['id_user_created']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE events SET coordinator_user_id = ?, name = ?, time_start = ?, time_end = ?, description = ?, id_user_updated = ? WHERE id = ?");
        return $stmt->execute([
            $data['coordinator_user_id'] ?: null,
            $data['name'],
            $data['time_start'],
            $data['time_end'],
            $data['description'] ?: null,
            $data['id_user_updated'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Generate recurring worship events for a given year & month.
     * Rule 1: Ibadah PDOCH - once a month on the 2nd Thursday.
     * Rule 2: Ibadah Dewasa Muda - every Wednesday except the Wednesday in the same week as the 2nd Thursday.
     */
    public function generateRecurringEvents($year, $month, $idUserCreated) {
        $thursdayCount = 0;
        $pdochDate = null;
        
        // Days in the chosen month
        $daysInMonth = date('t', strtotime("$year-$month-01"));
        
        // Find 2nd Thursday
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $timestamp = mktime(0, 0, 0, (int)$month, $day, (int)$year);
            $dayOfWeek = date('N', $timestamp); // 1 (Mon) - 7 (Sun)
            if ($dayOfWeek == 4) { // Thursday
                $thursdayCount++;
                if ($thursdayCount == 2) {
                    $pdochDate = date('Y-m-d', $timestamp);
                    break;
                }
            }
        }

        if (!$pdochDate) {
            return 0; // Couldn't find 2nd Thursday (should not happen)
        }

        // Wednesday in the same week as the 2nd Thursday (Thursday - 1 day)
        $excludedWednesday = date('Y-m-d', strtotime('-1 day', strtotime($pdochDate)));

        $generated = [];

        // Add the PDOCH Service (2nd Thursday)
        $generated[] = [
            'name' => 'Ibadah Bulanan PDOCH',
            'time_start' => $pdochDate . ' 19:00:00',
            'time_end' => $pdochDate . ' 21:00:00',
            'description' => 'Ibadah persekutuan doa rutin bulanan PDOCH di hari Kamis ke-2.'
        ];

        // Find all Wednesdays
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $timestamp = mktime(0, 0, 0, (int)$month, $day, (int)$year);
            $dayOfWeek = date('N', $timestamp);
            if ($dayOfWeek == 3) { // Wednesday
                $wedDate = date('Y-m-d', $timestamp);
                if ($wedDate !== $excludedWednesday) {
                    $generated[] = [
                        'name' => 'Ibadah Dewasa Muda',
                        'time_start' => $wedDate . ' 19:00:00',
                        'time_end' => $wedDate . ' 21:00:00',
                        'description' => 'Ibadah persekutuan rutin Dewasa Muda PDOCH.'
                    ];
                }
            }
        }

        // Insert into database, checking for duplicates
        $insertedCount = 0;
        $stmt = $this->db->prepare("INSERT INTO events (name, time_start, time_end, description, id_user_created) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($generated as $evt) {
            $check = $this->db->prepare("SELECT COUNT(*) FROM events WHERE name = ? AND time_start = ?");
            $check->execute([$evt['name'], $evt['time_start']]);
            if ($check->fetchColumn() == 0) {
                $stmt->execute([
                    $evt['name'],
                    $evt['time_start'],
                    $evt['time_end'],
                    $evt['description'],
                    $idUserCreated
                ]);
                $insertedCount++;
            }
        }

        return $insertedCount;
    }
}
