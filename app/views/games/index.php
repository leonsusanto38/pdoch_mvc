<?php
// Role icons and colors for each Pilar Legends game role
$roleIcons = ['Fighter' => '⚔️', 'Marksman' => '🏹', 'Assassin' => '🗡️', 'Wizard' => '🧙', 'Tank' => '🛡️', 'Cleric' => '✨'];
?>
<style>
/* ===== GAMES PAGE PREMIUM STYLES ===== */
.games-hero {
  background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
  border-radius: 20px;
  padding: 3rem 2rem;
  margin-bottom: 2rem;
  position: relative;
  overflow: hidden;
  color: #fff;
}
.games-hero::before {
  content: '';
  position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  pointer-events: none;
}
.games-hero .glow-circle {
  position: absolute; border-radius: 50%;
  filter: blur(80px); opacity: 0.15; pointer-events: none;
}
.games-hero .gc1 { width: 400px; height: 400px; background: #7c3aed; top: -100px; right: -100px; }
.games-hero .gc2 { width: 300px; height: 300px; background: #2563eb; bottom: -100px; left: -50px; }

.game-card {
  background: #fff;
  border-radius: 20px;
  overflow: hidden;
  border: 1px solid rgba(124,58,237,0.15);
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
}
.game-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 60px rgba(124,58,237,0.2);
  border-color: rgba(124,58,237,0.4);
}
.game-card .game-banner {
  height: 200px;
  background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4c1d95 70%, #7c3aed 100%);
  display: flex; align-items: center; justify-content: center;
  position: relative; overflow: hidden;
}
.game-card .game-banner::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse at center, rgba(139,92,246,0.3) 0%, transparent 70%);
}
.game-card .game-title-overlay {
  position: relative; z-index: 1; text-align: center; color: #fff;
}
.game-card .game-title-overlay .game-icon { font-size: 3rem; margin-bottom: 0.5rem; }
.game-card .game-title-overlay h3 {
  font-size: 1.6rem; font-weight: 800;
  letter-spacing: 2px; text-shadow: 0 0 20px rgba(139,92,246,0.8);
  margin: 0;
}
.game-card .game-title-overlay .game-subtitle {
  font-size: 0.75rem; opacity: 0.7; letter-spacing: 3px; text-transform: uppercase; margin-top: 4px;
}
.game-card .game-body { padding: 1.5rem; }
.game-card .game-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 1rem; }
.game-card .game-tag {
  font-size: 0.7rem; font-weight: 600; padding: 3px 10px; border-radius: 20px;
  background: rgba(124,58,237,0.1); color: #7c3aed; border: 1px solid rgba(124,58,237,0.2);
}
.game-card .play-btn {
  background: linear-gradient(135deg, #7c3aed, #2563eb);
  color: #fff; border: none; border-radius: 12px;
  padding: 0.8rem 1.5rem; width: 100%; font-weight: 700;
  font-size: 1rem; letter-spacing: 1px; transition: all 0.3s;
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.game-card .play-btn:hover {
  background: linear-gradient(135deg, #6d28d9, #1d4ed8);
  transform: scale(1.02); box-shadow: 0 8px 24px rgba(124,58,237,0.4);
}

.coming-soon-card {
  background: #f8f7ff;
  border: 2px dashed rgba(124,58,237,0.2);
  border-radius: 20px; padding: 3rem 1.5rem;
  text-align: center; color: #9ca3af;
  transition: all 0.3s;
}
.coming-soon-card:hover {
  border-color: rgba(124,58,237,0.4);
  background: rgba(124,58,237,0.03);
}

.stat-pill {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
  border-radius: 50px; padding: 6px 16px; font-size: 0.85rem; color: #fff;
}
.stat-pill .stat-val { font-weight: 800; font-size: 1.1rem; }

.role-chip {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 14px; border-radius: 50px; font-size: 0.8rem; font-weight: 600;
  background: rgba(255,255,255,0.12); color: #fff; border: 1px solid rgba(255,255,255,0.2);
  transition: all 0.2s;
}
.role-chip:hover { background: rgba(255,255,255,0.2); transform: scale(1.05); }

/* Particle animation */
@keyframes float {
  0%,100% { transform: translateY(0) rotate(0); opacity: 0.3; }
  50% { transform: translateY(-20px) rotate(5deg); opacity: 0.6; }
}
.particle {
  position: absolute; font-size: 1.5rem; pointer-events: none;
  animation: float 4s ease-in-out infinite;
}
</style>

<!-- Hero Section -->
<div class="games-hero">
  <div class="glow-circle gc1"></div>
  <div class="glow-circle gc2"></div>
  <!-- Floating particles -->
  <div class="particle" style="top:15%;left:5%;animation-delay:0s">⚔️</div>
  <div class="particle" style="top:25%;left:20%;animation-delay:1s">🏹</div>
  <div class="particle" style="top:60%;left:8%;animation-delay:2s">🛡️</div>
  <div class="particle" style="top:70%;right:15%;animation-delay:0.5s">🧙</div>
  <div class="particle" style="top:20%;right:25%;animation-delay:1.5s">✨</div>
  <div class="particle" style="top:50%;right:5%;animation-delay:2.5s">🗡️</div>

  <div class="position-relative text-center">
    <div class="mb-3">
      <span class="badge rounded-pill px-3 py-2" style="background:rgba(124,58,237,0.3);border:1px solid rgba(139,92,246,0.5);font-size:0.75rem;letter-spacing:2px;color:#c4b5fd;">
        🎮 PDOCH GAMES HUB
      </span>
    </div>
    <h1 class="fw-black mb-2" style="font-size:2.5rem;letter-spacing:2px;text-shadow:0 0 30px rgba(139,92,246,0.5);">
      GAME CENTER
    </h1>
    <p class="mb-4" style="opacity:0.7;font-size:1rem;">Bermain, berkompetisi, dan bersenang-senang bersama anggota Dewasa Muda PDOCH</p>

    <?php if (!empty($userStats['total_match'])): ?>
    <!-- User stats bar -->
    <div class="d-flex flex-wrap justify-content-center gap-2">
      <div class="stat-pill"><span class="stat-val"><?= $userStats['total_match'] ?></span> Match</div>
      <div class="stat-pill"><span class="stat-val" style="color:#4ade80;"><?= $userStats['total_win'] ?></span> Win</div>
      <div class="stat-pill"><span class="stat-val" style="color:#f87171;"><?= $userStats['total_lose'] ?></span> Lose</div>
      <div class="stat-pill"><span class="stat-val" style="color:#fbbf24;"><?= $userStats['total_kill'] ?></span> Kills</div>
      <div class="stat-pill"><span class="stat-val" style="color:#60a5fa;"><?= $userStats['total_damage_given'] ?></span> DMG Given</div>
    </div>
    <?php else: ?>
    <p class="mb-0" style="opacity:0.5;font-style:italic;">Belum ada statistik permainan. Ayo mulai main!</p>
    <?php endif; ?>
  </div>
</div>

<!-- Games Grid -->
<div class="row mb-2">
  <div class="col-12">
    <h4 class="fw-bold mb-4">
      <i class="bi bi-grid-3x3-gap-fill" style="color:#7c3aed;"></i>
      Pilih Game
    </h4>
  </div>
</div>

<div class="row g-4">
  <!-- Pilar Legends Card -->
  <div class="col-md-6 col-lg-4">
    <div class="game-card h-100">
      <div class="game-banner">
        <!-- Animated stars -->
        <div style="position:absolute;inset:0;overflow:hidden;">
          <?php for($i=0;$i<15;$i++): ?>
            <div style="position:absolute;width:<?= rand(2,4) ?>px;height:<?= rand(2,4) ?>px;background:rgba(255,255,255,<?= rand(3,8)/10 ?>);border-radius:50%;top:<?= rand(0,100) ?>%;left:<?= rand(0,100) ?>%;"></div>
          <?php endfor; ?>
        </div>
        <div class="game-title-overlay">
          <div class="game-icon">🏰</div>
          <h3>PILAR LEGENDS</h3>
          <div class="game-subtitle">Turn-Based Battle • Board Game</div>
        </div>
      </div>
      <div class="game-body">
        <div class="game-tags">
          <span class="game-tag">⚔️ Turn-Based</span>
          <span class="game-tag">👥 Multiplayer</span>
          <span class="game-tag">🎲 Dice Combat</span>
          <span class="game-tag">🏰 Strategy</span>
        </div>
        <p class="text-muted small mb-3">
          Game pertarungan turn-based dimana dua tim saling bertanding. Pilih role-mu, susun strategi, dan kalahkan tim lawan!
        </p>
        <!-- Roles preview -->
        <div class="mb-3">
          <small class="text-muted fw-bold d-block mb-2">AVAILABLE ROLES</small>
          <div class="d-flex flex-wrap gap-1">
            <?php foreach ($allRoles as $role): ?>
              <span class="role-chip" style="background:rgba(124,58,237,0.08);color:#4c1d95;border-color:rgba(124,58,237,0.2);" title="HP: <?= $role['base_hp'] ?> | ATK: <?= $role['base_attack'] ?> | MOV: <?= $role['base_movement'] ?> | Range: <?= $role['range_cells'] ?>">
                <?= $roleIcons[$role['name']] ?? '⚡' ?> <?= htmlspecialchars($role['name']) ?>
              </span>
            <?php endforeach; ?>
          </div>
        </div>
        <?php if ($userStats['total_match'] > 0 && !empty($userStats['role_picks'])): ?>
          <div class="mb-3">
            <small class="text-muted fw-bold d-block mb-1">YOUR MOST PLAYED ROLE</small>
            <span class="badge rounded-pill px-3 py-2" style="background:linear-gradient(135deg,#7c3aed,#2563eb);font-size:0.85rem;">
              <?= $roleIcons[$userStats['role_picks'][0]['role_name']] ?? '⚡' ?>
              <?= htmlspecialchars($userStats['role_picks'][0]['role_name']) ?>
              — <?= $userStats['role_picks'][0]['pick_count'] ?>× played
            </span>
          </div>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/pilar-legends" class="btn play-btn">
          <i class="bi bi-play-circle-fill fs-5"></i> MAIN SEKARANG
        </a>
      </div>
    </div>
  </div>

  <!-- Coming Soon Cards -->
  <?php for ($i = 0; $i < 3; $i++): ?>
  <div class="col-md-6 col-lg-4">
    <div class="coming-soon-card h-100 d-flex flex-column align-items-center justify-content-center">
      <div style="font-size:3rem;margin-bottom:1rem;opacity:0.3;">🎮</div>
      <h5 class="fw-bold mb-1" style="color:#c4b5fd;">Game Baru</h5>
      <p class="mb-2 small">Segera hadir...</p>
      <span class="badge rounded-pill px-3 py-2" style="background:rgba(124,58,237,0.08);color:#7c3aed;border:1px solid rgba(124,58,237,0.2);">
        Coming Soon
      </span>
    </div>
  </div>
  <?php endfor; ?>
</div>

<!-- Role Reference Section -->
<?php if (!empty($allRoles)): ?>
<div class="mt-5">
  <h5 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary"></i> Referensi Role — Pilar Legends</h5>
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>Role</th>
          <th class="text-center">Base ATK <small class="text-muted">(+🎲)</small></th>
          <th class="text-center">Base HP</th>
          <th class="text-center">Base MOV <small class="text-muted">(+🎲)</small></th>
          <th class="text-center">Range</th>
          <th>Deskripsi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($allRoles as $role): ?>
        <tr>
          <td>
            <span class="fw-bold"><?= $roleIcons[$role['name']] ?? '⚡' ?> <?= htmlspecialchars($role['name']) ?></span>
          </td>
          <td class="text-center fw-bold text-danger"><?= $role['base_attack'] ?></td>
          <td class="text-center fw-bold text-success"><?= $role['base_hp'] ?> HP</td>
          <td class="text-center fw-bold text-primary"><?= $role['base_movement'] ?></td>
          <td class="text-center"><?= $role['range_cells'] ?></td>
          <td class="text-muted small"><?= htmlspecialchars($role['description'] ?? '') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>
