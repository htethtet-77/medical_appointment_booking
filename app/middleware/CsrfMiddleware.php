<?php
namespace Asus\Medical\Middleware;

class CsrfMiddleware
{
    // Generate a CSRF token and store it in session
    public static function generateToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // If token already exists and is not expired, keep it
        $maxAge = 5 * 60; // 5 minutes
        $token = $_SESSION['csrf_token'] ?? null;
        $tokenTime = $_SESSION['csrf_token_time'] ?? 0;

        if (!$token || (time() - $tokenTime) > $maxAge) {
            $token = bin2hex(random_bytes(32)); // 64-character token
            $_SESSION['csrf_token'] = $token;
            $_SESSION['csrf_token_time'] = time();
        }

        return $token;
    }

    // Validate token from POST request
    public static function validateToken(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sessionToken = $_SESSION['csrf_token'] ?? null;
        $sessionTime  = $_SESSION['csrf_token_time'] ?? 0;
        $postToken    = $_POST['csrf_token'] ?? null;
        $maxAge       = 5 * 60; // 5 minutes

        if (
            !$sessionToken ||
            !$postToken ||
            !hash_equals($sessionToken, $postToken) ||
            (time() - $sessionTime) > $maxAge
        ) {
            http_response_code(419);
            require_once APPROOT . '/views/errors/csrf.php';
            exit;
        }

        // ✅ Do NOT unset here → allows refresh to work
    }
}
