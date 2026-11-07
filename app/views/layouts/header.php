<?php
use App\Core\Session;
$currentUser = Session::get('user_id');
$currentName = Session::get('name');
$currentUsername = Session::get('username');
$currentUserImage = Session::get('image');
$isAdmin = Session::isAdmin();
$isLoggedIn = Session::isLoggedIn();

// Active page logic helper
$currentPage = $_GET['x'] ?? 'home';
function isActive($page, $currentPage) {
    return $currentPage === $page ? 'active' : '';
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PDOCH Web App</title>
  <!-- Bootstrap 5.3.8 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Custom Premium CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg sticky-top bg-body-tertiary">
    <div class="container-lg">
      <a class="navbar-brand me-4" href="<?= BASE_URL ?>/home">
        <i class="bi bi-shield-heart-fill text-primary"></i> PDOCH
      </a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link <?= isActive('home', $currentPage) ?>" href="<?= BASE_URL ?>/home">
              <i class="bi bi-house"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= isActive('events', $currentPage) ?>" href="<?= BASE_URL ?>/events">
              <i class="bi bi-calendar-event"></i> Jadwal Ibadah
            </a>
          </li>
          
            <?php if ($isLoggedIn): ?>
            <li class="nav-item">
              <a class="nav-link <?= isActive('division', $currentPage) ?>" href="<?= BASE_URL ?>/division">
                <i class="bi bi-briefcase"></i> Divisi Saya
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= isActive('schedule', $currentPage) ?>" href="<?= BASE_URL ?>/schedule">
                <i class="bi bi-calendar-check"></i> Jadwal Pelayanan
              </a>
            </li>
            <?php if (Session::hasAnyRole(['Dewasa Muda', 'Admin'])): ?>
              <li class="nav-item">
                <a class="nav-link <?= isActive('games', $currentPage) ?>" href="<?= BASE_URL ?>/games">
                  <i class="bi bi-controller"></i> Games
                </a>
              </li>
            <?php endif; ?>
            <?php if (Session::hasAnyRole(['Admin', 'Pelayan'])): ?>
              <li class="nav-item">
                <a class="nav-link <?= isActive('social', $currentPage) ?>" href="<?= BASE_URL ?>/social">
                  <i class="bi bi-people"></i> Social
                </a>
              </li>
            <?php endif; ?>
          <?php endif; ?>

          <?php if ($isAdmin): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-primary fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-gear-fill"></i> Panel Admin
              </a>
              <ul class="dropdown-menu border-0 shadow-lg mt-2">
                <li>
                  <a class="dropdown-item py-2" href="<?= BASE_URL ?>/manage-user">
                    <i class="bi bi-people-fill text-primary me-2"></i> Kelola User
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-2" href="<?= BASE_URL ?>/manage-role">
                    <i class="bi bi-shield-lock-fill text-success me-2"></i> Kelola Role User
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-2" href="<?= BASE_URL ?>/manage-denomination">
                    <i class="bi bi-bookmark-star-fill text-warning me-2"></i> Kelola Denominasi
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-2" href="<?= BASE_URL ?>/manage-church">
                    <i class="bi bi-building-fill text-info me-2"></i> Kelola Gereja
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-2" href="<?= BASE_URL ?>/manage-division">
                    <i class="bi bi-grid-fill text-secondary me-2"></i> Kelola Divisi
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item py-2" href="<?= BASE_URL ?>/manage-schedule">
                    <i class="bi bi-calendar-event text-danger me-2"></i> Kelola Jadwal Tugas
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-2" href="<?= BASE_URL ?>/manage-news">
                    <i class="bi bi-newspaper text-info me-2"></i> Kelola Berita &amp; Warta
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-2" href="<?= BASE_URL ?>/manage-event">
                    <i class="bi bi-calendar-check-fill text-warning me-2"></i> Kelola Event &amp; Ibadah
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item py-2" href="<?= BASE_URL ?>/manage-pl-roles">
                    <i class="bi bi-joystick text-purple me-2" style="color:#8b5cf6"></i> Kelola Role Pilar Legends
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>
        </ul>

        <!-- Right Side Auth Menu -->
        <ul class="navbar-nav mb-2 mb-lg-0">
          <?php if ($isLoggedIn): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php if ($currentUserImage && file_exists(__DIR__ . '/../../../assets/uploads/' . $currentUserImage)): ?>
                  <img src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($currentUserImage) ?>" alt="Avatar" class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover;">
                <?php else: ?>
                  <i class="bi bi-person-circle fs-5 me-2"></i>
                <?php endif; ?>
                <span><?= htmlspecialchars($currentName) ?></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">
                <li>
                  <a class="dropdown-item py-2" href="<?= BASE_URL ?>/profile">
                    <i class="bi bi-person-bounding-box me-2 text-primary"></i> Edit Profil
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item py-2 text-danger" href="<?= BASE_URL ?>/logout">
                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                  </a>
                </li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="btn btn-primary btn-sm px-4 text-white" href="<?= BASE_URL ?>/login">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Flash Alerts Container -->
  <div class="container-lg mt-3">
    <?php if ($successMsg = Session::getFlash('success')): ?>
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= $successMsg ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    <?php if ($errorMsg = Session::getFlash('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errorMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
  </div>

  <main class="main-content container-lg py-4">
