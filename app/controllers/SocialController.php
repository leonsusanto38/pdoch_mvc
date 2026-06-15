<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class SocialController extends Controller {
    private $userModel;

    public function __construct() {
        Session::requireRoles(['Admin', 'Pelayan']);
        $this->userModel = $this->model('UserModel');
    }

    public function index() {
        $users = $this->userModel->getAll();
        $this->view('social/index', [
            'users' => $users
        ]);
    }
}
