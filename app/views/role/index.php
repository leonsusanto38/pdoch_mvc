<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-shield-lock-fill text-success"></i> Kelola Role User</h2>
    <p class="text-muted">Kelola jenis tingkat hak akses dan peran user dalam persekutuan</p>
  </div>
</div>

<div class="row g-4">
  <!-- List of roles -->
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm bg-white">
      <div class="card-header bg-white py-3">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-stars"></i> Daftar Role</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive border-0 shadow-none">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th width="80" class="text-center">ID</th>
                <th>Nama Role</th>
                <th width="180" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($roles)): ?>
                <tr>
                  <td colspan="3" class="text-center text-muted py-4">Belum ada role terdaftar.</td>
                </tr>
              <?php else: foreach ($roles as $role): ?>
                <tr class="<?= ($editRole && $editRole['id'] == $role['id']) ? 'table-primary bg-opacity-10' : '' ?>">
                  <td class="text-center text-muted fw-bold">#<?= $role['id'] ?></td>
                  <td>
                    <span class="fw-bold text-dark"><?= htmlspecialchars($role['name']) ?></span>
                    <?php if ($role['id'] == 1 || $role['id'] == 2): ?>
                      <span class="badge bg-primary bg-opacity-10 text-primary ms-2"><i class="bi bi-shield-fill-check"></i> System Default</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="<?= BASE_URL ?>/manage-role?edit_id=<?= $role['id'] ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil-square"></i> Edit
                      </a>
                      <?php if ($role['id'] != 1 && $role['id'] != 2): ?>
                        <a href="<?= BASE_URL ?>/manage-role?action=delete&id=<?= $role['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus role ini? User yang tergabung ke role ini tidak akan terhapus, namun relasi role akan hilang.')">
                          <i class="bi bi-trash"></i> Hapus
                        </a>
                      <?php else: ?>
                        <button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-lock-fill"></i> Terkunci</button>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Role form (Create / Edit) -->
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm bg-white p-4">
      <div class="card-body">
        <?php if ($editRole): ?>
          <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pencil-square"></i> Edit Role</h5>
          <p class="text-muted small">Perbarui nama role terpilih</p>

          <form action="<?= BASE_URL ?>/manage-role?action=update&id=<?= $editRole['id'] ?>" method="POST">
            <div class="mb-4">
              <label for="role_name" class="form-label small fw-bold">Nama Role</label>
              <input type="text" class="form-control" id="role_name" name="name" value="<?= htmlspecialchars($editRole['name']) ?>" required autofocus>
              <div class="form-text">Contoh: Pelayan, Aktivis, Pengurus Harian, Musik, dll.</div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary text-white flex-grow-1">
                <i class="bi bi-check-circle"></i> Perbarui Role
              </button>
              <a href="<?= BASE_URL ?>/manage-role" class="btn btn-secondary text-dark">
                Batal
              </a>
            </div>
          </form>
        <?php else: ?>
          <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle-fill"></i> Tambah Role Baru</h5>
          <p class="text-muted small">Buat level hak akses atau peran baru dalam database</p>

          <form action="<?= BASE_URL ?>/manage-role?action=save" method="POST">
            <div class="mb-4">
              <label for="role_name" class="form-label small fw-bold">Nama Role</label>
              <input type="text" class="form-control" id="role_name" name="name" placeholder="Masukkan nama role baru" required>
              <div class="form-text">Contoh: Pengurus Internal Lansia, Sound Engineer, Penerima Tamu, dll.</div>
            </div>

            <button type="submit" class="btn btn-primary text-white w-100">
              <i class="bi bi-plus-circle"></i> Tambah Role
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
