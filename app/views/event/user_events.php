<div class="row mb-4">
  <div class="col">
    <h2 class="fw-bold text-dark"><i class="bi bi-calendar-event text-primary"></i> Jadwal Ibadah PDOCH</h2>
    <p class="text-muted">Lihat seluruh jadwal kegiatan ibadah dan persekutuan</p>
  </div>
</div>

<!-- Upcoming Events -->
<section class="mb-5">
  <h4 class="fw-bold text-dark mb-3 border-bottom pb-2">
    <i class="bi bi-calendar-check text-success"></i> Jadwal Mendatang
  </h4>

  <?php if (empty($upcomingEvents)): ?>
    <div class="card border-0 shadow-sm bg-white p-5 text-center">
      <div class="text-muted mb-3"><i class="bi bi-calendar-x fs-1"></i></div>
      <h5>Belum ada jadwal ibadah mendatang.</h5>
      <p class="text-muted small">Jadwal akan segera diperbarui oleh admin.</p>
    </div>
  <?php else: ?>
    <div class="row g-3">
      <?php foreach ($upcomingEvents as $event):
        $ts = strtotime($event['time_start']);
        $dayNames = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $dayIndo = $dayNames[date('l', $ts)] ?? date('l', $ts);
        $months = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];
        $monthIndo = $months[date('F', $ts)] ?? date('F', $ts);
      ?>
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm bg-white">
            <div class="card-body p-4 d-flex flex-column">
              <div class="d-flex align-items-center mb-3">
                <div class="text-center bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3" style="min-width:56px;">
                  <div class="fw-bold fs-4 lh-1"><?= date('d', $ts) ?></div>
                  <div class="small text-uppercase"><?= strtoupper(substr($monthIndo, 0, 3)) ?></div>
                </div>
                <div>
                  <div class="fw-bold text-dark"><?= htmlspecialchars($event['name']) ?></div>
                  <div class="text-muted small"><?= $dayIndo ?>, <?= $monthIndo ?> <?= date('Y', $ts) ?></div>
                </div>
              </div>
              <p class="text-muted small flex-grow-1">
                <?= htmlspecialchars(mb_strimwidth($event['description'] ?? 'Tidak ada deskripsi.', 0, 100, '...')) ?>
              </p>
              <div class="border-top pt-2 mt-2 d-flex justify-content-between align-items-center">
                <span class="text-muted small"><i class="bi bi-clock"></i> <?= date('H:i', $ts) ?> – <?= date('H:i', strtotime($event['time_end'])) ?> WIB</span>
                <span class="text-muted small"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($event['coordinator_name'] ?? 'PDOCH') ?></span>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<!-- Past Events -->
<?php if (!empty($pastEvents)): ?>
  <section>
    <h4 class="fw-bold text-dark mb-3 border-bottom pb-2">
      <i class="bi bi-clock-history text-secondary"></i> Jadwal Sebelumnya
    </h4>
    <div class="table-responsive mb-5">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>Nama Ibadah</th>
            <th>Tanggal & Waktu</th>
            <th>Koordinator</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pastEvents as $event):
            $ts = strtotime($event['time_start']);
            $dayNames = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
            $dayIndo = $dayNames[date('l', $ts)] ?? date('l', $ts);
            $months = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];
            $monthIndo = $months[date('F', $ts)] ?? date('F', $ts);
          ?>
            <tr>
              <td>
                <span class="fw-semibold text-dark"><?= htmlspecialchars($event['name']) ?></span>
              </td>
              <td>
                <span class="text-muted small"><?= $dayIndo ?>, <?= date('d', $ts) ?> <?= $monthIndo ?> <?= date('Y', $ts) ?></span><br>
                <span class="text-muted small font-monospace"><?= date('H:i', $ts) ?> – <?= date('H:i', strtotime($event['time_end'])) ?> WIB</span>
              </td>
              <td>
                <span class="text-secondary small"><?= htmlspecialchars($event['coordinator_name'] ?? 'PDOCH') ?></span>
              </td>
            </tr>

            <?php
            $canViewEval = \App\Core\Session::hasAnyRole(['Admin', 'Pelayan', 'Aktivis']);
            $isPicOrAdmin = \App\Core\Session::isAdmin() || (\App\Core\Session::isLoggedIn() && $event['coordinator_user_id'] == \App\Core\Session::get('user_id'));

            if ($canViewEval || $isPicOrAdmin):
            ?>
              <tr class="table-light">
                <td colspan="3" class="ps-4 py-3 border-bottom-0">
                  <div class="bg-white p-3 rounded shadow-sm border-start border-3 border-info">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-chat-square-text text-info"></i> Evaluasi Event</h6>

                    <?php if (!empty($event['evaluations'])): ?>
                      <div class="mb-3">
                        <?php foreach ($event['evaluations'] as $eval): ?>
                          <div class="border-bottom pb-2 mb-2 last-border-none">
                            <p class="mb-1 text-dark small" style="white-space: pre-wrap;"><?= htmlspecialchars($eval['evaluation']) ?></p>
                            <small class="text-muted">
                              Oleh: <strong><?= htmlspecialchars($eval['pic_name'] ?? 'Anonim') ?></strong>
                              pada <?= date('d M Y H:i', strtotime($eval['created_at'])) ?>
                            </small>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    <?php else: ?>
                      <p class="text-muted small mb-2">Belum ada evaluasi untuk event ini.</p>
                    <?php endif; ?>

                    <?php if ($isPicOrAdmin): ?>
                      <form action="<?= BASE_URL ?>/events?action=add-evaluation" method="POST" class="mt-2">
                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                        <div class="input-group input-group-sm">
                          <textarea class="form-control" name="evaluation" rows="2" placeholder="Tulis evaluasi event di sini..." required></textarea>
                          <button class="btn btn-info text-white" type="submit">
                            <i class="bi bi-send-fill"></i> Kirim
                          </button>
                        </div>
                      </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
<?php endif; ?>