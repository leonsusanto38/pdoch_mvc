<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class EventController extends Controller
{
    private $eventModel;
    private $userModel;

    public function __construct()
    {
        $this->eventModel = $this->model('EventModel');
        $this->userModel = $this->model('UserModel');
    }

    // Public: list events for all users (no login required)
    public function userEvents()
    {
        $upcomingEvents = $this->eventModel->getUpcomingEvents(20);
        $pastEvents = $this->eventModel->getPastEvents(20);

        // Fetch evaluations for past events
        $evaluationModel = $this->model('EventEvaluationModel');
        $evals = $evaluationModel->getByEvents(array_column($pastEvents, 'id'));
        foreach ($pastEvents as &$event) {
            $event['evaluations'] = $evals[$event['id']] ?? [];
        }
        unset($event);

        $this->view('event/user_events', [
            'upcomingEvents' => $upcomingEvents,
            'pastEvents' => $pastEvents
        ]);
    }

    // Admin: manage events
    public function index()
    {
        Session::requireAdmin();
        $events = $this->eventModel->getAll();
        $users = $this->userModel->getAll();
        $this->view('event/index', [
            'events' => $events,
            'users' => $users
        ]);
    }

    public function create()
    {
        Session::requireAdmin();
        $users = $this->userModel->getAll();
        $this->view('event/create', ['users' => $users]);
    }

    public function save()
    {
        Session::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-event');
        }

        $name = trim($_POST['name'] ?? '');
        $timeStart = $_POST['time_start'] ?? '';
        $timeEnd   = $_POST['time_end'] ?? '';

        if (empty($name) || empty($timeStart) || empty($timeEnd)) {
            Session::setFlash('error', 'Nama, waktu mulai, dan waktu selesai wajib diisi.');
            $this->redirect('manage-event?action=create');
        }

        $data = [
            'coordinator_user_id' => $_POST['coordinator_user_id'] ?: null,
            'name' => $name,
            'time_start' => $timeStart,
            'time_end'   => $timeEnd,
            'description' => trim($_POST['description'] ?? '') ?: null,
            'id_user_created' => Session::get('user_id')
        ];

        if ($this->eventModel->create($data)) {
            Session::setFlash('success', 'Event berhasil dibuat.');
        } else {
            Session::setFlash('error', 'Gagal membuat event.');
        }
        $this->redirect('manage-event');
    }

    public function edit()
    {
        Session::requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-event');
        }

        $event = $this->eventModel->getById($id);
        if (!$event) {
            Session::setFlash('error', 'Event tidak ditemukan.');
            $this->redirect('manage-event');
        }
        $users = $this->userModel->getAll();
        $this->view('event/edit', ['event' => $event, 'users' => $users]);
    }

    public function update()
    {
        Session::requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-event');
        }

        $name = trim($_POST['name'] ?? '');
        $timeStart = $_POST['time_start'] ?? '';
        $timeEnd   = $_POST['time_end'] ?? '';

        if (empty($name) || empty($timeStart) || empty($timeEnd)) {
            Session::setFlash('error', 'Nama, waktu mulai, dan waktu selesai wajib diisi.');
            $this->redirect('manage-event?action=edit&id=' . $id);
        }

        $data = [
            'coordinator_user_id' => $_POST['coordinator_user_id'] ?: null,
            'name' => $name,
            'time_start' => $timeStart,
            'time_end'   => $timeEnd,
            'description' => trim($_POST['description'] ?? '') ?: null,
            'id_user_updated' => Session::get('user_id')
        ];

        if ($this->eventModel->update($id, $data)) {
            Session::setFlash('success', 'Event berhasil diperbarui.');
        } else {
            Session::setFlash('error', 'Gagal memperbarui event.');
        }
        $this->redirect('manage-event');
    }

    public function delete()
    {
        Session::requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('manage-event');
        }

        if ($this->eventModel->delete($id)) {
            Session::setFlash('success', 'Event berhasil dihapus.');
        } else {
            Session::setFlash('error', 'Gagal menghapus event.');
        }
        $this->redirect('manage-event');
    }

    // Auto-generate recurring worship events for a chosen month
    public function generate()
    {
        Session::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('manage-event');
        }

        $year  = (int)($_POST['year']  ?? date('Y'));
        $month = (int)($_POST['month'] ?? date('m'));

        if ($year < 2020 || $year > 2100 || $month < 1 || $month > 12) {
            Session::setFlash('error', 'Tahun atau bulan tidak valid.');
            $this->redirect('manage-event');
        }

        $count = $this->eventModel->generateRecurringEvents($year, $month, Session::get('user_id'));

        if ($count > 0) {
            Session::setFlash('success', "{$count} jadwal ibadah rutin berhasil di-generate untuk bulan " . date('F Y', mktime(0, 0, 0, $month, 1, $year)) . ".");
        } else {
            Session::setFlash('error', 'Tidak ada jadwal baru yang di-generate. Mungkin sudah ada di database.');
        }
        $this->redirect('manage-event');
    }

    public function addEvaluation()
    {
        Session::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('events');
        }

        $eventId = $_POST['event_id'] ?? null;
        $evaluationText = trim($_POST['evaluation'] ?? '');

        if (!$eventId || empty($evaluationText)) {
            Session::setFlash('error', 'Evaluasi tidak boleh kosong.');
            $this->redirect('events');
        }

        $event = $this->eventModel->getById($eventId);
        if (!$event) {
            Session::setFlash('error', 'Event tidak ditemukan.');
            $this->redirect('events');
        }

        $currentUserId = Session::get('user_id');
        $isAdmin = Session::isAdmin();
        $isPic = ($event['coordinator_user_id'] == $currentUserId);

        if (!$isAdmin && !$isPic) {
            Session::setFlash('error', 'Hanya Koordinator PIC event atau Admin yang dapat menambahkan evaluasi.');
            $this->redirect('events');
        }

        $evaluationModel = $this->model('EventEvaluationModel');
        $data = [
            'event_id' => $eventId,
            'pic_user_id' => $currentUserId,
            'evaluation' => $evaluationText
        ];

        if ($evaluationModel->create($data)) {
            Session::setFlash('success', 'Evaluasi event berhasil ditambahkan.');
        } else {
            Session::setFlash('error', 'Gagal menambahkan evaluasi.');
        }
        $this->redirect('events');
    }
}
