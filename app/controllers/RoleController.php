<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class RoleController extends Controller {
    private $roleModel;

    public function __construct() {
        Session::requireAdmin();
        $this->roleModel = $this->model('RoleModel');
    }

    public function index() {
        $roles = $this->roleModel->getAll();
        
        $editRole = null;
        $editId = $_GET['edit_id'] ?? null;
        if ($editId) {
            $editRole = $this->roleModel->getById($editId);
        }

        $this->view('role/index', [
            'roles' => $roles,
            'editRole' => $editRole
        ]);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-role');
        }

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            Session::setFlash('error', 'Nama role wajib diisi.');
            $this->redirect('manage-role');
        }

        $data = [
            'name' => $name,
            'id_user_created' => Session::get('user_id')
        ];

        if ($this->roleModel->create($data)) {
            Session::setFlash('success', 'Role baru berhasil ditambahkan.');
        } else {
            Session::setFlash('error', 'Gagal menambahkan role.');
        }

        $this->redirect('manage-role');
    }

    public function update() {
        $id = $_GET['id'] ?? null;
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-role');
        }

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            Session::setFlash('error', 'Nama role wajib diisi.');
            $this->redirect('manage-role?edit_id=' . $id);
        }

        $data = [
            'name' => $name,
            'id_user_updated' => Session::get('user_id')
        ];

        if ($this->roleModel->update($id, $data)) {
            Session::setFlash('success', 'Nama role berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui role.');
        }

        $this->redirect('manage-role');
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-role');
        }

        // Prevent deleting critical roles Admin (id=1) and Jemaat (id=2)
        if ($id == 1 || $id == 2) {
            Session::setFlash('error', 'Role bawaan sistem (Admin/Jemaat) tidak dapat dihapus.');
            $this->redirect('manage-role');
        }

        if ($this->roleModel->delete($id)) {
            Session::setFlash('success', 'Role berhasil dihapus.');
        } else {
            Session::setFlash('error', 'Gagal menghapus role. Pastikan tidak ada user yang terhubung ke role ini.');
        }

        $this->redirect('manage-role');
    }
}
