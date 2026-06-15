<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-file-earmark-medical text-primary me-2"></i> Tulis Berita Baru</h2>
    <p class="text-muted">Buat warta atau pengumuman baru untuk jemaat</p>
  </div>
</div>

<div class="card border-0 shadow-sm bg-white p-4">
  <div class="card-body">
    <form action="<?= BASE_URL ?>/manage-news?action=save" method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-12">
          <label for="title" class="form-label small fw-bold">Judul Berita / Warta <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan judul berita yang menarik" required>
        </div>

        <div class="col-12">
          <label for="image" class="form-label small fw-bold">Unggah Gambar / Cover (Opsional)</label>
          <input type="file" class="form-control" id="image" name="image" accept="image/*">
          <small class="text-muted">Format: JPG, PNG, GIF (Maks: 2MB)</small>
        </div>

        <div class="col-12">
          <label for="content" class="form-label small fw-bold">Isi Konten Berita <span class="text-danger">*</span></label>
          <textarea class="form-control" id="content" name="content" rows="8" placeholder="Tulis isi pengumuman atau berita secara lengkap di sini..." required></textarea>
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="<?= BASE_URL ?>/manage-news" class="btn btn-secondary text-dark">Batal</a>
        <button type="submit" class="btn btn-primary text-white px-4">
          <i class="bi bi-send-check"></i> Terbitkan Berita
        </button>
      </div>
    </form>
  </div>
</div>
