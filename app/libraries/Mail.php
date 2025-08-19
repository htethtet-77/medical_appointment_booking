<?php
// require_once __DIR__ . '/../../src/PHPMailer.php';
// require_once __DIR__ . '/../../src/SMTP.php';
// require_once __DIR__ . '/../../src/Exception.php';
namespace Asus\Medical\libraries;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    private $host = 'smtp.gmail.com';
    private $username = 'mediplusappointment@gmail.com';
    private $password = 'rwha rycc hxjg vibf'; // Gmail App Password
    private $port = 587;
    private $fromEmail = 'mediplusappointment@gmail.com';
    private $fromName = 'Medical Appointment Booking System';

    private function getMailer()
    {
        require '../vendor/autoload.php'; // Adjust path as needed

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $this->host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $this->username;
        $mail->Password   = $this->password;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = $this->port;
        $mail->setFrom($this->fromEmail, $this->fromName);
        $mail->isHTML(true);

        return $mail;
    }

    // Existing verification method
    public function verifyMail($recipient_mail, $recipient_name)
    {
        try {
            $mail = $this->getMailer();
            $mail->addAddress($recipient_mail, $recipient_name);
            $mail->Subject = 'Verify Mail';
            // $token = hash_hmac('sha256', $recipient_mail, SECRET_KEY);
            // $verificationLink = "http://localhost:8000/verify?email=" . urlencode($recipient_mail) . "&token=" ;
            $mail->Body = "<b><a href='http://localhost:8000/pages/login' target='_blank'>Click</a></b> Thank you for your registration.";
            $mail->AltBody = 'Visit the verification link to complete registration.';
            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }

    // New contact form email method
    public function sendContactMessage($fullName, $emailAddress, $subject, $message)
    {
        try {
            $mail = $this->getMailer();
            $mail->addAddress($this->fromEmail, 'Admin'); // Send to yourself
            $mail->Subject = "Contact Form Message: $subject";

            $mail->Body = "
                <h2>New Contact Message</h2>
                <p><strong>Name:</strong> {$fullName}</p>
                <p><strong>Email:</strong> {$emailAddress}</p>
                <p><strong>Subject:</strong> {$subject}</p>
                <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
            ";
            $mail->AltBody = strip_tags("Name: $fullName\nEmail: $emailAddress\nSubject: $subject\nMessage: $message");

            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}




?>