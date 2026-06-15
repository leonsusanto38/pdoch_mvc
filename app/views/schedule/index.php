<div class="row mb-4">
  <div class="col">
    <h2 class="fw-bold text-dark"><i class="bi bi-calendar-check text-primary me-2"></i> Jadwal Pelayanan & Latihan</h2>
    <p class="text-muted">Lihat jadwal tugas pelayanan dan sesi latihan Anda serta seluruh anggota tim</p>
  </div>
</div>

<!-- My outstanding schedules -->
<div class="row mb-5">
  <div class="col-12">
    <div class="card border-0 shadow-sm bg-white p-4">
      <div class="card-body">
        <h4 class="fw-bold text-dark mb-4"><i class="bi bi-person-badge text-success"></i> Jadwal Saya yang Outstanding</h4>

        <?php if (empty($mySchedules)): ?>
          <div class="text-center py-4 text-muted">
            <i class="bi bi-emoji-smile fs-1 d-block mb-2 text-secondary"></i>
            Anda tidak memiliki tugas pelayanan atau latihan yang terdekat.
          </div>
        <?php else: ?>
          <div class="row g-3">
            <?php foreach ($mySchedules as $s):
              $ts = strtotime($s['date']);
              $dayNames = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
              $dayIndo = $dayNames[date('l', $ts)] ?? date('l', $ts);
              $months = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];
              $monthIndo = $months[date('F', $ts)] ?? date('F', $ts);
            ?>
              <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-start border-4 <?= $s['type'] === 'pelayanan' ? 'border-primary' : 'border-warning' ?> shadow-sm bg-white">
                  <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <span class="badge <?= $s['type'] === 'pelayanan' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-warning bg-opacity-10 text-warning-emphasis' ?>">
                        <?= $s['type'] === 'pelayanan' ? 'Pelayanan' : 'Latihan' ?>
                      </span>
                      <small class="text-muted fw-bold"><?= $dayIndo ?>, <?= date('d', $ts) ?> <?= $monthIndo ?> <?= date('Y', $ts) ?></small>
                    </div>
                    <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($s['role']) ?></h5>
                    <?php if ($s['location']): ?>
                      <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($s['location']) ?></p>
                    <?php endif; ?>
                    <?php if ($s['description']): ?>
                      <p class="text-muted small mb-3"><?= htmlspecialchars($s['description']) ?></p>
                    <?php endif; ?>
                    <?php if ($s['notes']): ?>
                      <div class="bg-light rounded p-2 small border-start border-3 border-secondary text-secondary">
                        <i class="bi bi-info-circle-fill"></i> <strong>Catatan:</strong> <?= htmlspecialchars($s['notes']) ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- All outstanding schedules -->
<div class="row mb-5">
  <div class="col-12">
    <div class="card border-0 shadow-sm bg-white p-4">
      <div class="card-body">
        <h4 class="fw-bold text-dark mb-4"><i class="bi bi-calendar3 text-primary"></i> Seluruh Jadwal Outstanding</h4>

        <?php if (empty($allOutstanding)): ?>
          <div class="text-center py-4 text-muted">
            Belum ada jadwal pelayanan atau latihan yang diinput oleh admin.
          </div>
        <?php else: ?>
          <div class="table-responsive border-0 shadow-none">
            <table class="table table-hover align-middle">
              <thead>
                <tr>
                  <th>Jenis</th>
                  <th>Tanggal</th>
                  <th>Petugas / Pelayan</th>
                  <th>Tugas / Peran</th>
                  <th>Lokasi</th>
                  <th>Catatan</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($allOutstanding as $s):
                  $ts = strtotime($s['date']);
                  $dayNames = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
                  $dayIndo = $dayNames[date('l', $ts)] ?? date('l', $ts);
                  $months = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];
                  $monthIndo = $months[date('F', $ts)] ?? date('F', $ts);
                ?>
                  <tr>
                    <td>
                      <span class="badge <?= $s['type'] === 'pelayanan' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-warning bg-opacity-10 text-warning-emphasis' ?>">
                        <?= $s['type'] === 'pelayanan' ? 'Pelayanan' : 'Latihan' ?>
                      </span>
                    </td>
                    <td>
                      <span class="fw-semibold text-dark"><?= $dayIndo ?>, <?= date('d', $ts) ?> <?= $monthIndo ?></span>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold me-2" style="width:30px;height:30px;font-size:12px;">
                          <?= strtoupper(substr($s['user_name'], 0, 1)) ?>
                        </div>
                        <span class="text-dark fw-bold"><?= htmlspecialchars($s['user_name']) ?></span>
                      </div>
                    </td>
                    <td>
                      <span class="text-dark fw-semibold"><?= htmlspecialchars($s['role']) ?></span>
                      <?php if ($s['description']): ?>
                        <span class="text-muted d-block small"><?= htmlspecialchars($s['description']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="text-secondary small"><?= htmlspecialchars($s['location'] ?? '-') ?></span>
                    </td>
                    <td>
                      <span class="text-secondary small"><?= htmlspecialchars($s['notes'] ?? '-') ?></span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>