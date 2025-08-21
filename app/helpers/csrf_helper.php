<?php
namespace Asus\Medical\helpers;

use Asus\Medical\Middleware\CsrfMiddleware;

if (!function_exists('csrfInput')) {
    function csrfInput(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = CsrfMiddleware::generateToken();
        return '<input type="hidden" id="csrf_token" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
