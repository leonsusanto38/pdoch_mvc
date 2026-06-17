<?php
$isAdmin  = \App\Core\Session::isAdmin();
$joinCode = $joinCode ?? '';
?>
<style>
.pl-lobby-wrap {
  min-height: 70vh; display: flex; align-items: center; justify-content: center;
}
.pl-lobby-card {
  max-width: 520px; width: 100%; margin: auto;
  background: #fff; border-radius: 24px;
  box-shadow: 0 20px 60px rgba(124,58,237,0.15);
  overflow: hidden;
}
.pl-lobby-banner {
  background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4c1d95 70%, #7c3aed 100%);
  padding: 3rem 2rem; text-align: center; color: #fff; position: relative; overflow: hidden;
}
.pl-lobby-banner::before {
  content: ''; position: absolute; inset: 0;
  background: radial-gradient(ellipse at center, rgba(139,92,246,0.4) 0%, transparent 70%);
}
.pl-lobby-banner .banner-title {
  font-size: 2rem; font-weight: 900; letter-spacing: 3px;
  text-shadow: 0 0 30px rgba(139,92,246,0.8); position: relative; z-index: 1;
}
.pl-lobby-banner .banner-sub { opacity: 0.6; letter-spacing: 2px; font-size: 0.75rem; position: relative; z-index: 1; }
.pl-lobby-body { padding: 2rem; }
.option-btn {
  width: 100%; padding: 1.2rem 1.5rem; border-radius: 16px;
  border: 2px solid transparent; cursor: pointer; text-align: left;
  transition: all 0.3s; display: flex; align-items: center; gap: 16px;
  font-size: 1rem; font-weight: 600; text-decoration: none; color: #1e1b4b;
  background: #f5f3ff;
}
.option-btn:hover { border-color: #7c3aed; color: #1e1b4b; transform: translateX(4px); }
.option-btn .opt-icon { font-size: 2rem; flex-shrink: 0; }
.option-btn .opt-desc { font-size: 0.8rem; font-weight: 400; color: #6b7280; margin-top: 2px; }
.option-btn.create-btn { background: linear-gradient(135deg, #ede9fe, #ddd6fe); }
.option-btn.create-btn:hover { background: linear-gradient(135deg, #ddd6fe, #c4b5fd); border-color: #7c3aed; }
.divider-text { text-align: center; position: relative; margin: 1.5rem 0; }
.divider-text::before {
  content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px;
  background: #e5e7eb;
}
.divider-text span { background: #fff; padding: 0 12px; position: relative; color: #9ca3af; font-size: 0.85rem; }
.join-input {
  font-size: 1.8rem; font-weight: 900; letter-spacing: 8px; text-transform: uppercase;
  text-align: center; border: 2px solid #e5e7eb; border-radius: 14px;
  padding: 0.8rem; width: 100%; color: #1e1b4b; transition: all 0.3s;
}
.join-input:focus { border-color: #7c3aed; outline: none; box-shadow: 0 0 0 4px rgba(124,58,237,0.1); }
.join-btn {
  width: 100%; padding: 1rem; border-radius: 14px;
  background: linear-gradient(135deg, #7c3aed, #2563eb);
  color: #fff; border: none; font-size: 1rem; font-weight: 700;
  letter-spacing: 1px; cursor: pointer; transition: all 0.3s;
}
.join-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(124,58,237,0.4); }
.back-link { display: block; text-align: center; margin-top: 1rem; color: #9ca3af; font-size: 0.85rem; }
.back-link:hover { color: #7c3aed; }
</style>

<div class="pl-lobby-wrap">
  <div class="pl-lobby-card">
    <div class="pl-lobby-banner">
      <div style="font-size:3rem;margin-bottom:0.5rem;position:relative;z-index:1;">🏰</div>
      <div class="banner-title">PILAR LEGENDS</div>
      <div class="banner-sub mt-1">TURN-BASED BATTLE GAME</div>
    </div>

    <div class="pl-lobby-body">
      <!-- CREATE GAME (Admin only) -->
      <?php if ($isAdmin): ?>
        <form action="<?= BASE_URL ?>/pilar-legends?action=create-game" method="POST">
          <button type="submit" class="option-btn create-btn mb-3">
            <div class="opt-icon">🎮</div>
            <div>
              <div>Create Game</div>
              <div class="opt-desc">Buat sesi game baru sebagai Game Master. QR Code & kode akan digenerate otomatis.</div>
            </div>
          </button>
        </form>
        <div class="divider-text"><span>atau bergabung ke game yang sudah ada</span></div>
      <?php else: ?>
        <p class="text-muted text-center mb-3 small">
          <i class="bi bi-info-circle me-1"></i> Masukkan kode dari Game Master atau scan QR Code untuk bergabung.
        </p>
      <?php endif; ?>

      <!-- JOIN GAME -->
      <form action="<?= BASE_URL ?>/pilar-legends?action=join-game" method="POST">
        <div class="mb-3">
          <label class="form-label fw-bold small text-center d-block mb-2">KODE GAME (6 DIGIT)</label>
          <input type="text" class="join-input" name="game_code" maxlength="6"
                 placeholder="XXXXXX" value="<?= htmlspecialchars($joinCode) ?>"
                 id="gameCodeInput" autocomplete="off" required>
        </div>
        <button type="submit" class="join-btn">
          <i class="bi bi-door-open-fill me-2"></i> JOIN GAME
        </button>
      </form>

      <a href="<?= BASE_URL ?>/games" class="back-link">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Games
      </a>
    </div>
  </div>
</div>

<script>
// Auto-uppercase game code input
document.getElementById('gameCodeInput').addEventListener('input', function() {
  const pos = this.selectionStart;
  this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
  this.setSelectionRange(pos, pos);
});
</script>
