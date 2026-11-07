<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Upload;

class NewsController extends Controller
{
    private $newsModel;

    public function __construct()
    {
        Session::requireAdmin();
        $this->newsModel = $this->model('NewsModel');
    }

    // Admin manage news list
    public function manage()
    {
        $newsList = $this->newsModel->getAll();
        $this->view('news/manage', [
            'newsList' => $newsList
        ]);
    }

    public function create()
    {
        $this->view('news/create');
    }

    public function save()
    {
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
        try {
            $imageName = Upload::image('image', 'news');
        } catch (\RuntimeException $e) {
            Session::setFlash('error', $e->getMessage());
            $this->redirect('manage-news?action=create');
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

    public function edit()
    {
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

    public function update()
    {
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
        try {
            $newImage = Upload::image('image', 'news');
        } catch (\RuntimeException $e) {
            Session::setFlash('error', $e->getMessage());
            $this->redirect('manage-news?action=edit&id=' . $id);
        }
        if ($newImage) {
            $imageName = $newImage;
        }

        $data = [
            'title' => $title,
            'content' => $content,
            'image' => $imageName
        ];

        if ($this->newsModel->update($id, $data)) {
            if ($newImage) {
                Upload::delete($news['image']);   // hapus gambar lama setelah DB sukses
            }
            Session::setFlash('success', 'Berita berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui berita.');
        }
        $this->redirect('manage-news');
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-news');
        }

        $news = $this->newsModel->getById($id);
        if ($news) {
            if ($this->newsModel->delete($id)) {
                Upload::delete($news['image']);   // hapus file setelah data terhapus
                Session::setFlash('success', 'Berita berhasil dihapus.');
            } else {
                Session::setFlash('error', 'Gagal menghapus berita.');
            }
        }
        $this->redirect('manage-news');
    }
}
