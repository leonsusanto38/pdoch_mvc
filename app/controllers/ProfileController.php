<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class ProfileController extends Controller {
    private $userModel;
    private $churchModel;

    public function __construct() {
        Session::requireLogin();
        $this->userModel = $this->model('UserModel');
        $this->churchModel = $this->model('ChurchModel');
    }

    public function index() {
        $userId = Session::get('user_id');
        $user = $this->userModel->getById($userId);
        $churches = $this->churchModel->getAll();

        $this->view('profile/index', [
            'user' => $user,
            'churches' => $churches
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('profile');
        }

        $userId = Session::get('user_id');
        $user = $this->userModel->getById($userId);

        $name = trim($_POST['name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $birthDate = $_POST['birth_date'] ?? null;
        $phoneNumber = trim($_POST['phone_number'] ?? null);
        $address = trim($_POST['address'] ?? null);
        $gender = $_POST['gender'] ?? null;
        $idChurch = $_POST['id_church'] ?? null;

        if (empty($name) || empty($username)) {
            Session::setFlash('error', 'Nama dan Username wajib diisi.');
            $this->redirect('profile');
        }

        // Handle Image Upload
        $imageName = $user['image']; // Keep old image by default
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                if ($fileSize <= 2 * 1024 * 1024) { // Max 2MB
                    // Unique file name
                    $newFileName = 'user_' . $userId . '_' . time() . '.' . $fileExtension;
                    
                    $uploadFileDir = __DIR__ . '/../../assets/uploads/';
                    
                    // Create directory if not exists
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }
                    
                    $dest_path = $uploadFileDir . $newFileName;
                    
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        // Delete old profile image if it exists and is not a default
                        if ($user['image'] && file_exists($uploadFileDir . $user['image'])) {
                            unlink($uploadFileDir . $user['image']);
                        }
                        $imageName = $newFileName;
                        Session::set('image', $newFileName); // Update image in session
                    } else {
                        Session::setFlash('error', 'Terjadi kesalahan saat memindahkan file.');
                        $this->redirect('profile');
                    }
                } else {
                    Session::setFlash('error', 'Ukuran file terlalu besar. Maksimal 2MB.');
                    $this->redirect('profile');
                }
            } else {
                Session::setFlash('error', 'Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.');
                $this->redirect('profile');
            }
        }

        // Update database
        $updateData = [
            'name' => $name,
            'username' => $username,
            'birth_date' => $birthDate,
            'phone_number' => $phoneNumber,
            'address' => $address,
            'gender' => $gender,
            'image' => $imageName,
            'id_church' => $idChurch,
            'status' => $user['status'], // Keep status
            'id_user_updated' => $userId
        ];

        if ($this->userModel->update($userId, $updateData)) {
            Session::set('name', $name); // Update name in session
            Session::set('username', $username); // Update username in session
            Session::setFlash('success', 'Profil Anda berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui profil.');
        }

        $this->redirect('profile');
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('profile');
        }

        $userId = Session::get('user_id');
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            Session::setFlash('error', 'Semua kolom password harus diisi.');
            $this->redirect('profile');
        }

        $user = $this->userModel->getById($userId);

        if (!password_verify($oldPassword, $user['password'])) {
            Session::setFlash('error', 'Password lama Anda salah.');
            $this->redirect('profile');
        }

        if ($newPassword !== $confirmPassword) {
            Session::setFlash('error', 'Konfirmasi password baru tidak cocok.');
            $this->redirect('profile');
        }

        if (strlen($newPassword) < 6) {
            Session::setFlash('error', 'Password baru minimal harus 6 karakter.');
            $this->redirect('profile');
        }

        if ($this->userModel->changePassword($userId, $newPassword)) {
            Session::setFlash('success', 'Password Anda berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal mengubah password.');
        }

        $this->redirect('profile');
    }

    public function myDivisions() {
        $userId = Session::get('user_id');
        $divisions = $this->userModel->getDivisions($userId);

        $this->view('profile/divisions', [
            'divisions' => $divisions
        ]);
    }
}
