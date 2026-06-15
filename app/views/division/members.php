<div class="row mb-4">
  <div class="col">
    <h2 class="fw-bold text-dark">
      <i class="bi bi-people-fill text-success"></i> Anggota Divisi: <?= htmlspecialchars($division['name']) ?>
    </h2>
    <p class="text-muted">Kelola keanggotaan pelayan dalam divisi ini</p>
  </div>
  <div class="col-auto">
    <a href="<?= BASE_URL ?>/manage-division" class="btn btn-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Kembali ke Divisi
    </a>
  </div>
</div>

<div class="row g-4 mb-5">
  <!-- Member List -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm bg-white">
      <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-person-check"></i> Daftar Anggota</h5>
        <span class="badge bg-primary"><?= count($members) ?> Anggota</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive border-0 shadow-none">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Nama Anggota</th>
                <th>Peran dalam Divisi</th>
                <th width="150" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($members)): ?>
                <tr>
                  <td colspan="3" class="text-center text-muted py-4">
                    <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                    Belum ada anggota di divisi ini.
                  </td>
                </tr>
                <?php else: foreach ($members as $member): ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold me-3" style="width:38px;height:38px;flex-shrink:0;">
                          <?= strtoupper(substr($member['user_name'], 0, 1)) ?>
                        </div>
                        <div>
                          <span class="fw-bold text-dark d-block"><?= htmlspecialchars($member['user_name']) ?></span>
                          <span class="text-muted small">@<?= htmlspecialchars($member['user_username']) ?></span>
                        </div>
                      </div>
                    </td>
                    <td>
                      <form action="<?= BASE_URL ?>/manage-division?action=update-member&id=<?= $division['id'] ?>&user_id=<?= $member['user_id'] ?>" method="POST" class="d-flex gap-2 align-items-center">
                        <input type="text" class="form-control form-control-sm" name="role" value="<?= htmlspecialchars($member['role']) ?>" style="max-width:200px;">
                        <button type="submit" class="btn btn-outline-primary btn-sm" title="Simpan Peran">
                          <i class="bi bi-check"></i>
                        </button>
                      </form>
                    </td>
                    <td class="text-center">
                      <a href="<?= BASE_URL ?>/manage-division?action=remove-member&id=<?= $division['id'] ?>&user_id=<?= $member['user_id'] ?>"
                        class="btn btn-outline-danger btn-sm"
                        onclick="return confirm('Hapus anggota ini dari divisi?')">
                        <i class="bi bi-person-dash"></i> Hapus
                      </a>
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

  <!-- Add Member Form -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm bg-white p-4">
      <div class="card-body">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-plus-fill"></i> Tambah Anggota</h5>

        <form action="<?= BASE_URL ?>/manage-division?action=add-member&id=<?= $division['id'] ?>" method="POST">
          <div class="mb-3">
            <label for="user_id" class="form-label small fw-bold">Pilih User</label>
            <select class="form-select" id="user_id" name="user_id" required>
              <option value="">-- Pilih User --</option>
              <?php
              $memberIds = array_column($members, 'user_id');
              foreach ($users as $user):
                if (!in_array($user['id'], $memberIds)):
              ?>
                  <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (@<?= htmlspecialchars($user['username']) ?>)</option>
              <?php endif;
              endforeach; ?>
            </select>
          </div>

          <div class="mb-4">
            <label for="member_role" class="form-label small fw-bold">Peran dalam Divisi</label>
            <input type="text" class="form-control" id="member_role" name="role" placeholder="Contoh: WL, Gitaris, Sound Engineer, dll." value="Anggota">
          </div>

          <button type="submit" class="btn btn-success text-white w-100">
            <i class="bi bi-person-plus"></i> Tambahkan Anggota
          </button>
        </form>
      </div>
    </div>
  </div>
</div>