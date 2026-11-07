<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class PilarLegendsController extends Controller
{
    private function requireAccess(): void
    {
        Session::requireRoles(['Dewasa Muda', 'Admin']);
    }

    private function jsonResponse(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // ===== LOBBY / INDEX =====

    public function index()
    {
        $this->requireAccess();

        $userId  = Session::get('user_id');
        $plModel = $this->model('PilarLegendsModel');

        // Check for existing active session
        $active = $plModel->getActiveSessionForUser($userId);
        if ($active) {
            if ($active['user_role'] === 'gm') {
                $this->redirect('pilar-legends?action=game-master');
            } else {
                $this->redirect('pilar-legends?action=player-view');
            }
            return;
        }

        $joinCode = $_GET['join_code'] ?? '';

        $this->view('pilar_legends/lobby', [
            'joinCode' => strtoupper($joinCode),
            'isAdmin'  => Session::isAdmin(),
        ]);
    }

    // ===== CREATE GAME (Admin only) =====

    public function createGame()
    {
        Session::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('pilar-legends');
            return;
        }

        $userId  = Session::get('user_id');
        $plModel = $this->model('PilarLegendsModel');

        // Prevent duplicate sessions
        $existing = $plModel->getActiveSessionForUser($userId);
        if ($existing && $existing['user_role'] === 'gm') {
            Session::setFlash('info', 'Anda sudah memiliki sesi game aktif.');
            $this->redirect('pilar-legends?action=game-master');
            return;
        }

        $code = $plModel->createSession($userId);
        Session::setFlash('success', "Sesi game berhasil dibuat! Kode: <strong>$code</strong>");
        $this->redirect('pilar-legends?action=game-master');
    }

    // ===== JOIN GAME =====

    public function joinGame()
    {
        $this->requireAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('pilar-legends');
            return;
        }

        $userId   = Session::get('user_id');
        $gameCode = strtoupper(trim($_POST['game_code'] ?? ''));
        $plModel  = $this->model('PilarLegendsModel');

        if (empty($gameCode)) {
            Session::setFlash('error', 'Kode game wajib diisi.');
            $this->redirect('pilar-legends');
            return;
        }

        // Check already in session
        $existing = $plModel->getActiveSessionForUser($userId);
        if ($existing) {
            Session::setFlash('info', 'Anda sudah berada dalam sesi game aktif.');
            $this->redirect('pilar-legends?action=' . ($existing['user_role'] === 'gm' ? 'game-master' : 'player-view'));
            return;
        }

        $session = $plModel->getSessionByCode($gameCode);
        if (!$session) {
            Session::setFlash('error', 'Kode game tidak ditemukan.');
            $this->redirect('pilar-legends?join_code=' . $gameCode);
            return;
        }

        if ($session['status'] !== 'waiting') {
            Session::setFlash('error', 'Sesi game ini sudah tidak menerima player baru.');
            $this->redirect('pilar-legends');
            return;
        }

        // Prevent GM from joining as player in own session
        if ((int)$session['game_master_user_id'] === (int)$userId) {
            Session::setFlash('error', 'Anda adalah Game Master sesi ini.');
            $this->redirect('pilar-legends?action=game-master');
            return;
        }

        $plModel->joinSession($session['id'], $userId);
        $this->redirect('pilar-legends?action=player-view');
    }

    // ===== GAME MASTER VIEW =====

    public function gameMaster()
    {
        Session::requireAdmin();

        $userId  = Session::get('user_id');
        $plModel = $this->model('PilarLegendsModel');

        $active = $plModel->getActiveSessionForUser($userId);
        if (!$active || $active['user_role'] !== 'gm') {
            Session::setFlash('error', 'Tidak ada sesi game aktif sebagai Game Master.');
            $this->redirect('pilar-legends');
            return;
        }

        $players    = $plModel->getSessionPlayers($active['id']);
        $gameRoles  = $plModel->getAllRoles();
        $battleLogs = $plModel->getRecentBattleLogs($active['id']);
        $joinUrl    = BASE_URL . '/pilar-legends?join_code=' . $active['game_code'];
        $dashUrl    = BASE_URL . '/pilar-legends-dashboard?session_id=' . $active['id'];

        $this->view('pilar_legends/game_master', [
            'session'    => $active,
            'players'    => $players,
            'gameRoles'  => $gameRoles,
            'battleLogs' => $battleLogs,
            'joinUrl'    => $joinUrl,
            'dashUrl'    => $dashUrl,
        ]);
    }

    // ===== PLAYER VIEW =====

    public function playerView()
    {
        $this->requireAccess();

        $userId  = Session::get('user_id');
        $plModel = $this->model('PilarLegendsModel');

        $active = $plModel->getActiveSessionForUser($userId);
        if (!$active) {
            Session::setFlash('error', 'Anda tidak berada dalam sesi game aktif.');
            $this->redirect('pilar-legends');
            return;
        }

        if ($active['user_role'] === 'gm') {
            $this->redirect('pilar-legends?action=game-master');
            return;
        }

        $playerSession = $plModel->getPlayerSession($userId, $active['id']);
        $teamPlayers   = [];
        if ($playerSession && $playerSession['team']) {
            $teamPlayers = $plModel->getTeamPlayers($active['id'], $playerSession['team']);
        }
        $allPlayers = $plModel->getSessionPlayers($active['id']);
        $gameRoles  = $plModel->getAllRoles();
        $battleLogs = $plModel->getRecentBattleLogs($active['id']);
        $userStats  = $plModel->getPlayerStats($userId);

        $this->view('pilar_legends/player_view', [
            'session'       => $active,
            'playerSession' => $playerSession,
            'teamPlayers'   => $teamPlayers,
            'allPlayers'    => $allPlayers,
            'gameRoles'     => $gameRoles,
            'battleLogs'    => $battleLogs,
            'userStats'     => $userStats,
        ]);
    }

    // ===== PROJECTOR DASHBOARD =====

    public function dashboard()
    {
        $this->requireAccess();

        $sessionId = $_GET['session_id'] ?? null;

        if (!$sessionId) {
            $plModel = $this->model('PilarLegendsModel');
            $active  = $plModel->getActiveSessionForUser(Session::get('user_id'));
            $sessionId = $active['id'] ?? null;
        }

        if (!$sessionId) {
            die('<body style="background:#0a0a1a;color:#fff;font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;"><h2>Tidak ada sesi game aktif.</h2></body>');
        }

        $plModel = $this->model('PilarLegendsModel');
        $state   = $plModel->getFullGameState($sessionId);

        $this->view('pilar_legends/dashboard', [
            'state'     => $state,
            'sessionId' => $sessionId,
        ], 'fullscreen');
    }

    // ===== AJAX: POLL STATE =====

    public function pollState()
    {
        if (!Session::isLoggedIn()) {
            $this->jsonResponse(['error' => 'Not logged in'], 401);
        }

        $sessionId = $_GET['session_id'] ?? null;
        if (!$sessionId) {
            $this->jsonResponse(['error' => 'No session ID'], 400);
        }

        $plModel = $this->model('PilarLegendsModel');
        $session = $plModel->getSessionById($sessionId);

        if (!$session) {
            $this->jsonResponse(['error' => 'Session not found'], 404);
        }

        // Auto-skip check (battle turn timer)
        if ($session['status'] === 'battle' && $session['turn_timer_end']) {
            if (strtotime($session['turn_timer_end']) < time()) {
                $plModel->autoSkipTurn($sessionId);
            }
        }

        // Auto-assign check (discussion_role timer)
        if ($session['status'] === 'discussion_role' && $session['discussion_timer_end']) {
            if (strtotime($session['discussion_timer_end']) < time()) {
                $plModel->autoAssignRoles($sessionId);
            }
        }

        $state = $plModel->getFullGameState($sessionId);
        $this->jsonResponse($state);
    }

    // ===== AJAX: ASSIGN TEAM =====

    public function assignTeam()
    {
        Session::requireAdmin();

        $playerSessionId = (int)($_POST['player_session_id'] ?? 0);
        $team            = $_POST['team'] ?? null;

        if (!$playerSessionId) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid player session ID']);
        }

        $plModel = $this->model('PilarLegendsModel');
        $result  = $plModel->assignTeam($playerSessionId, $team);

        $this->jsonResponse(['success' => $result]);
    }

    // ===== AJAX: RANDOMIZE TEAMS =====

    public function randomizeTeams()
    {
        Session::requireAdmin();

        $sessionId = (int)($_POST['session_id'] ?? 0);
        if (!$sessionId) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid session ID']);
        }

        $plModel = $this->model('PilarLegendsModel');
        $result  = $plModel->randomizeTeams($sessionId);

        $this->jsonResponse(['success' => $result]);
    }

    // ===== AJAX: SET PLAYER ROLE (player picks their own role) =====

    public function setPlayerRole()
    {
        $this->requireAccess();

        $playerSessionId = (int)($_POST['player_session_id'] ?? 0);
        $roleId          = (int)($_POST['role_id'] ?? 0);

        if (!$playerSessionId || !$roleId) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid data']);
        }

        // Verify this is the player's own session
        $plModel  = $this->model('PilarLegendsModel');
        $userId   = Session::get('user_id');
        $active   = $plModel->getActiveSessionForUser($userId);
        if (!$active || (int)($active['player_session_id'] ?? 0) !== $playerSessionId) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
        }

        $result = $plModel->setPlayerRole($playerSessionId, $roleId);
        $this->jsonResponse(['success' => $result]);
    }

    // ===== AJAX: AUTO ASSIGN ROLES (GM force) =====

    public function autoAssignRoles()
    {
        Session::requireAdmin();

        $sessionId = (int)($_POST['session_id'] ?? 0);
        if (!$sessionId) {
            $this->jsonResponse(['success' => false]);
        }

        $plModel = $this->model('PilarLegendsModel');
        $plModel->autoAssignRoles($sessionId);
        $this->jsonResponse(['success' => true]);
    }

    // ===== AJAX: SET TURN ORDER (manual array of IDs in order) =====

    public function setTurnOrder()
    {
        Session::requireAdmin();

        $sessionId = (int)($_POST['session_id'] ?? 0);
        $orderJson = $_POST['order'] ?? '[]';
        $order     = json_decode($orderJson, true);

        if (!$sessionId || !is_array($order)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid data']);
        }

        $plModel = $this->model('PilarLegendsModel');
        $result  = $plModel->setTurnOrder($sessionId, $order);
        $this->jsonResponse(['success' => $result]);
    }

    // ===== AJAX: RANDOMIZE TURN ORDER =====

    public function randomizeTurns()
    {
        Session::requireAdmin();

        $sessionId = (int)($_POST['session_id'] ?? 0);
        if (!$sessionId) {
            $this->jsonResponse(['success' => false]);
        }

        $plModel = $this->model('PilarLegendsModel');
        $result  = $plModel->randomizeTurnOrder($sessionId);
        $this->jsonResponse(['success' => $result]);
    }

    // ===== AJAX: UPDATE SESSION STATUS =====

    public function updateStatus()
    {
        Session::requireAdmin();

        $sessionId = (int)($_POST['session_id'] ?? 0);
        $status    = $_POST['status'] ?? '';
        $validStatuses = ['waiting', 'team_assign', 'discussion_role', 'discussion_strategy', 'battle', 'finished'];

        if (!$sessionId || !in_array($status, $validStatuses)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid data']);
        }

        $plModel = $this->model('PilarLegendsModel');

        if ($status === 'battle') {
            $result = $plModel->startBattle($sessionId);
        } else {
            $result = $plModel->updateSessionStatus($sessionId, $status);
        }

        // Set discussion timer when entering discussion phases
        if ($result && in_array($status, ['discussion_role', 'discussion_strategy'])) {
            $plModel->setDiscussionTimer($sessionId, 2);
        }
        if ($result && !in_array($status, ['discussion_role', 'discussion_strategy'])) {
            $plModel->clearDiscussionTimer($sessionId);
        }

        $this->jsonResponse(['success' => $result]);
    }

    // ===== AJAX: PROCESS BATTLE ACTION =====

    public function processAction()
    {
        Session::requireAdmin();

        $data = [
            'session_id'        => (int)($_POST['session_id'] ?? 0),
            'player_session_id' => (int)($_POST['player_session_id'] ?? 0),
            'action_type'       => $_POST['action_type'] ?? '',
            'target_session_id' => (int)($_POST['target_session_id'] ?? 0) ?: null,
            'damage'            => (int)($_POST['damage'] ?? 0),
            'description'       => trim($_POST['description'] ?? ''),
        ];

        if (!$data['session_id'] || !$data['player_session_id'] || empty($data['action_type'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Data tidak lengkap.']);
        }

        $plModel = $this->model('PilarLegendsModel');
        $result  = $plModel->processAction($data);
        $this->jsonResponse($result);
    }

    // ===== AJAX: FINISH GAME =====

    public function finishGame()
    {
        Session::requireAdmin();

        $sessionId  = (int)($_POST['session_id'] ?? 0);
        $winnerTeam = $_POST['winner_team'] ?? null;

        if (!$sessionId) {
            $this->jsonResponse(['success' => false]);
        }

        $plModel = $this->model('PilarLegendsModel');
        $result  = $plModel->finishGame($sessionId, $winnerTeam ?: null);
        $this->jsonResponse(['success' => $result]);
    }
}
