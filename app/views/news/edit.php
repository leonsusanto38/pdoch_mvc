<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Berita / Warta</h2>
    <p class="text-muted">Perbarui berita atau pengumuman jemaat</p>
  </div>
</div>

<div class="card border-0 shadow-sm bg-white p-4">
  <div class="card-body">
    <form action="<?= BASE_URL ?>/manage-news?action=update&id=<?= $news['id'] ?>" method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-12">
          <label for="title" class="form-label small fw-bold">Judul Berita / Warta <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($news['title']) ?>" required>
        </div>

        <div class="col-12">
          <label for="image" class="form-label small fw-bold">Unggah Gambar / Cover Baru (Opsional)</label>
          <?php if ($news['image'] && file_exists(__DIR__ . '/../../../assets/uploads/' . $news['image'])): ?>
            <div class="mb-2">
              <img src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($news['image']) ?>" alt="Current Cover" class="rounded object-fit-cover border" style="width: 120px; height: 80px;">
              <small class="text-muted d-block">Cover saat ini</small>
            </div>
          <?php endif; ?>
          <input type="file" class="form-control" id="image" name="image" accept="image/*">
          <small class="text-muted">Format: JPG, PNG, GIF (Maks: 2MB) - Biarkan kosong jika tidak ingin mengganti cover</small>
        </div>

        <div class="col-12">
          <label for="content" class="form-label small fw-bold">Isi Konten Berita <span class="text-danger">*</span></label>
          <textarea class="form-control" id="content" name="content" rows="8" required><?= htmlspecialchars($news['content']) ?></textarea>
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="<?= BASE_URL ?>/manage-news" class="btn btn-secondary text-dark">Batal</a>
        <button type="submit" class="btn btn-primary text-white px-4">
          <i class="bi bi-save"></i> Perbarui Berita
        </button>
      </div>
    </form>
  </div>
</div>
