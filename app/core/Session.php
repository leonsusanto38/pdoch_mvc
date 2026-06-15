<?php
namespace App\Core;

class Session {
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        self::init();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        self::init();
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key) {
        self::init();
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        self::init();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy() {
        self::init();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public static function setFlash($key, $message) {
        self::init();
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash($key) {
        self::init();
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    public static function isLoggedIn() {
        return self::has('user_id');
    }

    public static function isAdmin() {
        $roles = self::get('roles', []);
        return in_array('Admin', $roles);
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            self::setFlash('error', 'Silakan login terlebih dahulu.');
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            self::setFlash('error', 'Anda tidak memiliki akses ke halaman ini.');
            header('Location: ' . BASE_URL . '/home');
            exit();
        }
    }

    public static function hasRole($roleName) {
        $roles = self::get('roles', []);
        return in_array($roleName, $roles);
    }

    public static function hasAnyRole($allowedRoles) {
        $roles = self::get('roles', []);
        foreach ($allowedRoles as $r) {
            if (in_array($r, $roles)) return true;
        }
        return false;
    }

    public static function requireRoles($allowedRoles) {
        self::requireLogin();
        if (!self::hasAnyRole($allowedRoles)) {
            self::setFlash('error', 'Anda tidak memiliki akses ke halaman ini.');
            header('Location: ' . BASE_URL . '/home');
            exit();
        }
    }
}
