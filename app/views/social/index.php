<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-people-fill text-primary me-2"></i> Direktori Sosial PDOCH</h2>
    <p class="text-muted">Daftar jemaat, pelayan, dan aktivis yang tergabung dalam keluarga besar PDOCH</p>
  </div>
</div>

<div class="row g-4 mb-5">
  <?php foreach ($users as $u): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card h-100 border-0 shadow-sm bg-white overflow-hidden position-relative">
        <!-- Floating gender icon / badge -->
        <span class="position-absolute top-0 end-0 m-3 badge <?= $u['gender'] === 'M' ? 'bg-info bg-opacity-10 text-info' : ($u['gender'] === 'F' ? 'bg-danger bg-opacity-10 text-danger' : 'bg-secondary bg-opacity-10 text-secondary') ?>">
          <?= $u['gender'] === 'M' ? '<i class="bi bi-gender-male"></i> Pria' : ($u['gender'] === 'F' ? '<i class="bi bi-gender-female"></i> Wanita' : 'Lainnya') ?>
        </span>

        <div class="card-body p-4 d-flex flex-column">
          <!-- Profile header with image and name -->
          <div class="d-flex align-items-center mb-4">
            <div class="rounded-circle overflow-hidden border border-2 border-primary border-opacity-25 me-3" style="width:72px; height:72px; flex-shrink:0;">
              <?php if ($u['image'] && file_exists(__DIR__ . '/../../../assets/uploads/' . $u['image'])): ?>
                <img src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($u['image']) ?>" alt="Avatar" class="w-100 h-100 object-fit-cover">
              <?php else: ?>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($u['name']) ?>&background=3b82f6&color=fff&size=120" alt="Avatar" class="w-100 h-100 object-fit-cover">
              <?php endif; ?>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($u['name']) ?></h5>
              <p class="text-muted small mb-2">@<?= htmlspecialchars($u['username']) ?></p>
              <div class="d-flex flex-wrap gap-1">
                <?php foreach ($u['roles'] as $r):
                  $badgeColor = 'bg-secondary';
                  if ($r['name'] === 'Admin') $badgeColor = 'bg-danger';
                  if ($r['name'] === 'Jemaat') $badgeColor = 'bg-primary';
                  if ($r['name'] === 'Pelayan') $badgeColor = 'bg-success';
                  if ($r['name'] === 'Aktivis') $badgeColor = 'bg-warning text-dark';
                ?>
                  <span class="badge <?= $badgeColor ?> btn-xs"><?= htmlspecialchars($r['name']) ?></span>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- Detail information list -->
          <div class="flex-grow-1 border-top pt-3">
            <div class="row g-2 small">
              <div class="col-5 text-muted fw-bold">Gereja Asal:</div>
              <div class="col-7 text-dark"><?= htmlspecialchars($u['church_name'] ?? 'Belum memilih') ?></div>

              <div class="col-5 text-muted fw-bold">Tanggal Lahir:</div>
              <div class="col-7 text-dark">
                <?php if ($u['birth_date']): ?>
                  <i class="bi bi-cake2 text-danger"></i> <?= date('d M Y', strtotime($u['birth_date'])) ?>
                <?php else: ?>
                  -
                <?php endif; ?>
              </div>

              <div class="col-5 text-muted fw-bold">No. Telepon:</div>
              <div class="col-7 text-dark">
                <?php if ($u['phone_number']): ?>
                  <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $u['phone_number']) ?>" target="_blank" class="text-decoration-none">
                    <i class="bi bi-whatsapp text-success"></i> <?= htmlspecialchars($u['phone_number']) ?>
                  </a>
                <?php else: ?>
                  -
                <?php endif; ?>
              </div>

              <div class="col-5 text-muted fw-bold">Alamat:</div>
              <div class="col-7 text-dark text-truncate" title="<?= htmlspecialchars($u['address'] ?? '') ?>">
                <?= htmlspecialchars($u['address'] ?? '-') ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>