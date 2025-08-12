<?php
require_once __DIR__ . '/../Interfaces/MailerInterface.php';
require_once __DIR__ . '/../Libraries/Mail.php';

class MailerService implements MailerInterface
{
    private Mail $mail;

    public function __construct()
    {
        $this->mail = new Mail();
    }

    // Existing method for verification
    public function sendVerification(string $email, string $name): void
    {
        $this->mail->verifyMail($email, $name);
    }

    // New method for contact form messages
    public function sendContactMessage(string $fullName, string $emailAddress, string $subject, string $message): bool
    {
        return $this->mail->sendContactMessage($fullName, $emailAddress, $subject, $message);
    }
}
