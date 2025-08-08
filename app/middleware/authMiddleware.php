<?php
class AuthMiddleware
{
    // Ensure session is started once
    private static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function adminOnly()
    {
        self::startSession();

        if (!isset($_SESSION['current_user']) || $_SESSION['current_user']['type_id'] != ROLE_ADMIN) {
            header("Location: " . URLROOT . "/pages/login");
            exit();
        }
    }

    public static function doctorOnly()
    {
        self::startSession();

        if (!isset($_SESSION['current_user']) || $_SESSION['current_user']['type_id'] != ROLE_DOCTOR) {
            header("Location: " . URLROOT . "/pages/login");
            exit();
        }
    }

    public static function patientOnly()
    {
        self::startSession();

        if (!isset($_SESSION['current_user']) || $_SESSION['current_user']['type_id'] != ROLE_PATIENT) {
            header("Location: " . URLROOT . "/pages/register");
            exit();
        }
    }

    public static function allowRoles(array $allowedRoles)
    {
        self::startSession();

        if (!isset($_SESSION['current_user']) || !in_array($_SESSION['current_user']['type_id'], $allowedRoles)) {
            header("Location: " . URLROOT . "/pages/login");
            exit();
        }
    }
}
