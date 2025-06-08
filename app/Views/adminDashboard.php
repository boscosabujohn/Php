<?php
require_once FCPATH . '../app/Config/init.php';

// Check if user is logged in
if (!session()->get('user_id')) {
    header('Location: /login');
    exit;
}

// Your dashboard HTML content here
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome <?= session()->get('user_name') ?></h1>
    <!-- Your dashboard content -->
</body>
</html>