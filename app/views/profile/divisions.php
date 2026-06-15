<div class="row mb-4">
  <div class="col-12">
    <h2 class="fw-bold text-dark"><i class="bi bi-briefcase text-primary me-2"></i> Divisi Pelayanan Saya</h2>
    <p class="text-muted">Lihat daftar divisi di mana Anda saat ini aktif melayani</p>
  </div>
</div>

<div class="row">
  <?php if (empty($divisions)): ?>
    <div class="col-12">
      <div class="card border-0 bg-white p-5 text-center shadow-sm">
        <div class="text-muted mb-3"><i class="bi bi-briefcase fs-1"></i></div>
        <h5>Anda belum bergabung di divisi pelayanan manapun.</h5>
        <p class="text-muted small">Jika Anda adalah pelayan atau aktivis, hubungi Administrator untuk mendaftarkan Anda ke divisi pelayanan.</p>
        <div class="mt-3">
          <a href="<?= BASE_URL ?>/home" class="btn btn-primary text-white">Kembali ke Home</a>
        </div>
      </div>
    </div>
  <?php else: ?>
    <?php foreach ($divisions as $div): ?>
      <div class="col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm bg-white">
          <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 fw-bold">
                <i class="bi bi-patch-check"></i> Aktif Melayani
              </span>
              <span class="text-muted small"><i class="bi bi-tag"></i> ID Divisi: #<?= $div['id'] ?></span>
            </div>
            
            <h4 class="fw-bold text-dark mb-2"><?= htmlspecialchars($div['name']) ?></h4>
            <p class="text-muted small mb-3 flex-grow-1">
              <?= htmlspecialchars($div['description'] ?? 'Tidak ada deskripsi.') ?>
            </p>

            <div class="bg-light rounded-3 p-3">
              <div class="row g-2">
                <div class="col-6">
                  <span class="text-muted d-block small">Peran Anda:</span>
                  <span class="fw-bold text-primary"><i class="bi bi-person-fill-gear"></i> <?= htmlspecialchars($div['member_role'] ?? 'Anggota') ?></span>
                </div>
                <div class="col-6 border-start ps-3">
                  <span class="text-muted d-block small">Koordinator:</span>
                  <span class="fw-semibold text-dark"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($div['coordinator_name'] ?? 'Admin PDOCH') ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
