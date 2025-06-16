<?php include_once __DIR__ . '/../../lang.php'; ?>
<!-- Login View for FMS Application: Compact Split Layout, Themed, Dropdown Language, RTL/LTR -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en' ?>" dir="<?= ($_SESSION['lang'] ?? 'en') === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $L['login_title'] ?></title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body { 
            height: 100%; 
            margin: 0; 
            background: var(--background); 
            color: var(--foreground); 
            font-family: var(--font-sans); 
        }
        
        .split-container { 
            display: flex; 
            min-height: 100vh; 
            flex-direction: row; 
        }
        
        .split-left { 
            flex: 1 1 0; 
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); 
            min-height: 200px; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .split-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .image-holder {
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }
        
        .image-holder img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
            filter: brightness(1.2);
        }
        
        .split-right { 
            flex: 1 1 0; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: var(--background);
            padding: 2rem;
        }
        
        .login-card { 
            background: var(--card); 
            color: var(--card-foreground); 
            border-radius: calc(var(--radius) * 2); 
            box-shadow: var(--shadow-xl); 
            border: 1px solid var(--border); 
            padding: 3rem 2.5rem; 
            min-width: 320px; 
            max-width: 420px; 
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        
        .login-title { 
            font-size: 2rem; 
            font-weight: 800; 
            margin-bottom: 0.5rem; 
            text-align: center;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .login-subtitle {
            text-align: center;
            color: var(--muted-foreground);
            margin-bottom: 2rem;
            font-size: 0.875rem;
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
        
        .form-select {
            font-family: var(--font-sans);
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            background: var(--background);
            color: var(--foreground);
        }
        
        .btn-enhanced { 
            border-radius: var(--radius); 
            font-family: var(--font-sans); 
            border: none; 
            transition: all 0.2s ease; 
            font-size: 1rem; 
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-bottom: 0.75rem;
        }
        
        .btn-primary-enhanced { 
            background: var(--primary); 
            color: var(--primary-foreground);
        }
        
        .btn-primary-enhanced:hover {
            background: hsl(from var(--primary) h s calc(l - 10%));
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-secondary-enhanced { 
            background: var(--secondary); 
            color: var(--secondary-foreground);
        }
        
        .forgot-link { 
            display: block; 
            margin-top: 1rem; 
            text-align: center; 
            color: var(--primary); 
            font-size: 0.875rem; 
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.2s ease;
        }
        
        .forgot-link:hover {
            color: hsl(from var(--primary) h s calc(l - 15%));
        }
        
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
            color: var(--muted-foreground);
            font-size: 0.875rem;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border);
        }
        
        .divider span {
            background: var(--card);
            padding: 0 1rem;
            position: relative;
            z-index: 1;
        }
        
        .language-selector {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .rtl .forgot-link { text-align: center; }
        
        @media (max-width: 900px) { 
            .split-container { flex-direction: column; } 
            .split-left, .split-right { 
                min-height: 200px; 
                flex: none; 
                width: 100%; 
            }
            .split-right { padding: 1rem; }
            .login-card { 
                padding: 2rem 1.5rem;
                min-width: 280px;
            }
            .image-holder {
                width: 200px;
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="split-container" id="splitContainer">
        <div class="split-left">
            <div class="image-holder">
                <img src="/assets/images/Kreupai.png" alt="Kreupai Logo">
            </div>
        </div>
        <div class="split-right">
            <div class="login-card" id="loginCard">
                <div class="login-title"><?= $L['login_title'] ?></div>
                <?php if (!empty($error)): ?>
                  <div class="alert alert-danger" style="background:var(--destructive);color:var(--destructive-foreground);border:none;"> <?= esc($L[$error] ?? $error) ?> </div>
                <?php endif; ?>
                <form id="loginForm" method="POST" action="/verify">
                    <div class="mb-3">
                        <label class="form-label" for="username"> <?= $L['label_username'] ?? $L['username'] ?? 'Username' ?> </label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="<?= $L['label_username'] ?? $L['username'] ?? 'Username' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password"> <?= $L['label_password'] ?? $L['password'] ?? 'Password' ?> </label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="<?= $L['label_password'] ?? $L['password'] ?? 'Password' ?>">
                    </div>
                    <div class="mb-2 text-end">
                        <a href="/reset-password"> <?= $L['forgot_password'] ?> </a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2"> <?= $L['login_button'] ?> </button>
                </form>
                <div class="text-center my-2">— <?= $L['or'] ?> —</div>
                <a href="/guest" class="btn btn-outline-secondary w-100 mb-2"> <?= $L['guest_button'] ?> </a>
                <div class="text-center mt-2">
                    <?php include __DIR__ . '/partials/lang_switcher.php'; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Show error messages in the selected language
    function showLoginError(msgKey) {
      let alert = document.querySelector('.alert-danger');
      if (!alert) {
        alert = document.createElement('div');
        alert.className = 'alert alert-danger';
        alert.style.background = 'var(--destructive)';
        alert.style.color = 'var(--destructive-foreground)';
        alert.style.border = 'none';
        document.getElementById('loginCard').prepend(alert);
      }
      if (window.L && window.L[msgKey]) {
        alert.innerHTML = window.L[msgKey];
      } else {
        alert.innerHTML = msgKey;
      }
    }
    </script>
</body>
</html>
