<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class ScheduleController extends Controller {
    private $scheduleModel;
    private $userModel;

    public function __construct() {
        Session::requireLogin();
        $this->scheduleModel = $this->model('ServiceScheduleModel');
        $this->userModel = $this->model('UserModel');
    }

    // General user view for outstanding schedules
    public function index() {
        $userId = Session::get('user_id');
        $mySchedules = $this->scheduleModel->getOutstandingByUser($userId);
        
        // Fetch all outstanding schedules so users know the full list of who is serving
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT s.*, u.name as user_name, u.username as user_username
            FROM service_schedules s
            JOIN users u ON s.user_id = u.id
            WHERE s.date >= CURDATE()
            ORDER BY s.date ASC, s.type DESC
        ");
        $stmt->execute();
        $allOutstanding = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->view('schedule/index', [
            'mySchedules' => $mySchedules,
            'allOutstanding' => $allOutstanding
        ]);
    }

    // Admin manage schedules
    public function manage() {
        Session::requireAdmin();
        $schedules = $this->scheduleModel->getAll();
        $users = $this->userModel->getAll();
        $this->view('schedule/manage', [
            'schedules' => $schedules,
            'users' => $users
        ]);
    }

    public function create() {
        Session::requireAdmin();
        $users = $this->userModel->getAll();
        $this->view('schedule/create', [
            'users' => $users
        ]);
    }

    public function save() {
        Session::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-schedule');
        }

        $userId = $_POST['user_id'] ?? null;
        $role = trim($_POST['role'] ?? '');
        $date = $_POST['date'] ?? '';
        $type = $_POST['type'] ?? 'pelayanan';
        $location = trim($_POST['location'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!$userId || empty($role) || empty($date)) {
            Session::setFlash('error', 'Semua kolom bertanda bintang wajib diisi.');
            $this->redirect('manage-schedule?action=create');
        }

        $data = [
            'user_id' => $userId,
            'role' => $role,
            'date' => $date,
            'type' => $type,
            'location' => $location ?: null,
            'notes' => $notes ?: null,
            'description' => $description ?: null
        ];

        if ($this->scheduleModel->create($data)) {
            Session::setFlash('success', 'Jadwal pelayanan berhasil dibuat.');
        } else {
            Session::setFlash('error', 'Gagal membuat jadwal pelayanan.');
        }
        $this->redirect('manage-schedule');
    }

    public function edit() {
        Session::requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-schedule');
        }

        $schedule = $this->scheduleModel->getById($id);
        if (!$schedule) {
            Session::setFlash('error', 'Jadwal tidak ditemukan.');
            $this->redirect('manage-schedule');
        }

        $users = $this->userModel->getAll();
        $this->view('schedule/edit', [
            'schedule' => $schedule,
            'users' => $users
        ]);
    }

    public function update() {
        Session::requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-schedule');
        }

        $userId = $_POST['user_id'] ?? null;
        $role = trim($_POST['role'] ?? '');
        $date = $_POST['date'] ?? '';
        $type = $_POST['type'] ?? 'pelayanan';
        $location = trim($_POST['location'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!$userId || empty($role) || empty($date)) {
            Session::setFlash('error', 'Semua kolom bertanda bintang wajib diisi.');
            $this->redirect('manage-schedule?action=edit&id=' . $id);
        }

        $data = [
            'user_id' => $userId,
            'role' => $role,
            'date' => $date,
            'type' => $type,
            'location' => $location ?: null,
            'notes' => $notes ?: null,
            'description' => $description ?: null
        ];

        if ($this->scheduleModel->update($id, $data)) {
            Session::setFlash('success', 'Jadwal pelayanan berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui jadwal pelayanan.');
        }
        $this->redirect('manage-schedule');
    }

    public function delete() {
        Session::requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-schedule');
        }

        if ($this->scheduleModel->delete($id)) {
            Session::setFlash('success', 'Jadwal pelayanan berhasil dihapus.');
        } else {
            Session::setFlash('error', 'Gagal menghapus jadwal pelayanan.');
        }
        $this->redirect('manage-schedule');
    }
}
