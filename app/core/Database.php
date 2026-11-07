<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $cfg     = APP_CONFIG['db'];
        $host    = $cfg['host'];
        $db      = $cfg['name'];
        $user    = $cfg['user'];
        $pass    = $cfg['pass'];
        $charset = $cfg['charset'];

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
            $this->pdo->exec("SET time_zone = '+07:00'");
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    private function autoSeed()
    {
        // Check if users table is empty
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            return; // Already seeded
        }

        // Disable FK checks to allow seeding self-referencing and dependent tables
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

        // 1. Seed Roles
        $this->pdo->exec("INSERT INTO roles (id, name, id_user_created) VALUES 
            (1, 'Admin', 1),
            (2, 'Jemaat', 1),
            (3, 'Pelayan', 1),
            (4, 'Aktivis', 1)
        ");

        // 2. Seed Denominations
        $this->pdo->exec("INSERT INTO denominations (id, name, id_user_created) VALUES 
            (1, 'Gereja Kristen Indonesia (GKI)', 1),
            (2, 'Gereja Bethel Indonesia (GBI)', 1),
            (3, 'Gereja Mawar Sharon (GMS)', 1),
            (4, 'Katolik Roma', 1)
        ");

        // 3. Seed Churches
        $this->pdo->exec("INSERT INTO churchs (id, id_denomination, name, address, id_user_created) VALUES 
            (1, 1, 'GKI Citra Harmoni', 'Perumahan Citra Harmoni Blok A', 1),
            (2, 2, 'GBI Citra Harmoni', 'Perumahan Citra Harmoni Blok B', 1),
            (3, 3, 'GMS Surabaya', 'Jl. Tegalsari No. 41, Surabaya', 1)
        ");

        // 4. Seed Users
        // Password awal diambil dari config.php
        $adminPassword = password_hash(APP_CONFIG['seed']['admin_password'], PASSWORD_BCRYPT);
        $userPassword  = password_hash(APP_CONFIG['seed']['user_password'], PASSWORD_BCRYPT);

        $this->pdo->exec("INSERT INTO users (id, id_church, name, username, password, gender, status, id_user_created) VALUES 
            (1, 1, 'Admin PDOCH', 'admin', '{$adminPassword}', 'M', 'active', 1),
            (2, 2, 'Leon Susanto', 'user', '{$userPassword}', 'M', 'active', 1)
        ");

        // 5. Seed User Roles
        $this->pdo->exec("INSERT INTO user_role (id_user, id_role, id_user_created) VALUES 
            (1, 1, 1), -- Admin gets Admin role
            (2, 2, 1)  -- Leon gets Jemaat role
        ");

        // 6. Seed Divisions
        $this->pdo->exec("INSERT INTO divisions (id, coordinator_user_id, name, description, id_user_created) VALUES 
            (1, 1, 'Musik & Pujian', 'Divisi yang mengatur musik, WL, singer, dan pemusik ibadah.', 1),
            (2, 1, 'Multimedia & Sound', 'Divisi yang mengelola sound system, live streaming, dan proyektor.', 1),
            (3, 1, 'Publikasi & Dokumentasi', 'Divisi yang mendokumentasikan kegiatan dan mempublikasikannya di sosial media.', 1),
            (4, 2, 'Internal Dewasa Muda', 'Pengurus yang mengorganisir persekutuan remaja dan dewasa muda.', 1),
            (5, 1, 'Internal Lansia', 'Pengurus pelayanan rohani untuk usia lanjut.', 1)
        ");

        // 7. Seed Division Members
        $this->pdo->exec("INSERT INTO division_member (division_id, user_id, role, id_user_created) VALUES 
            (1, 2, 'Pemusik Keyboard', 1),
            (4, 2, 'Koordinator Kegiatan', 1)
        ");

        // Re-enable FK checks
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    }
}
