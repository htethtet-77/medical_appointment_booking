<?php
// If your app defines APPROOT elsewhere, set a harmless default for tests.
if (!defined('APPROOT')) {
    define('APPROOT', __DIR__ . '/..'); // project root
}

// If you already have Composer's autoload, include it:
$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

// You can also predefine harmless shims for helpers if needed later.
if (!function_exists('setMessage')) {
    function setMessage($type, $msg) { /* no-op in unit tests */ }
}
if (!function_exists('redirect')) {
    function redirect($to) { /* no-op in unit tests */ }
}
