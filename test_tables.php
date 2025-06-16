<?php

// Load CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require_once realpath(__DIR__) . '/vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

// Connect to database
$db = \Config\Database::connect();

// Get all FMS tables
echo "FMS Tables:\n";
$query = $db->query("SHOW TABLES LIKE 'fms_%'");
$tables = $query->getResultArray();
foreach($tables as $table) {
    echo "- " . array_values($table)[0] . "\n";
}

// Check stored procedures
echo "\nFMS Stored Procedures:\n";
$query = $db->query("SHOW PROCEDURE STATUS WHERE Name LIKE 'fms_%'");
$procedures = $query->getResultArray();
foreach($procedures as $proc) {
    echo "- " . $proc['Name'] . "\n";
}

echo "\nDone.\n";
