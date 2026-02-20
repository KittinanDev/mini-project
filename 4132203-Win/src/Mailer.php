<?php

declare(strict_types=1);

namespace App;

use PHPMailer\PHPMailer\PHPMailer as PHPMailerClass;
use PHPMailer\PHPMailer\Exception as MailerException;

class Mailer
{
    private string $host;
    private int $port;

    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @return array{ok: bool, error: string|null}
     */
    public function send(string $fromEmail, string $fromName, string $toEmail, string $subject, string $body): array
    {
        try {
            $mail = new PHPMailerClass(true);
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->Port = $this->port;
            $mail->SMTPAuth = false;
            $mail->SMTPAutoTLS = false;
            $mail->SMTPSecure = false;

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail);
            $mail->Subject = $subject !== '' ? $subject : 'ข้อความใหม่จากเว็บไซต์';
            $mail->Body = $body !== '' ? $body : '(ไม่มีข้อความ)';
            $mail->AltBody = $mail->Body;
            $mail->isHTML(false);

            $mail->send();

            return ['ok' => true, 'error' => null];
        } catch (MailerException $exception) {
            return ['ok' => false, 'error' => $exception->getMessage()];
        }
    }
}
