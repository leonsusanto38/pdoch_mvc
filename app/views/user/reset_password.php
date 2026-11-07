<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-key-fill text-warning"></i> Reset Password User</h2>
    <p class="text-muted">Masukkan password baru untuk user: <strong><?= htmlspecialchars($user['name']) ?></strong> (Username: @<?= htmlspecialchars($user['username']) ?>)</p>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm bg-white p-4">
      <div class="card-body">
        <form action="<?= BASE_URL ?>/manage-user?action=reset-password&id=<?= $user['id'] ?>" method="POST">
          <?= csrf_field() ?>
          <div class="mb-4">
            <label for="password" class="form-label small fw-bold">Password Baru</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required autofocus>
            <div class="form-text text-muted">Pastikan Anda memberikan password baru ini ke user yang bersangkutan untuk login.</div>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <a href="<?= BASE_URL ?>/manage-user" class="btn btn-secondary text-dark">Batal</a>
            <button type="submit" class="btn btn-warning text-dark px-4 fw-bold">
              <i class="bi bi-key"></i> Reset Password
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>