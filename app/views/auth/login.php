<?php
use App\Core\Session;
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masuk - PDOCH</title>
  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <style>
    body {
      background: var(--dark-gradient);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .login-container {
      max-width: 420px;
      width: 100%;
    }
    .login-card {
      backdrop-filter: blur(16px) saturate(180%);
      background-color: rgba(255, 255, 255, 0.9);
      border: 1px solid rgba(255, 255, 255, 0.25);
      border-radius: 24px;
      padding: 40px 30px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }
  </style>
</head>
<body>

<div class="login-container">
  <!-- Brand Logo & Header -->
  <div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-gradient text-white rounded-circle mb-3" style="width: 60px; height: 60px; box-shadow: var(--btn-shadow);">
      <i class="bi bi-shield-heart-fill fs-3"></i>
    </div>
    <h3 class="fw-bold text-white mb-1">PDOCH Web App</h3>
    <p class="text-white-50">Persekutuan Doa Oikumene Citra Harmoni</p>
  </div>

  <div class="login-card">
    <h4 class="fw-bold text-dark mb-4 text-center">Silakan Masuk</h4>

    <!-- Flash Alert inside Card -->
    <?php if ($errorMsg = Session::getFlash('error')): ?>
      <div class="alert alert-danger border-0 py-2 small" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= htmlspecialchars($errorMsg) ?>
      </div>
    <?php endif; ?>
    <?php if ($successMsg = Session::getFlash('success')): ?>
      <div class="alert alert-success border-0 py-2 small" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i> <?= htmlspecialchars($successMsg) ?>
      </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/login?action=login" method="POST">
      <div class="mb-3">
        <label for="username" class="form-label small fw-semibold text-muted">Username</label>
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
          <input type="text" class="form-control bg-light border-start-0" id="username" name="username" placeholder="Masukkan username" required autofocus>
        </div>
      </div>

      <div class="mb-4">
        <label for="password" class="form-label small fw-semibold text-muted">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
          <input type="password" class="form-control bg-light border-start-0" id="password" name="password" placeholder="Masukkan password" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100 py-2.5 mb-3 text-white">
        Masuk <i class="bi bi-box-arrow-in-right ms-1"></i>
      </button>

      <a href="<?= BASE_URL ?>/home" class="btn btn-outline-secondary w-100 btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Home
      </a>
    </form>
  </div>

  <div class="text-center mt-4 text-white-50 small">
    Copyright &copy; PDOCH Citra Harmoni <?= date('Y') ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
