<!-- Unified Dashboard View for FMS Application -->
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang === 'ar' ? 'لوحة التحكم' : 'Dashboard' ?></title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
    </style>
</head>
<body class="<?= $lang === 'ar' ? 'rtl' : '' ?>">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <span class="navbar-brand">FMS <?= $lang === 'ar' ? 'لوحة التحكم' : 'Dashboard' ?></span>
            <div class="d-flex">
                <span class="navbar-text me-3">👤 <?= esc($user_name) ?></span>
                <a href="/logout" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <?php if (empty($roles)): ?>
            <div class="alert alert-warning text-center mt-5">
                <?= $lang === 'ar' ? 'لم يتم تعيين أي دور' : 'No role assigned' ?>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($roles as $role): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= $lang === 'ar' ? $role === 'Technician' ? 'فني' : ($role === 'Supervisor' ? 'مشرف' : $role) : $role ?>
                                </h5>
                                <p class="card-text">
                                    <?= $lang === 'ar' ? 'الوصول إلى ميزات ' : 'Access features for ' ?><?= $role ?>
                                </p>
                                <!-- Example: role-based actions -->
                                <?php if ($role === 'Technician'): ?>
                                    <a href="#" class="btn btn-success w-100 mb-2"><?= $lang === 'ar' ? 'مهام الفني' : 'Technician Tasks' ?></a>
                                <?php endif; ?>
                                <?php if ($role === 'Supervisor'): ?>
                                    <a href="#" class="btn btn-info w-100 mb-2"><?= $lang === 'ar' ? 'إشراف' : 'Supervision' ?></a>
                                <?php endif; ?>
                                <!-- Add more role-based widgets/actions here -->
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
