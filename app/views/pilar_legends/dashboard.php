<?php
// Fullscreen dashboard — standalone HTML page (no navbar/footer layout)
$session    = $state['session'] ?? [];
$teamA      = $state['team_a'] ?? [];
$teamB      = $state['team_b'] ?? [];
$logs       = $state['battle_logs'] ?? [];
$currentTurn= $state['current_turn_player'] ?? null;
$turnTimer  = $state['turn_timer_remaining'] ?? null;
$discTimer  = $state['discussion_timer_remaining'] ?? null;
$gmName     = $state['gm_name'] ?? '—';
$roleIcons  = ['Fighter' => '⚔️', 'Marksman' => '🏹', 'Assassin' => '🗡️', 'Wizard' => '🧙', 'Tank' => '🛡️', 'Cleric' => '✨'];
$statusLabels = ['waiting'=>'Menunggu','team_assign'=>'Pembagian Tim','discussion_role'=>'Diskusi Role','discussion_strategy'=>'Diskusi Strategi','battle'=>'BATTLE!','finished'=>'SELESAI'];
$statusColors = ['waiting'=>'#f59e0b','team_assign'=>'#3b82f6','discussion_role'=>'#8b5cf6','discussion_strategy'=>'#06b6d4','battle'=>'#ef4444','finished'=>'#4ade80'];
$status     = $session['status'] ?? 'waiting';
$stColor    = $statusColors[$status] ?? '#6b7280';
$stLabel    = $statusLabels[$status] ?? $status;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pilar Legends — Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Orbitron:wght@400;700;900&display=swap');

    * { margin:0; padding:0; box-sizing:border-box; }
    html, body { height:100%; background:#05050f; color:#fff; font-family:'Rajdhani',sans-serif; overflow:hidden; }

    /* ===== LAYOUT ===== */
    .dash-layout {
      display: grid;
      grid-template-rows: auto 1fr auto;
      height: 100vh;
      gap: 0;
    }

    /* ===== HEADER ===== */
    .dash-header {
      background: linear-gradient(90deg, #0f0c29, #302b63, #24243e);
      border-bottom: 2px solid rgba(139,92,246,0.3);
      padding: 0.6rem 1.5rem;
      display: flex; align-items: center; justify-content: space-between;
    }
    .dash-logo {
      font-family: 'Orbitron', sans-serif;
      font-size: 1.4rem; font-weight: 900; letter-spacing: 3px;
      background: linear-gradient(90deg, #c4b5fd, #93c5fd);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      text-shadow: none;
    }
    .game-code-pill {
      background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);
      border-radius: 50px; padding: 4px 16px;
      font-family: 'Orbitron', sans-serif; font-size: 1.2rem; font-weight: 700;
      letter-spacing: 4px;
    }
    .status-pill {
      border-radius: 50px; padding: 4px 20px; font-weight: 700;
      font-size: 0.9rem; letter-spacing: 1px; border: 2px solid;
    }

    /* ===== MAIN BATTLE AREA ===== */
    .dash-battle {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      gap: 1rem;
      padding: 1rem 1.5rem;
      overflow: hidden;
    }

    /* ===== TEAM PANELS ===== */
    .team-panel {
      display: flex; flex-direction: column; gap: 0.6rem;
    }
    .team-header {
      text-align: center; padding: 0.6rem;
      border-radius: 12px; font-family: 'Orbitron',sans-serif;
      font-weight: 700; font-size: 1rem; letter-spacing: 2px;
    }
    .team-a-header { background: linear-gradient(135deg, #1e3a5f, #1d4ed8); border: 2px solid #3b82f6; }
    .team-b-header { background: linear-gradient(135deg, #5f1e1e, #b91c1c); border: 2px solid #ef4444; }

    .player-card {
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 12px; padding: 0.7rem 1rem;
      transition: all 0.3s;
      position: relative; overflow: hidden;
    }
    .player-card.is-current-turn {
      border-color: #f59e0b;
      background: rgba(245,158,11,0.08);
      box-shadow: 0 0 20px rgba(245,158,11,0.2);
    }
    .player-card.is-current-turn::before {
      content: '▶';
      position: absolute; left: -2px; top: 50%; transform: translateY(-50%);
      color: #f59e0b; font-size: 1rem;
    }
    .player-card.is-dead {
      opacity: 0.35; filter: grayscale(1);
    }
    .player-card.team-a { border-left: 3px solid #3b82f6; }
    .player-card.team-b { border-left: 3px solid #ef4444; }

    .pc-name {
      font-weight: 700; font-size: 0.95rem;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .pc-role {
      font-size: 0.75rem; opacity: 0.6; margin-top: 1px;
    }
    .pc-stats {
      display: flex; gap: 8px; margin-top: 4px; flex-wrap: wrap;
    }
    .pc-stat { font-size: 0.7rem; padding: 1px 6px; border-radius: 4px; font-weight: 600; }
    .pc-stat-atk { background: rgba(220,38,38,0.3); color: #fca5a5; }
    .pc-stat-mov { background: rgba(37,99,235,0.3); color: #93c5fd; }
    .pc-stat-rng { background: rgba(217,119,6,0.3); color: #fcd34d; }

    /* HP Bar */
    .hp-bar-wrap { margin-top: 6px; }
    .hp-label { display: flex; justify-content: space-between; font-size: 0.7rem; opacity: 0.8; margin-bottom: 2px; }
    .hp-bar { height: 8px; border-radius: 4px; background: rgba(255,255,255,0.1); overflow: hidden; }
    .hp-fill { height: 100%; border-radius: 4px; transition: width 0.6s ease; }
    .hp-full { background: linear-gradient(90deg, #4ade80, #22c55e); }
    .hp-mid  { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
    .hp-low  { background: linear-gradient(90deg, #f87171, #ef4444); }
    .hp-dead { background: #374151; }

    /* Turn order (alive indicator dots) */
    .pc-order {
      position: absolute; top: 8px; right: 10px;
      font-family: 'Orbitron',sans-serif; font-size: 0.7rem;
      color: rgba(255,255,255,0.4); font-weight: 700;
    }

    /* ===== CENTER PANEL ===== */
    .center-panel {
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      width: 200px; gap: 1rem;
    }
    .vs-badge {
      font-family: 'Orbitron',sans-serif; font-size: 2.5rem; font-weight: 900;
      background: linear-gradient(135deg, #7c3aed, #2563eb);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .turn-indicator {
      background: rgba(245,158,11,0.1); border: 2px solid #f59e0b;
      border-radius: 12px; padding: 0.8rem; text-align: center;
      width: 100%;
    }
    .turn-name {
      font-weight: 800; font-size: 0.85rem; color: #fbbf24;
    }
    .timer-big {
      font-family: 'Orbitron',sans-serif; font-size: 2.8rem; font-weight: 900;
      text-align: center; color: #f59e0b; margin-top: 0.5rem; line-height: 1;
    }
    .timer-big.danger { color: #ef4444; animation: pulse-red 0.8s infinite; }
    @keyframes pulse-red { 50%{ opacity:0.5; } }

    .score-display {
      display: flex; gap: 0.5rem; width: 100%; justify-content: center;
    }
    .score-box {
      flex: 1; text-align: center; padding: 0.5rem;
      border-radius: 8px;
    }
    .score-box.team-a { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.3); }
    .score-box.team-b { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); }
    .score-val { font-family:'Orbitron',sans-serif; font-size:1.5rem; font-weight:900; }
    .score-lbl { font-size:0.65rem; opacity:0.6; letter-spacing:1px; }

    /* ===== BATTLE LOG FOOTER ===== */
    .dash-footer {
      background: rgba(255,255,255,0.03); border-top: 1px solid rgba(255,255,255,0.08);
      padding: 0.5rem 1.5rem;
      display: flex; align-items: center; gap: 1rem; overflow: hidden;
    }
    .log-scroll {
      display: flex; gap: 0.8rem; overflow: hidden; flex: 1;
    }
    .log-chip {
      flex-shrink: 0; padding: 3px 10px; border-radius: 50px;
      font-size: 0.75rem; white-space: nowrap;
      background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
    }
    .log-chip.attack { border-color: rgba(239,68,68,0.4); color: #fca5a5; }
    .log-chip.move   { border-color: rgba(59,130,246,0.4); color: #93c5fd; }
    .log-chip.skip, .log-chip.auto_skip { border-color: rgba(245,158,11,0.4); color: #fcd34d; }

    /* Win overlay */
    .win-overlay {
      display: none; position: fixed; inset: 0; z-index: 999;
      background: rgba(0,0,0,0.85);
      align-items: center; justify-content: center; flex-direction: column;
    }
    .win-overlay.active { display: flex; }
    .win-text {
      font-family: 'Orbitron',sans-serif; font-size: 4rem; font-weight: 900;
      background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      animation: glow-text 2s ease-in-out infinite;
    }
    @keyframes glow-text {
      0%,100% { filter: drop-shadow(0 0 10px rgba(245,158,11,0.5)); }
      50%      { filter: drop-shadow(0 0 30px rgba(245,158,11,0.9)); }
    }

    /* Particles / floating elements */
    @keyframes float-particle {
      0%   { transform: translateY(100vh) rotate(0deg); opacity: 0; }
      10%  { opacity: 0.7; }
      90%  { opacity: 0.7; }
      100% { transform: translateY(-20px) rotate(720deg); opacity: 0; }
    }
    .victory-particle {
      position: fixed; font-size: 2rem; pointer-events: none;
      animation: float-particle 3s ease-in-out infinite;
    }
  </style>
</head>
<body>
<div class="dash-layout">

  <!-- Header -->
  <div class="dash-header">
    <div class="dash-logo">🏰 PILAR LEGENDS</div>
    <div class="game-code-pill" id="dash-code"><?= htmlspecialchars($session['game_code'] ?? '???') ?></div>
    <div class="d-flex align-items-center gap-3">
      <div style="font-size:0.8rem;opacity:0.5;">GM: <?= htmlspecialchars($gmName) ?></div>
      <div class="status-pill" id="dash-status" style="color:<?= $stColor ?>;border-color:<?= $stColor ?>;">
        <?= $stLabel ?>
      </div>
    </div>
  </div>

  <!-- Battle Area -->
  <div class="dash-battle">

    <!-- Team A -->
    <div class="team-panel" id="team-a-panel">
      <div class="team-header team-a-header">🔵 <?= htmlspecialchars($session['team_a_name'] ?? 'TEAM A') ?></div>
      <?php foreach ($teamA as $p):
        $maxHp  = (int)($p['base_hp'] ?: 12);
        $curHp  = (int)($p['current_hp'] ?? $maxHp);
        $pct    = $maxHp > 0 ? max(0, $curHp / $maxHp * 100) : 0;
        $hpCls  = $pct > 50 ? 'hp-full' : ($pct > 25 ? 'hp-mid' : ($pct > 0 ? 'hp-low' : 'hp-dead'));
        $isCur  = $currentTurn && $currentTurn['id'] == $p['id'];
        $isDead = !$p['is_alive'];
      ?>
      <div class="player-card team-a <?= $isCur ? 'is-current-turn' : '' ?> <?= $isDead ? 'is-dead' : '' ?>" id="pc-<?= $p['id'] ?>">
        <?php if ($p['turn_order']): ?>
          <div class="pc-order">#<?= $p['turn_order'] ?></div>
        <?php endif; ?>
        <div class="d-flex align-items-center gap-2">
          <span style="font-size:1.4rem;"><?= $roleIcons[$p['role_name'] ?? ''] ?? '❓' ?></span>
          <div style="min-width:0;flex:1;">
            <div class="pc-name"><?= htmlspecialchars($p['user_name']) ?></div>
            <div class="pc-role"><?= htmlspecialchars($p['role_name'] ?? 'Belum pilih') ?></div>
          </div>
          <?php if ($isDead): ?>
            <span style="font-size:1.2rem;">💀</span>
          <?php endif; ?>
        </div>
        <?php if ($p['role_name']): ?>
        <div class="pc-stats">
          <span class="pc-stat pc-stat-atk">ATK <?= $p['base_attack'] ?></span>
          <span class="pc-stat pc-stat-mov">MOV <?= $p['base_movement'] ?></span>
          <span class="pc-stat pc-stat-rng">RNG <?= $p['range_cells'] ?></span>
        </div>
        <?php endif; ?>
        <div class="hp-bar-wrap">
          <div class="hp-label">
            <span>HP</span>
            <span id="hp-<?= $p['id'] ?>"><?= $curHp ?>/<?= $maxHp ?></span>
          </div>
          <div class="hp-bar">
            <div class="hp-fill <?= $hpCls ?>" id="hpfill-<?= $p['id'] ?>" style="width:<?= $pct ?>%"></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($teamA)): ?>
        <div style="text-align:center;opacity:0.3;padding:2rem;">Belum ada player</div>
      <?php endif; ?>
    </div>

    <!-- Center -->
    <div class="center-panel">
      <div class="vs-badge">VS</div>

      <!-- Score: alive players per team -->
      <div class="score-display">
        <?php $aliveA = count(array_filter($teamA, fn($p) => $p['is_alive'])); ?>
        <?php $aliveB = count(array_filter($teamB, fn($p) => $p['is_alive'])); ?>
        <div class="score-box team-a">
          <div class="score-val" id="alive-a"><?= $aliveA ?></div>
          <div class="score-lbl">ALIVE A</div>
        </div>
        <div class="score-box team-b">
          <div class="score-val" id="alive-b"><?= $aliveB ?></div>
          <div class="score-lbl">ALIVE B</div>
        </div>
      </div>

      <!-- Current Turn -->
      <?php if ($currentTurn && $session['status'] === 'battle'): ?>
      <div class="turn-indicator" id="turn-indicator">
        <div style="font-size:0.65rem;opacity:0.6;letter-spacing:2px;margin-bottom:4px;">GILIRAN</div>
        <div class="turn-name" id="turn-player-name">
          <?= $roleIcons[$currentTurn['role_name'] ?? ''] ?? '⚡' ?>
          <?= htmlspecialchars($currentTurn['user_name']) ?>
        </div>
        <div style="font-size:0.7rem;opacity:0.6;margin-top:2px;" id="turn-player-team">
          Tim <?= $currentTurn['team'] ?> • <?= $currentTurn['role_name'] ?? '—' ?>
        </div>
        <div class="timer-big" id="dash-turn-timer">
          <?php
          if ($turnTimer !== null) {
            $m = str_pad(floor($turnTimer/60),2,'0',STR_PAD_LEFT);
            $s = str_pad($turnTimer%60,2,'0',STR_PAD_LEFT);
            echo $m.':'.$s;
          } else { echo '01:00'; }
          ?>
        </div>
      </div>
      <?php elseif (in_array($session['status'], ['discussion_role','discussion_strategy'])): ?>
      <div class="turn-indicator" id="turn-indicator" style="border-color:#8b5cf6;background:rgba(139,92,246,0.1);">
        <div style="font-size:0.65rem;opacity:0.6;letter-spacing:2px;margin-bottom:4px;">
          <?= $session['status'] === 'discussion_role' ? 'DISKUSI ROLE' : 'DISKUSI STRATEGI' ?>
        </div>
        <div class="timer-big" style="color:#8b5cf6;" id="dash-disc-timer">
          <?php
          if ($discTimer !== null) {
            $m = str_pad(floor($discTimer/60),2,'0',STR_PAD_LEFT);
            $s = str_pad($discTimer%60,2,'0',STR_PAD_LEFT);
            echo $m.':'.$s;
          } else { echo '02:00'; }
          ?>
        </div>
      </div>
      <?php else: ?>
      <div style="text-align:center;opacity:0.4;">
        <div style="font-size:2rem;">⏳</div>
        <div style="font-size:0.8rem;margin-top:4px;"><?= $stLabel ?></div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Team B -->
    <div class="team-panel" id="team-b-panel">
      <div class="team-header team-b-header">🔴 <?= htmlspecialchars($session['team_b_name'] ?? 'TEAM B') ?></div>
      <?php foreach ($teamB as $p):
        $maxHp  = (int)($p['base_hp'] ?: 12);
        $curHp  = (int)($p['current_hp'] ?? $maxHp);
        $pct    = $maxHp > 0 ? max(0, $curHp / $maxHp * 100) : 0;
        $hpCls  = $pct > 50 ? 'hp-full' : ($pct > 25 ? 'hp-mid' : ($pct > 0 ? 'hp-low' : 'hp-dead'));
        $isCur  = $currentTurn && $currentTurn['id'] == $p['id'];
        $isDead = !$p['is_alive'];
      ?>
      <div class="player-card team-b <?= $isCur ? 'is-current-turn' : '' ?> <?= $isDead ? 'is-dead' : '' ?>" id="pc-<?= $p['id'] ?>">
        <?php if ($p['turn_order']): ?>
          <div class="pc-order">#<?= $p['turn_order'] ?></div>
        <?php endif; ?>
        <div class="d-flex align-items-center gap-2">
          <span style="font-size:1.4rem;"><?= $roleIcons[$p['role_name'] ?? ''] ?? '❓' ?></span>
          <div style="min-width:0;flex:1;">
            <div class="pc-name"><?= htmlspecialchars($p['user_name']) ?></div>
            <div class="pc-role"><?= htmlspecialchars($p['role_name'] ?? 'Belum pilih') ?></div>
          </div>
          <?php if ($isDead): ?>
            <span style="font-size:1.2rem;">💀</span>
          <?php endif; ?>
        </div>
        <?php if ($p['role_name']): ?>
        <div class="pc-stats">
          <span class="pc-stat pc-stat-atk">ATK <?= $p['base_attack'] ?></span>
          <span class="pc-stat pc-stat-mov">MOV <?= $p['base_movement'] ?></span>
          <span class="pc-stat pc-stat-rng">RNG <?= $p['range_cells'] ?></span>
        </div>
        <?php endif; ?>
        <div class="hp-bar-wrap">
          <div class="hp-label">
            <span>HP</span>
            <span id="hp-<?= $p['id'] ?>"><?= $curHp ?>/<?= $maxHp ?></span>
          </div>
          <div class="hp-bar">
            <div class="hp-fill <?= $hpCls ?>" id="hpfill-<?= $p['id'] ?>" style="width:<?= $pct ?>%"></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($teamB)): ?>
        <div style="text-align:center;opacity:0.3;padding:2rem;">Belum ada player</div>
      <?php endif; ?>
    </div>

  </div><!-- /battle area -->

  <!-- Battle Log Footer -->
  <div class="dash-footer">
    <div style="font-size:0.7rem;opacity:0.5;letter-spacing:2px;white-space:nowrap;">BATTLE LOG</div>
    <div class="log-scroll" id="dash-log">
      <?php foreach (array_slice($logs, 0, 8) as $log): ?>
      <div class="log-chip <?= $log['action_type'] ?>">
        <?php if ($log['action_type'] === 'attack'): ?>
          ⚔️ <?= htmlspecialchars($log['actor_name']) ?> → <?= htmlspecialchars($log['target_name']??'?') ?>
          (<?= $log['damage_dealt'] ?> DMG)
        <?php elseif ($log['action_type'] === 'move'): ?>
          🦶 <?= htmlspecialchars($log['actor_name']) ?> bergerak
        <?php elseif ($log['action_type'] === 'auto_skip'): ?>
          ⏰ <?= htmlspecialchars($log['actor_name']) ?> timeout
        <?php else: ?>
          ⏭️ <?= htmlspecialchars($log['actor_name']) ?> skip
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="opacity:0.3;font-size:0.7rem;white-space:nowrap;">Auto-refresh 2s</div>
  </div>

</div><!-- /dash-layout -->

<!-- Win Overlay -->
<div class="win-overlay" id="win-overlay">
  <div style="font-size:5rem;margin-bottom:1rem;animation:float-particle 2s infinite;">🏆</div>
  <div class="win-text" id="win-text">TEAM A MENANG!</div>
  <div style="font-size:1.5rem;opacity:0.6;margin-top:1rem;font-family:'Rajdhani',sans-serif;">Selamat kepada Tim Pemenang!</div>
</div>

<script>
const SESSION_ID  = <?= $sessionId ?>;
const BASE_URL    = '<?= BASE_URL ?>';
const roleIcons   = {Fighter:'⚔️',Marksman:'🏹',Assassin:'🗡️',Wizard:'🧙',Tank:'🛡️',Cleric:'✨'};
const statusLabels = { waiting:'Menunggu', team_assign:'Pembagian Tim', discussion_role:'Diskusi Role',
  discussion_strategy:'Diskusi Strategi', battle:'BATTLE!', finished:'SELESAI' };
const statusColors = { waiting:'#f59e0b', team_assign:'#3b82f6', discussion_role:'#8b5cf6',
  discussion_strategy:'#06b6d4', battle:'#ef4444', finished:'#4ade80' };

function escHtml(s) {
  if (!s) return '';
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function updateTimerEl(id, remaining, dangerThreshold = 10) {
  const el = document.getElementById(id);
  if (!el || remaining === null || remaining === undefined) return;
  remaining = Math.max(0, remaining);
  const m = String(Math.floor(remaining/60)).padStart(2,'0');
  const s = String(remaining%60).padStart(2,'0');
  el.textContent = m+':'+s;
  if (remaining <= dangerThreshold) el.classList.add('danger');
  else el.classList.remove('danger');
}

function getHpClass(pct) {
  if (pct <= 0) return 'hp-dead';
  if (pct <= 25) return 'hp-low';
  if (pct <= 50) return 'hp-mid';
  return 'hp-full';
}

function pollState() {
  fetch(BASE_URL + '/pilar-legends?action=poll-state&session_id=' + SESSION_ID)
    .then(r => r.json())
    .then(data => { if (!data.error) updateDashboard(data); })
    .catch(e => console.error('Dashboard poll error:', e));
}

function updateDashboard(data) {
  const session = data.session;
  const players = data.players;
  const logs    = data.battle_logs;
  const cur     = data.current_turn_player;

  // Status pill
  const statusEl = document.getElementById('dash-status');
  if (statusEl) {
    statusEl.textContent = statusLabels[session.status] || session.status;
    const c = statusColors[session.status] || '#6b7280';
    statusEl.style.color = c; statusEl.style.borderColor = c;
  }

  // Update HP bars and player cards
  players.forEach(p => {
    const card    = document.getElementById('pc-' + p.id);
    const hpEl    = document.getElementById('hp-' + p.id);
    const hpFill  = document.getElementById('hpfill-' + p.id);
    const maxHp   = parseInt(p.base_hp)||12;
    const curHp   = parseInt(p.current_hp)??maxHp;
    const pct     = maxHp > 0 ? Math.max(0, curHp/maxHp*100) : 0;
    const hpCls   = getHpClass(pct);
    const isDead  = !parseInt(p.is_alive);
    const isCur   = cur && parseInt(p.id) === parseInt(cur.id) && session.status === 'battle';

    if (hpEl)   hpEl.textContent = curHp+'/'+maxHp;
    if (hpFill) {
      hpFill.style.width = pct + '%';
      hpFill.className   = 'hp-fill ' + hpCls;
    }
    if (card) {
      card.className = 'player-card team-' + (p.team||'a').toLowerCase() +
        (isCur?' is-current-turn':'') + (isDead?' is-dead':'');
    }
  });

  // Alive count
  const teamA = players.filter(p => p.team === 'A');
  const teamB = players.filter(p => p.team === 'B');
  const aliveA = teamA.filter(p => parseInt(p.is_alive)).length;
  const aliveB = teamB.filter(p => parseInt(p.is_alive)).length;
  const elA = document.getElementById('alive-a');
  const elB = document.getElementById('alive-b');
  if (elA) elA.textContent = aliveA;
  if (elB) elB.textContent = aliveB;

  // Current turn
  if (cur && session.status === 'battle') {
    const nameEl = document.getElementById('turn-player-name');
    const teamEl = document.getElementById('turn-player-team');
    if (nameEl) nameEl.textContent = (roleIcons[cur.role_name]||'⚡') + ' ' + cur.user_name;
    if (teamEl) teamEl.textContent = 'Tim ' + cur.team + ' • ' + (cur.role_name || '—');
  }

  // Timers
  updateTimerEl('dash-turn-timer',   data.turn_timer_remaining, 10);
  updateTimerEl('dash-disc-timer',   data.discussion_timer_remaining, 15);

  // Battle log chips
  const logEl = document.getElementById('dash-log');
  if (logEl && logs) {
    const visible = logs.slice(0,8);
    let html = '';
    visible.forEach(log => {
      let text = '';
      if (log.action_type==='attack') text = `⚔️ ${escHtml(log.actor_name)} → ${escHtml(log.target_name||'?')} (${log.damage_dealt} DMG)`;
      else if (log.action_type==='move') text = `🦶 ${escHtml(log.actor_name)} bergerak`;
      else if (log.action_type==='auto_skip') text = `⏰ ${escHtml(log.actor_name)} timeout`;
      else text = `⏭️ ${escHtml(log.actor_name)} skip`;
      html += `<div class="log-chip ${log.action_type}">${text}</div>`;
    });
    logEl.innerHTML = html;
  }

  // Team panels rebuild (for new players joining)
  rebuildTeamPanel('team-a-panel', teamA, cur ? parseInt(cur.id) : null, session.status);
  rebuildTeamPanel('team-b-panel', teamB, cur ? parseInt(cur.id) : null, session.status);

  // Win overlay
  if (session.status === 'finished' && session.winner_team) {
    const overlay = document.getElementById('win-overlay');
    const txt     = document.getElementById('win-text');
    if (overlay) overlay.classList.add('active');
    if (txt) txt.textContent = 'TEAM ' + session.winner_team + ' MENANG!';
    clearInterval(pollInterval);
    addVictoryParticles(session.winner_team);
  }
}

function rebuildTeamPanel(panelId, players, currentTurnId, status) {
  const panel = document.getElementById(panelId);
  if (!panel || players.length === 0) return;
  const header = panel.querySelector('.team-header');

  let html = header ? header.outerHTML : '';
  players.forEach(p => {
    const maxHp  = parseInt(p.base_hp)||12;
    const curHp  = parseInt(p.current_hp)??maxHp;
    const pct    = maxHp > 0 ? Math.max(0, curHp/maxHp*100) : 0;
    const hpCls  = getHpClass(pct);
    const isDead = !parseInt(p.is_alive);
    const isCur  = parseInt(p.id) === currentTurnId && status === 'battle';
    const team   = (p.team||'a').toLowerCase();

    html += `<div class="player-card team-${team} ${isCur?'is-current-turn':''} ${isDead?'is-dead':''}" id="pc-${p.id}">
      ${p.turn_order?`<div class="pc-order">#${p.turn_order}</div>`:''}
      <div class="d-flex align-items-center gap-2">
        <span style="font-size:1.4rem;">${roleIcons[p.role_name]||'❓'}</span>
        <div style="min-width:0;flex:1;">
          <div class="pc-name">${escHtml(p.user_name)}</div>
          <div class="pc-role">${escHtml(p.role_name)||'Belum pilih'}</div>
        </div>
        ${isDead?'<span style="font-size:1.2rem;">💀</span>':''}
      </div>
      ${p.role_name?`<div class="pc-stats">
        <span class="pc-stat pc-stat-atk">ATK ${p.base_attack}</span>
        <span class="pc-stat pc-stat-mov">MOV ${p.base_movement}</span>
        <span class="pc-stat pc-stat-rng">RNG ${p.range_cells}</span>
      </div>`:''}
      <div class="hp-bar-wrap">
        <div class="hp-label"><span>HP</span><span id="hp-${p.id}">${curHp}/${maxHp}</span></div>
        <div class="hp-bar"><div class="hp-fill ${hpCls}" id="hpfill-${p.id}" style="width:${pct}%"></div></div>
      </div>
    </div>`;
  });
  panel.innerHTML = html;
}

function addVictoryParticles(team) {
  const emojis = team === 'A' ? ['🔵','⭐','🏆','🎉','✨'] : ['🔴','⭐','🏆','🎉','✨'];
  for (let i=0; i<15; i++) {
    const el = document.createElement('div');
    el.className = 'victory-particle';
    el.textContent = emojis[Math.floor(Math.random()*emojis.length)];
    el.style.left = Math.random()*100 + 'vw';
    el.style.animationDelay = Math.random()*3 + 's';
    el.style.animationDuration = (2 + Math.random()*2) + 's';
    document.body.appendChild(el);
  }
}

const pollInterval = setInterval(pollState, 2000);
pollState();
</script>
</body>
</html>
