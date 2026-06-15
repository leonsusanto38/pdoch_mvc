<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    private $eventModel;

    public function __construct() {
        $this->eventModel = $this->model('EventModel');
    }

    public function index() {
        // Fetch the next 3 upcoming worship services/events
        $upcomingEvents = $this->eventModel->getUpcomingEvents(3);

        // Fetch latest news
        $newsModel = $this->model('NewsModel');
        $latestNews = $newsModel->getLatest(5);

        // Fetch users celebrating birthday today
        $userModel = $this->model('UserModel');
        $birthdayUsers = $userModel->getBirthdaysToday();

        $this->view('home/index', [
            'upcomingEvents' => $upcomingEvents,
            'latestNews' => $latestNews,
            'birthdayUsers' => $birthdayUsers
        ]);
    }

    public function notFound() {
        $this->view('home/404');
    }
}
