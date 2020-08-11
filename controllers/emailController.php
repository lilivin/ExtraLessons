<?php

    require_once 'vendor/autoload.php';
    require_once 'config/constants.php';

    // Create the Transport
    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername(EMAIL)
    ->setPassword(PASSWORD);
    ;

    // Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

    function sendVerificationEmail($userEmail, $token){
        // Create a message
        global $mailer;

        $body = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Verify email</title>
            </head>
            <body>
                <p>Dziękujemy za założenie konta. Kliknij, aby zweryfikować email.</p>
                <a href="http://localhost/korki/index.php?token=' . $token . '">Verify email</a>
            </body>
            </html>
        ';

        $message = (new Swift_Message('Verify your email address'))
        ->setFrom(EMAIL)
        ->setTo($userEmail)
        ->setBody($body, 'text/html');

        // Send the message
        $result = $mailer->send($message);
    }

    function sendPasswordResetLink($userEmail, $token) {
        // Create a message
        global $mailer;

        $body = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Verify email</title>
            </head>
            <body>
                <p>Kliknij, aby zresetować swoje hasło</p>
                <a href="http://localhost/korki/index.php?password-token=' . $token . '">Resetuj hasło</a>
            </body>
            </html>
        ';

        $message = (new Swift_Message('Reset your password'))
        ->setFrom(EMAIL)
        ->setTo($userEmail)
        ->setBody($body, 'text/html');

        // Send the message
        $result = $mailer->send($message);
    }

?>