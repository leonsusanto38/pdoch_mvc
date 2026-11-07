<?php

namespace App\Core;

class App
{
    public function __construct()
    {
        Session::init();

        // Boot database (triggers auto-seeding if empty)
        try {
            Database::getInstance();
        } catch (\Exception $e) {
            die("<div style='font-family:sans-serif;padding:40px;color:#dc2626;'><h2>Database Error</h2><p>" . htmlspecialchars($e->getMessage()) . "</p></div>");
        }

        $page   = strtolower(trim($_GET['x'] ?? 'home'));
        $action = $_GET['action'] ?? null;

        // --- Page → Controller map ---
        $controllerMap = [
            'home'                    => 'HomeController',
            'login'                   => 'AuthController',
            'logout'                  => 'AuthController',
            'profile'                 => 'ProfileController',
            'division'                => 'ProfileController',
            'events'                  => 'EventController',
            'social'                  => 'SocialController',
            'schedule'                => 'ScheduleController',
            'manage-schedule'         => 'ScheduleController',
            'manage-news'             => 'NewsController',
            'manage-user'             => 'UserController',
            'manage-role'             => 'RoleController',
            'manage-denomination'     => 'DenominationController',
            'manage-church'           => 'ChurchController',
            'manage-division'         => 'DivisionController',
            'manage-event'            => 'EventController',
            // Games
            'games'                   => 'GamesController',
            'pilar-legends'           => 'PilarLegendsController',
            'pilar-legends-dashboard' => 'PilarLegendsController',
            'manage-pl-roles'         => 'PilarLegendsRoleController',
        ];

        // --- Default method per page (when no ?action= given) ---
        $defaultMethodMap = [
            'home'                    => 'index',
            'login'                   => 'index',
            'logout'                  => 'logout',
            'profile'                 => 'index',
            'division'                => 'myDivisions',
            'events'                  => 'userEvents',
            'social'                  => 'index',
            'schedule'                => 'index',
            'manage-schedule'         => 'manage',
            'manage-news'             => 'manage',
            'manage-user'             => 'index',
            'manage-role'             => 'index',
            'manage-denomination'     => 'index',
            'manage-church'           => 'index',
            'manage-division'         => 'index',
            'manage-event'            => 'index',
            // Games
            'games'                   => 'index',
            'pilar-legends'           => 'index',
            'pilar-legends-dashboard' => 'dashboard',
            'manage-pl-roles'         => 'index',
        ];

        // --- Action → method name map (hyphen/snake to camelCase) ---
        $actionMethodMap = [
            // Auth
            'login'            => 'login',
            // Profile
            'update'           => 'update',
            'change-password'  => 'changePassword',
            // User
            'save'             => 'save',
            'edit'             => 'edit',
            'delete'           => 'delete',
            'create'           => 'create',
            'reset-password'   => 'resetPassword',
            'roles'            => 'roles',
            'update-roles'     => 'updateRoles',
            // Role / Denomination / Church
            // 'save','update','delete' shared above
            // Division
            'members'          => 'members',
            'add-member'       => 'addMember',
            'remove-member'    => 'removeMember',
            'update-member'    => 'updateMember',
            // Event
            'generate'         => 'generate',
            'add-evaluation'   => 'addEvaluation',
            // Pilar Legends Game
            'create-game'      => 'createGame',
            'join-game'        => 'joinGame',
            'game-master'      => 'gameMaster',
            'player-view'      => 'playerView',
            'dashboard'        => 'dashboard',
            'poll-state'       => 'pollState',
            'assign-team'      => 'assignTeam',
            'randomize-teams'  => 'randomizeTeams',
            'set-player-role'  => 'setPlayerRole',
            'auto-assign-roles' => 'autoAssignRoles',
            'set-turn-order'   => 'setTurnOrder',
            'randomize-turns'  => 'randomizeTurns',
            'process-action'   => 'processAction',
            'update-status'    => 'updateStatus',
            'finish-game'      => 'finishGame',
        ];

        // Resolve controller class
        $controllerName = $controllerMap[$page] ?? 'HomeController';
        $controllerClass = "App\\Controllers\\" . $controllerName;

        $controller = new $controllerClass();

        // Resolve method
        if ($action && isset($actionMethodMap[$action])) {
            $method = $actionMethodMap[$action];
        } elseif ($page === 'logout') {
            $method = 'logout';
        } else {
            $method = $defaultMethodMap[$page] ?? 'index';
        }

        // Safety check
        if (!method_exists($controller, $method)) {
            $controller = new \App\Controllers\HomeController();
            $method = 'notFound';
        }

        // --- Proteksi CSRF & aksi hapus wajib POST ---
        $isPost = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';

        if (in_array($method, ['delete', 'removeMember'], true) && !$isPost) {
            Session::setFlash('error', 'Aksi hapus harus lewat tombol di halaman.');
            header('Location: ' . BASE_URL . '/home');
            exit();
        }

        if ($isPost && !Session::verifyCsrf()) {
            if ($page === 'pilar-legends') {
                http_response_code(419);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Token tidak valid. Muat ulang halaman.']);
                exit();
            }
            $back = $_SERVER['HTTP_REFERER'] ?? '';
            if (strpos($back, BASE_URL) !== 0) {
                $back = BASE_URL . '/home';
            }
            Session::setFlash('error', 'Sesi form kedaluwarsa. Silakan muat ulang halaman lalu coba lagi.');
            header('Location: ' . $back);
            exit();
        }

        call_user_func([$controller, $method]);
    }
}
