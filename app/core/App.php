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
            'home'                => 'HomeController',
            'login'               => 'AuthController',
            'logout'              => 'AuthController',
            'profile'             => 'ProfileController',
            'division'            => 'ProfileController',
            'events'              => 'EventController',
            'social'              => 'SocialController',
            'schedule'            => 'ScheduleController',
            'manage-schedule'     => 'ScheduleController',
            'manage-news'         => 'NewsController',
            'manage-user'         => 'UserController',
            'manage-role'         => 'RoleController',
            'manage-denomination' => 'DenominationController',
            'manage-church'       => 'ChurchController',
            'manage-division'     => 'DivisionController',
            'manage-event'        => 'EventController',
        ];

        // --- Default method per page (when no ?action= given) ---
        $defaultMethodMap = [
            'home'                => 'index',
            'login'               => 'index',
            'logout'              => 'logout',
            'profile'             => 'index',
            'division'            => 'myDivisions',
            'events'              => 'userEvents',
            'social'              => 'index',
            'schedule'            => 'index',
            'manage-schedule'     => 'manage',
            'manage-news'         => 'manage',
            'manage-user'         => 'index',
            'manage-role'         => 'index',
            'manage-denomination' => 'index',
            'manage-church'       => 'index',
            'manage-division'     => 'index',
            'manage-event'        => 'index',
        ];

        // --- Action → method name map (hyphen/snake to camelCase) ---
        $actionMethodMap = [
            // Auth
            'login'          => 'login',
            // Profile
            'update'         => 'update',
            'change-password' => 'changePassword',
            // User
            'save'           => 'save',
            'edit'           => 'edit',
            'delete'         => 'delete',
            'create'         => 'create',
            'reset-password' => 'resetPassword',
            'roles'          => 'roles',
            'update-roles'   => 'updateRoles',
            // Role / Denomination / Church
            // 'save','update','delete' shared above
            // Division
            'members'        => 'members',
            'add-member'     => 'addMember',
            'remove-member'  => 'removeMember',
            'update-member'  => 'updateMember',
            // Event
            'generate'       => 'generate',
            'add-evaluation' => 'addEvaluation',
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

        call_user_func([$controller, $method]);
    }
}
