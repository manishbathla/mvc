<?php

/**
 * File: Mail.php.
 * Author: Self
 * Standard: PSR-12.
 * Do not change codes without permission.
 * Date: 4/2/2020
 */

namespace App;

class Mail
{
    public static function send($to, $subject, $text, $html)
    {
        $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
            ->setUsername('manishdakshbathla@gmail.com')
            ->setPassword('googletumc')
        ;

        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message('Wonderful Subject'))
            ->setFrom(['john@doe.com' => 'John Doe'])
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($html)
            ->addPart($text)
        ;
        $mailer->send($message);
    }
}