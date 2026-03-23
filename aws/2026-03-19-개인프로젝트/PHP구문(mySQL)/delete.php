<?php
header('Content-Type: text/html; charset=utf-8');

/* ── 선택: 로그인 등 다른 페이지와 호환 필요 시 세션 유지 ── */
$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_set_cookie_params([
  'lifetime' => 0, 'path' => '/', 'domain' => '',
  'secure' => $secure, 'httponly' => true, 'samesite' => 'Lax',
]);
session_start();

/* ── CSRF Stateless 토큰 유틸 ── */
function _b64u_enc(string $s): string { return rtrim(strtr(base64_encode($s), '+/', '-_'), '='); }
function _b64u_dec(string $s): string|false {
  $pad = strlen($s) % 4; if ($pad) $s .= str_repeat('=', 4 - $pad);
  return base64_decode(strtr($s, '-_', '+/'), true);
}
function _csrf_secret(): string {
  $k = getenv('CSRF_SECRET');
  return $k !== false && strlen($k) >= 32 ? $k : '!!CHANGE_ME__DEMO_WEAK_SECRET__USE_ENV_32B_PLUS!!';
}
function make_csrf_token(string $action, string $userID, int $ttl = 600): string {
  $payload = json_encode([
    'ts' => time(), 'act' => $action, 'uid' => $userID, 'n' => bin2hex(random_bytes(8))
  ], JSON_UNESCAPED_UNICODE);
  $sig = hash_hmac('sha256', $payload, _csrf_secret(), true);
  return _b64u_enc($payload) . '.' . _b64u_enc($sig);
}

/* ===============================
   DB 연결 + 문자셋
================================= */
$con = mysqli_connect("localhost", "user1", "P@ssw0rd", "sqlDB");
if (!$con) {
  echo "<!doctype html><meta charset='utf-8'><p>MySQL 접속 실패: "
      . htmlspecialchars(mysqli_connect_error(), ENT_QUOTES, 'UTF-8') . "</p>";
  exit;
}
mysqli_set_charset($con, "utf8mb4");

/* ===============================
   입력 파라미터 & 조회
================================= */
$reqUserId = trim($_GET['userID'] ?? '');
$stmt = mysqli_prepare($con, "SELECT userID, name FROM userTBL WHERE userID = ?");
mysqli_stmt_bind_param($stmt, 's', $reqUserId);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if (!$res) {
  echo "<!doctype html><meta charset='utf-8'><p>데이터 조회 실패!</p><p>원인: "
      . htmlspecialchars(mysqli_error($con), ENT_QUOTES, 'UTF-8') .
      "</p><p><a href='main.html'>← 초기 화면</a></p>";
  exit;
}
if (mysqli_num_rows($res) === 0) {
  echo "<!doctype html><meta charset='utf-8'><p>"
      . htmlspecialchars($reqUserId, ENT_QUOTES, 'UTF-8') .
      " 아이디의 회원이 없습니다.</p><p><a href='main.html'>← 초기 화면</a></p>";
  exit;
}
$row = mysqli_fetch_assoc($res);
mysqli_free_result($res);
mysqli_stmt_close($stmt);
mysqli_close($con);

/* ===============================
   출력 헬퍼 + CSRF(Stateless)
================================= */
$h = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$userID = $row['userID'] ?? '';
$name   = $row['name']   ?? '';
$csrf   = make_csrf_token('delete_user', $userID, 600);
?>
<!doctype html>
<html lang="ko" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>회원 삭제 - <?= $h($userID) ?></title>
  <style>
    :root{ --bg:#0e1229; --card:#151b3a; --text:#eaf0ff; --muted:#9aa3c7;
      --accent:#7ca6ff; --accent2:#8df3ff; --danger:#ff6b6b; --warn:#ffd166;
      --border:rgba(255,255,255,.12); --input:#0d1231; --shadow:0 16px 44px rgba(0,0,0,.38); }
    [data-theme="light"]{ --bg:#f3f6ff; --card:#ffffff; --text:#1a2038; --muted:#586285;
      --accent:#4c7dff; --accent2:#3bd6ff; --danger:#ef4444; --warn:#f59e0b;
      --border:rgba(10,20,60,.14); --input:#f7faff; --shadow:0 12px 28px rgba(32,40,94,.12); }
    *{box-sizing:border-box}
    body{ margin:0; background:
      radial-gradient(1000px 520px at 12% -10%, rgba(124,166,255,.18), transparent 60%),
      radial-gradient(900px 600px at 110% 0%, rgba(141,243,255,.18), transparent 60%),
      var(--bg);
      color:var(--text); font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Noto Sans KR',Pretendard,sans-serif;
      min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px; }
    .app{width:min(880px,100%)} .nav{display:flex; justify-content:space-between; align-items:center; margin-bottom:14px}
    .title{display:flex; gap:10px; align-items:center} .title h1{margin:0; font-size:22px}
    .badge{display:inline-flex; gap:8px; align-items:center; padding:8px 12px; border-radius:999px;
      background:linear-gradient(90deg,var(--accent),var(--accent2)); color:#0c1233; font-weight:900;}
    .btn{appearance:none; border:1px solid var(--border); background:transparent; color:var(--text);
      padding:10px 14px; border-radius:12px; cursor:pointer; font-weight:700;}
    .btn:hover{filter:brightness(1.08)}
    .card{background:linear-gradient(180deg,rgba(255,255,255,.03),rgba(255,255,255,.01)), var(--card);
      border:1px solid var(--border); border-radius:22px; box-shadow:var(--shadow); overflow:hidden;}
    .head{display:flex; justify-content:space-between; align-items:center; padding:18px 18px 0}
    .wrap{padding:18px}
    .warn{display:flex; align-items:center; gap:10px; padding:12px 14px; margin-bottom:14px;
      border:1px solid rgba(255,107,107,.35); background:rgba(255,107,107,.10); border-radius:12px;}
    .info{display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px}
    .field{background:var(--input); border:1px solid var(--border); border-radius:14px; padding:12px}
    .label{display:block; font-size:12px; color:var(--muted); margin-bottom:6px}
    .value{font-weight:800}
    .confirm{background:var(--input); border:1px solid var(--border); border-radius:14px; padding:12px; margin-top:8px}
    .row{display:flex; gap:10px; align-items:center; flex-wrap:wrap}
    .input{background:transparent; border:none; outline:none; color:var(--text); padding:6px 0; font-size:16px}
    .footer{display:flex; justify-content:space-between; align-items:center; padding:0 18px 18px; color:var(--muted)}
    .link{color:var(--text); text-decoration:none; border:1px solid var(--border); padding:10px 12px; border-radius:10px}
    .danger{background:linear-gradient(90deg,var(--danger),#ff9f9f); color:#2b0b0b; border:none; padding:12px 18px; border-radius:12px; font-weight:900; cursor:pointer}
    .danger[disabled]{opacity:.6; cursor:not-allowed}
    .ghost{background:transparent; border:1px solid var(--border); color:var(--text); padding:12px 18px; border-radius:12px; font-weight:900; cursor:pointer}
    .toast{position:fixed; right:16px; bottom:16px; display:flex; flex-direction:column; gap:10px; z-index:50}
    .toast .msg{background:var(--card); border:1px solid var(--border); color:var(--text); padding:12px 14px; border-radius:12px; box-shadow:var(--shadow); animation:slide .2s ease}
    @keyframes slide{from{opacity:0; transform:translateY(8px)} to{opacity:1; transform:translateY(0)}}
  </style>
</head>
<body>
  <div class="app">
    <nav class="nav">
      <div class="title">
        <h1>🗑️ 회원 삭제</h1>
        <span class="badge">ID: <?= $h($userID) ?></span>
      </div>
      <div class="actions"><button class="btn" id="toggle">🌗 테마 전환</button></div>
    </nav>

    <section class="card" role="region" aria-label="회원 삭제 확인">
      <div class="head"><h2>⚠️ 삭제 전 확인</h2></div>
      <div class="wrap">
        <div class="warn">이 작업은 <strong>되돌릴 수 없습니다.</strong> 데이터를 영구 삭제합니다.</div>

        <div class="info">
          <div class="field"><span class="label">👤 아이디</span><div class="value"><?= $h($userID) ?></div></div>
          <div class="field"><span class="label">🧑 이름</span><div class="value"><?= $h($name) ?></div></div>
        </div>

        <form id="delForm" class="confirm" method="post" action="delete_result.php" novalidate>
          <input type="hidden" name="userID" value="<?= $h($userID) ?>">
          <input type="hidden" name="csrf"   value="<?= $h($csrf) ?>">
          <input type="hidden" name="csrf_action" value="delete_user">

          <div class="row" style="margin-bottom:8px">
            <label class="label" for="confirmId">안전을 위해 아이디를 다시 입력하세요.</label>
            <input class="input" id="confirmId" name="confirmId" type="text" placeholder="아이디를 정확히 입력" autocomplete="off">
          </div>

          <div class="row" style="margin-bottom:12px">
            <input id="agree" type="checkbox"><label for="agree">삭제에 동의합니다.</label>
          </div>

          <div class="row">
            <button type="button" class="ghost" onclick="history.back()">↩️ 취소</button>
            <button type="submit" class="danger" id="deleteBtn" disabled>🗑️ 회원 삭제</button>
          </div>
        </form>
      </div>

      <div class="footer">
        <div style="display:flex; gap:8px; align-items:center;">
          <a class="link" href="main.html">🏠 초기 화면</a>
          <a class="link" href="select.php">📋 회원 목록</a>
        </div>
        <span>단축키: <span style="font-family:monospace">Alt+D</span> 확인 입력 포커스</span>
      </div>
    </section>
  </div>

  <div class="toast" id="toast"></div>
  <script>
    const root = document.documentElement;
    document.getElementById('toggle').addEventListener('click', ()=>{
      root.dataset.theme = (root.dataset.theme === 'light') ? 'dark' : 'light';
      toast(root.dataset.theme === 'light' ? '라이트 테마로 변경되었습니다.' : '다크 테마로 변경되었습니다.');
    });

    const confirmId = document.getElementById('confirmId');
    const agree     = document.getElementById('agree');
    const btn       = document.getElementById('deleteBtn');
    const expectId  = <?= json_encode($userID, JSON_UNESCAPED_UNICODE) ?>;
    function refresh(){ btn.disabled = !(confirmId.value.trim() === expectId && agree.checked); }
    confirmId.addEventListener('input', refresh); agree.addEventListener('change', refresh); refresh();

    window.addEventListener('keydown', (e)=>{ if(e.altKey && e.key.toLowerCase()==='d'){ e.preventDefault(); confirmId.focus(); }});

    const toastBox = document.getElementById('toast');
    function toast(text, ttl=2200){
      const el = document.createElement('div'); el.className = 'msg'; el.textContent = text;
      toastBox.appendChild(el);
      setTimeout(()=>{ el.style.opacity='0'; el.style.transform='translateY(6px)'; }, ttl-300);
      setTimeout(()=>{ toastBox.removeChild(el); }, ttl);
    }
  </script>
</body>
</html>
