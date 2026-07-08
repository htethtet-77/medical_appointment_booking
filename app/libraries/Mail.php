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
            $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset='UTF-8'>
        <style>
            body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
            }
            .email-container {
            background: white;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            }
            .btn {
            display: inline-block;
            padding: 12px 20px;
            margin-top: 20px;
            font-size: 16px;
            color: white;
            background-color: #28a745;
            text-decoration: none;
            border-radius: 5px;
            }
            .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #888;
            }
        </style>
        </head>
        <body>
        <div class='email-container'>
            <h2>Verify Your Email</h2>
            <p>Thank you for registering with us, <b>{$recipient_name}</b>!</p>
            <p>Please click the button below to verify your email address:</p>
            <a class='btn' href='http://localhost:8000/pages/login' target='_blank'>Verify Email</a>
            <div class='footer'>
            If you did not sign up for this account, you can safely ignore this email.
            </div>
        </div>
        </body>
        </html>
        ";

        $mail->AltBody = "Thank you for registering, {$recipient_name}! Please verify your email by visiting: http://localhost:8000/pages/login";
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