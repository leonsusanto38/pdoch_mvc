<?php
$roleIcons = ['Fighter' => '⚔️', 'Marksman' => '🏹', 'Assassin' => '🗡️', 'Wizard' => '🧙', 'Tank' => '🛡️', 'Cleric' => '✨'];
$sessionId     = $session['id'];
$sessionStatus = $session['status'];
$myId          = $playerSession['id'] ?? null;
$myTeam        = $playerSession['team'] ?? null;
$myRoleId      = $playerSession['pl_role_id'] ?? null;
$myHp          = $playerSession['current_hp'] ?? null;
$myRoleName    = $playerSession['role_name'] ?? null;
$myTurnOrder   = $playerSession['turn_order'] ?? null;
$myIsAlive     = $playerSession['is_alive'] ?? 1;
?>
<style>
.pv-layout { display: grid; grid-template-columns: 1fr 320px; gap: 1.5rem; }
@media (max-width: 991px) { .pv-layout { grid-template-columns: 1fr; } }

.pv-hero {
  background: linear-gradient(135deg, #1e1b4b, #312e81);
  border-radius: 16px; color: #fff; padding: 1.5rem; margin-bottom: 1.5rem;
  display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;
}
.pv-role-badge {
  background: rgba(255,255,255,0.1); border: 2px solid rgba(255,255,255,0.2);
  border-radius: 16px; padding: 1rem 1.5rem; text-align: center; min-width: 120px;
}
.pv-role-badge .role-icon { font-size: 2.5rem; }
.pv-role-badge .role-name { font-weight: 800; letter-spacing: 1px; font-size: 1rem; margin-top: 4px; }
.pv-role-badge.unset { border-style: dashed; opacity: 0.6; }

.big-stat {
  text-align: center;
  background: rgba(255,255,255,0.1); border-radius: 12px; padding: 0.8rem 1rem;
}
.big-stat .val { font-size: 2rem; font-weight: 900; }
.big-stat .lbl { font-size: 0.7rem; opacity: 0.7; letter-spacing: 1px; }

.hp-ring {
  width: 80px; height: 80px; border-radius: 50%; background: conic-gradient(#4ade80 var(--hp-pct, 100%), #374151 0);
  display: flex; align-items: center; justify-content: center; position: relative;
  flex-shrink: 0;
}
.hp-ring::before {
  content: ''; position: absolute; inset: 8px; background: #1e1b4b; border-radius: 50%;
}
.hp-ring .hp-text { position: relative; z-index: 1; font-weight: 900; font-size: 0.8rem; }

.panel-card {
  background: #fff; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.06);
  border: 1px solid rgba(124,58,237,0.08); overflow: hidden; margin-bottom: 1.5rem;
}
.panel-header {
  padding: 1rem 1.5rem; border-bottom: 1px solid #f3f4f6;
  display: flex; align-items: center; justify-content: space-between;
  background: linear-gradient(90deg, #faf5ff, #fff);
}
.panel-header h6 { font-weight: 800; margin: 0; color: #1e1b4b; }
.panel-body { padding: 1.25rem 1.5rem; }

.teammate-card {
  display: flex; align-items: center; gap: 12px; padding: 12px;
  border-radius: 12px; border: 2px solid transparent; transition: all 0.2s;
  margin-bottom: 8px;
}
.teammate-card.team-a { border-color: rgba(59,130,246,0.2); background: rgba(59,130,246,0.04); }
.teammate-card.team-b { border-color: rgba(239,68,68,0.2); background: rgba(239,68,68,0.04); }
.teammate-card.is-turn { border-color: #f59e0b; background: rgba(245,158,11,0.08); animation: pulse-card 2s infinite; }
.teammate-card.dead { opacity: 0.4; filter: grayscale(1); }
@keyframes pulse-card { 0%,100%{ box-shadow:0 0 0 0 rgba(245,158,11,0.3); } 50%{ box-shadow:0 0 0 8px rgba(245,158,11,0); } }

.hp-bar-sm { height: 6px; border-radius: 3px; background: #e5e7eb; overflow: hidden; width: 80px; }
.hp-fill-sm { height: 100%; border-radius: 3px; background: #4ade80; transition: width 0.5s; }
.hp-fill-sm.low  { background: #fbbf24; }
.hp-fill-sm.crit { background: #f87171; }

.role-card {
  border: 2px solid transparent; border-radius: 14px; padding: 1rem; cursor: pointer;
  transition: all 0.3s; text-align: center; background: #f9fafb;
}
.role-card:hover { border-color: #7c3aed; background: #faf5ff; transform: translateY(-2px); }
.role-card.selected { border-color: #7c3aed; background: #f5f3ff; box-shadow: 0 0 0 4px rgba(124,58,237,0.1); }
.role-card.disabled { opacity: 0.4; cursor: not-allowed; }
.role-icon-lg { font-size: 2.5rem; }
.role-stat { font-size: 0.7rem; font-weight: 600; padding: 2px 6px; border-radius: 4px; color: #fff; display: inline-block; }

.turn-item {
  display: flex; align-items: center; gap: 10px; padding: 8px 12px;
  border-radius: 8px; margin-bottom: 4px; transition: all 0.3s;
}
.turn-item.is-current { background: rgba(245,158,11,0.1); border: 2px solid #f59e0b; }
.turn-item.dead-turn { opacity: 0.4; }

.log-item {
  padding: 8px 10px; border-radius: 8px; margin-bottom: 5px; font-size: 0.78rem;
}
.log-attack  { background: rgba(239,68,68,0.07);  border-left: 3px solid #ef4444; }
.log-move    { background: rgba(59,130,246,0.07); border-left: 3px solid #3b82f6; }
.log-skip    { background: rgba(107,114,128,0.07); border-left: 3px solid #9ca3af; }
.log-auto_skip { background: rgba(245,158,11,0.07); border-left: 3px solid #f59e0b; }

.timer-badge {
  font-size: 1.8rem; font-weight: 900; font-family: monospace;
  color: #f59e0b; text-align: center; display: block;
}
.timer-badge.danger { color: #dc2626; animation: blink 0.8s infinite; }
@keyframes blink { 50%{ opacity:0.4; } }
</style>

<!-- Hero: My Stats -->
<div class="pv-hero">
  <div class="pv-role-badge <?= $myRoleId ? '' : 'unset' ?>">
    <div class="role-icon"><?= $myRoleName ? ($roleIcons[$myRoleName] ?? '⚡') : '❓' ?></div>
    <div class="role-name"><?= $myRoleName ? htmlspecialchars($myRoleName) : 'Belum pilih' ?></div>
  </div>

  <div class="flex-grow-1">
    <div class="fw-black fs-5 mb-1" style="letter-spacing:1px;">
      <?= htmlspecialchars($playerSession['user_name'] ?? '') ?>
      <?php if ($myTeam): ?>
        <span class="badge rounded-pill ms-2" style="background:<?= $myTeam==='A'?'#1d4ed8':'#b91c1c' ?>;font-size:0.8rem;">
          Tim <?= $myTeam ?>
        </span>
      <?php endif; ?>
      <?php if ($myTurnOrder): ?>
        <span class="badge bg-warning text-dark ms-1" style="font-size:0.8rem;">Giliran #<?= $myTurnOrder ?></span>
      <?php endif; ?>
      <?php if (!$myIsAlive): ?>
        <span class="badge bg-danger ms-1">💀 Gugur</span>
      <?php endif; ?>
    </div>

    <div class="d-flex flex-wrap gap-2 mt-2">
      <?php if ($myRoleName): ?>
        <div class="big-stat">
          <div class="val text-danger"><?= $playerSession['base_attack'] ?? '?' ?></div>
          <div class="lbl">BASE ATK</div>
        </div>
        <div class="big-stat">
          <div class="val text-success"><?= $myHp ?? '?' ?></div>
          <div class="lbl">CURRENT HP</div>
        </div>
        <div class="big-stat">
          <div class="val text-primary"><?= $playerSession['base_movement'] ?? '?' ?></div>
          <div class="lbl">BASE MOV</div>
        </div>
        <div class="big-stat">
          <div class="val text-warning"><?= $playerSession['range_cells'] ?? '?' ?></div>
          <div class="lbl">RANGE</div>
        </div>
      <?php else: ?>
        <div style="opacity:0.7;font-size:0.85rem;">
          <i class="bi bi-info-circle me-1"></i>
          <?php if ($sessionStatus === 'discussion_role'): ?>
            Pilih role-mu sekarang! ⬇️
          <?php else: ?>
            Tunggu instruksi dari Game Master.
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Current status chip -->
  <div class="text-end">
    <?php
    $statusMap = ['waiting'=>'⏳ Menunggu','team_assign'=>'👥 Pembagian Tim','discussion_role'=>'💬 Diskusi Role',
                  'discussion_strategy'=>'🗺️ Diskusi Strategi','battle'=>'⚔️ Battle!','finished'=>'🏁 Selesai'];
    ?>
    <span class="badge rounded-pill px-3 py-2 mb-1 d-block" style="background:rgba(255,255,255,0.2);font-size:0.85rem;" id="status-chip">
      <?= $statusMap[$sessionStatus] ?? $sessionStatus ?>
    </span>
    <span class="timer-badge d-none" id="hero-timer">—</span>
  </div>
</div>

<div class="pv-layout">
  <!-- Main Column -->
  <div>

    <!-- ===== ROLE SELECTION (discussion_role only) ===== -->
    <?php if ($sessionStatus === 'discussion_role' && !$myRoleId): ?>
    <div class="panel-card" id="role-select-panel">
      <div class="panel-header">
        <h6>🎭 Pilih Role-mu!</h6>
        <span class="timer-badge" id="role-timer">02:00</span>
      </div>
      <div class="panel-body">
        <p class="text-muted small mb-3">Diskusikan dengan timmu dan pilih role yang sesuai. Dalam satu tim, usahakan setiap role berbeda.</p>
        <div class="row g-3" id="role-grid">
          <?php foreach ($gameRoles as $role): ?>
          <div class="col-6 col-md-4">
            <div class="role-card <?= $myRoleId == $role['id'] ? 'selected' : '' ?>"
                 onclick="selectRole(<?= $role['id'] ?>, this)">
              <div class="role-icon-lg"><?= $roleIcons[$role['name']] ?? '⚡' ?></div>
              <div class="fw-bold mt-1"><?= htmlspecialchars($role['name']) ?></div>
              <div class="d-flex flex-wrap justify-content-center gap-1 mt-2">
                <span class="role-stat" style="background:#dc2626;">ATK <?= $role['base_attack'] ?></span>
                <span class="role-stat" style="background:#16a34a;">HP <?= $role['base_hp'] ?></span>
                <span class="role-stat" style="background:#2563eb;">MOV <?= $role['base_movement'] ?></span>
                <span class="role-stat" style="background:#d97706;">RNG <?= $role['range_cells'] ?></span>
              </div>
              <div class="text-muted mt-2" style="font-size:0.7rem;"><?= htmlspecialchars(mb_strimwidth($role['description']??'',0,80,'…')) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($myRoleId && $sessionStatus === 'discussion_role'): ?>
    <div class="alert border-0 rounded-3 mb-3" style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.3)!important;">
      <i class="bi bi-check-circle-fill text-success me-2"></i>
      Role kamu sudah dikunci: <strong><?= $roleIcons[$myRoleName] ?? '' ?> <?= htmlspecialchars($myRoleName) ?></strong>. Tunggu timer selesai.
    </div>
    <?php endif; ?>

    <!-- Team Members -->
    <div class="panel-card">
      <div class="panel-header">
        <h6>
          <?= $myTeam === 'A' ? '🔵' : ($myTeam === 'B' ? '🔴' : '👥') ?>
          Tim <?= $myTeam ?? 'Saya' ?> — Anggota
        </h6>
        <span class="badge" style="background:<?= $myTeam==='A'?'#1d4ed8':'#b91c1c' ?>;">
          <?= count($teamPlayers) ?> Player
        </span>
      </div>
      <div class="panel-body" id="team-list">
        <?php foreach ($teamPlayers as $p):
          $maxHp = (int)($p['base_hp'] ?: 12);
          $curHp = (int)($p['current_hp'] ?? $maxHp);
          $pct   = $maxHp > 0 ? ($curHp / $maxHp * 100) : 0;
          $cls   = $pct > 50 ? '' : ($pct > 25 ? 'low' : 'crit');
          $dead  = !$p['is_alive'];
        ?>
        <div class="teammate-card team-<?= strtolower($p['team'] ?? 'a') ?> <?= $dead ? 'dead' : '' ?>">
          <div style="font-size:1.8rem;"><?= $roleIcons[$p['role_name'] ?? ''] ?? '❓' ?></div>
          <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2">
              <span class="fw-bold"><?= htmlspecialchars($p['user_name']) ?></span>
              <?php if ($p['id'] == $myId): ?><span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:0.65rem;">KAMU</span><?php endif; ?>
              <?php if ($dead): ?><span class="badge bg-danger" style="font-size:0.65rem;">💀 Gugur</span><?php endif; ?>
              <?php if ($p['turn_order']): ?><span class="badge bg-light text-muted" style="font-size:0.65rem;">Giliran #<?= $p['turn_order'] ?></span><?php endif; ?>
            </div>
            <div class="text-muted small"><?= $p['role_name'] ? ($roleIcons[$p['role_name']]??'').' '.$p['role_name'] : 'Belum pilih role' ?></div>
            <!-- Stats row -->
            <?php if ($p['role_name']): ?>
            <div class="d-flex gap-2 mt-1 flex-wrap">
              <small class="text-danger fw-bold">ATK <?= $p['base_attack'] ?></small>
              <small class="text-primary fw-bold">MOV <?= $p['base_movement'] ?></small>
              <small class="text-warning fw-bold">RNG <?= $p['range_cells'] ?></small>
            </div>
            <?php endif; ?>
          </div>
          <div class="text-end">
            <div class="fw-bold text-<?= $cls === 'crit' ? 'danger' : ($cls === 'low' ? 'warning' : 'success') ?>">
              <?= $curHp ?>/<?= $maxHp ?> HP
            </div>
            <div class="hp-bar-sm mt-1">
              <div class="hp-fill-sm <?= $cls ?>" style="width:<?= $pct ?>%"></div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($teamPlayers)): ?>
          <p class="text-muted text-center small">Belum ada pembagian tim.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Role Details Modal Button -->
    <div class="panel-card">
      <div class="panel-header"><h6><i class="bi bi-book me-2"></i> Referensi Role</h6></div>
      <div class="panel-body">
        <div class="row g-2">
          <?php foreach ($gameRoles as $role): ?>
          <div class="col-4 col-md-2">
            <button class="btn btn-sm w-100 py-2 text-center border" style="border-radius:10px;"
                    data-bs-toggle="modal" data-bs-target="#roleDetailModal"
                    data-role='<?= htmlspecialchars(json_encode($role)) ?>'
                    onclick="showRoleDetail(this)">
              <div style="font-size:1.5rem;"><?= $roleIcons[$role['name']] ?? '⚡' ?></div>
              <div class="small fw-bold" style="font-size:0.7rem;"><?= htmlspecialchars($role['name']) ?></div>
            </button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column -->
  <div>

    <!-- Status / Timer -->
    <div class="panel-card">
      <div class="panel-header"><h6><i class="bi bi-clock me-1"></i> Status Game</h6></div>
      <div class="panel-body text-center">
        <div id="status-display" class="fw-bold fs-5 mb-2">
          <?= $statusMap[$sessionStatus] ?? $sessionStatus ?>
        </div>
        <?php if ($sessionStatus === 'battle'): ?>
        <div class="mb-1 text-muted small fw-bold">GILIRAN SEKARANG</div>
        <div class="fw-bold" id="pv-current-turn">—</div>
        <div class="text-muted small mt-1" id="pv-current-role">—</div>
        <div class="timer-badge mt-2" id="pv-battle-timer">01:00</div>
        <?php elseif (in_array($sessionStatus, ['discussion_role', 'discussion_strategy'])): ?>
        <div class="text-muted small fw-bold mb-1">SISA WAKTU DISKUSI</div>
        <div class="timer-badge" id="pv-discussion-timer">02:00</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Turn Order -->
    <?php if ($sessionStatus !== 'waiting' && $sessionStatus !== 'team_assign'): ?>
    <div class="panel-card">
      <div class="panel-header"><h6><i class="bi bi-list-ol me-1"></i> Urutan Giliran</h6></div>
      <div class="panel-body p-2" id="pv-turn-order">
        <?php
        usort($allPlayers, fn($a,$b) => ($a['turn_order']??999)<=>($b['turn_order']??999));
        foreach ($allPlayers as $p):
          if (!$p['turn_order']) continue;
        ?>
        <div class="turn-item <?= !$p['is_alive'] ? 'dead-turn' : '' ?>" id="turn-<?= $p['id'] ?>">
          <span class="fw-black" style="color:#7c3aed;min-width:22px;"><?= $p['turn_order'] ?></span>
          <span class="badge" style="background:<?= $p['team']==='A'?'#1d4ed8':'#b91c1c' ?>;font-size:0.65rem;">T<?= $p['team'] ?></span>
          <span class="small"><?= htmlspecialchars(mb_strimwidth($p['user_name'],0,12,'…')) ?></span>
          <span class="ms-auto text-muted" style="font-size:1rem;"><?= $roleIcons[$p['role_name']] ?? '' ?></span>
          <?php if (!$p['is_alive']): ?><span>💀</span><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Lifetime Stats -->
    <div class="panel-card">
      <div class="panel-header"><h6><i class="bi bi-trophy me-1"></i> Statistik Kamu</h6></div>
      <div class="panel-body">
        <?php if ($userStats['total_match'] > 0): ?>
        <div class="row g-2 text-center">
          <div class="col-4">
            <div class="fw-black" style="font-size:1.5rem;color:#7c3aed;"><?= $userStats['total_match'] ?></div>
            <div class="text-muted small">Match</div>
          </div>
          <div class="col-4">
            <div class="fw-black" style="font-size:1.5rem;color:#4ade80;"><?= $userStats['total_win'] ?></div>
            <div class="text-muted small">Win</div>
          </div>
          <div class="col-4">
            <div class="fw-black" style="font-size:1.5rem;color:#f87171;"><?= $userStats['total_lose'] ?></div>
            <div class="text-muted small">Lose</div>
          </div>
          <div class="col-4">
            <div class="fw-black" style="font-size:1.3rem;color:#fbbf24;"><?= $userStats['total_kill'] ?></div>
            <div class="text-muted small">Kills</div>
          </div>
          <div class="col-4">
            <div class="fw-black" style="font-size:1.3rem;color:#ef4444;"><?= $userStats['total_damage_given'] ?></div>
            <div class="text-muted small">Damage</div>
          </div>
          <div class="col-4">
            <div class="fw-black" style="font-size:1.3rem;color:#6b7280;"><?= $userStats['total_death'] ?></div>
            <div class="text-muted small">Deaths</div>
          </div>
        </div>
        <?php else: ?>
        <p class="text-muted small text-center">Belum ada statistik.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Battle Log -->
    <div class="panel-card">
      <div class="panel-header"><h6><i class="bi bi-journal-text me-1"></i> Battle Log</h6></div>
      <div class="panel-body p-2" id="pv-battle-log" style="max-height:300px;overflow-y:auto;">
        <?php foreach ($battleLogs as $log): ?>
        <div class="log-item log-<?= $log['action_type'] ?>">
          <strong><?= htmlspecialchars($log['actor_name']) ?></strong>
          <?php if ($log['action_type'] === 'attack'): ?>
            ⚔️ menyerang <strong><?= htmlspecialchars($log['target_name'] ?? '?') ?></strong>
            — <span class="text-danger fw-bold"><?= $log['damage_dealt'] ?> DMG</span>
          <?php elseif ($log['action_type'] === 'move'): ?>
            🦶 bergerak
          <?php elseif ($log['action_type'] === 'auto_skip'): ?>
            ⏰ auto-skip
          <?php else: ?>
            ⏭️ skip
          <?php endif; ?>
          <div style="font-size:0.68rem;color:#9ca3af;"><?= date('H:i:s', strtotime($log['created_at'])) ?></div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($battleLogs)): ?>
        <p class="text-muted small text-center mt-2">Battle belum dimulai.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Role Detail Modal -->
<div class="modal fade" id="roleDetailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius:20px;overflow:hidden;">
      <div class="modal-header" style="background:linear-gradient(135deg,#1e1b4b,#312e81);color:#fff;border:none;">
        <div class="d-flex align-items-center gap-3">
          <div id="modal-role-icon" style="font-size:2.5rem;"></div>
          <div>
            <h5 class="modal-title fw-black mb-0" id="modal-role-name"></h5>
            <div style="opacity:0.7;font-size:0.8rem;">Pilar Legends Role</div>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3 text-center mb-3" id="modal-role-stats"></div>
        <p id="modal-role-desc" class="text-muted small"></p>
      </div>
    </div>
  </div>
</div>

<script>
const SESSION_ID   = <?= $sessionId ?>;
const BASE_URL     = '<?= BASE_URL ?>';
const MY_PS_ID     = <?= $myId ?? 'null' ?>;
const MY_TEAM      = '<?= $myTeam ?>';
const roleIcons    = {Fighter:'⚔️',Marksman:'🏹',Assassin:'🗡️',Wizard:'🧙',Tank:'🛡️',Cleric:'✨'};
const statusMap    = { waiting:'⏳ Menunggu', team_assign:'👥 Pembagian Tim', discussion_role:'💬 Diskusi Role',
  discussion_strategy:'🗺️ Diskusi Strategi', battle:'⚔️ Battle!', finished:'🏁 Selesai' };
let currentStatus  = '<?= $sessionStatus ?>';

// ===== ROLE DETAIL MODAL =====
function showRoleDetail(btn) {
  const role = JSON.parse(btn.dataset.role);
  const icons = {Fighter:'⚔️',Marksman:'🏹',Assassin:'🗡️',Wizard:'🧙',Tank:'🛡️',Cleric:'✨'};
  document.getElementById('modal-role-icon').textContent = icons[role.name] || '⚡';
  document.getElementById('modal-role-name').textContent = role.name;
  document.getElementById('modal-role-desc').textContent = role.description || '';
  document.getElementById('modal-role-stats').innerHTML = `
    <div class="col-3"><div style="font-size:1.5rem;font-weight:900;color:#dc2626;">${role.base_attack}</div><div class="text-muted small">Base ATK</div><div class="text-muted" style="font-size:0.65rem;">(+🎲 dadu)</div></div>
    <div class="col-3"><div style="font-size:1.5rem;font-weight:900;color:#16a34a;">${role.base_hp}</div><div class="text-muted small">Base HP</div></div>
    <div class="col-3"><div style="font-size:1.5rem;font-weight:900;color:#2563eb;">${role.base_movement}</div><div class="text-muted small">Base MOV</div><div class="text-muted" style="font-size:0.65rem;">(+🎲 dadu)</div></div>
    <div class="col-3"><div style="font-size:1.5rem;font-weight:900;color:#d97706;">${role.range_cells}</div><div class="text-muted small">Range</div><div class="text-muted" style="font-size:0.65rem;">(tetap)</div></div>
  `;
}

// ===== ROLE SELECTION =====
async function selectRole(roleId, cardEl) {
  if (!MY_PS_ID) return alert('Error: player session tidak ditemukan.');
  document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
  cardEl.classList.add('selected');
  const fd = new FormData();
  fd.append('player_session_id', MY_PS_ID);
  fd.append('role_id', roleId);
  const r = await fetch(BASE_URL + '/pilar-legends?action=set-player-role', { method:'POST', body:fd });
  const res = await r.json();
  if (res.success) {
    setTimeout(() => location.reload(), 1000);
  } else {
    alert(res.message || 'Gagal memilih role.');
  }
}

// ===== POLLING =====
function pollState() {
  fetch(BASE_URL + '/pilar-legends?action=poll-state&session_id=' + SESSION_ID)
    .then(r => r.json())
    .then(data => { if (!data.error) updateUI(data); })
    .catch(e => console.error('Poll:', e));
}

function updateUI(data) {
  const session = data.session;
  currentStatus = session.status;

  // Status chip
  document.getElementById('status-chip').textContent = statusMap[session.status] || session.status;
  const statusDisplay = document.getElementById('status-display');
  if (statusDisplay) statusDisplay.textContent = statusMap[session.status] || session.status;

  // Timer displays
  if (data.discussion_timer_remaining !== null) {
    updateTimerEl('pv-discussion-timer', data.discussion_timer_remaining);
    updateTimerEl('role-timer', data.discussion_timer_remaining);
  }
  if (data.turn_timer_remaining !== null) {
    updateTimerEl('pv-battle-timer', data.turn_timer_remaining);
  }

  // Battle: current turn
  if (session.status === 'battle' && data.current_turn_player) {
    const p = data.current_turn_player;
    const nameEl = document.getElementById('pv-current-turn');
    if (nameEl) nameEl.textContent = p.user_name + ' (Tim ' + p.team + ')';
    const roleEl = document.getElementById('pv-current-role');
    if (roleEl) roleEl.textContent = (roleIcons[p.role_name]||'') + ' ' + (p.role_name||'');

    // Highlight current turn in turn-order list
    document.querySelectorAll('.turn-item').forEach(el => el.classList.remove('is-current'));
    const curEl = document.getElementById('turn-' + p.id);
    if (curEl) curEl.classList.add('is-current');
  }

  // Update battle log
  updateBattleLog(data.battle_logs);

  // Update team members
  updateTeamPanel(data.players);
  updateTurnOrderPanel(data.players, session.current_turn_player_session_id);

  // Game finished
  if (session.status === 'finished') {
    clearInterval(pollInterval);
    alert('🏁 Game selesai! Pemenang: Tim ' + (session.winner_team || 'Tidak ada'));
    location.reload();
  }
}

function updateTimerEl(id, remaining) {
  const el = document.getElementById(id);
  if (!el) return;
  remaining = Math.max(0, remaining);
  const m = String(Math.floor(remaining/60)).padStart(2,'0');
  const s = String(remaining%60).padStart(2,'0');
  el.textContent = m+':'+s;
  el.className = el.className.replace(' danger','') + (remaining<=10?' danger':'');
}

function escHtml(s) {
  if (!s) return '';
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function updateBattleLog(logs) {
  const el = document.getElementById('pv-battle-log');
  if (!el || !logs) return;
  let html = '';
  logs.forEach(log => {
    let desc = '';
    if (log.action_type==='attack') desc = `⚔️ menyerang <strong>${escHtml(log.target_name||'?')}</strong> — <span class="text-danger fw-bold">${log.damage_dealt} DMG</span>`;
    else if (log.action_type==='move') desc = '🦶 bergerak';
    else if (log.action_type==='auto_skip') desc = '⏰ auto-skip';
    else desc = '⏭️ skip';
    html += `<div class="log-item log-${log.action_type}"><strong>${escHtml(log.actor_name)}</strong> ${desc}<div style="font-size:.68rem;color:#9ca3af;">${log.created_at.substring(11,19)}</div></div>`;
  });
  el.innerHTML = html || '<p class="text-muted small text-center mt-2">Belum ada aksi.</p>';
}

function updateTeamPanel(players) {
  const el = document.getElementById('team-list');
  if (!el) return;
  const myTeamPlayers = players.filter(p => p.team === MY_TEAM);
  if (myTeamPlayers.length === 0) return;
  let html = '';
  myTeamPlayers.forEach(p => {
    const maxHp = parseInt(p.base_hp)||12;
    const curHp = parseInt(p.current_hp)??maxHp;
    const pct   = maxHp>0 ? curHp/maxHp*100 : 0;
    const cls   = pct>50?'':(pct>25?'low':'crit');
    const dead  = !parseInt(p.is_alive);
    html += `<div class="teammate-card team-${(p.team||'a').toLowerCase()} ${dead?'dead':''}">
      <div style="font-size:1.8rem;">${roleIcons[p.role_name]||'❓'}</div>
      <div class="flex-grow-1">
        <div class="d-flex align-items-center gap-1 flex-wrap">
          <span class="fw-bold">${escHtml(p.user_name)}</span>
          ${p.id==MY_PS_ID?'<span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.65rem;">KAMU</span>':''}
          ${dead?'<span class="badge bg-danger" style="font-size:.65rem;">💀</span>':''}
          ${p.turn_order?`<span class="badge bg-light text-muted" style="font-size:.65rem;">Giliran #${p.turn_order}</span>`:''}
        </div>
        <div class="text-muted small">${p.role_name?(roleIcons[p.role_name]||'')+' '+p.role_name:'Belum pilih role'}</div>
        ${p.role_name?`<div class="d-flex gap-2 mt-1 flex-wrap"><small class="text-danger fw-bold">ATK ${p.base_attack}</small><small class="text-primary fw-bold">MOV ${p.base_movement}</small><small class="text-warning fw-bold">RNG ${p.range_cells}</small></div>`:''}
      </div>
      <div class="text-end">
        <div class="fw-bold text-${cls==='crit'?'danger':cls==='low'?'warning':'success'}">${curHp}/${maxHp} HP</div>
        <div class="hp-bar-sm mt-1"><div class="hp-fill-sm ${cls}" style="width:${pct}%"></div></div>
      </div>
    </div>`;
  });
  el.innerHTML = html;
}

function updateTurnOrderPanel(players, currentTurnId) {
  const el = document.getElementById('pv-turn-order');
  if (!el) return;
  const sorted = [...players].sort((a,b)=>(parseInt(a.turn_order)||999)-(parseInt(b.turn_order)||999));
  let html = '';
  sorted.forEach(p => {
    if (!p.turn_order) return;
    const isCurrent = parseInt(p.id) === parseInt(currentTurnId);
    const dead = !parseInt(p.is_alive);
    html += `<div class="turn-item ${isCurrent?'is-current':''} ${dead?'dead-turn':''}" id="turn-${p.id}">
      <span class="fw-black" style="color:#7c3aed;min-width:22px;">${p.turn_order}</span>
      <span class="badge" style="background:${p.team==='A'?'#1d4ed8':'#b91c1c'};font-size:.65rem;">T${p.team}</span>
      <span class="small">${escHtml((p.user_name||'').substring(0,12))}${(p.user_name||'').length>12?'…':''}</span>
      <span class="ms-auto">${roleIcons[p.role_name]||''}</span>
      ${dead?'<span>💀</span>':''}
    </div>`;
  });
  el.innerHTML = html || '<p class="text-muted small text-center">Urutan belum ditetapkan.</p>';
}

const pollInterval = setInterval(pollState, 3000);
pollState();
</script>
