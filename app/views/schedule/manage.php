<div class="row align-items-center mb-4">
  <div class="col">
    <h2 class="fw-bold text-dark"><i class="bi bi-calendar-event-fill text-danger"></i> Kelola Jadwal Pelayanan & Latihan</h2>
    <p class="text-muted">Kelola penugasan pelayanan dan sesi latihan anggota tim</p>
  </div>
  <div class="col-auto">
    <a href="<?= BASE_URL ?>/manage-schedule?action=create" class="btn btn-primary text-white btn-sm">
      <i class="bi bi-plus-circle"></i> Tambah Jadwal Baru
    </a>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th width="50" class="text-center">No</th>
        <th>Jenis</th>
        <th>Tanggal</th>
        <th>Petugas / Pelayan</th>
        <th>Tugas / Peran</th>
        <th>Lokasi</th>
        <th>Catatan</th>
        <th width="120" class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($schedules)): ?>
        <tr>
          <td colspan="8" class="text-center text-muted py-4">Belum ada jadwal. Tambahkan jadwal baru.</td>
        </tr>
        <?php else: $no = 1;
        foreach ($schedules as $s):
          $ts = strtotime($s['date']);
          $dayNames = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
          $dayIndo = $dayNames[date('l', $ts)] ?? date('l', $ts);
          $isPast = $ts < strtotime('today');
        ?>
          <tr class="<?= $isPast ? 'text-muted bg-light' : '' ?>">
            <td class="text-center fw-bold text-muted"><?= $no++ ?></td>
            <td>
              <span class="badge <?= $s['type'] === 'pelayanan' ? 'bg-primary' : 'bg-warning text-dark' ?>">
                <?= ucfirst($s['type']) ?>
              </span>
            </td>
            <td>
              <span class="fw-bold"><?= $dayIndo ?>, <?= date('d M Y', $ts) ?></span>
            </td>
            <td>
              <span class="fw-bold"><?= htmlspecialchars($s['user_name']) ?></span><br>
              <span class="small text-muted">@<?= htmlspecialchars($s['user_username']) ?></span>
            </td>
            <td>
              <span class="text-dark fw-bold"><?= htmlspecialchars($s['role']) ?></span>
              <?php if ($s['description']): ?>
                <span class="d-block small text-muted"><?= htmlspecialchars($s['description']) ?></span>
              <?php endif; ?>
            </td>
            <td>
              <span class="small"><?= htmlspecialchars($s['location'] ?? '-') ?></span>
            </td>
            <td>
              <span class="small text-muted"><?= htmlspecialchars($s['notes'] ?? '-') ?></span>
            </td>
            <td class="text-center">
              <div class="btn-group">
                <a href="<?= BASE_URL ?>/manage-schedule?action=edit&id=<?= $s['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Edit">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <form action="<?= BASE_URL ?>/manage-schedule?action=delete&id=<?= $s['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
      <?php endforeach;
      endif; ?>
    </tbody>
  </table>
</div>