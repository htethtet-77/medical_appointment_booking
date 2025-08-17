<?php
namespace Asus\Medical\Middleware;

class AuthMiddleware
{
    private static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function allowRoles(array $allowedRoles)
    {
        self::startSession();

        if (!isset($_SESSION['current_user']) || !in_array($_SESSION['current_user']['type_id'], $allowedRoles)) {
            // Redirect depending on role or login status
            header("Location: " . URLROOT . "/pages/login");
            exit();
        }
    }
}
