<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class NewsController extends Controller {
    private $newsModel;

    public function __construct() {
        Session::requireAdmin();
        $this->newsModel = $this->model('NewsModel');
    }

    // Admin manage news list
    public function manage() {
        $newsList = $this->newsModel->getAll();
        $this->view('news/manage', [
            'newsList' => $newsList
        ]);
    }

    public function create() {
        $this->view('news/create');
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-news');
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (empty($title) || empty($content)) {
            Session::setFlash('error', 'Judul dan Konten wajib diisi.');
            $this->redirect('manage-news?action=create');
        }

        // Handle news image upload (optional)
        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExtension, $allowedExtensions)) {
                if ($fileSize <= 2 * 1024 * 1024) { // Max 2MB
                    $imageName = 'news_' . time() . '_' . uniqid() . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../../assets/uploads/';
                    
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }
                    
                    if (!move_uploaded_file($fileTmpPath, $uploadFileDir . $imageName)) {
                        $imageName = null;
                    }
                }
            }
        }

        $data = [
            'title' => $title,
            'content' => $content,
            'image' => $imageName,
            'created_by' => Session::get('user_id')
        ];

        if ($this->newsModel->create($data)) {
            Session::setFlash('success', 'Berita berhasil diterbitkan.');
        } else {
            Session::setFlash('error', 'Gagal menerbitkan berita.');
        }
        $this->redirect('manage-news');
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-news');
        }

        $news = $this->newsModel->getById($id);
        if (!$news) {
            Session::setFlash('error', 'Berita tidak ditemukan.');
            $this->redirect('manage-news');
        }

        $this->view('news/edit', [
            'news' => $news
        ]);
    }

    public function update() {
        $id = $_GET['id'] ?? null;
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-news');
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (empty($title) || empty($content)) {
            Session::setFlash('error', 'Judul dan Konten wajib diisi.');
            $this->redirect('manage-news?action=edit&id=' . $id);
        }

        $news = $this->newsModel->getById($id);
        $imageName = $news['image']; // Keep old image by default

        // Check if new image is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExtension, $allowedExtensions)) {
                if ($fileSize <= 2 * 1024 * 1024) {
                    $newImageName = 'news_' . time() . '_' . uniqid() . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../../assets/uploads/';
                    
                    if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newImageName)) {
                        // Delete old news image if exists
                        if ($news['image'] && file_exists($uploadFileDir . $news['image'])) {
                            unlink($uploadFileDir . $news['image']);
                        }
                        $imageName = $newImageName;
                    }
                }
            }
        }

        $data = [
            'title' => $title,
            'content' => $content,
            'image' => $imageName
        ];

        if ($this->newsModel->update($id, $data)) {
            Session::setFlash('success', 'Berita berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui berita.');
        }
        $this->redirect('manage-news');
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-news');
        }

        $news = $this->newsModel->getById($id);
        if ($news) {
            $uploadFileDir = __DIR__ . '/../../assets/uploads/';
            if ($news['image'] && file_exists($uploadFileDir . $news['image'])) {
                unlink($uploadFileDir . $news['image']);
            }
            if ($this->newsModel->delete($id)) {
                Session::setFlash('success', 'Berita berhasil dihapus.');
            } else {
                Session::setFlash('error', 'Gagal menghapus berita.');
            }
        }
        $this->redirect('manage-news');
    }
}
