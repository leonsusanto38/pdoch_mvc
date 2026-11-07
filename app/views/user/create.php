<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-person-plus-fill text-primary"></i> Tambah User Baru</h2>
    <p class="text-muted">Buat akun baru untuk jemaat, pelayan, atau aktivis PDOCH</p>
  </div>
</div>

<div class="card border-0 shadow-sm bg-white p-4">
  <div class="card-body">
    <form action="<?= BASE_URL ?>/manage-user?action=save" method="POST">
      <?= csrf_field() ?>
      <div class="row g-3">
        <!-- Akun Credentials -->
        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-shield-lock"></i> Kredensial Akun</h5>

        <div class="col-md-4">
          <label for="name" class="form-label small fw-bold">Nama Lengkap</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Contoh: Budi Santoso" required>
        </div>

        <div class="col-md-4">
          <label for="username" class="form-label small fw-bold">Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Contoh: budis123" required>
        </div>

        <div class="col-md-4">
          <label for="password" class="form-label small fw-bold">Password Awal</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required>
        </div>

        <!-- Profil Biografi -->
        <h5 class="fw-bold text-dark border-bottom pb-2 mt-4 mb-3"><i class="bi bi-person-lines-fill"></i> Data Profil</h5>

        <div class="col-md-4">
          <label class="form-label small fw-bold">Jenis Kelamin</label>
          <div class="d-flex gap-3 mt-2">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gender" id="gender_m" value="M" checked>
              <label class="form-check-label" for="gender_m">Laki-laki</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gender" id="gender_f" value="F">
              <label class="form-check-label" for="gender_f">Perempuan</label>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <label for="birth_date" class="form-label small fw-bold">Tanggal Lahir</label>
          <input type="date" class="form-control" id="birth_date" name="birth_date">
        </div>

        <div class="col-md-4">
          <label for="phone_number" class="form-label small fw-bold">No. Telepon / WA</label>
          <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Contoh: 08123456789">
        </div>

        <div class="col-md-6">
          <label for="id_church" class="form-label small fw-bold">Gereja Lokal Asal</label>
          <select class="form-select" id="id_church" name="id_church">
            <option value="">-- Pilih Gereja --</option>
            <?php foreach ($churches as $church): ?>
              <option value="<?= $church['id'] ?>"><?= htmlspecialchars($church['name']) ?> (<?= htmlspecialchars($church['denomination_name'] ?? 'Denominasi Lain') ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label for="status" class="form-label small fw-bold">Status Akun</label>
          <select class="form-select" id="status" name="status">
            <option value="active">Aktif</option>
            <option value="inactive">Nonaktif</option>
          </select>
        </div>

        <div class="col-12">
          <label for="address" class="form-label small fw-bold">Alamat Rumah</label>
          <textarea class="form-control" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="<?= BASE_URL ?>/manage-user" class="btn btn-secondary text-dark">
          Batal
        </a>
        <button type="submit" class="btn btn-primary text-white px-4">
          <i class="bi bi-person-check-fill"></i> Simpan User
        </button>
      </div>
    </form>
  </div>
</div>