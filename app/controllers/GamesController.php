<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class GamesController extends Controller
{
    public function __construct()
    {
        Session::requireRoles(['Dewasa Muda', 'Admin']);
    }

    public function index()
    {
        $plModel    = $this->model('PilarLegendsModel');
        $userId     = Session::get('user_id');
        $userStats  = $plModel->getPlayerStats($userId);
        $allRoles   = $plModel->getAllRoles();

        $this->view('games/index', [
            'userStats' => $userStats,
            'allRoles'  => $allRoles,
        ]);
    }
}
