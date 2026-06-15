<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class DivisionController extends Controller {
    private $divisionModel;
    private $userModel;

    public function __construct() {
        Session::requireAdmin();
        $this->divisionModel = $this->model('DivisionModel');
        $this->userModel = $this->model('UserModel');
    }

    public function index() {
        $divisions = $this->divisionModel->getAll();
        $users = $this->userModel->getAll(); // for coordinator dropdown
        
        $editDiv = null;
        $editId = $_GET['edit_id'] ?? null;
        if ($editId) {
            $editDiv = $this->divisionModel->getById($editId);
        }

        $this->view('division/index', [
            'divisions' => $divisions,
            'users' => $users,
            'editDiv' => $editDiv
        ]);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-division');
        }

        $name = trim($_POST['name'] ?? '');
        $coordinatorId = $_POST['coordinator_user_id'] ?? null;
        $description = trim($_POST['description'] ?? '');

        if (empty($name)) {
            Session::setFlash('error', 'Nama divisi wajib diisi.');
            $this->redirect('manage-division');
        }

        $data = [
            'name' => $name,
            'coordinator_user_id' => $coordinatorId ?: null,
            'description' => $description ?: null,
            'id_user_created' => Session::get('user_id')
        ];

        if ($this->divisionModel->create($data)) {
            Session::setFlash('success', 'Divisi baru berhasil ditambahkan.');
        } else {
            Session::setFlash('error', 'Gagal menambahkan divisi.');
        }

        $this->redirect('manage-division');
    }

    public function update() {
        $id = $_GET['id'] ?? null;
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-division');
        }

        $name = trim($_POST['name'] ?? '');
        $coordinatorId = $_POST['coordinator_user_id'] ?? null;
        $description = trim($_POST['description'] ?? '');

        if (empty($name)) {
            Session::setFlash('error', 'Nama divisi wajib diisi.');
            $this->redirect('manage-division?edit_id=' . $id);
        }

        $data = [
            'name' => $name,
            'coordinator_user_id' => $coordinatorId ?: null,
            'description' => $description ?: null,
            'id_user_updated' => Session::get('user_id')
        ];

        if ($this->divisionModel->update($id, $data)) {
            Session::setFlash('success', 'Divisi berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui divisi.');
        }

        $this->redirect('manage-division');
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-division');
        }

        if ($this->divisionModel->delete($id)) {
            Session::setFlash('success', 'Divisi berhasil dihapus beserta seluruh riwayat anggotanya.');
        } else {
            Session::setFlash('error', 'Gagal menghapus divisi.');
        }

        $this->redirect('manage-division');
    }

    // --- Division Member Management ---

    public function members() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-division');
        }

        $division = $this->divisionModel->getById($id);
        if (!$division) {
            Session::setFlash('error', 'Divisi tidak ditemukan.');
            $this->redirect('manage-division');
        }

        $members = $this->divisionModel->getMembers($id);
        $users = $this->userModel->getAll(); // all users for the selection dropdown

        $this->view('division/members', [
            'division' => $division,
            'members' => $members,
            'users' => $users
        ]);
    }

    public function addMember() {
        $id = $_GET['id'] ?? null; // division_id
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-division');
        }

        $userId = $_POST['user_id'] ?? null;
        $role = trim($_POST['role'] ?? 'Anggota');

        if (empty($userId)) {
            Session::setFlash('error', 'User wajib dipilih.');
            $this->redirect('manage-division?action=members&id=' . $id);
        }

        if ($this->divisionModel->addMember($id, $userId, $role, Session::get('user_id'))) {
            Session::setFlash('success', 'Anggota berhasil ditambahkan ke divisi.');
        } else {
            Session::setFlash('error', 'Gagal menambahkan anggota. Mungkin user sudah tergabung di divisi ini.');
        }

        $this->redirect('manage-division?action=members&id=' . $id);
    }

    public function removeMember() {
        $id = $_GET['id'] ?? null; // division_id
        $userId = $_GET['user_id'] ?? null;
        if (!$id || !$userId) {
            $this->redirect('manage-division');
        }

        if ($this->divisionModel->removeMember($id, $userId)) {
            Session::setFlash('success', 'Anggota berhasil dikeluarkan dari divisi.');
        } else {
            Session::setFlash('error', 'Gagal mengeluarkan anggota.');
        }

        $this->redirect('manage-division?action=members&id=' . $id);
    }

    public function updateMember() {
        $id = $_GET['id'] ?? null; // division_id
        $userId = $_GET['user_id'] ?? null;
        if (!$id || !$userId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-division');
        }

        $role = trim($_POST['role'] ?? 'Anggota');

        if ($this->divisionModel->updateMemberRole($id, $userId, $role, Session::get('user_id'))) {
            Session::setFlash('success', 'Peran anggota berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui peran anggota.');
        }

        $this->redirect('manage-division?action=members&id=' . $id);
    }
}
