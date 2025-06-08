<!-- Password Reset View for FMS Application: Styled to match login page -->
<!DOCTYPE html>
<html lang="<?= $lang ?? 'en' ?>" dir="<?= ($lang ?? 'en') === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ($lang ?? 'en') === 'ar' ? 'تحديث كلمة المرور' : 'Update Password' ?></title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body { height: 100%; margin: 0; background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .reset-card { background: var(--card); color: var(--card-foreground); border-radius: var(--radius); box-shadow: var(--shadow-md); border: 1px solid var(--border); padding: 1.2rem 1rem; min-width: 220px; max-width: 320px; width: 100%; margin: 0 auto; }
        .reset-title { font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem; text-align: center; }
        .form-label, .form-control { font-family: var(--font-sans); font-size: 1em; }
        .btn { border-radius: var(--radius) !important; box-shadow: var(--shadow-xs); font-family: var(--font-sans); border: none; font-size: 1em; padding: 0.5em 0.8em; }
        .btn[data-theme="primary"] { background: var(--primary) !important; color: var(--primary-foreground) !important; }
        .alert { margin-top: 0.7em; font-size: 0.95em; border-radius: var(--radius); }
        @media (max-width: 900px) { .reset-card { min-width: 100px; max-width: 98vw; } }
        <?php if (getenv('CI_ENVIRONMENT') && getenv('CI_ENVIRONMENT') !== 'production'): ?>
        /* Debug Panel Styles */
        .debug-panel { position:fixed; left:0; right:0; bottom:0; z-index:9999; background:var(--card); color:var(--card-foreground); border-radius:var(--radius) var(--radius) 0 0; box-shadow:var(--shadow-md); font-family:var(--font-sans); font-size:1em; padding:0.7em 1em 1.5em 1em; max-height:40vh; overflow-y:auto; }
        .debug-header { font-weight:700; margin-bottom:0.5em; }
        .debug-log { max-height:22vh; overflow-y:auto; font-size:0.97em; margin-bottom:0.7em; }
        .debug-panel button { background:var(--primary); color:var(--primary-foreground); border:none; border-radius:var(--radius); padding:0.4em 1.2em; font-size:1em; box-shadow:var(--shadow-xs); }
        @media (max-width:900px){.debug-panel{font-size:0.95em;}}
        <?php endif; ?>
    </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center" style="min-height:100vh;">
        <div class="reset-card">
            <div class="reset-title"> <?= ($lang ?? 'en') === 'ar' ? 'تحديث كلمة المرور' : 'Update Password' ?> </div>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" style="background:var(--destructive);color:var(--destructive-foreground);border:none;"> <?= esc($error) ?> </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success" style="background:var(--success);color:var(--success-foreground);border:none;text-align:center;"> <?= esc($success) ?> </div>
                <div class="d-grid gap-2 mt-2 mb-2">
                    <button class="btn" data-theme="primary" onclick="window.location.href='/login'">Go to Login</button>
                </div>
            <?php endif; ?>
            <form id="resetForm" method="post" action="" autocomplete="off">
                <div class="mb-3">
                    <label for="email" class="form-label"> <?= ($lang ?? 'en') === 'ar' ? 'البريد الإلكتروني' : 'Email' ?> </label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"> <?= ($lang ?? 'en') === 'ar' ? 'كلمة المرور الجديدة' : 'New Password' ?> </label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label"> <?= ($lang ?? 'en') === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' ?> </label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn w-100" data-theme="primary" id="updateBtn"> <?= ($lang ?? 'en') === 'ar' ? 'تحديث' : 'Update' ?> </button>
            </form>
        </div>
    </div>
    <?php
    $debugLog = [];
    $success = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $debugLog[] = ['msg' => 'Form submitted', 'status' => 'OK'];
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $debugLog[] = ['msg' => 'Email: ' . $email, 'status' => 'OK'];
        if ($password !== $confirmPassword) {
            $debugLog[] = ['msg' => 'Passwords do not match', 'status' => 'ERROR'];
            $error = 'Passwords do not match';
        } else {
            $debugLog[] = ['msg' => 'Passwords match', 'status' => 'OK'];
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $debugLog[] = ['msg' => 'Hash generated: ' . $hash, 'status' => 'OK'];
            try {
                $db = \Config\Database::connect();
                $updated = $db->query("UPDATE fms_users SET password = ? WHERE email = ?", [$hash, $email]);
                $debugLog[] = ['msg' => 'Database update attempted for email: ' . $email, 'status' => 'OK'];
                if ($updated && $db->affectedRows() > 0) {
                    $debugLog[] = ['msg' => 'Database updated successfully', 'status' => 'OK'];
                    $success = 'Password updated successfully';
                } else {
                    $debugLog[] = ['msg' => 'Database update failed: No rows affected', 'status' => 'ERROR'];
                    $error = 'Failed to update password';
                }
            } catch (\Throwable $e) {
                $debugLog[] = ['msg' => 'Database update failed: ' . $e->getMessage(), 'status' => 'ERROR'];
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    ?>
    <!-- Debug Panel: Always visible -->
    <div class="debug-panel" id="debugPanel">
      <div class="debug-header">Debug Log</div>
      <div class="debug-log" id="debugLog">
        <?php foreach ($debugLog as $log): ?>
          <div><span style="font-weight:600">[<?= esc($log['status']) ?>]</span> <?= date('H:i:s') ?>: <?= esc($log['msg']) ?></div>
        <?php endforeach; ?>
      </div>
      <button id="ackBtn" onclick="acknowledgeDebugPanel()" <?= (empty($debugLog) || end($debugLog)['status'] !== 'OK' || (end($debugLog)['msg'] !== 'Database updated successfully')) ? 'disabled' : '' ?>>Acknowledge and Close</button>
    </div>
    <script>
    function acknowledgeDebugPanel() {
      document.getElementById('debugPanel').style.display = 'none';
    }
    </script>
    <script>
// --- Password Reset AJAX Logic using API ---
const resetForm = document.getElementById('resetForm');
const updateBtn = document.getElementById('updateBtn');
const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('confirm_password');
const emailInput = document.getElementById('email');
const debugLog = [];
function addDebugLog(msg, status) {
  debugLog.push({msg, status, time: new Date().toLocaleTimeString()});
  renderDebugLog();
}
function renderDebugLog() {
  const logDiv = document.getElementById('debugLog');
  if (!logDiv) return;
  logDiv.innerHTML = debugLog.map(e => `<span style='font-weight:600'>[${e.status}]</span> ${e.time}: ${e.msg}`).join('<br>');
}
function acknowledgeDebugPanel() {
  document.getElementById('debugPanel').style.display = 'none';
}
function showMessage(msg, type) {
  let alert = document.querySelector('.alert-' + (type === 'success' ? 'success' : 'danger'));
  if (!alert) {
    alert = document.createElement('div');
    alert.className = 'alert alert-' + (type === 'success' ? 'success' : 'danger');
    alert.style.background = type === 'success' ? 'var(--success)' : 'var(--destructive)';
    alert.style.color = type === 'success' ? 'var(--success-foreground)' : 'var(--destructive-foreground)';
    alert.style.border = 'none';
    resetForm.before(alert);
  }
  alert.innerHTML = msg;
  alert.style.display = '';
}
function hideMessage() {
  let alert = document.querySelector('.alert-danger');
  if (alert) alert.style.display = 'none';
}
// Hide error on input if passwords now match
passwordInput.oninput = confirmInput.oninput = function() {
  if (passwordInput.value && confirmInput.value && passwordInput.value === confirmInput.value) {
    hideMessage();
  }
};
resetForm.onsubmit = async function(e) {
  e.preventDefault();
  addDebugLog('Form submitted', 'OK');
  const email = emailInput.value.trim();
  const password = passwordInput.value;
  const confirm = confirmInput.value;
  if (password !== confirm) {
    addDebugLog('Passwords do not match', 'ERROR');
    showMessage('Passwords do not match', 'error');
    return;
  }
  addDebugLog('Passwords match', 'OK');
  updateBtn.disabled = true;
  addDebugLog('Calling API: update-user-password-by-key', 'OK');
  try {
    const res = await fetch('/api/other/update-user-password-by-key', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ key: email, password })
    });
    const data = await res.json();
    addDebugLog('API response: ' + JSON.stringify(data), data.status === 'ok' ? 'OK' : 'ERROR');
    if (data.status === 'ok') {
      showMessage('Password updated successfully', 'success');
      addDebugLog('Database updated successfully', 'OK');
      setTimeout(() => { window.location.href = 'login.php'; }, 2000);
    } else {
      showMessage(data.message || 'Failed to update password', 'error');
      updateBtn.disabled = false;
    }
  } catch (err) {
    addDebugLog('AJAX error: ' + err, 'ERROR');
    showMessage('An error occurred while processing your request. Please try again or contact support.', 'error');
    updateBtn.disabled = false;
  }
};
addDebugLog('Form loaded', 'OK');
</script>
    <!-- <script>
    document.getElementById('updateBtn').addEventListener('click', function(e) {
      alert('Update clicked');
    });
    </script> -->
</body>
</html>
