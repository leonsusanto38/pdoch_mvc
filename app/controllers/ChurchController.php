<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class ChurchController extends Controller {
    private $churchModel;
    private $denominationModel;

    public function __construct() {
        Session::requireAdmin();
        $this->churchModel = $this->model('ChurchModel');
        $this->denominationModel = $this->model('DenominationModel');
    }

    public function index() {
        $churches = $this->churchModel->getAll();
        $denominations = $this->denominationModel->getAll();
        
        $editChurch = null;
        $editId = $_GET['edit_id'] ?? null;
        if ($editId) {
            $editChurch = $this->churchModel->getById($editId);
        }

        $this->view('church/index', [
            'churches' => $churches,
            'denominations' => $denominations,
            'editChurch' => $editChurch
        ]);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-church');
        }

        $name = trim($_POST['name'] ?? '');
        $idDenomination = $_POST['id_denomination'] ?? null;
        $address = trim($_POST['address'] ?? '');

        if (empty($name)) {
            Session::setFlash('error', 'Nama gereja wajib diisi.');
            $this->redirect('manage-church');
        }

        $data = [
            'name' => $name,
            'id_denomination' => $idDenomination ?: null,
            'address' => $address ?: null,
            'id_user_created' => Session::get('user_id')
        ];

        if ($this->churchModel->create($data)) {
            Session::setFlash('success', 'Gereja baru berhasil ditambahkan.');
        } else {
            Session::setFlash('error', 'Gagal menambahkan gereja.');
        }

        $this->redirect('manage-church');
    }

    public function update() {
        $id = $_GET['id'] ?? null;
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-church');
        }

        $name = trim($_POST['name'] ?? '');
        $idDenomination = $_POST['id_denomination'] ?? null;
        $address = trim($_POST['address'] ?? '');

        if (empty($name)) {
            Session::setFlash('error', 'Nama gereja wajib diisi.');
            $this->redirect('manage-church?edit_id=' . $id);
        }

        $data = [
            'name' => $name,
            'id_denomination' => $idDenomination ?: null,
            'address' => $address ?: null,
            'id_user_updated' => Session::get('user_id')
        ];

        if ($this->churchModel->update($id, $data)) {
            Session::setFlash('success', 'Gereja berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui gereja.');
        }

        $this->redirect('manage-church');
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-church');
        }

        if ($this->churchModel->delete($id)) {
            Session::setFlash('success', 'Gereja berhasil dihapus.');
        } else {
            Session::setFlash('error', 'Gagal menghapus gereja. Pastikan tidak ada user/jemaat yang terdaftar di bawah gereja asal ini.');
        }

        $this->redirect('manage-church');
    }
}
