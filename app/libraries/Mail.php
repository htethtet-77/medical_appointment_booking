<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{

    public function verifyMail($recipient_mail,$recipient_name)
    {
        // Load Composer's autoloader
        require '../vendor/autoload.php';

        try {

            // Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

            //Server settings
            $mail->SMTPDebug = false;// Enable verbose debug output
            $mail->isSMTP(); // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';// Set the SMTP server to send through
            $mail->SMTPAuth   = true;// Enable SMTP authentication
            $mail->Username   = 'htethtetwin664@gmail.com';// SMTP username
            $mail->Password   = 'evph nvsr raat wgcf';// SMTP password
            $mail->SMTPSecure = 'tls';// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;// TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('htethtetwin664@gmail.com', 'Medical Appointment Booking');  
            $mail->addAddress($recipient_mail,$recipient_name);     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Verify Mail';
            $verificationLink = "https://yourdomain.com/verify?email=" . urlencode($recipient_mail) . "&token=some_unique_token_here";

            $mail->Body = "<b><a href='$verificationLink' target='_blank'>Click here</a></b> to verify your registration.";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $success = $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

}




?>