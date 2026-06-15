<div class="row align-items-center mb-4">
  <div class="col">
    <h2 class="fw-bold text-dark"><i class="bi bi-people-fill text-primary"></i> Kelola User</h2>
    <p class="text-muted">Kelola akun jemaat, pelayan, aktivis, dan admin</p>
  </div>
  <div class="col-auto">
    <a href="<?= BASE_URL ?>/manage-user?action=create" class="btn btn-primary text-white">
      <i class="bi bi-person-plus-fill"></i> Tambah User Baru
    </a>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th width="50" class="text-center">No</th>
        <th>Nama / Username</th>
        <th>Gereja Asal</th>
        <th>Role User</th>
        <th class="text-center">Status</th>
        <th width="260" class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($users)): ?>
        <tr>
          <td colspan="6" class="text-center text-muted py-4">Belum ada data user.</td>
        </tr>
      <?php else: $no = 1; foreach ($users as $user): ?>
        <tr>
          <td class="text-center text-muted fw-bold"><?= $no++ ?></td>
          <td>
            <div class="d-flex align-items-center">
              <div class="me-3">
                <?php if ($user['image'] && file_exists(__DIR__ . '/../../../assets/uploads/' . $user['image'])): ?>
                  <img src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($user['image']) ?>" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                <?php else: ?>
                  <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                  </div>
                <?php endif; ?>
              </div>
              <div>
                <span class="d-block fw-bold text-dark"><?= htmlspecialchars($user['name']) ?></span>
                <span class="text-muted small">@<?= htmlspecialchars($user['username']) ?></span>
              </div>
            </div>
          </td>
          <td>
            <span class="text-secondary"><?= htmlspecialchars($user['church_name'] ?? 'Tidak Terkait') ?></span>
          </td>
          <td>
            <?php if (empty($user['roles'])): ?>
              <span class="badge bg-secondary text-wrap">Tidak ada role</span>
            <?php else: ?>
              <?php foreach ($user['roles'] as $role): ?>
                <?php 
                  $badgeClass = 'bg-secondary';
                  if ($role['name'] === 'Admin') $badgeClass = 'bg-danger';
                  elseif ($role['name'] === 'Jemaat') $badgeClass = 'bg-primary';
                  elseif ($role['name'] === 'Pelayan') $badgeClass = 'bg-success';
                  elseif ($role['name'] === 'Aktivis') $badgeClass = 'bg-warning text-dark';
                ?>
                <span class="badge <?= $badgeClass ?> me-1"><?= htmlspecialchars($role['name']) ?></span>
              <?php endforeach; ?>
            <?php endif; ?>
          </td>
          <td class="text-center">
            <?php if ($user['status'] === 'active'): ?>
              <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle"></i> Aktif</span>
            <?php else: ?>
              <span class="badge bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle"></i> Nonaktif</span>
            <?php endif; ?>
          </td>
          <td class="text-center">
            <div class="btn-group">
              <a href="<?= BASE_URL ?>/manage-user?action=edit&id=<?= $user['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Edit Profil">
                <i class="bi bi-pencil-square"></i> Edit
              </a>
              <a href="<?= BASE_URL ?>/manage-user?action=roles&id=<?= $user['id'] ?>" class="btn btn-outline-success btn-sm" title="Kelola Role">
                <i class="bi bi-shield-check"></i> Role
              </a>
              <a href="<?= BASE_URL ?>/manage-user?action=reset-password&id=<?= $user['id'] ?>" class="btn btn-outline-warning btn-sm" title="Reset Password">
                <i class="bi bi-key"></i> Sandi
              </a>
              <a href="<?= BASE_URL ?>/manage-user?action=delete&id=<?= $user['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')" title="Hapus User">
                <i class="bi bi-trash"></i>
              </a>
            </div>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
