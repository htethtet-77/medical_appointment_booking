<?php

require_once "config/config.php";
require_once "helpers/url_helper.php";
require_once "helpers/message_helper.php";
require_once "helpers/UserValidator.php";


spl_autoload_register(function ($class) {
    $prefix = 'Asus\\Medical\\';
    $base_dir = __DIR__ . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Autoloader error: Class: $class Expected: $file");
    }
});
