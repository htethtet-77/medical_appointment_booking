<?php
// app/Interfaces/MailerInterface.php
interface MailerInterface {
    public function sendVerification(string $email, string $name): void;
    public function sendContactMessage(string $fullName, string $emailAddress, string $subject, string $message): bool;

}

?>