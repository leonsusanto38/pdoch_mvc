<!-- Admin: Manage Events -->
<div class="row align-items-center mb-4">
  <div class="col">
    <h2 class="fw-bold text-dark"><i class="bi bi-calendar-check-fill text-danger"></i> Kelola Event & Ibadah</h2>
    <p class="text-muted">CRUD jadwal ibadah & kegiatan, dan generator jadwal otomatis</p>
  </div>
  <div class="col-auto d-flex gap-2">
    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#generateModal">
      <i class="bi bi-magic"></i> Generate Otomatis
    </button>
    <a href="<?= BASE_URL ?>/manage-event?action=create" class="btn btn-primary text-white btn-sm">
      <i class="bi bi-plus-circle"></i> Tambah Event
    </a>
  </div>
</div>

<!-- Events Table -->
<div class="table-responsive">
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th width="60" class="text-center">No</th>
        <th>Nama Event / Ibadah</th>
        <th>Mulai</th>
        <th>Selesai</th>
        <th>Koordinator</th>
        <th class="text-center" width="150">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($events)): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada event. Tambahkan atau gunakan fitur Generate Otomatis.</td></tr>
      <?php else: $no = 1; foreach ($events as $event):
        $ts = strtotime($event['time_start']);
        $dayNames = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $dayIndo = $dayNames[date('l', $ts)] ?? date('l',$ts);
        $isPast = $ts < time();
      ?>
        <tr class="<?= $isPast ? 'text-muted' : '' ?>">
          <td class="text-center fw-bold text-muted"><?= $no++ ?></td>
          <td>
            <span class="fw-bold <?= $isPast ? 'text-secondary' : 'text-dark' ?>"><?= htmlspecialchars($event['name']) ?></span>
            <?php if (!$isPast): ?>
              <span class="badge bg-success bg-opacity-10 text-success ms-2 small">Mendatang</span>
            <?php else: ?>
              <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2 small">Selesai</span>
            <?php endif; ?>
          </td>
          <td>
            <span class="small"><?= $dayIndo ?>, <?= date('d M Y', $ts) ?></span><br>
            <span class="text-muted small font-monospace"><?= date('H:i', $ts) ?> WIB</span>
          </td>
          <td>
            <span class="text-muted small font-monospace"><?= date('H:i', strtotime($event['time_end'])) ?> WIB</span>
          </td>
          <td>
            <span class="small text-secondary"><?= htmlspecialchars($event['coordinator_name'] ?? 'PDOCH') ?></span>
          </td>
          <td class="text-center">
            <div class="btn-group">
              <a href="<?= BASE_URL ?>/manage-event?action=edit&id=<?= $event['id'] ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil-square"></i></a>
              <a href="<?= BASE_URL ?>/manage-event?action=delete&id=<?= $event['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus event ini?')"><i class="bi bi-trash"></i></a>
            </div>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<!-- Generate Recurring Events Modal -->
<div class="modal fade" id="generateModal" tabindex="-1" aria-labelledby="generateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="generateModalLabel">
          <i class="bi bi-magic text-success"></i> Generate Jadwal Ibadah Otomatis
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted small mb-4">
          Fitur ini akan otomatis membuat jadwal untuk:
          <ul class="mt-2">
            <li><strong>Ibadah Bulanan PDOCH</strong> – Kamis ke-2 setiap bulan</li>
            <li><strong>Ibadah Dewasa Muda</strong> – Setiap Rabu, kecuali Rabu yang seminggu dengan Kamis ke-2</li>
          </ul>
        </p>
        <form action="<?= BASE_URL ?>/manage-event?action=generate" method="POST">
          <div class="row g-3">
            <div class="col-6">
              <label for="gen_year" class="form-label small fw-bold">Tahun</label>
              <input type="number" class="form-control" id="gen_year" name="year" value="<?= date('Y') ?>" min="2020" max="2100" required>
            </div>
            <div class="col-6">
              <label for="gen_month" class="form-label small fw-bold">Bulan</label>
              <select class="form-select" id="gen_month" name="month" required>
                <?php
                  $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                  foreach ($months as $i => $m):
                ?>
                  <option value="<?= $i+1 ?>" <?= ($i+1) == date('m') ? 'selected' : '' ?>><?= $m ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success text-white flex-grow-1">
              <i class="bi bi-lightning-charge"></i> Generate Sekarang
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
