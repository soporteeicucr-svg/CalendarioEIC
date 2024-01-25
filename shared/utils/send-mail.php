<?php
use PHPMailer\PHPMailer\PHPMailer;

include 'variables.php';

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    require $_SERVER['DOCUMENT_ROOT'] . $rootDir . '/shared/lib/phpmailer/src/PHPMailer.php';
    require $_SERVER['DOCUMENT_ROOT'] . $rootDir . '/shared/lib/phpmailer/src/SMTP.php';
    require $_SERVER['DOCUMENT_ROOT'] . $rootDir . '/shared/lib/phpmailer/src/Exception.php';
} else {
    require $_SERVER['DOCUMENT_ROOT'] . '/shared/lib/phpmailer/src/PHPMailer.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/shared/lib/phpmailer/src/SMTP.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/shared/lib/phpmailer/src/Exception.php';
}

function sendMail($address, $subject, $message, $header)
{
    include 'variables.php';
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->Host = 'smtp.ucr.ac.cr';
        $mail->SMTPAuth = true;
        $mail->Username = $mailAddress;
        $mail->Password = $mailPassword;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($mailAddress, $mailAlias);
        $mail->addAddress($address);

        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mailContent = "$header$message";
        $mail->Body = $mailContent;

        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (exception $e) {
    }
}

function notifyMail($subject, $message, $header, $approvalRequired)
{
    include 'variables.php';
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->Host = 'smtp.ucr.ac.cr';
        $mail->SMTPAuth = true;
        $mail->Username = $mailAddress;
        $mail->Password = $mailPassword;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($mailAddress, $mailAlias);
        $mail->addAddress($administratorMail);
        if ($approvalRequired) {
            $mail->addAddress($principalMail);
        }

        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mailContent = "$header$message";
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