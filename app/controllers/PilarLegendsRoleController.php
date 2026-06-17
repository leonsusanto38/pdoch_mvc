<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class PilarLegendsRoleController extends Controller
{
    private $roleModel;

    public function __construct()
    {
        Session::requireAdmin();
        $this->roleModel = $this->model('PilarLegendsRoleModel');
    }

    public function index()
    {
        $roles    = $this->roleModel->getAll();
        $editRole = null;
        $editId   = $_GET['edit_id'] ?? null;

        if ($editId) {
            $editRole = $this->roleModel->getById($editId);
        }

        $this->view('manage_pl_roles/index', [
            'roles'    => $roles,
            'editRole' => $editRole,
        ]);
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-pl-roles');
        }

        $data = [
            'name'          => trim($_POST['name'] ?? ''),
            'base_attack'   => (int)($_POST['base_attack'] ?? 0),
            'base_hp'       => (int)($_POST['base_hp'] ?? 0),
            'base_movement' => (int)($_POST['base_movement'] ?? 0),
            'range_cells'   => (int)($_POST['range_cells'] ?? 0),
            'description'   => trim($_POST['description'] ?? ''),
            'created_by'    => Session::get('user_id'),
        ];

        if (empty($data['name'])) {
            Session::setFlash('error', 'Nama role wajib diisi.');
            $this->redirect('manage-pl-roles');
        }

        if ($this->roleModel->create($data)) {
            Session::setFlash('success', "Role '{$data['name']}' berhasil ditambahkan.");
        } else {
            Session::setFlash('error', 'Gagal menambahkan role.');
        }

        $this->redirect('manage-pl-roles');
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-pl-roles');
        }

        $data = [
            'name'          => trim($_POST['name'] ?? ''),
            'base_attack'   => (int)($_POST['base_attack'] ?? 0),
            'base_hp'       => (int)($_POST['base_hp'] ?? 0),
            'base_movement' => (int)($_POST['base_movement'] ?? 0),
            'range_cells'   => (int)($_POST['range_cells'] ?? 0),
            'description'   => trim($_POST['description'] ?? ''),
        ];

        if (empty($data['name'])) {
            Session::setFlash('error', 'Nama role wajib diisi.');
            $this->redirect('manage-pl-roles?edit_id=' . $id);
        }

        if ($this->roleModel->update($id, $data)) {
            Session::setFlash('success', 'Role berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui role.');
        }

        $this->redirect('manage-pl-roles');
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-pl-roles');
        }

        // Protect default seeded roles (id 1-6)
        if ($id <= 6) {
            Session::setFlash('error', 'Role bawaan sistem tidak dapat dihapus.');
            $this->redirect('manage-pl-roles');
        }

        if ($this->roleModel->delete($id)) {
            Session::setFlash('success', 'Role berhasil dihapus.');
        } else {
            Session::setFlash('error', 'Gagal menghapus role. Mungkin role sedang digunakan dalam sesi game aktif.');
        }

        $this->redirect('manage-pl-roles');
    }
}
