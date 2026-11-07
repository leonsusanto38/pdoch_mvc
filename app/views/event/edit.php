<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-calendar-event text-warning"></i> Edit Event / Ibadah</h2>
    <p class="text-muted">Perbarui detail jadwal ibadah atau kegiatan</p>
  </div>
</div>

<div class="card border-0 shadow-sm bg-white p-4">
  <div class="card-body">
    <form action="<?= BASE_URL ?>/manage-event?action=update&id=<?= $event['id'] ?>" method="POST">
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-12">
          <label for="name" class="form-label small fw-bold">Nama Event / Ibadah</label>
          <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($event['name']) ?>" required>
        </div>

        <div class="col-md-6">
          <label for="time_start" class="form-label small fw-bold">Waktu Mulai</label>
          <input type="datetime-local" class="form-control" id="time_start" name="time_start"
            value="<?= date('Y-m-d\TH:i', strtotime($event['time_start'])) ?>" required>
        </div>

        <div class="col-md-6">
          <label for="time_end" class="form-label small fw-bold">Waktu Selesai</label>
          <input type="datetime-local" class="form-control" id="time_end" name="time_end"
            value="<?= date('Y-m-d\TH:i', strtotime($event['time_end'])) ?>" required>
        </div>

        <div class="col-12">
          <label for="coordinator_user_id" class="form-label small fw-bold">Koordinator / Penanggung Jawab</label>
          <select class="form-select" id="coordinator_user_id" name="coordinator_user_id">
            <option value="">-- Pilih Koordinator (opsional) --</option>
            <?php foreach ($users as $user): ?>
              <option value="<?= $user['id'] ?>" <?= $event['coordinator_user_id'] == $user['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($user['name']) ?> (@<?= htmlspecialchars($user['username']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-12">
          <label for="description" class="form-label small fw-bold">Deskripsi</label>
          <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="<?= BASE_URL ?>/manage-event" class="btn btn-secondary text-dark">Batal</a>
        <button type="submit" class="btn btn-primary text-white px-4">
          <i class="bi bi-save"></i> Perbarui Event
        </button>
      </div>
    </form>
  </div>
</div>