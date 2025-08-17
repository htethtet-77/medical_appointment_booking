<?php
namespace Asus\Medical\helpers;

use Asus\Medical\Middleware\CsrfMiddleware;

if (!function_exists('csrfInput')) {
    /**
     * Generates a hidden CSRF input for forms
     * @return string
     */
    function csrfInput(): string
    {
        $token = CsrfMiddleware::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}
