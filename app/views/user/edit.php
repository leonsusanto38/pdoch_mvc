<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-pencil-square text-primary"></i> Edit Data User</h2>
    <p class="text-muted">Perbarui data profil user: <?= htmlspecialchars($user['name']) ?></p>
  </div>
</div>

<div class="card border-0 shadow-sm bg-white p-4">
  <div class="card-body">
    <form action="<?= BASE_URL ?>/manage-user?action=update&id=<?= $user['id'] ?>" method="POST">
      <div class="row g-3">
        <!-- Akun Credentials -->
        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-shield-lock"></i> Kredensial Akun</h5>
        
        <div class="col-md-6">
          <label for="name" class="form-label small fw-bold">Nama Lengkap</label>
          <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div class="col-md-6">
          <label for="username" class="form-label small fw-bold">Username</label>
          <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <!-- Profil Biografi -->
        <h5 class="fw-bold text-dark border-bottom pb-2 mt-4 mb-3"><i class="bi bi-person-lines-fill"></i> Data Profil</h5>

        <div class="col-md-4">
          <label class="form-label small fw-bold">Jenis Kelamin</label>
          <div class="d-flex gap-3 mt-2">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gender" id="gender_m" value="M" <?= $user['gender'] === 'M' ? 'checked' : '' ?>>
              <label class="form-check-label" for="gender_m">Laki-laki</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gender" id="gender_f" value="F" <?= $user['gender'] === 'F' ? 'checked' : '' ?>>
              <label class="form-check-label" for="gender_f">Perempuan</label>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <label for="birth_date" class="form-label small fw-bold">Tanggal Lahir</label>
          <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?= $user['birth_date'] ?>">
        </div>

        <div class="col-md-4">
          <label for="phone_number" class="form-label small fw-bold">No. Telepon / WA</label>
          <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>" placeholder="Contoh: 08123456789">
        </div>

        <div class="col-md-6">
          <label for="id_church" class="form-label small fw-bold">Gereja Lokal Asal</label>
          <select class="form-select" id="id_church" name="id_church">
            <option value="">-- Pilih Gereja --</option>
            <?php foreach ($churches as $church): ?>
              <option value="<?= $church['id'] ?>" <?= $user['id_church'] == $church['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($church['name']) ?> (<?= htmlspecialchars($church['denomination_name'] ?? 'Denominasi Lain') ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label for="status" class="form-label small fw-bold">Status Akun</label>
          <select class="form-select" id="status" name="status">
            <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
            <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
          </select>
        </div>

        <div class="col-12">
          <label for="address" class="form-label small fw-bold">Alamat Rumah</label>
          <textarea class="form-control" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="<?= BASE_URL ?>/manage-user" class="btn btn-secondary text-dark">
          Batal
        </a>
        <button type="submit" class="btn btn-primary text-white px-4">
          <i class="bi bi-save"></i> Perbarui User
        </button>
      </div>
    </form>
  </div>
</div>
