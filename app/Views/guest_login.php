<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Login - FMS</title>
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
        
        .guest-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem 1rem;
        }
        
        .guest-card { 
            background: var(--card); 
            color: var(--card-foreground); 
            border-radius: calc(var(--radius) * 2); 
            box-shadow: var(--shadow-2xl); 
            border: 1px solid var(--border); 
            padding: 3rem; 
            width: 100%; 
            max-width: 500px;
            backdrop-filter: blur(10px);
            text-align: center;
        }
        
        .guest-title { 
            font-size: 2.5rem; 
            font-weight: 800; 
            margin-bottom: 1rem; 
            color: var(--foreground);
            letter-spacing: -0.025em;
        }
        
        .guest-subtitle {
            color: var(--muted-foreground);
            margin-bottom: 2.5rem;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .welcome-message {
            background: var(--muted);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary);
        }
        
        .welcome-message h4 {
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .welcome-message p {
            color: var(--muted-foreground);
            margin-bottom: 0;
            font-size: 0.95rem;
        }
        
        .btn-primary-enhanced {
            background: var(--primary);
            color: var(--primary-foreground);
            border: none;
            padding: 0.875rem 2rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0.5rem;
        }
        
        .btn-primary-enhanced:hover {
            background: hsl(from var(--primary) h s calc(l - 10%));
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
            color: var(--primary-foreground);
        }
        
        .btn-secondary-enhanced {
            background: var(--secondary);
            color: var(--secondary-foreground);
            border: 1px solid var(--border);
            padding: 0.875rem 2rem;
            border-radius: var(--radius);
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0.5rem;
        }
        
        .btn-secondary-enhanced:hover {
            background: var(--accent);
            color: var(--accent-foreground);
            border-color: var(--accent);
            transform: translateY(-1px);
        }
        
        .divider {
            margin: 2rem 0;
            text-align: center;
            position: relative;
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
            color: var(--muted-foreground);
            font-size: 0.875rem;
        }
        
        @media (max-width: 576px) {
            .guest-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .guest-title {
                font-size: 2rem;
            }
            
            .btn-primary-enhanced,
            .btn-secondary-enhanced {
                width: 100%;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="guest-container">
        <div class="guest-card">
            <h1 class="guest-title">Welcome, Guest!</h1>
            <p class="guest-subtitle">Access the tenant portal and manage your maintenance requests</p>
            
            <div class="welcome-message">
                <h4>🏠 New Tenant?</h4>
                <p>Register now to access your tenant portal and submit maintenance requests online.</p>
            </div>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-enhanced mb-3">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-enhanced mb-3">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <div class="d-flex flex-column align-items-center">
                <a href="/tenant" class="btn-primary-enhanced">
                    📝 Register as New Tenant
                </a>
                
                <div class="divider">
                    <span>or</span>
                </div>
                
                <a href="/login" class="btn-secondary-enhanced">
                    🔐 Staff Login
                </a>
            </div>
            
            <div class="mt-4">
                <small class="text-muted">
                    Need help? Contact your property manager or building administrator.
                </small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
