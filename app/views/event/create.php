<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-calendar-plus text-primary"></i> Tambah Event / Ibadah</h2>
    <p class="text-muted">Buat jadwal ibadah atau kegiatan baru secara manual</p>
  </div>
</div>

<div class="card border-0 shadow-sm bg-white p-4">
  <div class="card-body">
    <form action="<?= BASE_URL ?>/manage-event?action=save" method="POST">
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-12">
          <label for="name" class="form-label small fw-bold">Nama Event / Ibadah</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Contoh: Ibadah Bulanan PDOCH, Ibadah Dewasa Muda, dll." required>
        </div>

        <div class="col-md-6">
          <label for="time_start" class="form-label small fw-bold">Waktu Mulai</label>
          <input type="datetime-local" class="form-control" id="time_start" name="time_start" required>
        </div>

        <div class="col-md-6">
          <label for="time_end" class="form-label small fw-bold">Waktu Selesai</label>
          <input type="datetime-local" class="form-control" id="time_end" name="time_end" required>
        </div>

        <div class="col-12">
          <label for="coordinator_user_id" class="form-label small fw-bold">Koordinator / Penanggung Jawab</label>
          <select class="form-select" id="coordinator_user_id" name="coordinator_user_id">
            <option value="">-- Pilih Koordinator (opsional) --</option>
            <?php foreach ($users as $user): ?>
              <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (@<?= htmlspecialchars($user['username']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-12">
          <label for="description" class="form-label small fw-bold">Deskripsi</label>
          <textarea class="form-control" id="description" name="description" rows="4" placeholder="Informasi tambahan tentang event ini..."></textarea>
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="<?= BASE_URL ?>/manage-event" class="btn btn-secondary text-dark">Batal</a>
        <button type="submit" class="btn btn-primary text-white px-4">
          <i class="bi bi-calendar-plus"></i> Simpan Event
        </button>
      </div>
    </form>
  </div>
</div>