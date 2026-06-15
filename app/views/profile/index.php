<div class="row">
  <div class="col-12 mb-4">
    <h2 class="fw-bold text-dark"><i class="bi bi-person-bounding-box text-primary me-2"></i> Pengaturan Profil</h2>
    <p class="text-muted">Kelola data profil pribadi Anda di bawah ini</p>
  </div>
</div>

<div class="row g-4">
  <!-- Profile details card -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm bg-white p-4">
      <div class="card-body">
        <h5 class="fw-bold text-dark mb-4">Informasi Pribadi</h5>

        <form action="<?= BASE_URL ?>/profile?action=update" method="POST" enctype="multipart/form-data">
          <!-- Profile Avatar Section -->
          <div class="text-center mb-4">
            <label for="image-upload" class="profile-avatar-container d-block" style="cursor: pointer;">
              <?php if ($user['image'] && file_exists(__DIR__ . '/../../../assets/uploads/' . $user['image'])): ?>
                <img id="avatar-preview" src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($user['image']) ?>" alt="Avatar">
              <?php else: ?>
                <img id="avatar-preview" src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=3b82f6&color=fff&size=120" alt="Avatar">
              <?php endif; ?>
              <span class="profile-avatar-upload-btn">
                <i class="bi bi-camera"></i> Ubah Foto
              </span>
            </label>
            <input type="file" id="image-upload" name="image" class="d-none" accept="image/*" onchange="previewImage(event)">
            <p class="text-muted small mt-2 mb-0">Klik foto untuk mengunggah gambar baru (Max: 2MB, formats: JPG, PNG)</p>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="name" class="form-label small fw-bold">Nama Lengkap</label>
              <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            
            <div class="col-md-6">
              <label for="username" class="form-label small fw-bold">Username</label>
              <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>

            <div class="col-md-6">
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

            <div class="col-md-6">
              <label for="birth_date" class="form-label small fw-bold">Tanggal Lahir</label>
              <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?= $user['birth_date'] ?>">
            </div>

            <div class="col-md-6">
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

            <div class="col-12">
              <label for="address" class="form-label small fw-bold">Alamat Rumah</label>
              <textarea class="form-control" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>
          </div>

          <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary text-white px-4">
              <i class="bi bi-save"></i> Simpan Profil
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Change password card -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm bg-white p-4">
      <div class="card-body">
        <h5 class="fw-bold text-dark mb-4">Ganti Password</h5>

        <form action="<?= BASE_URL ?>/profile?action=change-password" method="POST">
          <div class="mb-3">
            <label for="old_password" class="form-label small fw-bold">Password Lama</label>
            <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Masukkan password lama" required>
          </div>

          <div class="mb-3">
            <label for="new_password" class="form-label small fw-bold">Password Baru</label>
            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Minimal 6 karakter" required>
          </div>

          <div class="mb-4">
            <label for="confirm_password" class="form-label small fw-bold">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Ketik ulang password baru" required>
          </div>

          <button type="submit" class="btn btn-outline-danger w-100">
            <i class="bi bi-key"></i> Perbarui Password
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function previewImage(event) {
  const reader = new FileReader();
  reader.onload = function(){
    const output = document.getElementById('avatar-preview');
    output.src = reader.result;
  };
  reader.readAsDataURL(event.target.files[0]);
}
</script>
