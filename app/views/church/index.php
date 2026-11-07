<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-building-fill text-info"></i> Kelola Gereja Lokal</h2>
    <p class="text-muted">Kelola daftar gereja asal jemaat PDOCH</p>
  </div>
</div>

<div class="row g-4">
  <!-- List of churches -->
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm bg-white">
      <div class="card-header bg-white py-3">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-houses"></i> Daftar Gereja</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive border-0 shadow-none">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th width="80" class="text-center">ID</th>
                <th>Nama Gereja</th>
                <th>Denominasi</th>
                <th>Alamat</th>
                <th width="150" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($churches)): ?>
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">Belum ada gereja terdaftar.</td>
                </tr>
                <?php else: foreach ($churches as $church): ?>
                  <tr class="<?= ($editChurch && $editChurch['id'] == $church['id']) ? 'table-primary bg-opacity-10' : '' ?>">
                    <td class="text-center text-muted fw-bold">#<?= $church['id'] ?></td>
                    <td>
                      <span class="fw-bold text-dark"><?= htmlspecialchars($church['name']) ?></span>
                    </td>
                    <td>
                      <span class="badge bg-secondary bg-opacity-10 text-secondary"><?= htmlspecialchars($church['denomination_name'] ?? 'Denominasi Lain') ?></span>
                    </td>
                    <td>
                      <span class="text-muted small"><?= htmlspecialchars($church['address'] ?? '-') ?></span>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="<?= BASE_URL ?>/manage-church?edit_id=<?= $church['id'] ?>" class="btn btn-outline-secondary btn-sm">
                          <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="<?= BASE_URL ?>/manage-church?action=delete&id=<?= $church['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus gereja ini?')">
                          <?= csrf_field() ?>
                          <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
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

  <!-- Church form (Create / Edit) -->
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm bg-white p-4">
      <div class="card-body">
        <?php if ($editChurch): ?>
          <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pencil-square"></i> Edit Gereja</h5>
          <p class="text-muted small">Perbarui detail gereja terpilih</p>

          <form action="<?= BASE_URL ?>/manage-church?action=update&id=<?= $editChurch['id'] ?>" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
              <label for="church_name" class="form-label small fw-bold">Nama Gereja</label>
              <input type="text" class="form-control" id="church_name" name="name" value="<?= htmlspecialchars($editChurch['name']) ?>" required autofocus>
            </div>

            <div class="mb-3">
              <label for="id_denomination" class="form-label small fw-bold">Denominasi</label>
              <select class="form-select" id="id_denomination" name="id_denomination">
                <option value="">-- Pilih Denominasi --</option>
                <?php foreach ($denominations as $denom): ?>
                  <option value="<?= $denom['id'] ?>" <?= $editChurch['id_denomination'] == $denom['id'] ? 'selected' : '' ?>><?= htmlspecialchars($denom['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-4">
              <label for="address" class="form-label small fw-bold">Alamat Gereja</label>
              <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($editChurch['address'] ?? '') ?></textarea>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary text-white flex-grow-1">
                <i class="bi bi-check-circle"></i> Perbarui
              </button>
              <a href="<?= BASE_URL ?>/manage-church" class="btn btn-secondary text-dark">
                Batal
              </a>
            </div>
          </form>
        <?php else: ?>
          <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle-fill"></i> Tambah Gereja</h5>
          <p class="text-muted small">Hubungkan gereja baru dengan denominasinya</p>

          <form action="<?= BASE_URL ?>/manage-church?action=save" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
              <label for="church_name" class="form-label small fw-bold">Nama Gereja</label>
              <input type="text" class="form-control" id="church_name" name="name" placeholder="Contoh: GKI Citra Harmoni" required>
            </div>

            <div class="mb-3">
              <label for="id_denomination" class="form-label small fw-bold">Denominasi</label>
              <select class="form-select" id="id_denomination" name="id_denomination">
                <option value="">-- Pilih Denominasi --</option>
                <?php foreach ($denominations as $denom): ?>
                  <option value="<?= $denom['id'] ?>"><?= htmlspecialchars($denom['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-4">
              <label for="address" class="form-label small fw-bold">Alamat Gereja</label>
              <textarea class="form-control" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap gereja jika ada"></textarea>
            </div>

            <button type="submit" class="btn btn-primary text-white w-100">
              <i class="bi bi-plus-circle"></i> Tambah Gereja
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>