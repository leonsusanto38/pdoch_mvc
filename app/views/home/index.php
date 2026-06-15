<!-- HERO SECTION -->
<div class="p-5 mb-5 hero-gradient rounded-4 shadow-sm text-center text-md-start">
  <div class="row align-items-center py-4">
    <div class="col-md-8">
      <span class="badge bg-primary bg-opacity-25 text-primary mb-3 px-3 py-2 fs-6">
        <i class="bi bi-geo-alt"></i> Citra Harmoni, Sidoarjo
      </span>
      <h1 class="display-3 fw-extrabold mb-3">PDOCH</h1>
      <p class="fs-4 text-white-50 mb-4">
        Persekutuan Doa Oikumene Citra Harmoni
      </p>
      <p class="lead text-white-50 col-lg-10 mb-4">
        Wadah persekutuan doa lintas denominasi gereja untuk memuliakan nama Tuhan Yesus, menyatukan tubuh Kristus, serta bertumbuh bersama dalam iman, kasih, dan pelayanan.
      </p>
      <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start">
        <a href="<?= BASE_URL ?>/events" class="btn btn-primary btn-lg text-white">
          <i class="bi bi-calendar-event"></i> Lihat Jadwal Ibadah
        </a>
        <a href="#visi-misi" class="btn btn-outline-light btn-lg">
          Tentang Kami <i class="bi bi-arrow-down-short"></i>
        </a>
      </div>
    </div>
    <div class="col-md-4 d-none d-md-block text-center">
      <i class="bi bi-shield-heart-fill text-primary" style="font-size: 10rem; opacity: 0.85; filter: drop-shadow(0 0 20px rgba(59,130,246,0.3));"></i>
    </div>
  </div>
</div>

<!-- BIRTHDAY BANNER -->
<?php if (!empty($birthdayUsers)): ?>
  <div class="alert bg-success bg-opacity-10 border-0 shadow-sm p-4 rounded-4 mb-5 position-relative overflow-hidden" style="border-left: 6px solid #10b981 !important;">
    <div class="row align-items-center g-3">
      <div class="col-auto">
        <div class="fs-1 text-success"><i class="bi bi-gift-fill animate-bounce"></i></div>
      </div>
      <div class="col">
        <h4 class="fw-bold text-success-emphasis mb-1">Selamat Ulang Tahun! 🎉</h4>
        <p class="text-success-emphasis mb-0 small">
          Hari ini keluarga besar PDOCH bersyukur dan bersukacita atas hari ulang tahun:
          <strong>
            <?php 
              $names = array_map(function($u) { return htmlspecialchars($u['name']); }, $birthdayUsers);
              echo implode(', ', $names);
            ?>
          </strong>. 
          Tuhan Yesus memberkati senantiasa dengan kesehatan, sukacita, dan penyertaan-Nya yang melimpah!
        </p>
      </div>
      <div class="col-auto d-flex gap-2">
        <?php foreach ($birthdayUsers as $bu): ?>
          <div class="rounded-circle overflow-hidden border border-2 border-success" style="width: 48px; height: 48px;" title="<?= htmlspecialchars($bu['name']) ?>">
            <?php if ($bu['image'] && file_exists(__DIR__ . '/../../../assets/uploads/' . $bu['image'])): ?>
              <img src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($bu['image']) ?>" alt="Avatar" class="w-100 h-100 object-fit-cover">
            <?php else: ?>
              <img src="https://ui-avatars.com/api/?name=<?= urlencode($bu['name']) ?>&background=10b981&color=fff&size=48" alt="Avatar" class="w-100 h-100 object-fit-cover">
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- UPCOMING EVENTS SECTION -->
<section class="mb-5">
  <div class="row align-items-center mb-4">
    <div class="col">
      <h3 class="fw-bold text-dark mb-1">
        <i class="bi bi-calendar3 text-primary me-2"></i> Jadwal Ibadah Terdekat
      </h3>
      <p class="text-muted">Jangan lewatkan kesempatan untuk beribadah dan bersekutu bersama</p>
    </div>
    <div class="col-auto">
      <a href="<?= BASE_URL ?>/events" class="btn btn-secondary btn-sm">
        Semua Jadwal <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>

  <div class="row">
    <?php if (empty($upcomingEvents)): ?>
      <div class="col-12">
        <div class="card border-0 bg-white p-5 text-center">
          <div class="text-muted mb-3"><i class="bi bi-calendar-x fs-1"></i></div>
          <h5>Belum ada jadwal ibadah mendatang.</h5>
          <p class="text-muted small">Admin akan segera memperbarui jadwal ibadah terbaru.</p>
        </div>
      </div>
    <?php else: ?>
      <?php foreach ($upcomingEvents as $event): ?>
        <?php 
          $timestamp = strtotime($event['time_start']);
          $dayName = date('l', $timestamp);
          $dayNameIndo = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
          ][$dayName] ?? $dayName;
          
          $dateFormatted = date('d M Y', $timestamp);
          $timeFormatted = date('H:i', $timestamp) . ' - ' . date('H:i', strtotime($event['time_end'])) . ' WIB';
        ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100 border-0 shadow-sm bg-white">
            <div class="card-body p-4 d-flex flex-column">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fw-bold">
                  <?= $dayNameIndo ?>, <?= $dateFormatted ?>
                </span>
                <span class="text-muted small"><i class="bi bi-clock"></i> <?= date('H:i', $timestamp) ?></span>
              </div>
              <h5 class="fw-bold card-title mb-2"><?= htmlspecialchars($event['name']) ?></h5>
              <p class="card-text text-muted flex-grow-1 small">
                <?= htmlspecialchars(mb_strimwidth($event['description'] ?? 'Tidak ada deskripsi.', 0, 100, '...')) ?>
              </p>
              <hr class="text-black-50 my-3">
              <div class="d-flex align-items-center justify-content-between text-muted small">
                <span>
                  <i class="bi bi-person-badge"></i> Coord: <?= htmlspecialchars($event['coordinator_name'] ?? 'PDOCH') ?>
                </span>
                <span class="text-primary font-monospace">
                  <?= $timeFormatted ?>
                </span>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>

<!-- NEWS & WARTA SECTION -->
<section class="mb-5 border-top pt-4">
  <div class="row align-items-center mb-4">
    <div class="col">
      <h3 class="fw-bold text-dark mb-1">
        <i class="bi bi-newspaper text-danger me-2"></i> Berita & Pengumuman Terbaru
      </h3>
      <p class="text-muted">Informasi penting dan kegiatan terupdate dari persekutuan kita</p>
    </div>
    <?php if (\App\Core\Session::isAdmin()): ?>
      <div class="col-auto">
        <a href="<?= BASE_URL ?>/manage-news" class="btn btn-outline-danger btn-sm">
          <i class="bi bi-gear-fill"></i> Kelola Berita
        </a>
      </div>
    <?php endif; ?>
  </div>

  <?php if (empty($latestNews)): ?>
    <div class="card border-0 bg-white p-5 text-center shadow-sm">
      <div class="text-muted mb-3"><i class="bi bi-journal-x fs-1"></i></div>
      <h5>Belum ada berita atau pengumuman terbaru.</h5>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($latestNews as $news): ?>
        <div class="col-md-6">
          <div class="card h-100 border-0 shadow-sm bg-white overflow-hidden">
            <div class="row g-0 h-100">
              <?php if ($news['image'] && file_exists(__DIR__ . '/../../../assets/uploads/' . $news['image'])): ?>
                <div class="col-sm-4 bg-light position-relative" style="min-height: 120px;">
                  <img src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($news['image']) ?>" alt="News Cover" class="w-100 h-100 object-fit-cover position-absolute">
                </div>
              <?php endif; ?>
              <div class="<?= $news['image'] ? 'col-sm-8' : 'col-12' ?> p-4 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small"><i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($news['created_at'])) ?></span>
                    <span class="badge bg-danger bg-opacity-10 text-danger small">Warta</span>
                  </div>
                  <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($news['title']) ?></h5>
                  <p class="text-muted small mb-3" style="white-space: pre-wrap;"><?= htmlspecialchars($news['content']) ?></p>
                </div>
                <div class="border-top pt-2 mt-2">
                  <small class="text-muted"><i class="bi bi-person"></i> Oleh: <strong><?= htmlspecialchars($news['creator_name'] ?? 'PDOCH') ?></strong></small>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<!-- VISI & MISI SECTION -->
<section id="visi-misi" class="py-4 mb-5 border-top">
  <div class="text-center mb-5 mt-4">
    <h3 class="fw-bold text-dark mb-2">Visi & Misi PDOCH</h3>
    <p class="text-muted">Landasan pelayanan kami dalam menyatukan tubuh Kristus</p>
  </div>

  <div class="row g-4">
    <!-- Visi Card -->
    <div class="col-md-5">
      <div class="card h-100 border-0 shadow-sm bg-primary bg-gradient text-white p-4">
        <div class="card-body">
          <div class="mb-4">
            <i class="bi bi-eye-fill fs-1"></i>
          </div>
          <h4 class="fw-bold mb-3">Visi Kami</h4>
          <p class="fs-5 lh-base mb-0" style="opacity: 0.9;">
            Menjadi wadah persekutuan yang kudus, menyatukan tubuh Kristus dari berbagai latar belakang gereja (Oikumene) di Citra Harmoni, serta memancarkan kasih dan kebenaran Kristus bagi sesama.
          </p>
        </div>
      </div>
    </div>

    <!-- Misi Card -->
    <div class="col-md-7">
      <div class="card h-100 border-0 shadow-sm bg-white p-4">
        <div class="card-body">
          <div class="mb-4 text-primary">
            <i class="bi bi-rocket-takeoff-fill fs-1"></i>
          </div>
          <h4 class="fw-bold text-dark mb-3">Misi Kami</h4>
          <ul class="list-group list-group-flush">
            <li class="list-group-item px-0 py-3 bg-transparent d-flex align-items-start border-light-subtle">
              <span class="badge bg-primary rounded-circle me-3 mt-1 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">1</span>
              <div>
                <h6 class="fw-bold mb-1 text-dark">Membangun Mezbah Doa</h6>
                <p class="text-muted mb-0 small">Mengadakan persekutuan doa rutin bulanan dan wadah doa oikumene yang konsisten.</p>
              </div>
            </li>
            <li class="list-group-item px-0 py-3 bg-transparent d-flex align-items-start border-light-subtle">
              <span class="badge bg-primary rounded-circle me-3 mt-1 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">2</span>
              <div>
                <h6 class="fw-bold mb-1 text-dark">Menjalin Kesatuan Oikumene</h6>
                <p class="text-muted mb-0 small">Menjembatani perbedaan doktrin demi kasih Kristus dan kebersamaan umat percaya di lingkungan sekitar.</p>
              </div>
            </li>
            <li class="list-group-item px-0 py-3 bg-transparent d-flex align-items-start border-0">
              <span class="badge bg-primary rounded-circle me-3 mt-1 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">3</span>
              <div>
                <h6 class="fw-bold mb-1 text-dark">Menggali dan Mengembangkan Talenta</h6>
                <p class="text-muted mb-0 small">Mendorong setiap jemaat untuk terlibat aktif melayani melalui musik, multimedia, sound, publikasi, dan pelayanan sosial.</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
