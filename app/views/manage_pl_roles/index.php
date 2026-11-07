<?php
$roleIcons = ['Fighter' => '⚔️', 'Marksman' => '🏹', 'Assassin' => '🗡️', 'Wizard' => '🧙', 'Tank' => '🛡️', 'Cleric' => '✨'];
?>
<style>
  .pl-manage-header {
    background: linear-gradient(135deg, #1e1b4b, #312e81);
    border-radius: 16px;
    color: #fff;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
  }

  .pl-manage-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 80% 50%, rgba(139, 92, 246, 0.3) 0%, transparent 60%);
  }

  .role-stat-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #fff;
  }

  .stat-atk {
    background: #dc2626;
  }

  .stat-hp {
    background: #16a34a;
  }

  .stat-mov {
    background: #2563eb;
  }

  .stat-rng {
    background: #d97706;
  }
</style>

<div class="pl-manage-header">
  <div class="position-relative">
    <h2 class="fw-black mb-1" style="letter-spacing:2px;text-shadow:0 0 20px rgba(139,92,246,0.6);">
      🎮 Kelola Role Pilar Legends
    </h2>
    <p class="mb-0" style="opacity:0.7;">Atur statistik role yang tersedia dalam game Pilar Legends</p>
  </div>
</div>

<div class="row g-4">
  <!-- Role List -->
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white py-3 border-bottom">
        <h5 class="fw-bold mb-0"><i class="bi bi-list-stars me-2" style="color:#7c3aed;"></i> Daftar Role Game</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Role</th>
                <th class="text-center">ATK</th>
                <th class="text-center">HP</th>
                <th class="text-center">MOV</th>
                <th class="text-center">Range</th>
                <th class="text-center" width="140">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($roles)): ?>
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">Belum ada role.</td>
                </tr>
                <?php else: foreach ($roles as $role): ?>
                  <tr class="<?= ($editRole && $editRole['id'] == $role['id']) ? 'table-primary bg-opacity-10' : '' ?>">
                    <td>
                      <span class="fw-bold"><?= $roleIcons[$role['name']] ?? '⚡' ?> <?= htmlspecialchars($role['name']) ?></span>
                      <?php if ($role['id'] <= 6): ?>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1 small">Default</span>
                      <?php endif; ?>
                      <?php if ($role['description']): ?>
                        <div class="text-muted" style="font-size:0.72rem;"><?= htmlspecialchars(mb_strimwidth($role['description'], 0, 60, '…')) ?></div>
                      <?php endif; ?>
                    </td>
                    <td class="text-center"><span class="role-stat-badge stat-atk"><?= $role['base_attack'] ?></span></td>
                    <td class="text-center"><span class="role-stat-badge stat-hp"><?= $role['base_hp'] ?> HP</span></td>
                    <td class="text-center"><span class="role-stat-badge stat-mov"><?= $role['base_movement'] ?></span></td>
                    <td class="text-center"><span class="role-stat-badge stat-rng"><?= $role['range_cells'] ?></span></td>
                    <td class="text-center">
                      <div class="btn-group btn-group-sm">
                        <a href="<?= BASE_URL ?>/manage-pl-roles?edit_id=<?= $role['id'] ?>" class="btn btn-outline-secondary">
                          <i class="bi bi-pencil-square"></i>
                        </a>
                        <?php if ($role['id'] > 6): ?>
                          <form action="<?= BASE_URL ?>/manage-pl-roles?action=delete&id=<?= $role['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus role <?= htmlspecialchars($role['name']) ?>?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                          </form>
                        <?php else: ?>
                          <button class="btn btn-outline-secondary" disabled><i class="bi bi-lock-fill"></i></button>
                        <?php endif; ?>
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

  <!-- Form -->
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <?php if ($editRole): ?>
          <h5 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Role</h5>
          <p class="text-muted small mb-4">Perbarui statistik role: <strong><?= htmlspecialchars($editRole['name']) ?></strong></p>
          <form action="<?= BASE_URL ?>/manage-pl-roles?action=update&id=<?= $editRole['id'] ?>" method="POST">
            <?= csrf_field() ?>
          <?php else: ?>
            <h5 class="fw-bold mb-1"><i class="bi bi-plus-circle-fill me-2" style="color:#7c3aed;"></i> Tambah Role Baru</h5>
            <p class="text-muted small mb-4">Buat role baru dengan statistik yang ditentukan</p>
            <form action="<?= BASE_URL ?>/manage-pl-roles?action=save" method="POST">
              <?= csrf_field() ?>
            <?php endif; ?>

            <div class="mb-3">
              <label class="form-label fw-bold small">Nama Role</label>
              <input type="text" class="form-control" name="name"
                value="<?= htmlspecialchars($editRole['name'] ?? '') ?>" required placeholder="contoh: Ranger, Paladin, ...">
            </div>

            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label fw-bold small">Base Attack</label>
                <div class="input-group">
                  <span class="input-group-text bg-danger text-white border-0">⚔️</span>
                  <input type="number" class="form-control" name="base_attack" min="1" max="20"
                    value="<?= $editRole['base_attack'] ?? '' ?>" required placeholder="e.g. 3">
                </div>
              </div>
              <div class="col-6">
                <label class="form-label fw-bold small">Base HP</label>
                <div class="input-group">
                  <span class="input-group-text bg-success text-white border-0">❤️</span>
                  <input type="number" class="form-control" name="base_hp" min="1" max="100"
                    value="<?= $editRole['base_hp'] ?? '' ?>" required placeholder="e.g. 12">
                </div>
              </div>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label fw-bold small">Base Movement</label>
                <div class="input-group">
                  <span class="input-group-text bg-primary text-white border-0">🦶</span>
                  <input type="number" class="form-control" name="base_movement" min="1" max="10"
                    value="<?= $editRole['base_movement'] ?? '' ?>" required placeholder="e.g. 2">
                </div>
              </div>
              <div class="col-6">
                <label class="form-label fw-bold small">Range (tetap)</label>
                <div class="input-group">
                  <span class="input-group-text bg-warning text-white border-0">🎯</span>
                  <input type="number" class="form-control" name="range_cells" min="1" max="10"
                    value="<?= $editRole['range_cells'] ?? '' ?>" required placeholder="e.g. 1">
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold small">Deskripsi (opsional)</label>
              <textarea class="form-control" name="description" rows="3"
                placeholder="Deskripsi singkat tentang role ini..."><?= htmlspecialchars($editRole['description'] ?? '') ?></textarea>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary text-white flex-grow-1" style="background:linear-gradient(135deg,#7c3aed,#2563eb);border:none;">
                <i class="bi bi-check-circle-fill me-1"></i>
                <?= $editRole ? 'Perbarui Role' : 'Tambah Role' ?>
              </button>
              <?php if ($editRole): ?>
                <a href="<?= BASE_URL ?>/manage-pl-roles" class="btn btn-secondary">Batal</a>
              <?php endif; ?>
            </div>
            </form>

            <!-- Stats legend -->
            <div class="mt-4 pt-3 border-top">
              <small class="text-muted fw-bold d-block mb-2">KETERANGAN STATS</small>
              <div class="d-flex flex-wrap gap-2">
                <span class="role-stat-badge stat-atk px-3 py-1">ATK = Base Attack (+🎲 saat giliran)</span>
                <span class="role-stat-badge stat-hp px-3 py-1">HP = Base HP langsung</span>
                <span class="role-stat-badge stat-mov px-3 py-1">MOV = Movement (+🎲 saat giliran)</span>
                <span class="role-stat-badge stat-rng px-3 py-1">Range = Jangkauan (tetap)</span>
              </div>
            </div>
      </div>
    </div>
  </div>
</div>