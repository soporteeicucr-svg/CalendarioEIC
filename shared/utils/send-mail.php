<?php
use PHPMailer\PHPMailer\PHPMailer;

require $_SERVER['DOCUMENT_ROOT'] . '/shared/lib/phpmailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/shared/lib/phpmailer/src/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/shared/lib/phpmailer/src/Exception.php';

function sendMail($address, $subject, $message)
{
    include 'variables.php';
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->Host = 'smtp.ucr.ac.cr';
        $mail->SMTPAuth = true;
        $mail->Username = $mailUser;
        $mail->Password = $mailPassword;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('ingenieria.civil@ucr.ac.cr', 'Sistema de reserva de espacios EIC');
        $mail->addAddress($address);

        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mailContent = "<h3>No contestar a este correo</h3>$message";
        $mail->Body = $mailContent;

        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (exception $e) {
    }
}
?>