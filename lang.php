<?php
session_start();
$lang = $_SESSION['lang'] ?? 'en';
$lang_file = __DIR__ . "/lang/{$lang}.php";
if (!file_exists($lang_file)) {
    $lang_file = __DIR__ . "/lang/en.php";
}
$L = require $lang_file;
