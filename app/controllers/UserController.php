<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class UserController extends Controller {
    private $userModel;
    private $churchModel;
    private $roleModel;

    public function __construct() {
        Session::requireAdmin();
        $this->userModel = $this->model('UserModel');
        $this->churchModel = $this->model('ChurchModel');
        $this->roleModel = $this->model('RoleModel');
    }

    public function index() {
        $users = $this->userModel->getAll();
        $this->view('user/index', [
            'users' => $users
        ]);
    }

    public function create() {
        $churches = $this->churchModel->getAll();
        $this->view('user/create', [
            'churches' => $churches
        ]);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-user');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $name = trim($_POST['name'] ?? '');
        
        if (empty($username) || empty($password) || empty($name)) {
            Session::setFlash('error', 'Nama, Username, dan Password wajib diisi.');
            $this->redirect('manage-user?action=create');
        }

        // Check if username is taken
        $users = $this->userModel->getAll();
        foreach ($users as $u) {
            if ($u['username'] === $username) {
                Session::setFlash('error', 'Username sudah digunakan oleh user lain.');
                $this->redirect('manage-user?action=create');
            }
        }

        $data = [
            'id_church' => $_POST['id_church'] ?: null,
            'name' => $name,
            'username' => $username,
            'password' => $password,
            'birth_date' => $_POST['birth_date'] ?: null,
            'phone_number' => $_POST['phone_number'] ?: null,
            'address' => $_POST['address'] ?: null,
            'gender' => $_POST['gender'] ?: null,
            'status' => $_POST['status'] ?: 'active',
            'image' => null,
            'id_user_created' => Session::get('user_id')
        ];

        $userId = $this->userModel->create($data);
        if ($userId) {
            // Assign default Jemaat role if none is set
            $this->userModel->updateRoles($userId, [2], Session::get('user_id'));
            Session::setFlash('success', 'User berhasil dibuat dengan role default: Jemaat.');
        } else {
            Session::setFlash('error', 'Gagal membuat user.');
        }

        $this->redirect('manage-user');
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-user');
        }

        $user = $this->userModel->getById($id);
        $churches = $this->churchModel->getAll();

        if (!$user) {
            Session::setFlash('error', 'User tidak ditemukan.');
            $this->redirect('manage-user');
        }

        $this->view('user/edit', [
            'user' => $user,
            'churches' => $churches
        ]);
    }

    public function update() {
        $id = $_GET['id'] ?? null;
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-user');
        }

        $user = $this->userModel->getById($id);
        if (!$user) {
            Session::setFlash('error', 'User tidak ditemukan.');
            $this->redirect('manage-user');
        }

        $username = trim($_POST['username'] ?? '');
        $name = trim($_POST['name'] ?? '');

        if (empty($username) || empty($name)) {
            Session::setFlash('error', 'Nama dan Username wajib diisi.');
            $this->redirect('manage-user?action=edit&id=' . $id);
        }

        // Check unique username (excluding itself)
        $users = $this->userModel->getAll();
        foreach ($users as $u) {
            if ($u['username'] === $username && $u['id'] != $id) {
                Session::setFlash('error', 'Username sudah digunakan oleh user lain.');
                $this->redirect('manage-user?action=edit&id=' . $id);
            }
        }

        $data = [
            'id_church' => $_POST['id_church'] ?: null,
            'name' => $name,
            'username' => $username,
            'birth_date' => $_POST['birth_date'] ?: null,
            'phone_number' => $_POST['phone_number'] ?: null,
            'address' => $_POST['address'] ?: null,
            'gender' => $_POST['gender'] ?: null,
            'status' => $_POST['status'] ?: 'active',
            'image' => $user['image'],
            'id_user_updated' => Session::get('user_id')
        ];

        if ($this->userModel->update($id, $data)) {
            Session::setFlash('success', 'User berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui user.');
        }

        $this->redirect('manage-user');
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-user');
        }

        // Prevent self-deletion
        if ($id == Session::get('user_id')) {
            Session::setFlash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            $this->redirect('manage-user');
        }

        if ($this->userModel->delete($id)) {
            Session::setFlash('success', 'User berhasil dihapus.');
        } else {
            Session::setFlash('error', 'Gagal menghapus user.');
        }

        $this->redirect('manage-user');
    }

    public function resetPassword() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-user');
        }

        $user = $this->userModel->getById($id);
        if (!$user) {
            Session::setFlash('error', 'User tidak ditemukan.');
            $this->redirect('manage-user');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            if (strlen($password) < 6) {
                Session::setFlash('error', 'Password minimal harus 6 karakter.');
            } elseif ($this->userModel->changePassword($id, $password)) {
                Session::setFlash('success', "Password untuk user {$user['name']} berhasil direset.");
                $this->redirect('manage-user');
            } else {
                Session::setFlash('error', 'Gagal mereset password.');
            }
        }

        $this->view('user/reset_password', [
            'user' => $user
        ]);
    }

    public function roles() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-user');
        }

        $user = $this->userModel->getById($id);
        $roles = $this->roleModel->getAll();

        if (!$user) {
            Session::setFlash('error', 'User tidak ditemukan.');
            $this->redirect('manage-user');
        }

        $userRoleIds = array_map(function($r) {
            return $r['id'];
        }, $user['roles']);

        $this->view('user/roles', [
            'user' => $user,
            'roles' => $roles,
            'userRoleIds' => $userRoleIds
        ]);
    }

    public function updateRoles() {
        $id = $_GET['id'] ?? null;
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-user');
        }

        $roleIds = $_POST['roles'] ?? [];

        if ($this->userModel->updateRoles($id, $roleIds, Session::get('user_id'))) {
            Session::setFlash('success', 'Role user berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui role user.');
        }

        $this->redirect('manage-user');
    }
}
