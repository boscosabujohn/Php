<?php include_once __DIR__ . '/../../lang.php'; ?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en' ?>" dir="<?= ($_SESSION['lang'] ?? 'en') === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $L['admin_dashboard'] ?? 'Admin Test Dashboard' ?></title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .test-links { max-width: 800px; margin: 2rem auto; background: var(--card); border-radius: var(--radius); box-shadow: var(--shadow-md); padding: 2rem; }
        .test-links h2 { text-align: center; margin-bottom: 1.5rem; }
        .test-links h4 { border-bottom: 2px solid; padding-bottom: 0.5rem; }
        .test-links ul { list-style: none; padding: 0; }
        .test-links li { margin-bottom: 0.5rem; }
        .test-links a { font-size: 1rem; }
        .badge { font-size: 0.75em; }
    </style>
</head>
<body>
    <div class="test-links">
        <h2><?= $L['admin_dashboard'] ?? 'Admin Test Dashboard' ?></h2>
        
        <?php $session = session(); $isLoggedIn = $session->has('user_id'); ?>
        
        <div class="alert <?= $isLoggedIn ? 'alert-success' : 'alert-warning' ?> text-center mb-4">
            <?php if ($isLoggedIn): ?>
                ✅ <strong>Session Active</strong> - User: <?= $session->get('user_name') ?> | Roles: <?= implode(', ', $session->get('roles') ?? []) ?>
                <br><a href="/logout" class="btn btn-sm btn-outline-danger mt-2">Logout</a>
            <?php else: ?>
                ⚠️ <strong>No Active Session</strong> - Dashboard pages will redirect to login
                <br><a href="/test_login" class="btn btn-sm btn-primary mt-2">Create Test Session</a>
            <?php endif; ?>
        </div>
        
        <h4 class="mt-4 mb-3 text-primary">Authentication & Basic Pages</h4>
        <ul>
            <li><a href="/login" class="btn btn-outline-primary w-100 mb-2">Login</a></li>
            <li><a href="/test_login" class="btn btn-outline-info w-100 mb-2">🧪 Test Login (Mock Session)</a></li>
            <li><a href="/reset-password" class="btn btn-outline-primary w-100 mb-2">Reset Password</a></li>
            <li><a href="/guest" class="btn btn-outline-secondary w-100 mb-2">Guest Access</a></li>
            <li><a href="/tenant" class="btn btn-outline-secondary w-100 mb-2">Tenant Registration</a></li>
            <li><a href="/logout" class="btn btn-outline-danger w-100 mb-2">Logout</a></li>
        </ul>

        <h4 class="mt-4 mb-3 text-success">Dashboards</h4>
        <ul>
            <li><a href="/dashboard" class="btn btn-outline-success w-100 mb-2">Main Dashboard</a></li>
            <li><a href="/admin_dashboard" class="btn btn-outline-success w-100 mb-2">Admin Dashboard</a></li>
            <li><a href="/supervisor_dashboard" class="btn btn-outline-success w-100 mb-2">Supervisor Dashboard</a></li>
            <li><a href="/technician_dashboard" class="btn btn-outline-success w-100 mb-2">Technician Dashboard</a></li>
            <li><a href="/planner_dashboard" class="btn btn-outline-success w-100 mb-2">Planner Dashboard</a></li>
        </ul>

        <h4 class="mt-4 mb-3 text-info">Management Pages</h4>
        <ul>
            <li><a href="/tenants_management" class="btn btn-outline-info w-100 mb-2">Tenants Management</a></li>
            <li><a href="/users_management" class="btn btn-outline-info w-100 mb-2">Users Management</a></li>
            <li><a href="/role_permissions_management" class="btn btn-outline-info w-100 mb-2">Role Permissions Management</a></li>
            <li><a href="/properties_management" class="btn btn-outline-info w-100 mb-2">Properties Management</a></li>
            <li><a href="/lookup_types_management" class="btn btn-outline-info w-100 mb-2">Lookup Types Management</a></li>
            <li><a href="/lookup_types_values_management" class="btn btn-outline-info w-100 mb-2">Lookup Types Values Management</a></li>
            <li><a href="/teams_management" class="btn btn-outline-info w-100 mb-2">Teams Management</a></li>
            <li><a href="/technician_skills_management" class="btn btn-outline-info w-100 mb-2">Technician Skills Management</a></li>
        </ul>

        <h4 class="mt-4 mb-3 text-warning">Workflow & Operations</h4>
        <ul>
            <li><a href="/tenant_portal" class="btn btn-outline-warning w-100 mb-2">Tenant Portal</a></li>
            <li><a href="/workflow/escalations" class="btn btn-outline-warning w-100 mb-2">Workflow Escalations</a></li>
            <li><a href="/assignment/1" class="btn btn-outline-warning w-100 mb-2">Assignment (Sample)</a></li>
            <li><a href="/tenant_signature/1" class="btn btn-outline-warning w-100 mb-2">Tenant Signature (Sample)</a></li>
            <li><a href="/feedback/1" class="btn btn-outline-warning w-100 mb-2">Feedback (Sample)</a></li>
            <li><a href="/technician_feedback/1" class="btn btn-outline-warning w-100 mb-2">Technician Feedback (Sample)</a></li>
            <li><a href="/supervisor_review/1" class="btn btn-outline-warning w-100 mb-2">Supervisor Review (Sample)</a></li>
        </ul>

        <h4 class="mt-4 mb-3 text-secondary">API Endpoints (for testing)</h4>
        <ul>
            <li><span class="badge bg-secondary me-2">POST</span>/api/user/login</li>
            <li><span class="badge bg-secondary me-2">POST</span>/api/crud/create</li>
            <li><span class="badge bg-secondary me-2">POST</span>/api/crud/update</li>
            <li><span class="badge bg-secondary me-2">POST</span>/api/crud/delete</li>
            <li><span class="badge bg-secondary me-2">POST</span>/api/crud/filter</li>
            <li><span class="badge bg-secondary me-2">POST</span>/api/other/call</li>
            <li><span class="badge bg-secondary me-2">POST</span>/api/other/get-user-by-key</li>
            <li><span class="badge bg-secondary me-2">POST</span>/api/other/update-user-password-by-key</li>
        </ul>

        <div class="mt-4 p-3 bg-light rounded">
            <h6 class="text-muted">Status Report</h6>
            <div class="row">
                <div class="col-4">
                    <small class="text-success">✅ <strong>Working</strong></small><br>
                    <small>• Login System<br>• Reset Password<br>• All Dashboards<br>• Management Pages<br>• Test Navigation</small>
                </div>
                <div class="col-4">
                    <small class="text-info">ℹ️ <strong>Available</strong></small><br>
                    <small>• Mock Session Testing<br>• All CRUD Operations<br>• Workflow Escalations<br>• Multi-language Support</small>
                </div>
                <div class="col-4">
                    <small class="text-warning">🔧 <strong>Next Steps</strong></small><br>
                    <small>• Database Testing<br>• User Role Permissions<br>• Production Deployment</small>
                </div>
            </div>
            <div class="mt-3 text-center">
                <small class="text-muted">🎯 <strong>Quick Access:</strong> 
                    <a href="/test_login" class="btn btn-sm btn-outline-primary mx-1">Mock Login</a>
                    <a href="/admin_dashboard" class="btn btn-sm btn-outline-success mx-1">Admin</a>
                    <a href="/logout" class="btn btn-sm btn-outline-danger mx-1">Logout</a>
                </small>
            </div>
        </div>
    </div>
</body>
</html>
