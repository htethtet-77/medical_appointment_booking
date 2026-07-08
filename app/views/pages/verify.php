<?php
require_once 'config.php'; // includes DB connection (Database class)

$token = $_GET['token'] ?? '';

if ($token) {
    $sql = "SELECT * FROM email_verification_tokens WHERE token = :token";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':token' => $token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Activate the user
        $sql = "UPDATE users SET is_confirmed = 1 WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $row['user_email']]);

        // Delete token after verification
        $sql = "DELETE FROM email_verification_tokens WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $row['id']]);

        echo "Email verified! You can now log in.";
    } else {
        echo "Invalid or expired verification link.";
    }
} else {
    echo "No token provided.";
}
?>