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
        $mg = new Mailgun(Config::MAILGUN_API_KEY);
        $domain = Config::MAILGUN_DOMAIN;

        $mg->sendMessage(
            $domain,
            [
            'from' => 'your-sender@your-domain.com',
            'to' => $to,
            'subject' => $subject,
            'text' => $text,
            'html' => $html
             ]
        );

        $transport = (new \Swift_SmtpTransport('smtp.example.org', 25))
            ->setUsername('your username')
            ->setPassword('your password')
        ;

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Create a message
        $message = (new \Swift_Message('Wonderful Subject'))
            ->setFrom(['john@doe.com' => 'John Doe'])
            ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
            ->setBody('Here is the message itself')
        ;

        $mailer->send($message);
    }
}