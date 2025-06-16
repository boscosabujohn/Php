<?php include_once __DIR__ . '/../../lang.php'; ?>
<!-- Password Reset View for FMS Application: Styled to match login page -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en' ?>" dir="<?= ($_SESSION['lang'] ?? 'en') === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $L['reset_password_form_title'] ?? 'Update Password' ?></title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body { 
            height: 100%; 
            margin: 0; 
            background: linear-gradient(135deg, var(--primary) 0%, hsl(from var(--primary) h s calc(l - 15%)) 100%);
            color: var(--foreground); 
            font-family: var(--font-sans); 
        }
        
        .reset-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }
        
        .reset-card { 
            background: var(--card); 
            color: var(--card-foreground); 
            border-radius: calc(var(--radius) * 2); 
            box-shadow: var(--shadow-2xl); 
            border: 1px solid var(--border); 
            padding: 2.5rem 2rem; 
            width: 100%; 
            max-width: 420px;
            backdrop-filter: blur(10px);
        }
        
        .reset-title { 
            font-size: 1.75rem; 
            font-weight: 800; 
            margin-bottom: 0.5rem; 
            text-align: center; 
            color: var(--foreground);
            letter-spacing: -0.025em;
        }
        
        .reset-subtitle {
            text-align: center;
            color: var(--muted-foreground);
            margin-bottom: 2rem;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label { 
            font-family: var(--font-sans); 
            font-size: 0.875rem; 
            font-weight: 600;
            color: var(--foreground);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .form-control { 
            font-family: var(--font-sans); 
            font-size: 1rem;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            background: var(--background);
            color: var(--foreground);
            transition: all 0.2s ease;
            width: 100%;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px hsl(from var(--primary) h s l / 0.1);
            outline: none;
        }
        
        .btn { 
            border-radius: var(--radius) !important; 
            box-shadow: var(--shadow-md); 
            font-family: var(--font-sans); 
            border: none; 
            font-size: 1rem; 
            font-weight: 600;
            padding: 0.875rem 1.5rem;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .btn[data-theme="primary"] { 
            background: var(--primary) !important; 
            color: var(--primary-foreground) !important; 
        }
        
        .btn[data-theme="primary"]:hover {
            background: hsl(from var(--primary) h s calc(l - 10%)) !important;
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }
        
        .alert { 
            margin-bottom: 1.5rem; 
            font-size: 0.875rem; 
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            border: none;
        }
        
        .alert-success {
            background: hsl(142 76% 96%);
            color: hsl(142 76% 16%);
        }
        
        .alert-danger {
            background: hsl(0 84% 96%);
            color: hsl(0 84% 16%);
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
        }
        
        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            color: hsl(from var(--primary) h s calc(l - 10%));
            text-decoration: underline;
        }
        
        @media (max-width: 480px) { 
            .reset-card { 
                padding: 1.5rem 1rem;
                margin: 0.5rem;
            }
            .reset-title {
                font-size: 1.5rem;
            }
        }
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
    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-title">Update Password</div>
            <div class="reset-subtitle">Enter your email and new password to update your account</div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"> 
                    <?= esc($L[$error] ?? $error) ?> 
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"> 
                    <?= esc($L[$success] ?? $success) ?> 
                </div>
                <div class="login-link">
                    <a href="/login">Return to Login</a>
                </div>
            <?php else: ?>
                <form id="resetForm" method="post" action="/reset-password" autocomplete="off">
                    <div class="form-group">
                        <label for="email" class="form-label"> 
                            <?= $L['label_email'] ?? 'Email' ?> 
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label"> 
                            <?= $L['label_new_password'] ?? 'New Password' ?> 
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label"> 
                            <?= $L['label_confirm_password'] ?? 'Confirm Password' ?> 
                        </label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn w-100" data-theme="primary" id="updateBtn"> 
                        <?= $L['label_save'] ?? 'Update Password' ?> 
                    </button>
                </form>
                
                <div class="login-link">
                    <a href="/login">Back to Login</a>
                </div>
            <?php endif; ?>
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
  // Use language resource for error/success messages
  if (window.L && window.L[msg]) {
    alert.innerHTML = window.L[msg];
  } else {
    alert.innerHTML = msg;
  }
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
  showMessage('processing', 'info');
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
      showMessage('password_updated_success', 'success');
      addDebugLog('Database updated successfully', 'OK');
      setTimeout(() => {
        // Show a message before redirect
        alert('Password updated successfully. Redirecting to login...');
        window.location.href = '/login';
      }, 2000);
    } else {
      showMessage(data.message || 'password_update_failed', 'error');
      updateBtn.disabled = false;
      // Show debug info if failed
      alert('Password update failed. Debug: ' + JSON.stringify(data));
    }
  } catch (err) {
    addDebugLog('AJAX error: ' + err, 'ERROR');
    showMessage('ajax_error', 'error');
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
