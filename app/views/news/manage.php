<div class="row align-items-center mb-4">
  <div class="col">
    <h2 class="fw-bold text-dark"><i class="bi bi-newspaper text-danger"></i> Kelola Berita / Warta</h2>
    <p class="text-muted">Kelola berita, pengumuman, dan informasi penting untuk ditampilkan di halaman depan</p>
  </div>
  <div class="col-auto">
    <a href="<?= BASE_URL ?>/manage-news?action=create" class="btn btn-primary text-white btn-sm">
      <i class="bi bi-plus-circle"></i> Tulis Berita Baru
    </a>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th width="50" class="text-center">No</th>
        <th width="100">Foto/Gambar</th>
        <th>Judul Berita</th>
        <th>Isi Konten</th>
        <th>Diterbitkan Oleh</th>
        <th>Tanggal Rilis</th>
        <th width="120" class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($newsList)): ?>
        <tr>
          <td colspan="7" class="text-center text-muted py-4">Belum ada berita. Tulis berita baru.</td>
        </tr>
      <?php else: $no = 1; foreach ($newsList as $n): ?>
        <tr>
          <td class="text-center fw-bold text-muted"><?= $no++ ?></td>
          <td>
            <?php if ($n['image'] && file_exists(__DIR__ . '/../../../assets/uploads/' . $n['image'])): ?>
              <img src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($n['image']) ?>" alt="News Image" class="rounded object-fit-cover" style="width: 80px; height: 50px;">
            <?php else: ?>
              <span class="badge bg-light text-secondary border">No Image</span>
            <?php endif; ?>
          </td>
          <td>
            <span class="fw-bold text-dark"><?= htmlspecialchars($n['title']) ?></span>
          </td>
          <td>
            <span class="text-muted small"><?= htmlspecialchars(mb_strimwidth($n['content'], 0, 100, '...')) ?></span>
          </td>
          <td>
            <span class="small"><?= htmlspecialchars($n['creator_name'] ?? 'PDOCH') ?></span>
          </td>
          <td>
            <span class="small text-muted"><?= date('d M Y H:i', strtotime($n['created_at'])) ?></span>
          </td>
          <td class="text-center">
            <div class="btn-group">
              <a href="<?= BASE_URL ?>/manage-news?action=edit&id=<?= $n['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Edit">
                <i class="bi bi-pencil-square"></i>
              </a>
              <a href="<?= BASE_URL ?>/manage-news?action=delete&id=<?= $n['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus berita ini?')" title="Hapus">
                <i class="bi bi-trash"></i>
              </a>
            </div>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
