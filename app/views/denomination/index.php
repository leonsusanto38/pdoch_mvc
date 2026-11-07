<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-bookmark-star-fill text-warning"></i> Kelola Denominasi</h2>
    <p class="text-muted">Kelola jenis aliran/denominasi gereja asal jemaat (Oikumene)</p>
  </div>
</div>

<div class="row g-4">
  <!-- List of denominations -->
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm bg-white">
      <div class="card-header bg-white py-3">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-columns-reverse"></i> Daftar Denominasi</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive border-0 shadow-none">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th width="80" class="text-center">ID</th>
                <th>Nama Denominasi</th>
                <th width="180" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($denominations)): ?>
                <tr>
                  <td colspan="3" class="text-center text-muted py-4">Belum ada denominasi terdaftar.</td>
                </tr>
                <?php else: foreach ($denominations as $denom): ?>
                  <tr class="<?= ($editDenom && $editDenom['id'] == $denom['id']) ? 'table-primary bg-opacity-10' : '' ?>">
                    <td class="text-center text-muted fw-bold">#<?= $denom['id'] ?></td>
                    <td>
                      <span class="fw-bold text-dark"><?= htmlspecialchars($denom['name']) ?></span>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="<?= BASE_URL ?>/manage-denomination?edit_id=<?= $denom['id'] ?>" class="btn btn-outline-secondary btn-sm">
                          <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form action="<?= BASE_URL ?>/manage-denomination?action=delete&id=<?= $denom['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus denominasi ini? Gereja yang tergabung di denominasi ini tidak boleh ada agar bisa dihapus.')">
                          <?= csrf_field() ?>
                          <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
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

  <!-- Denomination form (Create / Edit) -->
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm bg-white p-4">
      <div class="card-body">
        <?php if ($editDenom): ?>
          <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pencil-square"></i> Edit Denominasi</h5>
          <p class="text-muted small">Perbarui nama denominasi terpilih</p>

          <form action="<?= BASE_URL ?>/manage-denomination?action=update&id=<?= $editDenom['id'] ?>" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
              <label for="denom_name" class="form-label small fw-bold">Nama Denominasi</label>
              <input type="text" class="form-control" id="denom_name" name="name" value="<?= htmlspecialchars($editDenom['name']) ?>" required autofocus>
              <div class="form-text">Contoh: GKI, GMS, GBI, Katolik Roma, GKJW, Advent, dll.</div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary text-white flex-grow-1">
                <i class="bi bi-check-circle"></i> Perbarui
              </button>
              <a href="<?= BASE_URL ?>/manage-denomination" class="btn btn-secondary text-dark">
                Batal
              </a>
            </div>
          </form>
        <?php else: ?>
          <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle-fill"></i> Tambah Denominasi</h5>
          <p class="text-muted small">Buat denominasi baru untuk dikaitkan dengan gereja asal</p>

          <form action="<?= BASE_URL ?>/manage-denomination?action=save" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
              <label for="denom_name" class="form-label small fw-bold">Nama Denominasi</label>
              <input type="text" class="form-control" id="denom_name" name="name" placeholder="Masukkan nama denominasi baru" required>
              <div class="form-text">Contoh: Gereja Kristen Indonesia, Gereja Bethel Indonesia, dll.</div>
            </div>

            <button type="submit" class="btn btn-primary text-white w-100">
              <i class="bi bi-plus-circle"></i> Tambah Denominasi
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>