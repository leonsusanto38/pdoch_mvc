<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-shield-check text-success"></i> Kelola Role User</h2>
    <p class="text-muted">Tentukan satu atau lebih role untuk user: <strong><?= htmlspecialchars($user['name']) ?></strong></p>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm bg-white p-4">
      <div class="card-body">
        <h5 class="fw-bold text-dark mb-4">Pilih Role User</h5>

        <form action="<?= BASE_URL ?>/manage-user?action=update-roles&id=<?= $user['id'] ?>" method="POST">
          <div class="mb-4">
            <?php if (empty($roles)): ?>
              <div class="alert alert-warning">Belum ada role terdaftar. Silakan buat role terlebih dahulu.</div>
            <?php else: ?>
              <?php foreach ($roles as $role): ?>
                <div class="form-check mb-3 py-2 px-3 border rounded-3 bg-light-subtle">
                  <input class="form-check-input ms-0 me-3" type="checkbox" name="roles[]" value="<?= $role['id'] ?>" id="role_<?= $role['id'] ?>" <?= in_array($role['id'], $userRoleIds) ? 'checked' : '' ?>>
                  <label class="form-check-label fw-semibold text-dark" for="role_<?= $role['id'] ?>">
                    <?= htmlspecialchars($role['name']) ?>
                  </label>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <a href="<?= BASE_URL ?>/manage-user" class="btn btn-secondary text-dark">Batal</a>
            <button type="submit" class="btn btn-success text-white px-4">
              <i class="bi bi-shield-check"></i> Simpan Role
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
