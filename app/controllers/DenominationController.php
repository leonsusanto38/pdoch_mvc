<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class DenominationController extends Controller {
    private $denominationModel;

    public function __construct() {
        Session::requireAdmin();
        $this->denominationModel = $this->model('DenominationModel');
    }

    public function index() {
        $denominations = $this->denominationModel->getAll();
        
        $editDenom = null;
        $editId = $_GET['edit_id'] ?? null;
        if ($editId) {
            $editRole = $this->denominationModel->getById($editId);
            $editDenom = $editRole; // alias for clear naming
        }

        $this->view('denomination/index', [
            'denominations' => $denominations,
            'editDenom' => $editDenom
        ]);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-denomination');
        }

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            Session::setFlash('error', 'Nama denominasi wajib diisi.');
            $this->redirect('manage-denomination');
        }

        $data = [
            'name' => $name,
            'id_user_created' => Session::get('user_id')
        ];

        if ($this->denominationModel->create($data)) {
            Session::setFlash('success', 'Denominasi baru berhasil ditambahkan.');
        } else {
            Session::setFlash('error', 'Gagal menambahkan denominasi.');
        }

        $this->redirect('manage-denomination');
    }

    public function update() {
        $id = $_GET['id'] ?? null;
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-denomination');
        }

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            Session::setFlash('error', 'Nama denominasi wajib diisi.');
            $this->redirect('manage-denomination?edit_id=' . $id);
        }

        $data = [
            'name' => $name,
            'id_user_updated' => Session::get('user_id')
        ];

        if ($this->denominationModel->update($id, $data)) {
            Session::setFlash('success', 'Denominasi berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui denominasi.');
        }

        $this->redirect('manage-denomination');
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-denomination');
        }

        if ($this->denominationModel->delete($id)) {
            Session::setFlash('success', 'Denominasi berhasil dihapus.');
        } else {
            Session::setFlash('error', 'Gagal menghapus denominasi. Pastikan tidak ada gereja lokal yang terhubung ke denominasi ini.');
        }

        $this->redirect('manage-denomination');
    }
}
