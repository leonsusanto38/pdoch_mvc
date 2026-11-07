-- ============================================================
-- Pilar Legends — Database Migration
-- Run this in phpMyAdmin for database: pdoch
-- ============================================================

-- 1. Add "Dewasa Muda" role
INSERT IGNORE INTO `roles` (`name`, `id_user_created`) VALUES ('Dewasa Muda', 1);

-- 2. Assign "Dewasa Muda" role to specified users (Leon, Frenzo, Dyllen, Daniel, Okidharma, Stanley)
INSERT IGNORE INTO `user_role` (`id_user`, `id_role`, `id_user_created`)
SELECT u.id, r.id, 1
FROM `users` u
CROSS JOIN `roles` r
WHERE r.name = 'Dewasa Muda'
  AND (
    u.name LIKE '%Leon%'
    OR u.name LIKE '%Frenzo%'
    OR u.name LIKE '%Dyllen%'
    OR u.name LIKE '%Daniel%'
    OR u.name LIKE '%Okidharma%'
    OR u.name LIKE '%Stanley%'
  );

-- 3. Pilar Legends game roles table
CREATE TABLE IF NOT EXISTS `pl_roles` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `base_attack` INT NOT NULL COMMENT 'Base ATK (+ dice in game)',
  `base_hp` INT NOT NULL COMMENT 'Base HP already calculated (base_def × 4)',
  `base_movement` INT NOT NULL COMMENT 'Base movement (+ dice in game)',
  `range_cells` INT NOT NULL COMMENT 'Attack range in cells (fixed)',
  `description` TEXT,
  `created_by` INT DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seed Pilar Legends roles
INSERT IGNORE INTO `pl_roles` (`id`, `name`, `base_attack`, `base_hp`, `base_movement`, `range_cells`, `description`) VALUES
(1, 'Fighter',  3, 12,  2, 1, 'Pejuang garis depan dengan HP tinggi dan serangan seimbang. Andalan tim dalam pertempuran jarak dekat.'),
(2, 'Marksman', 4,  4,  3, 2, 'Penembak jitu dengan serangan tinggi dan range 2, namun sangat rentan terhadap serangan langsung.'),
(3, 'Assassin', 5,  4,  2, 1, 'Pembunuh bayaran dengan damage tertinggi. HP sangat rendah, memerlukan posisi yang tepat untuk menyerang.'),
(4, 'Wizard',   4,  4,  3, 2, 'Penyihir dengan kemampuan serangan jarak jauh yang mematikan. Mobilitas tinggi dengan sihir kuat.'),
(5, 'Tank',     1, 16,  3, 1, 'Benteng tim dengan HP tertinggi. Damage sangat rendah namun mampu menahan serangan musuh dalam waktu lama.'),
(6, 'Cleric',   2,  8,  4, 3, 'Pendukung tim dengan mobilitas tertinggi dan range serangan terluas. Sangat fleksibel di segala posisi.');

-- 4. Game sessions table
CREATE TABLE IF NOT EXISTS `pl_game_sessions` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `game_code` VARCHAR(6) NOT NULL UNIQUE,
  `game_master_user_id` INT NOT NULL,
  `status` ENUM('waiting','team_assign','discussion_role','discussion_strategy','battle','finished') NOT NULL DEFAULT 'waiting',
  `current_turn_player_session_id` INT DEFAULT NULL,
  `turn_timer_end` DATETIME DEFAULT NULL,
  `discussion_timer_end` DATETIME DEFAULT NULL,
  `team_a_name` VARCHAR(100) DEFAULT 'Team A',
  `team_b_name` VARCHAR(100) DEFAULT 'Team B',
  `winner_team` ENUM('A','B') DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`game_master_user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5. Player sessions table (players in a game)
CREATE TABLE IF NOT EXISTS `pl_player_sessions` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `game_session_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `team` ENUM('A','B') DEFAULT NULL,
  `pl_role_id` INT DEFAULT NULL,
  `turn_order` INT DEFAULT NULL,
  `current_hp` INT DEFAULT NULL,
  `is_alive` TINYINT(1) NOT NULL DEFAULT 1,
  `joined_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`game_session_id`) REFERENCES `pl_game_sessions`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`pl_role_id`) REFERENCES `pl_roles`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 6. Battle log table
CREATE TABLE IF NOT EXISTS `pl_battle_logs` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `game_session_id` INT NOT NULL,
  `player_session_id` INT NOT NULL,
  `action_type` ENUM('move','attack','skip','auto_skip') NOT NULL,
  `target_player_session_id` INT DEFAULT NULL,
  `damage_dealt` INT NOT NULL DEFAULT 0,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`game_session_id`) REFERENCES `pl_game_sessions`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`player_session_id`) REFERENCES `pl_player_sessions`(`id`),
  FOREIGN KEY (`target_player_session_id`) REFERENCES `pl_player_sessions`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 7. Lifetime player stats table
CREATE TABLE IF NOT EXISTS `pl_player_stats` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL UNIQUE,
  `total_match` INT NOT NULL DEFAULT 0,
  `total_win` INT NOT NULL DEFAULT 0,
  `total_lose` INT NOT NULL DEFAULT 0,
  `total_damage_given` INT NOT NULL DEFAULT 0,
  `total_damage_taken` INT NOT NULL DEFAULT 0,
  `total_kill` INT NOT NULL DEFAULT 0,
  `total_death` INT NOT NULL DEFAULT 0,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 8. Role pick statistics per player
CREATE TABLE IF NOT EXISTS `pl_role_pick_stats` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `pl_role_id` INT NOT NULL,
  `pick_count` INT NOT NULL DEFAULT 0,
  UNIQUE KEY `uq_user_role` (`user_id`, `pl_role_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`pl_role_id`) REFERENCES `pl_roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- Done! Check that all tables were created successfully.
-- ============================================================
