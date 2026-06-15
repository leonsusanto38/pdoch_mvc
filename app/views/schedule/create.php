<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-calendar-plus text-primary me-2"></i> Tambah Jadwal Baru</h2>
    <p class="text-muted">Buat tugas pelayanan atau sesi latihan baru untuk anggota tim</p>
  </div>
</div>

<div class="card border-0 shadow-sm bg-white p-4">
  <div class="card-body">
    <form action="<?= BASE_URL ?>/manage-schedule?action=save" method="POST">
      <div class="row g-3">
        <div class="col-md-6">
          <label for="user_id" class="form-label small fw-bold">Pelayan / Anggota Tim <span class="text-danger">*</span></label>
          <select class="form-select" id="user_id" name="user_id" required>
            <option value="">-- Pilih Anggota --</option>
            <?php foreach ($users as $u): ?>
              <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?> (@<?= htmlspecialchars($u['username']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label for="type" class="form-label small fw-bold">Jenis Agenda <span class="text-danger">*</span></label>
          <select class="form-select" id="type" name="type" required>
            <option value="pelayanan">Pelayanan</option>
            <option value="latihan">Latihan</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="role" class="form-label small fw-bold">Tugas / Peran Pelayanan <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="role" name="role" placeholder="Contoh: Pianist, Worship Leader, Soundman, dll." required>
        </div>

        <div class="col-md-6">
          <label for="date" class="form-label small fw-bold">Tanggal Agenda <span class="text-danger">*</span></label>
          <input type="date" class="form-control" id="date" name="date" required>
        </div>

        <div class="col-md-6">
          <label for="location" class="form-label small fw-bold">Lokasi / Ruangan</label>
          <input type="text" class="form-control" id="location" name="location" placeholder="Contoh: R. Utama Ibadah, R. Musik Lt. 2, dll.">
        </div>

        <div class="col-md-6">
          <label for="notes" class="form-label small fw-bold">Catatan Khusus</label>
          <input type="text" class="form-control" id="notes" name="notes" placeholder="Contoh: Datang 30 menit lebih awal, dsb.">
        </div>

        <div class="col-12">
          <label for="description" class="form-label small fw-bold">Deskripsi Tambahan</label>
          <textarea class="form-control" id="description" name="description" rows="3" placeholder="Masukkan keterangan tambahan jika ada..."></textarea>
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="<?= BASE_URL ?>/manage-schedule" class="btn btn-secondary text-dark">Batal</a>
        <button type="submit" class="btn btn-primary text-white px-4">
          <i class="bi bi-save"></i> Simpan Jadwal
        </button>
      </div>
    </form>
  </div>
</div>
