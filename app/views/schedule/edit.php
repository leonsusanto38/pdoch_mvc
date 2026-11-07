<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-calendar-event text-warning me-2"></i> Edit Jadwal Pelayanan</h2>
    <p class="text-muted">Perbarui penugasan pelayanan atau sesi latihan</p>
  </div>
</div>

<div class="card border-0 shadow-sm bg-white p-4">
  <div class="card-body">
    <form action="<?= BASE_URL ?>/manage-schedule?action=update&id=<?= $schedule['id'] ?>" method="POST">
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-md-6">
          <label for="user_id" class="form-label small fw-bold">Pelayan / Anggota Tim <span class="text-danger">*</span></label>
          <select class="form-select" id="user_id" name="user_id" required>
            <?php foreach ($users as $u): ?>
              <option value="<?= $u['id'] ?>" <?= $schedule['user_id'] == $u['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['name']) ?> (@<?= htmlspecialchars($u['username']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label for="type" class="form-label small fw-bold">Jenis Agenda <span class="text-danger">*</span></label>
          <select class="form-select" id="type" name="type" required>
            <option value="pelayanan" <?= $schedule['type'] === 'pelayanan' ? 'selected' : '' ?>>Pelayanan</option>
            <option value="latihan" <?= $schedule['type'] === 'latihan' ? 'selected' : '' ?>>Latihan</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="role" class="form-label small fw-bold">Tugas / Peran Pelayanan <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="role" name="role" value="<?= htmlspecialchars($schedule['role']) ?>" required>
        </div>

        <div class="col-md-6">
          <label for="date" class="form-label small fw-bold">Tanggal Agenda <span class="text-danger">*</span></label>
          <input type="date" class="form-control" id="date" name="date" value="<?= $schedule['date'] ?>" required>
        </div>

        <div class="col-md-6">
          <label for="location" class="form-label small fw-bold">Lokasi / Ruangan</label>
          <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($schedule['location'] ?? '') ?>" placeholder="Contoh: R. Utama Ibadah, R. Musik Lt. 2, dll.">
        </div>

        <div class="col-md-6">
          <label for="notes" class="form-label small fw-bold">Catatan Khusus</label>
          <input type="text" class="form-control" id="notes" name="notes" value="<?= htmlspecialchars($schedule['notes'] ?? '') ?>" placeholder="Contoh: Datang 30 menit lebih awal, dsb.">
        </div>

        <div class="col-12">
          <label for="description" class="form-label small fw-bold">Deskripsi Tambahan</label>
          <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($schedule['description'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="<?= BASE_URL ?>/manage-schedule" class="btn btn-secondary text-dark">Batal</a>
        <button type="submit" class="btn btn-primary text-white px-4">
          <i class="bi bi-save"></i> Perbarui Jadwal
        </button>
      </div>
    </form>
  </div>
</div>