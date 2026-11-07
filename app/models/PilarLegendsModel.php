<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class PilarLegendsModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // ===== GAME ROLES =====

    public function getAllRoles(): array
    {
        $stmt = $this->db->query("SELECT * FROM pl_roles ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function getRoleById($id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM pl_roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    // ===== SESSION MANAGEMENT =====

    public function createSession($gameMasterUserId): string
    {
        do {
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code  = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $chk = $this->db->prepare("SELECT id FROM pl_game_sessions WHERE game_code = ?");
            $chk->execute([$code]);
        } while ($chk->fetch());

        $stmt = $this->db->prepare("
            INSERT INTO pl_game_sessions (game_code, game_master_user_id, status)
            VALUES (?, ?, 'waiting')
        ");
        $stmt->execute([$code, $gameMasterUserId]);

        return $code;
    }

    public function getSessionByCode($code): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM pl_game_sessions WHERE game_code = ?");
        $stmt->execute([strtoupper(trim($code))]);
        return $stmt->fetch() ?: null;
    }

    public function getSessionById($id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT gs.*, u.name AS gm_name
            FROM pl_game_sessions gs
            LEFT JOIN users u ON u.id = gs.game_master_user_id
            WHERE gs.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Returns active session for user (GM or player).
     * Adds 'user_role' => 'gm'|'player' and 'player_session_id' (if player).
     */
    public function getActiveSessionForUser($userId): ?array
    {
        // Check if user is GM
        $stmt = $this->db->prepare("
            SELECT * FROM pl_game_sessions
            WHERE game_master_user_id = ? AND status != 'finished'
            ORDER BY created_at DESC LIMIT 1
        ");
        $stmt->execute([$userId]);
        $session = $stmt->fetch();
        if ($session) {
            $session['user_role'] = 'gm';
            return $session;
        }

        // Check if user is a player
        $stmt = $this->db->prepare("
            SELECT gs.*, ps.id as player_session_id
            FROM pl_game_sessions gs
            JOIN pl_player_sessions ps ON gs.id = ps.game_session_id
            WHERE ps.user_id = ? AND gs.status != 'finished'
            ORDER BY gs.created_at DESC LIMIT 1
        ");
        $stmt->execute([$userId]);
        $session = $stmt->fetch();
        if ($session) {
            $session['user_role'] = 'player';
            return $session;
        }

        return null;
    }

    public function updateSessionStatus($sessionId, $status): bool
    {
        $stmt = $this->db->prepare("UPDATE pl_game_sessions SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $sessionId]);
    }

    // ===== PLAYER MANAGEMENT =====

    public function joinSession($sessionId, $userId): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO pl_player_sessions (game_session_id, user_id)
            VALUES (?, ?)
        ");
        $stmt->execute([$sessionId, $userId]);
        return (int)$this->db->lastInsertId();
    }

    public function getPlayerSession($userId, $sessionId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT ps.*, u.name as user_name, u.image as user_image,
                   r.name as role_name, r.base_attack, r.base_hp,
                   r.base_movement, r.range_cells
            FROM pl_player_sessions ps
            JOIN users u ON ps.user_id = u.id
            LEFT JOIN pl_roles r ON ps.pl_role_id = r.id
            WHERE ps.user_id = ? AND ps.game_session_id = ?
            LIMIT 1
        ");
        $stmt->execute([$userId, $sessionId]);
        return $stmt->fetch() ?: null;
    }

    public function getSessionPlayers($sessionId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                ps.*,
                u.name      AS user_name,
                u.image     AS user_image,
                r.name      AS role_name,
                r.base_attack,
                r.base_hp,
                r.base_movement,
                r.range_cells
            FROM pl_player_sessions ps
            JOIN users u ON ps.user_id = u.id
            LEFT JOIN pl_roles r ON ps.pl_role_id = r.id
            WHERE ps.game_session_id = ?
            ORDER BY ps.team ASC, ps.turn_order ASC, ps.id ASC
        ");
        $stmt->execute([$sessionId]);
        return $stmt->fetchAll();
    }

    public function getTeamPlayers($sessionId, $team): array
    {
        $stmt = $this->db->prepare("
            SELECT
                ps.*,
                u.name      AS user_name,
                u.image     AS user_image,
                r.name      AS role_name,
                r.base_attack,
                r.base_hp,
                r.base_movement,
                r.range_cells
            FROM pl_player_sessions ps
            JOIN users u ON ps.user_id = u.id
            LEFT JOIN pl_roles r ON ps.pl_role_id = r.id
            WHERE ps.game_session_id = ? AND ps.team = ?
            ORDER BY ps.turn_order ASC, ps.id ASC
        ");
        $stmt->execute([$sessionId, $team]);
        return $stmt->fetchAll();
    }

    // ===== TEAM MANAGEMENT =====

    public function assignTeam($playerSessionId, $team): bool
    {
        $team = in_array($team, ['A', 'B']) ? $team : null;
        $stmt = $this->db->prepare("UPDATE pl_player_sessions SET team = ? WHERE id = ?");
        return $stmt->execute([$team, $playerSessionId]);
    }

    public function randomizeTeams($sessionId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM pl_player_sessions WHERE game_session_id = ?");
        $stmt->execute([$sessionId]);
        $playerIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        shuffle($playerIds);
        $half  = (int)ceil(count($playerIds) / 2);
        $teamA = array_slice($playerIds, 0, $half);
        $teamB = array_slice($playerIds, $half);

        $upd = $this->db->prepare("UPDATE pl_player_sessions SET team = ? WHERE id = ?");
        foreach ($teamA as $id) $upd->execute(['A', $id]);
        foreach ($teamB as $id) $upd->execute(['B', $id]);

        return true;
    }

    // ===== ROLE PICKING =====

    public function setPlayerRole($playerSessionId, $roleId): bool
    {
        $role = $this->getRoleById($roleId);
        if (!$role) return false;

        $stmt = $this->db->prepare("
            UPDATE pl_player_sessions SET pl_role_id = ?, current_hp = ? WHERE id = ?
        ");
        return $stmt->execute([$roleId, $role['base_hp'], $playerSessionId]);
    }

    public function autoAssignRoles($sessionId): void
    {
        // Get players without roles
        $stmt = $this->db->prepare("
            SELECT ps.id, ps.user_id, ps.team
            FROM pl_player_sessions ps
            WHERE ps.game_session_id = ? AND ps.pl_role_id IS NULL
        ");
        $stmt->execute([$sessionId]);
        $unassigned = $stmt->fetchAll();
        if (empty($unassigned)) return;

        $allRoles = $this->getAllRoles();

        // Role yang sudah dipakai, dikelompokkan per tim (1 query)
        $takenStmt = $this->db->prepare("
            SELECT team, pl_role_id FROM pl_player_sessions
            WHERE game_session_id = ? AND pl_role_id IS NOT NULL
        ");
        $takenStmt->execute([$sessionId]);
        $takenByTeam = [];
        foreach ($takenStmt->fetchAll() as $row) {
            $takenByTeam[$row['team']][] = (int)$row['pl_role_id'];
        }

        // Disiapkan sekali, dipakai ulang di dalam loop
        $prefStmt = $this->db->prepare("
            SELECT pl_role_id FROM pl_role_pick_stats
            WHERE user_id = ? ORDER BY pick_count DESC LIMIT 1
        ");

        foreach ($unassigned as $player) {
            $taken = $takenByTeam[$player['team']] ?? [];

            // Coba role yang paling sering dipakai player ini
            $prefStmt->execute([$player['user_id']]);
            $roleId = (int)$prefStmt->fetchColumn();

            // Kalau tidak ada / sudah dipakai teman setim, pilih acak dari yang tersisa
            if (!$roleId || in_array($roleId, $taken, true)) {
                $available = array_values(array_filter($allRoles, fn($r) => !in_array((int)$r['id'], $taken, true)));
                if (empty($available)) $available = $allRoles;
                $roleId = (int)$available[array_rand($available)]['id'];
            }

            $this->setPlayerRole($player['id'], $roleId);
            $takenByTeam[$player['team']][] = $roleId;
        }
    }

    // ===== TURN MANAGEMENT =====

    public function setTurnOrder($sessionId, array $orderedPlayerSessionIds): bool
    {
        $stmt = $this->db->prepare("
            UPDATE pl_player_sessions SET turn_order = ? WHERE id = ? AND game_session_id = ?
        ");
        foreach ($orderedPlayerSessionIds as $idx => $psId) {
            $stmt->execute([$idx + 1, $psId, $sessionId]);
        }
        return true;
    }

    /**
     * Random turn order: interleaved pattern A1, B1, A2, B2, ...
     */
    public function randomizeTurnOrder($sessionId): bool
    {
        $stmtA = $this->db->prepare("
            SELECT id FROM pl_player_sessions WHERE game_session_id = ? AND team = 'A' ORDER BY RAND()
        ");
        $stmtA->execute([$sessionId]);
        $teamA = $stmtA->fetchAll(PDO::FETCH_COLUMN);

        $stmtB = $this->db->prepare("
            SELECT id FROM pl_player_sessions WHERE game_session_id = ? AND team = 'B' ORDER BY RAND()
        ");
        $stmtB->execute([$sessionId]);
        $teamB = $stmtB->fetchAll(PDO::FETCH_COLUMN);

        $order   = 1;
        $maxLen  = max(count($teamA), count($teamB));
        $upd     = $this->db->prepare("UPDATE pl_player_sessions SET turn_order = ? WHERE id = ?");

        for ($i = 0; $i < $maxLen; $i++) {
            if (isset($teamA[$i])) $upd->execute([$order++, $teamA[$i]]);
            if (isset($teamB[$i])) $upd->execute([$order++, $teamB[$i]]);
        }

        return true;
    }

    public function startBattle($sessionId): bool
    {
        // Find player with turn_order = 1
        $stmt = $this->db->prepare("
            SELECT id FROM pl_player_sessions
            WHERE game_session_id = ? AND turn_order = 1 AND is_alive = 1
            LIMIT 1
        ");
        $stmt->execute([$sessionId]);
        $first = $stmt->fetchColumn();

        if (!$first) return false;

        $upd = $this->db->prepare("
            UPDATE pl_game_sessions
            SET status = 'battle',
                current_turn_player_session_id = ?,
                turn_timer_end = DATE_ADD(NOW(), INTERVAL 60 SECOND)
            WHERE id = ?
        ");
        return $upd->execute([$first, $sessionId]);
    }

    public function getNextAlivePlayer($sessionId, $currentOrder): ?array
    {
        $stmt = $this->db->prepare("
            SELECT ps.*, u.name AS user_name, r.name AS role_name
            FROM pl_player_sessions ps
            JOIN users u ON ps.user_id = u.id
            LEFT JOIN pl_roles r ON ps.pl_role_id = r.id
            WHERE ps.game_session_id = ? AND ps.is_alive = 1 AND ps.turn_order IS NOT NULL
            ORDER BY ps.turn_order ASC
        ");
        $stmt->execute([$sessionId]);
        $players = $stmt->fetchAll();

        if (empty($players)) return null;

        // Find first with turn_order > current
        foreach ($players as $p) {
            if ($p['turn_order'] > $currentOrder) return $p;
        }
        // Wrap to beginning
        return $players[0];
    }

    // ===== TIMERS =====

    public function setDiscussionTimer($sessionId, int $minutes = 2): bool
    {
        $stmt = $this->db->prepare("
            UPDATE pl_game_sessions
            SET discussion_timer_end = DATE_ADD(NOW(), INTERVAL ? MINUTE)
            WHERE id = ?
        ");
        return $stmt->execute([$minutes, $sessionId]);
    }

    public function clearDiscussionTimer($sessionId): bool
    {
        $stmt = $this->db->prepare("UPDATE pl_game_sessions SET discussion_timer_end = NULL WHERE id = ?");
        return $stmt->execute([$sessionId]);
    }

    // ===== BATTLE ACTIONS =====

    public function processAction(array $data): array
    {
        $sessionId        = $data['session_id'];
        $playerSessionId  = $data['player_session_id'];
        $actionType       = $data['action_type'];
        $targetSessionId  = $data['target_session_id'] ?? null;
        $damageDealt      = (int)($data['damage'] ?? 0);
        $description      = $data['description'] ?? '';

        // Validate session is in battle
        $session = $this->getSessionById($sessionId);
        if (!$session || $session['status'] !== 'battle') {
            return ['success' => false, 'message' => 'Game tidak dalam status battle.'];
        }

        // Verify it's this player's turn
        if ((int)$session['current_turn_player_session_id'] !== (int)$playerSessionId) {
            return ['success' => false, 'message' => 'Bukan giliran player ini.'];
        }

        // Apply attack damage
        if ($actionType === 'attack' && $targetSessionId && $damageDealt > 0) {
            $hit = $this->db->prepare("
                UPDATE pl_player_sessions
                SET current_hp = GREATEST(0, current_hp - ?),
                    is_alive   = IF(current_hp > 0, 1, 0)
                WHERE id = ? AND game_session_id = ? AND is_alive = 1
            ");
            $hit->execute([$damageDealt, $targetSessionId, $sessionId]);
            if ($hit->rowCount() === 0) {
                return ['success' => false, 'message' => 'Target tidak valid atau sudah mati.'];
            }
        }

        // Build description
        if (empty($description)) {
            if ($actionType === 'attack') {
                $description = "Serangan dilakukan, damage $damageDealt.";
            } elseif ($actionType === 'move') {
                $description = "Player bergerak.";
            } else {
                $description = "Player melewati giliran.";
            }
        }

        // Log action
        $this->db->prepare("
            INSERT INTO pl_battle_logs
                (game_session_id, player_session_id, action_type, target_player_session_id, damage_dealt, description)
            VALUES (?, ?, ?, ?, ?, ?)
        ")->execute([$sessionId, $playerSessionId, $actionType, $targetSessionId ?: null, $damageDealt, $description]);

        // Check winner
        $winner = $this->checkWinner($sessionId);
        if ($winner) {
            $this->db->prepare("
                UPDATE pl_game_sessions SET status = 'finished', winner_team = ? WHERE id = ?
            ")->execute([$winner, $sessionId]);
            $this->updateLifetimeStats($sessionId);
            return ['success' => true, 'game_over' => true, 'winner' => $winner];
        }

        // Advance to next turn
        $ordStmt = $this->db->prepare("SELECT turn_order FROM pl_player_sessions WHERE id = ?");
        $ordStmt->execute([$playerSessionId]);
        $currentOrder = (int)$ordStmt->fetchColumn();

        $nextPlayer = $this->getNextAlivePlayer($sessionId, $currentOrder);
        if ($nextPlayer) {
            $this->db->prepare("
                UPDATE pl_game_sessions
                SET current_turn_player_session_id = ?,
                    turn_timer_end = DATE_ADD(NOW(), INTERVAL 60 SECOND)
                WHERE id = ?
            ")->execute([$nextPlayer['id'], $sessionId]);
        }

        return ['success' => true, 'game_over' => false];
    }

    public function autoSkipTurn($sessionId): bool
    {
        // Get session with timer check
        $stmt = $this->db->prepare("
            SELECT id, current_turn_player_session_id, turn_timer_end
            FROM pl_game_sessions
            WHERE id = ? AND status = 'battle'
              AND turn_timer_end IS NOT NULL
              AND turn_timer_end < NOW()
        ");
        $stmt->execute([$sessionId]);
        $session = $stmt->fetch();

        if (!$session) return false;

        $playerSessionId = $session['current_turn_player_session_id'];
        $timerEnd        = $session['turn_timer_end'];

        // Atomic update: only update if timer_end hasn't changed (prevents double-skip)
        $atomicUpd = $this->db->prepare("
            UPDATE pl_game_sessions
            SET turn_timer_end = DATE_ADD(NOW(), INTERVAL 60 SECOND)
            WHERE id = ? AND turn_timer_end = ?
        ");
        $atomicUpd->execute([$sessionId, $timerEnd]);

        if ($atomicUpd->rowCount() === 0) return false; // Another client already processed

        // Log auto-skip
        $this->db->prepare("
            INSERT INTO pl_battle_logs (game_session_id, player_session_id, action_type, description)
            VALUES (?, ?, 'auto_skip', 'Waktu habis — giliran otomatis dilewati.')
        ")->execute([$sessionId, $playerSessionId]);

        // Get current turn order
        $ordStmt = $this->db->prepare("SELECT turn_order FROM pl_player_sessions WHERE id = ?");
        $ordStmt->execute([$playerSessionId]);
        $currentOrder = (int)$ordStmt->fetchColumn();

        // Find and assign next player
        $nextPlayer = $this->getNextAlivePlayer($sessionId, $currentOrder);
        if ($nextPlayer) {
            $this->db->prepare("
                UPDATE pl_game_sessions SET current_turn_player_session_id = ? WHERE id = ?
            ")->execute([$nextPlayer['id'], $sessionId]);
        }

        return true;
    }

    private function checkWinner($sessionId): ?string
    {
        $stmtA = $this->db->prepare("
            SELECT COUNT(*) FROM pl_player_sessions WHERE game_session_id = ? AND team = 'A' AND is_alive = 1
        ");
        $stmtA->execute([$sessionId]);
        $aliveA = (int)$stmtA->fetchColumn();

        $stmtB = $this->db->prepare("
            SELECT COUNT(*) FROM pl_player_sessions WHERE game_session_id = ? AND team = 'B' AND is_alive = 1
        ");
        $stmtB->execute([$sessionId]);
        $aliveB = (int)$stmtB->fetchColumn();

        if ($aliveA === 0 && $aliveB > 0) return 'B';
        if ($aliveB === 0 && $aliveA > 0) return 'A';
        return null;
    }

    // ===== LIFETIME STATS =====

    public function updateLifetimeStats($sessionId): void
    {
        $session = $this->getSessionById($sessionId);
        if (!$session || !$session['winner_team']) return;

        $winner  = $session['winner_team'];
        $players = $this->getSessionPlayers($sessionId);

        // Damage diberi & diterima untuk SEMUA player dalam 1 query
        $dmgStmt = $this->db->prepare("
            SELECT ps.id,
                   COALESCE(dg.total, 0) AS damage_given,
                   COALESCE(dt.total, 0) AS damage_taken
            FROM pl_player_sessions ps
            LEFT JOIN (
                SELECT player_session_id, SUM(damage_dealt) AS total
                FROM pl_battle_logs
                WHERE game_session_id = ?
                GROUP BY player_session_id
            ) dg ON dg.player_session_id = ps.id
            LEFT JOIN (
                SELECT target_player_session_id, SUM(damage_dealt) AS total
                FROM pl_battle_logs
                WHERE game_session_id = ?
                GROUP BY target_player_session_id
            ) dt ON dt.target_player_session_id = ps.id
            WHERE ps.game_session_id = ?
        ");
        $dmgStmt->execute([$sessionId, $sessionId, $sessionId]);
        $damage = [];
        foreach ($dmgStmt->fetchAll() as $row) {
            $damage[$row['id']] = $row;
        }

        // Kill = serangan TERAKHIR ke target yang akhirnya mati
        $killStmt = $this->db->prepare("
            SELECT bl.player_session_id, COUNT(*) AS kills
            FROM pl_battle_logs bl
            JOIN (
                SELECT target_player_session_id, MAX(id) AS last_id
                FROM pl_battle_logs
                WHERE game_session_id = ? AND action_type = 'attack' AND damage_dealt > 0
                GROUP BY target_player_session_id
            ) last_hit ON last_hit.last_id = bl.id
            JOIN pl_player_sessions tps ON tps.id = bl.target_player_session_id AND tps.is_alive = 0
            GROUP BY bl.player_session_id
        ");
        $killStmt->execute([$sessionId]);
        $kills = $killStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        // Disiapkan sekali, dipakai ulang untuk tiap player
        $statStmt = $this->db->prepare("
            INSERT INTO pl_player_stats
                (user_id, total_match, total_win, total_lose, total_damage_given, total_damage_taken, total_kill, total_death)
            VALUES (?, 1, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                total_match        = total_match + 1,
                total_win          = total_win + VALUES(total_win),
                total_lose         = total_lose + VALUES(total_lose),
                total_damage_given = total_damage_given + VALUES(total_damage_given),
                total_damage_taken = total_damage_taken + VALUES(total_damage_taken),
                total_kill         = total_kill + VALUES(total_kill),
                total_death        = total_death + VALUES(total_death)
        ");
        $pickStmt = $this->db->prepare("
            INSERT INTO pl_role_pick_stats (user_id, pl_role_id, pick_count)
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE pick_count = pick_count + 1
        ");

        $this->db->beginTransaction();
        try {
            foreach ($players as $player) {
                $isWin = ($player['team'] === $winner) ? 1 : 0;
                $statStmt->execute([
                    $player['user_id'],
                    $isWin,
                    1 - $isWin,
                    (int)($damage[$player['id']]['damage_given'] ?? 0),
                    (int)($damage[$player['id']]['damage_taken'] ?? 0),
                    (int)($kills[$player['id']] ?? 0),
                    $player['is_alive'] ? 0 : 1,
                ]);
                if ($player['pl_role_id']) {
                    $pickStmt->execute([$player['user_id'], $player['pl_role_id']]);
                }
            }
            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getPlayerStats($userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM pl_player_stats WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $stats = $stmt->fetch();

        if (!$stats) {
            $stats = [
                'total_match'        => 0,
                'total_win'          => 0,
                'total_lose'         => 0,
                'total_damage_given' => 0,
                'total_damage_taken' => 0,
                'total_kill'         => 0,
                'total_death'        => 0,
            ];
        }

        // Role pick counts
        $rpStmt = $this->db->prepare("
            SELECT rps.*, r.name AS role_name
            FROM pl_role_pick_stats rps
            JOIN pl_roles r ON rps.pl_role_id = r.id
            WHERE rps.user_id = ?
            ORDER BY rps.pick_count DESC
        ");
        $rpStmt->execute([$userId]);
        $stats['role_picks'] = $rpStmt->fetchAll();

        return $stats;
    }

    // ===== STATE / POLLING =====

    public function getFullGameState($sessionId, ?array $session = null): array
    {
        $session = $session ?? $this->getSessionById($sessionId);
        if (!$session) {
            return ['error' => 'Session not found'];
        }

        $players = $this->getSessionPlayers($sessionId);

        // Recent battle logs with actor/target names
        $logStmt = $this->db->prepare("
            SELECT
                bl.*,
                u.name  AS actor_name,
                tu.name AS target_name,
                tr.name AS target_role_name
            FROM pl_battle_logs bl
            JOIN pl_player_sessions ps ON bl.player_session_id = ps.id
            JOIN users u ON ps.user_id = u.id
            LEFT JOIN pl_player_sessions tps ON bl.target_player_session_id = tps.id
            LEFT JOIN users tu ON tps.user_id = tu.id
            LEFT JOIN pl_roles tr ON tps.pl_role_id = tr.id
            WHERE bl.game_session_id = ?
            ORDER BY bl.created_at DESC
            LIMIT 20
        ");
        $logStmt->execute([$sessionId]);
        $battleLogs = $logStmt->fetchAll();

        // GM name (sudah ikut di-JOIN oleh getSessionById)
        $gmName = $session['gm_name'] ?? null;

        // Timers
        $turnTimerRemaining = null;
        if ($session['turn_timer_end']) {
            $turnTimerRemaining = max(0, strtotime($session['turn_timer_end']) - time());
        }
        $discussionTimerRemaining = null;
        if ($session['discussion_timer_end']) {
            $discussionTimerRemaining = max(0, strtotime($session['discussion_timer_end']) - time());
        }

        // Current turn player
        $currentTurnPlayer = null;
        foreach ($players as $p) {
            if ((int)$p['id'] === (int)$session['current_turn_player_session_id']) {
                $currentTurnPlayer = $p;
                break;
            }
        }

        // Separate teams
        $teamA = array_values(array_filter($players, fn($p) => $p['team'] === 'A'));
        $teamB = array_values(array_filter($players, fn($p) => $p['team'] === 'B'));

        return [
            'session'                    => $session,
            'gm_name'                    => $gmName,
            'players'                    => $players,
            'team_a'                     => $teamA,
            'team_b'                     => $teamB,
            'current_turn_player'        => $currentTurnPlayer,
            'battle_logs'                => $battleLogs,
            'turn_timer_remaining'       => $turnTimerRemaining,
            'discussion_timer_remaining' => $discussionTimerRemaining,
            'timestamp'                  => time(),
        ];
    }

    public function getRecentBattleLogs($sessionId, int $limit = 20): array
    {
        $stmt = $this->db->prepare("
            SELECT
                bl.*,
                u.name  AS actor_name,
                tu.name AS target_name
            FROM pl_battle_logs bl
            JOIN pl_player_sessions ps ON bl.player_session_id = ps.id
            JOIN users u ON ps.user_id = u.id
            LEFT JOIN pl_player_sessions tps ON bl.target_player_session_id = tps.id
            LEFT JOIN users tu ON tps.user_id = tu.id
            WHERE bl.game_session_id = ?
            ORDER BY bl.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$sessionId, $limit]);
        return $stmt->fetchAll();
    }

    public function finishGame($sessionId, $winnerTeam = null): bool
    {
        $winnerTeam = in_array($winnerTeam, ['A', 'B'], true) ? $winnerTeam : $this->checkWinner($sessionId);

        $stmt = $this->db->prepare("
        UPDATE pl_game_sessions
        SET status = 'finished', winner_team = ?
        WHERE id = ? AND status != 'finished'
    ");
        $stmt->execute([$winnerTeam, $sessionId]);

        if ($stmt->rowCount() === 0) {
            return false;
        }
        $this->updateLifetimeStats($sessionId);
        return true;
    }
}
