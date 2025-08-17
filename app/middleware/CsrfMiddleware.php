<?php
namespace Asus\Medical\Middleware;

class CsrfMiddleware
{
    // Generate a CSRF token and store it in session
    public static function generateToken(): string
    {
        if (!session_id()) {
            session_start();
        }

        $token = bin2hex(random_bytes(32)); // 64-character token
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    // Validate token from POST request
     public static function validateToken(): void
    {
        $sessionToken = $_SESSION['csrf_token'] ?? null;
        $postToken    = $_POST['csrf_token'] ?? null;

        if (!$sessionToken || !$postToken || !hash_equals($sessionToken, $postToken)) {
            // Stop execution and show CSRF error page
            http_response_code(419);
            require_once APPROOT . '/views/errors/csrf.php';
            exit;
        }

        // Token is valid, remove it to prevent reuse
        unset($_SESSION['csrf_token']);
    }
}
