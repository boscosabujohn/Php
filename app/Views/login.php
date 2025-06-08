<!-- Login View for FMS Application: Compact Split Layout, Themed, Dropdown Language, RTL/LTR -->
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang === 'ar' ? 'تسجيل الدخول' : 'Login' ?></title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body { height: 100%; margin: 0; background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .split-container { display: flex; min-height: 100vh; flex-direction: row; }
        .split-left { flex: 1 1 0; background: linear-gradient(135deg, var(--primary) 60%, var(--accent) 100%); min-height: 200px; }
        .split-right { flex: 1 1 0; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--secondary) 60%, var(--muted) 100%); }
        .login-card { background: var(--card); color: var(--card-foreground); border-radius: var(--radius); box-shadow: var(--shadow-md); border: 1px solid var(--border); padding: 1.2rem 1rem; min-width: 220px; max-width: 320px; width: 100%; }
        .login-title { font-size: 1.3rem; font-weight: 700; margin-bottom: 1rem; text-align: center; }
        .form-label, .form-control, .form-select { font-family: var(--font-sans); font-size: 1em; }
        .btn { border-radius: var(--radius) !important; box-shadow: var(--shadow-xs); font-family: var(--font-sans); border: none; transition: background 0.2s, color 0.2s; font-size: 1em; padding: 0.5em 0.8em; }
        .btn[data-theme="primary"] { background: var(--primary) !important; color: var(--primary-foreground) !important; }
        .btn[data-theme="accent"] { background: var(--accent) !important; color: var(--accent-foreground) !important; }
        .forgot-link { display: block; margin-top: 0.3rem; text-align: right; color: var(--primary); font-size: 0.9em; text-decoration: underline; cursor: pointer; }
        .rtl .forgot-link { text-align: left; }
        @media (max-width: 900px) { .split-container { flex-direction: column; } .split-left, .split-right { min-height: 120px; flex: none; width: 100%; } }
    </style>
</head>
<body>
    <div class="split-container" id="splitContainer">
        <div class="split-left"></div>
        <div class="split-right">
            <div class="login-card" id="loginCard">
                <div class="login-title">FMS <span data-i18n="loginTitle"><?= $lang === 'ar' ? 'تسجيل الدخول' : 'Login' ?></span></div>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" style="background:var(--destructive);color:var(--destructive-foreground);border:none;">
                        <?= esc($error) ?>
                    </div>
                <?php endif; ?>
                <form id="loginForm" method="post" action="/verify" autocomplete="on">
                    <div class="mb-2">
                        <label for="email" class="form-label" data-i18n="email"> <?= $lang === 'ar' ? 'البريد الإلكتروني' : 'Email' ?> </label>
                        <input type="email" class="form-control" id="email" name="email" required autocomplete="username">
                    </div>
                    <div class="mb-1">
                        <label for="password" class="form-label" data-i18n="password"> <?= $lang === 'ar' ? 'كلمة المرور' : 'Password' ?> </label>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                    </div>
                    <a href="/reset-password" class="forgot-link" id="forgotLink" data-i18n="forgot"> <?= $lang === 'ar' ? 'نسيت كلمة المرور؟' : 'Forgot Password?' ?> </a>
                    <div class="mb-2 mt-2">
                        <label class="form-label" data-i18n="language"> <?= $lang === 'ar' ? 'اللغة' : 'Language' ?> </label>
                        <select class="form-select" id="langSelect" style="max-width:140px;display:inline-block;">
                            <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>English</option>
                            <option value="ar" <?= $lang === 'ar' ? 'selected' : '' ?>>العربية</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn" data-theme="primary" data-i18n="login"> <?= $lang === 'ar' ? 'تسجيل الدخول' : 'Login' ?> </button>
                        <a href="/guest" class="btn" data-theme="accent" data-i18n="guest"> <?= $lang === 'ar' ? 'الدخول كضيف' : 'Continue as Guest' ?> </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    const dict = {
      en: { loginTitle: 'Login', email: 'Email', password: 'Password', forgot: 'Forgot Password?', language: 'Language', login: 'Login', guest: 'Continue as Guest', footer: 'Illustrative purpose only. Actual user will fetch the role automatically.', invalid: 'Invalid email or password', },
      ar: { loginTitle: 'تسجيل الدخول', email: 'البريد الإلكتروني', password: 'كلمة المرور', forgot: 'نسيت كلمة المرور؟', language: 'اللغة', login: 'تسجيل الدخول', guest: 'الدخول كضيف', footer: 'لأغراض توضيحية فقط. المستخدم الفعلي سيتم جلب الدور تلقائيًا.', invalid: 'بيانات الدخول غير صحيحة', }
    };
    function setLang(lang) {
      localStorage.setItem('fms_lang', lang);
      document.documentElement.lang = lang;
      document.body.dir = lang === 'ar' ? 'rtl' : 'ltr';
      document.body.className = lang === 'ar' ? 'rtl' : '';
      document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (dict[lang][key]) el.textContent = dict[lang][key];
      });
      document.getElementById('langSelect').value = lang;
      const container = document.getElementById('splitContainer');
      if (lang === 'ar') { container.insertBefore(container.children[1], container.children[0]); } else { container.insertBefore(container.children[0], container.children[1]); }
    }
    function getLang() { return localStorage.getItem('fms_lang') || 'en'; }
    document.addEventListener('DOMContentLoaded', function() {
      setLang(getLang());
      document.getElementById('langSelect').onchange = function() { setLang(this.value); };
      document.getElementById('loginForm').addEventListener('submit', function(e) {
        let lang = getLang();
        let hidden = document.createElement('input');
        hidden.type = 'hidden'; hidden.name = 'language'; hidden.value = lang; this.appendChild(hidden);
      });
    });
    // --- Login AJAX Logic using API ---
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
      loginForm.onsubmit = async function(e) {
        e.preventDefault();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        // Call get-user-by-key to fetch user info
        let debugMsg = '';
        try {
          const res = await fetch('/api/other/get-user-by-key', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ key: email })
          });
          const data = await res.json();
          debugMsg += 'API response: ' + JSON.stringify(data) + '\n';
          if (data.status === 'ok' && data.data && data.data.length > 0) {
            const user = data.data[0];
            // Check password hash
            const hash = user.password;
            // Password check must be done server-side for security, so submit to /verify
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/verify';
            form.style.display = 'none';
            [
              ['email', email],
              ['password', password],
              ['language', document.getElementById('langSelect') ? document.getElementById('langSelect').value : 'en']
            ].forEach(([k, v]) => {
              const input = document.createElement('input');
              input.type = 'hidden'; input.name = k; input.value = v;
              form.appendChild(input);
            });
            document.body.appendChild(form);
            form.submit();
          } else {
            showLoginError('Invalid email or password');
          }
        } catch (err) {
          showLoginError('AJAX error: ' + err);
        }
      };
    }
    function showLoginError(msg) {
      let alert = document.querySelector('.alert-danger');
      if (!alert) {
        alert = document.createElement('div');
        alert.className = 'alert alert-danger';
        alert.style.background = 'var(--destructive)';
        alert.style.color = 'var(--destructive-foreground)';
        alert.style.border = 'none';
        document.getElementById('loginCard').prepend(alert);
      }
      alert.innerHTML = msg;
    }
    </script>
</body>
</html>
