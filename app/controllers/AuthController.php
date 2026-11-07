<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
    }

    public function index()
    {
        if (Session::isLoggedIn()) {
            $this->redirect('home');
        }
        $this->view('auth/login', [], 'auth'); // Render login without standard default layouts
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            Session::setFlash('error', 'Username dan password harus diisi.');
            $this->redirect('login');
        }

        $result = $this->userModel->login($username, $password);

        if ($result['success']) {
            $user = $result['user'];

            session_regenerate_id(true);

            // Set User Session
            Session::set('user_id', $user['id']);
            Session::set('username', $user['username']);
            Session::set('name', $user['name']);
            Session::set('image', $user['image']);

            // Fetch and set role names
            $roles = $this->userModel->getRoles($user['id']);
            $roleNames = array_map(function ($role) {
                return $role['name'];
            }, $roles);
            Session::set('roles', $roleNames);

            Session::setFlash('success', "Selamat datang kembali, {$user['name']}!");
            $this->redirect('home');
        } else {
            Session::setFlash('error', $result['message']);
            $this->redirect('login');
        }
    }

    public function logout()
    {
        Session::destroy();
        // Set flash message in temporary cookie or session if needed, but since destroy resets everything:
        Session::init();
        Session::setFlash('success', 'Anda berhasil keluar.');
        $this->redirect('login');
    }
}
