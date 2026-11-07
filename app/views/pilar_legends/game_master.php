<?php
$sessionId     = $session['id'];
$sessionStatus = $session['status'];
$gameCode      = $session['game_code'];
$roleIcons     = ['Fighter' => '⚔️', 'Marksman' => '🏹', 'Assassin' => '🗡️', 'Wizard' => '🧙', 'Tank' => '🛡️', 'Cleric' => '✨'];
$statusLabels  = [
  'waiting'             => ['label' => 'Menunggu Player', 'color' => '#f59e0b'],
  'team_assign'         => ['label' => 'Pembagian Tim',   'color' => '#3b82f6'],
  'discussion_role'     => ['label' => 'Diskusi Role',    'color' => '#8b5cf6'],
  'discussion_strategy' => ['label' => 'Diskusi Strategi', 'color' => '#06b6d4'],
  'battle'              => ['label' => 'Battle!',         'color' => '#ef4444'],
  'finished'            => ['label' => 'Selesai',         'color' => '#6b7280'],
];
$st = $statusLabels[$sessionStatus] ?? ['label' => $sessionStatus, 'color' => '#6b7280'];
$teamA = array_values(array_filter($players, fn($p) => $p['team'] === 'A'));
$teamB = array_values(array_filter($players, fn($p) => $p['team'] === 'B'));
?>
<style>
  :root {
    --accent: #7c3aed;
    --accent2: #2563eb;
  }

  .gm-layout {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1.5rem;
  }

  @media (max-width: 1199px) {
    .gm-layout {
      grid-template-columns: 1fr;
    }
  }

  .gm-topbar {
    background: linear-gradient(135deg, #1e1b4b, #312e81, #4c1d95);
    border-radius: 16px;
    padding: 1.5rem;
    color: #fff;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border-radius: 50px;
    padding: 6px 18px;
    font-weight: 700;
    font-size: 0.9rem;
    border: 2px solid currentColor;
  }

  .panel-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(124, 58, 237, 0.08);
    overflow: hidden;
    margin-bottom: 1.5rem;
  }

  .panel-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(90deg, #faf5ff, #fff);
  }

  .panel-header h6 {
    font-weight: 800;
    margin: 0;
    color: #1e1b4b;
  }

  .panel-body {
    padding: 1.25rem 1.5rem;
  }

  .player-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
  }

  .player-row:last-child {
    border-bottom: none;
  }

  .player-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7c3aed, #2563eb);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: 0.9rem;
    flex-shrink: 0;
  }

  .player-info {
    flex: 1;
    min-width: 0;
  }

  .player-name {
    font-weight: 700;
    font-size: 0.9rem;
    color: #1e1b4b;
  }

  .player-meta {
    font-size: 0.75rem;
    color: #6b7280;
  }

  .hp-bar-wrap {
    width: 80px;
  }

  .hp-bar {
    height: 6px;
    border-radius: 3px;
    background: #e5e7eb;
    overflow: hidden;
  }

  .hp-fill {
    height: 100%;
    border-radius: 3px;
    background: linear-gradient(90deg, #4ade80, #22c55e);
    transition: width 0.5s;
  }

  .hp-fill.low {
    background: linear-gradient(90deg, #fbbf24, #f59e0b);
  }

  .hp-fill.crit {
    background: linear-gradient(90deg, #f87171, #ef4444);
  }

  .team-badge-a {
    background: rgba(59, 130, 246, 0.1);
    color: #1d4ed8;
    border: 1px solid rgba(59, 130, 246, 0.3);
  }

  .team-badge-b {
    background: rgba(239, 68, 68, 0.1);
    color: #b91c1c;
    border: 1px solid rgba(239, 68, 68, 0.3);
  }

  .action-btn {
    width: 100%;
    padding: 0.75rem;
    border-radius: 12px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .action-btn:hover {
    transform: translateY(-2px);
  }

  .action-btn.primary-action {
    background: linear-gradient(135deg, #7c3aed, #2563eb);
    color: #fff;
  }

  .action-btn.danger-action {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: #fff;
  }

  .action-btn.success-action {
    background: linear-gradient(135deg, #059669, #047857);
    color: #fff;
  }

  .action-btn.secondary-action {
    background: #f3f4f6;
    color: #374151;
  }

  .action-btn.warning-action {
    background: linear-gradient(135deg, #d97706, #b45309);
    color: #fff;
  }

  .qr-code-box {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    border: 2px dashed rgba(124, 58, 237, 0.2);
  }

  .game-code-display {
    font-size: 2.5rem;
    font-weight: 900;
    letter-spacing: 8px;
    color: #1e1b4b;
    font-family: monospace;
    background: #f5f3ff;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    display: inline-block;
    margin: 0.5rem 0;
  }

  .log-item {
    padding: 8px 12px;
    border-radius: 8px;
    margin-bottom: 6px;
    font-size: 0.8rem;
    line-height: 1.4;
  }

  .log-attack {
    background: rgba(239, 68, 68, 0.08);
    border-left: 3px solid #ef4444;
  }

  .log-move {
    background: rgba(59, 130, 246, 0.08);
    border-left: 3px solid #3b82f6;
  }

  .log-skip {
    background: rgba(107, 114, 128, 0.08);
    border-left: 3px solid #9ca3af;
  }

  .log-auto_skip {
    background: rgba(245, 158, 11, 0.08);
    border-left: 3px solid #f59e0b;
  }

  .turn-panel {
    background: linear-gradient(135deg, #fef3c7, #fffbeb);
    border: 2px solid #f59e0b;
    border-radius: 14px;
    padding: 1.25rem;
  }

  .timer-display {
    font-size: 3rem;
    font-weight: 900;
    font-family: monospace;
    text-align: center;
    color: #1e1b4b;
  }

  .timer-display.danger {
    color: #dc2626;
    animation: pulse-danger 1s infinite;
  }

  @keyframes pulse-danger {

    0%,
    100% {
      opacity: 1;
    }

    50% {
      opacity: 0.6;
    }
  }

  .sortable-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .sortable-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    background: #f9fafb;
    border-radius: 10px;
    margin-bottom: 6px;
    cursor: grab;
    border: 2px solid transparent;
    transition: all 0.2s;
    user-select: none;
  }

  .sortable-item.dragging {
    opacity: 0.4;
    border-color: #7c3aed;
  }

  .sortable-item.drag-over {
    border-color: #7c3aed;
    background: #f5f3ff;
  }

  .drag-handle {
    color: #9ca3af;
    cursor: grab;
    font-size: 1.1rem;
  }

  .section-tab {
    display: none;
  }

  .section-tab.active {
    display: block;
  }

  .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
    display: inline-block;
  }

  .qr-holder {
    display: flex;
    justify-content: center;
    margin: 1rem 0;
  }

  .join-link a {
    word-break: break-all;
  }
</style>

<!-- Top Bar -->
<div class="gm-topbar">
  <div>
    <div class="d-flex align-items-center gap-3 mb-1">
      <span style="font-size:1.5rem;">🎮</span>
      <div>
        <h4 class="fw-black mb-0" style="letter-spacing:2px;">PILAR LEGENDS</h4>
        <div style="opacity:0.6;font-size:0.75rem;">Game Master Panel</div>
      </div>
    </div>
  </div>

  <div class="d-flex align-items-center gap-2 flex-wrap">
    <span class="status-badge" style="color:<?= $st['color'] ?>;background:<?= $st['color'] ?>22;">
      <span class="status-dot"></span>
      <span class="status-label"><?= $st['label'] ?></span>
    </span>
    <span class="badge bg-white text-dark fw-bold"><?= count($players) ?> Player(s)</span>
    <a href="<?= htmlspecialchars($dashUrl) ?>" target="_blank"
      class="btn btn-sm btn-outline-light fw-bold">
      <i class="bi bi-display me-1"></i> Show Dashboard
    </a>
  </div>
</div>

<div class="gm-layout">
  <!-- Main Panel (left) -->
  <div>

    <!-- ===== WAITING STATE ===== -->
    <div id="section-waiting" class="section-tab <?= $sessionStatus === 'waiting' ? 'active' : '' ?>">
      <div class="panel-card">
        <div class="panel-header">
          <h6><i class="bi bi-broadcast me-2" style="color:#f59e0b;"></i> Lobby — Menunggu Player</h6>
          <span class="badge bg-warning text-dark"><?= count($players) ?> bergabung</span>
        </div>
        <div class="panel-body">
          <div class="qr-code-box mb-3">
            <div class="text-muted small mb-1">GAME CODE</div>
            <div class="game-code-display"><?= $gameCode ?></div>
            <div id="qrcode" class="qr-holder"></div>
            <div class="text-muted" style="font-size:0.75rem;">Bagikan kode atau scan QR di atas</div>
          </div>
          <div id="player-list-waiting">
            <?php foreach ($players as $p): ?>
              <div class="player-row">
                <div class="player-avatar"><?= strtoupper(substr($p['user_name'], 0, 2)) ?></div>
                <div class="player-info">
                  <div class="player-name"><?= htmlspecialchars($p['user_name']) ?></div>
                  <div class="player-meta">Menunggu pembagian tim...</div>
                </div>
                <span class="badge bg-light text-muted">Belum dibagi</span>
              </div>
            <?php endforeach; ?>
          </div>
          <?php if (count($players) >= 2): ?>
            <div class="mt-3">
              <button class="action-btn primary-action" onclick="changeStatus('team_assign')">
                <i class="bi bi-people-fill"></i> Tutup Lobby & Mulai Pembagian Tim
              </button>
            </div>
          <?php else: ?>
            <div class="alert alert-info border-0 mt-3 small">
              <i class="bi bi-info-circle me-1"></i> Butuh minimal 2 player untuk memulai.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- ===== TEAM ASSIGN STATE ===== -->
    <div id="section-team_assign" class="section-tab <?= $sessionStatus === 'team_assign' ? 'active' : '' ?>">
      <div class="panel-card">
        <div class="panel-header">
          <h6><i class="bi bi-people-fill me-2" style="color:#3b82f6;"></i> Pembagian Tim</h6>
          <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary fw-bold" onclick="randomizeTeams()">
              🎲 Random Teams
            </button>
          </div>
        </div>
        <div class="panel-body">
          <div class="row g-3 mb-3" id="team-assign-list">
            <!-- Populated by JS poll -->
            <?php foreach ($players as $p): ?>
              <div class="col-12">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                  <div class="player-avatar"><?= strtoupper(substr($p['user_name'], 0, 2)) ?></div>
                  <div class="flex-grow-1">
                    <div class="fw-bold"><?= htmlspecialchars($p['user_name']) ?></div>
                  </div>
                  <div class="d-flex gap-2">
                    <button onclick="assignTeam(<?= $p['id'] ?>, 'A')"
                      class="btn btn-sm <?= $p['team'] === 'A' ? 'btn-primary' : 'btn-outline-primary' ?> fw-bold"
                      id="team-btn-<?= $p['id'] ?>-A">Team A</button>
                    <button onclick="assignTeam(<?= $p['id'] ?>, 'B')"
                      class="btn btn-sm <?= $p['team'] === 'B' ? 'btn-danger' : 'btn-outline-danger' ?> fw-bold"
                      id="team-btn-<?= $p['id'] ?>-B">Team B</button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <button class="action-btn success-action" onclick="changeStatus('discussion_role')">
            <i class="bi bi-check-circle-fill"></i> Konfirmasi Tim & Mulai Diskusi Role
          </button>
        </div>
      </div>
    </div>

    <!-- ===== DISCUSSION ROLE STATE ===== -->
    <div id="section-discussion_role" class="section-tab <?= $sessionStatus === 'discussion_role' ? 'active' : '' ?>">
      <div class="panel-card">
        <div class="panel-header">
          <h6><i class="bi bi-person-badge me-2" style="color:#8b5cf6;"></i> Diskusi Role (2 Menit)</h6>
          <div class="timer-display" id="discussion-timer" style="font-size:1.5rem;">--:--</div>
        </div>
        <div class="panel-body">
          <div id="role-selection-list">
            <!-- Updated by polling -->
            <?php foreach ($players as $p): ?>
              <div class="player-row">
                <div class="player-avatar" style="background:<?= $p['team'] === 'A' ? '#1d4ed8' : '#b91c1c' ?>">
                  <?= strtoupper(substr($p['user_name'], 0, 2)) ?>
                </div>
                <div class="player-info">
                  <div class="player-name"><?= htmlspecialchars($p['user_name']) ?></div>
                  <div class="player-meta">Tim <?= $p['team'] ?? '?' ?></div>
                </div>
                <span class="badge <?= $p['pl_role_id'] ? 'bg-success' : 'bg-secondary' ?>">
                  <?= $p['role_name'] ? ($roleIcons[$p['role_name']] ?? '') . ' ' . $p['role_name'] : 'Belum pilih' ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="d-flex gap-2 mt-3">
            <button class="action-btn warning-action flex-grow-1" onclick="autoAssignRoles()">
              🤖 Auto-Assign Sisa Role
            </button>
          </div>
        </div>
      </div>

      <!-- Turn Order Setup -->
      <div class="panel-card">
        <div class="panel-header">
          <h6><i class="bi bi-sort-numeric-down me-2" style="color:#7c3aed;"></i> Atur Urutan Giliran</h6>
          <button class="btn btn-sm btn-outline-secondary" onclick="randomizeTurns()">
            🎲 Random Order
          </button>
        </div>
        <div class="panel-body">
          <p class="text-muted small mb-3">Drag untuk mengatur urutan. Pola: A1 → B1 → A2 → B2 → dst</p>
          <ul class="sortable-list" id="turn-order-list">
            <?php foreach ($players as $idx => $p): ?>
              <li class="sortable-item" data-ps-id="<?= $p['id'] ?>" draggable="true">
                <span class="drag-handle">⠿</span>
                <span class="fw-bold" style="color:#7c3aed;min-width:24px;"><?= $idx + 1 ?></span>
                <span class="badge <?= $p['team'] === 'A' ? 'team-badge-a' : 'team-badge-b' ?>">
                  Tim <?= $p['team'] ?? '?' ?>
                </span>
                <span><?= htmlspecialchars($p['user_name']) ?></span>
                <?php if ($p['role_name']): ?>
                  <span class="ms-auto text-muted small"><?= $roleIcons[$p['role_name']] ?? '' ?> <?= $p['role_name'] ?></span>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
          <button class="action-btn primary-action mt-2" onclick="saveTurnOrder()">
            <i class="bi bi-check2-all"></i> Simpan Urutan & Mulai Diskusi Strategi
          </button>
        </div>
      </div>
    </div>

    <!-- ===== DISCUSSION STRATEGY STATE ===== -->
    <div id="section-discussion_strategy" class="section-tab <?= $sessionStatus === 'discussion_strategy' ? 'active' : '' ?>">
      <div class="panel-card">
        <div class="panel-header">
          <h6><i class="bi bi-map me-2" style="color:#06b6d4;"></i> Diskusi Strategi (2 Menit)</h6>
          <div class="timer-display" id="strategy-timer" style="font-size:1.5rem;">--:--</div>
        </div>
        <div class="panel-body">
          <div class="alert" style="background:rgba(6,182,212,0.08);border:1px solid rgba(6,182,212,0.2);border-radius:12px;">
            <i class="bi bi-map-fill me-2" style="color:#06b6d4;"></i>
            <strong>Instruksi:</strong> Persilahkan setiap tim untuk duduk bersama dan berdiskusi. Tiap tim meletakkan karakter mereka ke peta game yang sudah dicetak.
          </div>

          <!-- Turn Order Display -->
          <h6 class="fw-bold mb-3 mt-2">Urutan Giliran:</h6>
          <div id="turn-order-display">
            <?php
            usort($players, fn($a, $b) => ($a['turn_order'] ?? 999) <=> ($b['turn_order'] ?? 999));
            foreach ($players as $p): if (!$p['turn_order']) continue;
            ?>
              <div class="d-flex align-items-center gap-3 p-2 border-bottom">
                <span class="fw-black" style="color:#7c3aed;width:24px;"><?= $p['turn_order'] ?></span>
                <span class="badge <?= $p['team'] === 'A' ? 'team-badge-a' : 'team-badge-b' ?>">Tim <?= $p['team'] ?></span>
                <span><?= htmlspecialchars($p['user_name']) ?></span>
                <span class="ms-auto text-muted small"><?= $roleIcons[$p['role_name']] ?? '' ?> <?= $p['role_name'] ?></span>
              </div>
            <?php endforeach; ?>
          </div>

          <button class="action-btn danger-action mt-4" onclick="startBattle()">
            <i class="bi bi-fire"></i> ⚔️ MULAI BATTLE!
          </button>
        </div>
      </div>
    </div>

    <!-- ===== BATTLE STATE ===== -->
    <div id="section-battle" class="section-tab <?= $sessionStatus === 'battle' ? 'active' : '' ?>">
      <!-- Current Turn Panel -->
      <div class="turn-panel mb-3" id="current-turn-panel">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div>
            <div class="text-muted small fw-bold">GILIRAN SEKARANG</div>
            <div class="fw-black fs-5" id="current-turn-name">—</div>
            <div class="text-muted small" id="current-turn-role">—</div>
          </div>
          <div>
            <div class="text-muted small text-center fw-bold mb-1">TIMER</div>
            <div class="timer-display" id="battle-timer">01:00</div>
          </div>
        </div>
      </div>

      <!-- Action Input Panel -->
      <div class="panel-card">
        <div class="panel-header">
          <h6><i class="bi bi-controller me-2 text-danger"></i> Input Aksi Player</h6>
        </div>
        <div class="panel-body">
          <input type="hidden" id="cur-ps-id" value="">
          <div class="mb-3">
            <label class="form-label fw-bold small">Tipe Aksi</label>
            <div class="d-flex gap-2">
              <?php foreach (['move' => ['🦶', 'Move', 'primary'], 'attack' => ['⚔️', 'Attack', 'danger'], 'skip' => ['⏭️', 'Skip', 'secondary']] as $k => [$ico, $lbl, $clr]): ?>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="action_type" id="act-<?= $k ?>"
                    value="<?= $k ?>" <?= $k === 'move' ? 'checked' : '' ?> onchange="toggleActionFields()">
                  <label class="form-check-label fw-bold" for="act-<?= $k ?>"><?= $ico ?> <?= $lbl ?></label>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div id="attack-fields" style="display:none;">
            <div class="mb-3">
              <label class="form-label fw-bold small">Target Player</label>
              <select class="form-select" id="target-player-select">
                <option value="">— Pilih Target —</option>
                <?php foreach ($players as $p): if (!$p['is_alive']) continue; ?>
                  <option value="<?= $p['id'] ?>" data-team="<?= $p['team'] ?>">
                    <?= htmlspecialchars($p['user_name']) ?> (Tim <?= $p['team'] ?>) — <?= $p['role_name'] ?> | <?= $p['current_hp'] ?> HP
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold small">Damage</label>
              <input type="number" class="form-control" id="damage-input" min="0" max="99" placeholder="Masukkan damage">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Keterangan (opsional)</label>
            <input type="text" class="form-control" id="action-description" placeholder="contoh: Bergerak ke sel C4, menyerang Player 2...">
          </div>

          <button class="action-btn primary-action" onclick="submitAction()">
            <i class="bi bi-check-circle-fill"></i> Submit & Lanjutkan ke Giliran Berikutnya
          </button>
        </div>
      </div>

      <!-- HP Overview -->
      <div class="panel-card">
        <div class="panel-header">
          <h6><i class="bi bi-heart-pulse me-2 text-danger"></i> Status HP</h6>
        </div>
        <div class="panel-body">
          <div class="row g-3">
            <div class="col-6">
              <div class="text-center fw-bold mb-2" style="color:#1d4ed8;">🔵 TEAM A</div>
              <div id="hp-overview-a">
                <?php foreach ($teamA as $p): ?>
                  <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="small fw-bold" style="min-width:90px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                      title="<?= htmlspecialchars($p['user_name']) ?>"><?= htmlspecialchars(mb_strimwidth($p['user_name'], 0, 10, '…')) ?></span>
                    <div class="flex-grow-1">
                      <div class="hp-bar">
                        <?php
                        $maxHp = $p['base_hp'] ?: 12;
                        $curHp = $p['current_hp'] ?? $maxHp;
                        $pct   = $maxHp > 0 ? ($curHp / $maxHp * 100) : 0;
                        $cls   = $pct > 50 ? '' : ($pct > 25 ? 'low' : 'crit');
                        ?>
                        <div class="hp-fill <?= $cls ?>" style="width:<?= $pct ?>%"></div>
                      </div>
                    </div>
                    <span class="small text-muted"><?= $curHp ?>/<?= $maxHp ?></span>
                    <?php if (!$p['is_alive']): ?><span class="badge bg-danger">💀</span><?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center fw-bold mb-2" style="color:#b91c1c;">🔴 TEAM B</div>
              <div id="hp-overview-b">
                <?php foreach ($teamB as $p): ?>
                  <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="small fw-bold" style="min-width:90px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                      title="<?= htmlspecialchars($p['user_name']) ?>"><?= htmlspecialchars(mb_strimwidth($p['user_name'], 0, 10, '…')) ?></span>
                    <div class="flex-grow-1">
                      <div class="hp-bar">
                        <?php
                        $maxHp = $p['base_hp'] ?: 12;
                        $curHp = $p['current_hp'] ?? $maxHp;
                        $pct   = $maxHp > 0 ? ($curHp / $maxHp * 100) : 0;
                        $cls   = $pct > 50 ? '' : ($pct > 25 ? 'low' : 'crit');
                        ?>
                        <div class="hp-fill <?= $cls ?>" style="width:<?= $pct ?>%"></div>
                      </div>
                    </div>
                    <span class="small text-muted"><?= $curHp ?>/<?= $maxHp ?></span>
                    <?php if (!$p['is_alive']): ?><span class="badge bg-danger">💀</span><?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <button class="action-btn danger-action mt-3" onclick="confirmFinishGame()">
            <i class="bi bi-flag-fill"></i> Akhiri Game
          </button>
        </div>
      </div>
    </div>

    <!-- ===== FINISHED STATE ===== -->
    <div id="section-finished" class="section-tab <?= $sessionStatus === 'finished' ? 'active' : '' ?>">
      <div class="panel-card">
        <div class="panel-body text-center py-5">
          <div style="font-size:4rem;">🏆</div>
          <h3 class="fw-black mt-2">GAME SELESAI!</h3>
          <?php if ($session['winner_team']): ?>
            <div class="my-3">
              <span class="badge rounded-pill px-4 py-3 fs-5 fw-bold"
                style="background:linear-gradient(135deg,#7c3aed,#2563eb);">
                🥇 WINNER: TEAM <?= $session['winner_team'] ?>
              </span>
            </div>
          <?php endif; ?>
          <a href="<?= BASE_URL ?>/games" class="btn btn-outline-primary rounded-pill px-4 mt-3">
            <i class="bi bi-house me-2"></i> Kembali ke Games
          </a>
        </div>
      </div>
    </div>

  </div><!-- /main panel -->

  <!-- Right Sidebar -->
  <div>
    <!-- Game Code (always visible) -->
    <div class="panel-card">
      <div class="panel-header">
        <h6>🔑 Game Code</h6>
      </div>
      <div class="panel-body text-center">
        <div class="game-code-display mb-2"><?= $gameCode ?></div>
        <div id="qr-sidebar" class="qr-holder"></div>
        <small class="text-muted d-block mt-2 join-link">
          Join link: <a href="<?= htmlspecialchars($joinUrl) ?>" target="_blank" style="font-size:0.7rem;"><?= htmlspecialchars($joinUrl) ?></a>
        </small>
      </div>
    </div>

    <!-- Battle Log -->
    <div class="panel-card">
      <div class="panel-header">
        <h6><i class="bi bi-journal-text me-1"></i> Battle Log</h6>
        <span class="badge bg-secondary" id="log-count">0</span>
      </div>
      <div class="panel-body p-2" id="battle-log-sidebar" style="max-height:350px;overflow-y:auto;">
        <?php foreach ($battleLogs as $log): ?>
          <div class="log-item log-<?= $log['action_type'] ?>">
            <strong><?= htmlspecialchars($log['actor_name']) ?></strong>
            <?php if ($log['action_type'] === 'attack'): ?>
              ⚔️ menyerang <strong><?= htmlspecialchars($log['target_name'] ?? '?') ?></strong>
              — <span class="text-danger fw-bold"><?= $log['damage_dealt'] ?> DMG</span>
            <?php elseif ($log['action_type'] === 'move'): ?>
              🦶 bergerak
            <?php elseif ($log['action_type'] === 'auto_skip'): ?>
              ⏰ auto-skip (waktu habis)
            <?php else: ?>
              ⏭️ skip giliran
            <?php endif; ?>
            <?php if ($log['description']): ?>
              <div class="text-muted" style="font-size:0.72rem;"><?= htmlspecialchars($log['description']) ?></div>
            <?php endif; ?>
            <div style="font-size:0.7rem;color:#9ca3af;"><?= date('H:i:s', strtotime($log['created_at'])) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
  const SESSION_ID = <?= $sessionId ?>;
  const BASE_URL = '<?= BASE_URL ?>';
  const CSRF_TOKEN = '<?= csrf_token() ?>';
  const GAME_CODE = '<?= $gameCode ?>';
  const JOIN_URL = '<?= addslashes($joinUrl) ?>';
  let currentStatus = '<?= $sessionStatus ?>';
  let pollInterval;
  let timerInterval;
  let turnOrderDirty = false;
  const STATUS_INFO = <?= json_encode($statusLabels) ?>;

  // QR Code generation
  try {
    new QRCode(document.getElementById('qrcode'), {
      text: JOIN_URL,
      width: 160,
      height: 160,
      colorDark: '#1e1b4b',
      colorLight: '#ffffff'
    });
    new QRCode(document.getElementById('qr-sidebar'), {
      text: JOIN_URL,
      width: 130,
      height: 130,
      colorDark: '#1e1b4b',
      colorLight: '#ffffff'
    });
  } catch (e) {
    console.log('QR error:', e);
  }

  // ===== STATE MACHINE =====
  function showSection(status) {
    document.querySelectorAll('.section-tab').forEach(el => el.classList.remove('active'));
    const section = document.getElementById('section-' + status);
    if (section) section.classList.add('active');
    currentStatus = status;
  }

  // ===== POLLING =====
  function pollState() {
    fetch(BASE_URL + '/pilar-legends?action=poll-state&session_id=' + SESSION_ID)
      .then(r => r.json())
      .then(data => {
        if (data.error) return;
        updateUI(data);
      })
      .catch(e => console.error('Poll error:', e));
  }

  function updateUI(data) {
    const session = data.session;
    const players = data.players;
    const logs = data.battle_logs;

    showSection(session.status);

    // Update status badge (teks + warna)
    const st = STATUS_INFO[session.status] || {
      label: session.status,
      color: '#6b7280'
    };
    document.querySelectorAll('.status-badge').forEach(badge => {
      badge.style.color = st.color;
      badge.style.background = st.color + '22';
      const label = badge.querySelector('.status-label');
      if (label) label.textContent = st.label;
    });

    // Update player count
    const pcBadge = document.querySelector('.gm-topbar .badge.bg-white');
    if (pcBadge) pcBadge.textContent = players.length + ' Player(s)';

    // Update timers
    if (data.discussion_timer_remaining !== null) {
      updateTimerDisplay('discussion-timer', data.discussion_timer_remaining);
      updateTimerDisplay('strategy-timer', data.discussion_timer_remaining);
    }
    if (data.turn_timer_remaining !== null) {
      updateTimerDisplay('battle-timer', data.turn_timer_remaining);
    }

    // Update current turn
    if (session.current_turn_player_session_id && data.current_turn_player) {
      const p = data.current_turn_player;
      const el = document.getElementById('cur-ps-id');
      if (el) el.value = p.id;
      const nameEl = document.getElementById('current-turn-name');
      if (nameEl) nameEl.textContent = p.user_name + ' (Tim ' + p.team + ')';
      const roleEl = document.getElementById('current-turn-role');
      if (roleEl) roleEl.textContent = (p.role_name || 'Belum ada role') + ' | HP: ' + p.current_hp;
    }

    // Update battle log
    updateBattleLog(logs);

    // Update HP overview for battle
    updateHpOverview(players, 'A', 'hp-overview-a');
    updateHpOverview(players, 'B', 'hp-overview-b');

    // Update turn order sortable list labels
    updateTurnOrderList(players);

    // Update team assign buttons
    updateTeamAssign(players);

    // Update role selection list
    updateRoleList(players);

    // Update target select options
    updateTargetSelect(players);

    // Game over
    if (session.status === 'finished') {
      clearInterval(pollInterval);
      clearInterval(timerInterval);
      if (session.winner_team) {
        const panel = document.querySelector('#section-finished .panel-body');
        if (panel) {
          const winnerEl = panel.querySelector('.badge');
          if (winnerEl) winnerEl.textContent = '🥇 WINNER: TEAM ' + session.winner_team;
        }
      }
    }
  }

  function updateTimerDisplay(id, remaining) {
    const el = document.getElementById(id);
    if (!el) return;
    remaining = Math.max(0, remaining);
    const m = String(Math.floor(remaining / 60)).padStart(2, '0');
    const s = String(remaining % 60).padStart(2, '0');
    el.textContent = m + ':' + s;
    el.className = 'timer-display' + (remaining <= 10 ? ' danger' : '');
  }

  function updateBattleLog(logs) {
    const container = document.getElementById('battle-log-sidebar');
    const countEl = document.getElementById('log-count');
    if (!container) return;
    if (countEl) countEl.textContent = logs.length;

    const typeMap = {
      attack: '⚔️',
      move: '🦶',
      skip: '⏭️',
      auto_skip: '⏰'
    };
    let html = '';
    logs.forEach(log => {
      let desc = typeMap[log.action_type] || '';
      if (log.action_type === 'attack') {
        desc += ` menyerang <strong>${escHtml(log.target_name || '?')}</strong> — <span class="text-danger fw-bold">${log.damage_dealt} DMG</span>`;
      } else if (log.action_type === 'move') {
        desc += ' bergerak';
      } else if (log.action_type === 'auto_skip') {
        desc += ' auto-skip (waktu habis)';
      } else {
        desc += ' skip giliran';
      }
      const time = log.created_at.substring(11, 19);
      html += `<div class="log-item log-${log.action_type}"><strong>${escHtml(log.actor_name)}</strong> ${desc}`;
      if (log.description) html += `<div class="text-muted" style="font-size:.72rem;">${escHtml(log.description)}</div>`;
      html += `<div style="font-size:.7rem;color:#9ca3af;">${time}</div></div>`;
    });
    container.innerHTML = html || '<p class="text-muted text-center small mt-2">Belum ada aksi.</p>';
  }

  function updateHpOverview(players, team, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const filtered = players.filter(p => p.team === team);
    let html = '';
    filtered.forEach(p => {
      const maxHp = parseInt(p.base_hp) || 12;
      const curHp = parseInt(p.current_hp) ?? maxHp;
      const pct = maxHp > 0 ? (curHp / maxHp * 100) : 0;
      const cls = pct > 50 ? '' : (pct > 25 ? 'low' : 'crit');
      const dead = !parseInt(p.is_alive);
      html += `<div class="d-flex align-items-center gap-2 mb-2">
      <span class="small fw-bold" style="min-width:90px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${escHtml(p.user_name)}">${escHtml(p.user_name.substring(0,10))}${p.user_name.length>10?'…':''}</span>
      <div class="flex-grow-1"><div class="hp-bar"><div class="hp-fill ${cls}" style="width:${pct}%"></div></div></div>
      <span class="small text-muted">${curHp}/${maxHp}</span>
      ${dead ? '<span class="badge bg-danger">💀</span>' : ''}
    </div>`;
    });
    container.innerHTML = html || '<p class="text-muted small">Tidak ada player.</p>';
  }

  function updateTurnOrderList(players) {
    if (turnOrderDirty) return;
    // Update labels of sortable items (in case player count changed)
    const sorted = [...players].sort((a, b) => (parseInt(a.turn_order) || 999) - (parseInt(b.turn_order) || 999));
    const list = document.getElementById('turn-order-list');
    if (!list || list.children.length === 0) return;

    const icons = {
      Fighter: '⚔️',
      Marksman: '🏹',
      Assassin: '🗡️',
      Wizard: '🧙',
      Tank: '🛡️',
      Cleric: '✨'
    };
    // Re-render turn order list
    let html = '';
    sorted.forEach((p, idx) => {
      const icon = p.role_name ? (icons[p.role_name] || '⚡') + ' ' + p.role_name : '';
      html += `<li class="sortable-item" data-ps-id="${p.id}" draggable="true">
      <span class="drag-handle">⠿</span>
      <span class="fw-bold" style="color:#7c3aed;min-width:24px;">${idx+1}</span>
      <span class="badge ${p.team==='A'?'team-badge-a':'team-badge-b'}">Tim ${p.team||'?'}</span>
      <span>${escHtml(p.user_name)}</span>
      ${p.role_name ? `<span class="ms-auto text-muted small">${icon}</span>` : ''}
    </li>`;
    });
    list.innerHTML = html;
    initSortable();
  }

  function updateTeamAssign(players) {
    const container = document.getElementById('team-assign-list');
    if (!container) return;
    let html = '';
    players.forEach(p => {
      html += `<div class="col-12">
      <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
        <div class="player-avatar" style="background:${p.team==='A'?'#1d4ed8':p.team==='B'?'#b91c1c':'#6b7280'}">${p.user_name.substring(0,2).toUpperCase()}</div>
        <div class="flex-grow-1"><div class="fw-bold">${escHtml(p.user_name)}</div></div>
        <div class="d-flex gap-2">
          <button onclick="assignTeam(${p.id},'A')" class="btn btn-sm ${p.team==='A'?'btn-primary':'btn-outline-primary'} fw-bold">Team A</button>
          <button onclick="assignTeam(${p.id},'B')" class="btn btn-sm ${p.team==='B'?'btn-danger':'btn-outline-danger'} fw-bold">Team B</button>
        </div>
      </div>
    </div>`;
    });
    container.innerHTML = html;
  }

  function updateRoleList(players) {
    const container = document.getElementById('role-selection-list');
    if (!container) return;
    const icons = {
      Fighter: '⚔️',
      Marksman: '🏹',
      Assassin: '🗡️',
      Wizard: '🧙',
      Tank: '🛡️',
      Cleric: '✨'
    };
    let html = '';
    players.forEach(p => {
      html += `<div class="player-row">
      <div class="player-avatar" style="background:${p.team==='A'?'#1d4ed8':'#b91c1c'}">${p.user_name.substring(0,2).toUpperCase()}</div>
      <div class="player-info">
        <div class="player-name">${escHtml(p.user_name)}</div>
        <div class="player-meta">Tim ${p.team||'?'}</div>
      </div>
      <span class="badge ${p.pl_role_id?'bg-success':'bg-secondary'}">
        ${p.role_name ? (icons[p.role_name]||'')+ ' ' + p.role_name : 'Belum pilih'}
      </span>
    </div>`;
    });
    container.innerHTML = html;
  }

  function updateTargetSelect(players) {
    const sel = document.getElementById('target-player-select');
    if (!sel) return;
    const alive = players.filter(p => parseInt(p.is_alive));
    sel.innerHTML = '<option value="">— Pilih Target —</option>';
    alive.forEach(p => {
      sel.innerHTML += `<option value="${p.id}" data-team="${p.team}">${escHtml(p.user_name)} (Tim ${p.team}) — ${p.role_name||'?'} | ${p.current_hp} HP</option>`;
    });
  }

  function escHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  // ===== DRAG & DROP SORTABLE =====
  let dragSrc = null;

  function initSortable() {
    const list = document.getElementById('turn-order-list');
    if (!list) return;
    list.querySelectorAll('.sortable-item').forEach(item => {
      item.addEventListener('dragstart', function(e) {
        dragSrc = this;
        turnOrderDirty = true;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
      });
      item.addEventListener('dragend', function() {
        this.classList.remove('dragging');
        list.querySelectorAll('.sortable-item').forEach(el => el.classList.remove('drag-over'));
        renumberList();
      });
      item.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        if (this !== dragSrc) {
          list.querySelectorAll('.sortable-item').forEach(el => el.classList.remove('drag-over'));
          this.classList.add('drag-over');
        }
      });
      item.addEventListener('drop', function(e) {
        e.preventDefault();
        if (this !== dragSrc) {
          const items = [...list.querySelectorAll('.sortable-item')];
          const srcIdx = items.indexOf(dragSrc);
          const dstIdx = items.indexOf(this);
          if (srcIdx < dstIdx) list.insertBefore(dragSrc, this.nextSibling);
          else list.insertBefore(dragSrc, this);
        }
      });
    });
  }

  function renumberList() {
    document.querySelectorAll('#turn-order-list .sortable-item').forEach((item, idx) => {
      const numEl = item.querySelector('span.fw-bold');
      if (numEl) numEl.textContent = idx + 1;
    });
  }
  initSortable();

  // ===== TOGGLE ACTION FIELDS =====
  function toggleActionFields() {
    const actionType = document.querySelector('input[name="action_type"]:checked')?.value;
    const attackFields = document.getElementById('attack-fields');
    if (attackFields) attackFields.style.display = actionType === 'attack' ? 'block' : 'none';
  }

  // ===== API CALLS =====
  async function apiPost(action, data) {
    const formData = new FormData();
    formData.append('csrf_token', CSRF_TOKEN);
    Object.entries(data).forEach(([k, v]) => formData.append(k, v));
    const r = await fetch(BASE_URL + '/pilar-legends?action=' + action, {
      method: 'POST',
      body: formData
    });
    return r.json();
  }

  async function changeStatus(status) {
    const res = await apiPost('update-status', {
      session_id: SESSION_ID,
      status
    });
    if (res.success) {
      pollState();
    } else alert(res.message || 'Gagal update status');
  }

  async function startBattle() {
    if (!confirm('Mulai Battle? Timer akan berjalan!')) return;
    const res = await apiPost('update-status', {
      session_id: SESSION_ID,
      status: 'battle'
    });
    if (res.success) {
      pollState();
    }
  }

  async function assignTeam(playerSessionId, team) {
    const res = await apiPost('assign-team', {
      player_session_id: playerSessionId,
      team
    });
    if (res.success) {
      pollState();
    }
  }

  async function randomizeTeams() {
    const res = await apiPost('randomize-teams', {
      session_id: SESSION_ID
    });
    if (res.success) {
      pollState();
    }
  }

  async function autoAssignRoles() {
    const res = await apiPost('auto-assign-roles', {
      session_id: SESSION_ID
    });
    if (res.success) {
      pollState();
    }
  }

  async function saveTurnOrder() {
    const items = document.querySelectorAll('#turn-order-list .sortable-item');
    const order = [...items].map(el => el.dataset.psId);
    const res = await apiPost('set-turn-order', {
      session_id: SESSION_ID,
      order: JSON.stringify(order)
    });
    if (res.success) {
      turnOrderDirty = false;
      const r2 = await apiPost('update-status', {
        session_id: SESSION_ID,
        status: 'discussion_strategy'
      });
      if (r2.success) pollState();
    } else {
      alert('Gagal menyimpan urutan giliran.');
    }
  }

  async function randomizeTurns() {
    const res = await apiPost('randomize-turns', {
      session_id: SESSION_ID
    });
    if (res.success) {
      pollState();
      turnOrderDirty = false;
    }
  }

  async function submitAction() {
    const psId = document.getElementById('cur-ps-id')?.value;
    const type = document.querySelector('input[name="action_type"]:checked')?.value;
    const target = document.getElementById('target-player-select')?.value;
    const damage = document.getElementById('damage-input')?.value || 0;
    const desc = document.getElementById('action-description')?.value || '';

    if (!psId) return alert('Belum ada player yang mendapat giliran.');
    if (!type) return alert('Pilih tipe aksi.');
    if (type === 'attack' && !target) return alert('Pilih target untuk Attack.');

    const res = await apiPost('process-action', {
      session_id: SESSION_ID,
      player_session_id: psId,
      action_type: type,
      target_session_id: target || '',
      damage: damage,
      description: desc,
    });

    if (res.success) {
      if (res.game_over) {
        alert('🏆 Game Over! Pemenang: Team ' + (res.winner || '?'));
        pollState();
      } else {
        // Clear form
        document.getElementById('target-player-select').value = '';
        document.getElementById('damage-input').value = '';
        document.getElementById('action-description').value = '';
        document.querySelector('input[value="move"]').checked = true;
        toggleActionFields();
        pollState();
      }
    } else {
      alert(res.message || 'Gagal submit aksi.');
    }
  }

  async function confirmFinishGame() {
    const winner = prompt('Masukkan tim pemenang (A / B), atau kosongkan jika draw:');
    const res = await apiPost('finish-game', {
      session_id: SESSION_ID,
      winner_team: (winner || '').toUpperCase()
    });
    if (res.success) {
      pollState();
    }
  }

  // ===== START POLLING =====
  pollInterval = setInterval(pollState, 3000);
  pollState();
</script>