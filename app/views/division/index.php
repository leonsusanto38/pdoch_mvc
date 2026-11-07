<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-grid-fill text-secondary"></i> Kelola Divisi Pelayanan</h2>
    <p class="text-muted">Kelola divisi internal, kepengurusan, dan tim pelayanan PDOCH</p>
  </div>
</div>

<div class="row g-4 mb-5">
  <!-- List of divisions -->
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm bg-white">
      <div class="card-header bg-white py-3">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-diagram-3"></i> Struktur Divisi</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive border-0 shadow-none">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th width="80" class="text-center">ID</th>
                <th>Nama Divisi</th>
                <th>Koordinator</th>
                <th width="200" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($divisions)): ?>
                <tr>
                  <td colspan="4" class="text-center text-muted py-4">Belum ada divisi pelayanan.</td>
                </tr>
                <?php else: foreach ($divisions as $div): ?>
                  <tr class="<?= ($editDiv && $editDiv['id'] == $div['id']) ? 'table-primary bg-opacity-10' : '' ?>">
                    <td class="text-center text-muted fw-bold">#<?= $div['id'] ?></td>
                    <td>
                      <span class="fw-bold text-dark d-block"><?= htmlspecialchars($div['name']) ?></span>
                      <span class="text-muted small"><?= htmlspecialchars(mb_strimwidth($div['description'] ?? '', 0, 60, '...')) ?></span>
                    </td>
                    <td>
                      <span class="text-secondary small"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($div['coordinator_name'] ?? 'Belum Ditentukan') ?></span>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="<?= BASE_URL ?>/manage-division?action=members&id=<?= $div['id'] ?>" class="btn btn-outline-success btn-sm" title="Kelola Anggota">
                          <i class="bi bi-people"></i> Anggota
                        </a>
                        <a href="<?= BASE_URL ?>/manage-division?edit_id=<?= $div['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Edit Divisi">
                          <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="<?= BASE_URL ?>/manage-division?action=delete&id=<?= $div['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi ini? Seluruh riwayat keanggotaan divisi ini juga akan dihapus.')">
                          <?= csrf_field() ?>
                          <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Divisi"><i class="bi bi-trash"></i></button>
                        </form>
                      </div>
                    </td>
                  </tr>
              <?php endforeach;
              endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Division form (Create / Edit) -->
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm bg-white p-4">
      <div class="card-body">
        <?php if ($editDiv): ?>
          <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pencil-square"></i> Edit Divisi</h5>
          <p class="text-muted small">Perbarui data divisi pelayanan</p>

          <form action="<?= BASE_URL ?>/manage-division?action=update&id=<?= $editDiv['id'] ?>" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
              <label for="div_name" class="form-label small fw-bold">Nama Divisi</label>
              <input type="text" class="form-control" id="div_name" name="name" value="<?= htmlspecialchars($editDiv['name']) ?>" required autofocus>
            </div>

            <div class="mb-3">
              <label for="coordinator_user_id" class="form-label small fw-bold">Koordinator Divisi</label>
              <select class="form-select" id="coordinator_user_id" name="coordinator_user_id">
                <option value="">-- Pilih Koordinator --</option>
                <?php foreach ($users as $user): ?>
                  <option value="<?= $user['id'] ?>" <?= $editDiv['coordinator_user_id'] == $user['id'] ? 'selected' : '' ?>><?= htmlspecialchars($user['name']) ?> (@<?= htmlspecialchars($user['username']) ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-4">
              <label for="description" class="form-label small fw-bold">Deskripsi Tugas / Divisi</label>
              <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($editDiv['description'] ?? '') ?></textarea>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary text-white flex-grow-1">
                <i class="bi bi-check-circle"></i> Perbarui Divisi
              </button>
              <a href="<?= BASE_URL ?>/manage-division" class="btn btn-secondary text-dark">
                Batal
              </a>
            </div>
          </form>
        <?php else: ?>
          <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle-fill"></i> Tambah Divisi Baru</h5>
          <p class="text-muted small">Buat kelompok pelayanan baru</p>

          <form action="<?= BASE_URL ?>/manage-division?action=save" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
              <label for="div_name" class="form-label small fw-bold">Nama Divisi</label>
              <input type="text" class="form-control" id="div_name" name="name" placeholder="Contoh: Musik & Pujian, Multimedia, dll." required>
            </div>

            <div class="mb-3">
              <label for="coordinator_user_id" class="form-label small fw-bold">Koordinator Divisi</label>
              <select class="form-select" id="coordinator_user_id" name="coordinator_user_id">
                <option value="">-- Pilih Koordinator --</option>
                <?php foreach ($users as $user): ?>
                  <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (@<?= htmlspecialchars($user['username']) ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-4">
              <label for="description" class="form-label small fw-bold">Deskripsi Tugas / Divisi</label>
              <textarea class="form-control" id="description" name="description" rows="4" placeholder="Jelaskan peran dan lingkup pelayanan divisi ini..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary text-white w-100">
              <i class="bi bi-plus-circle"></i> Tambah Divisi
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>