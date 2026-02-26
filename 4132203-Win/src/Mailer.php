<?php

declare(strict_types=1);

namespace App;

use PHPMailer\PHPMailer\PHPMailer as PHPMailerClass;
use PHPMailer\PHPMailer\Exception as MailerException;

class Mailer
{
    // โฮสต์ SMTP ที่ใช้ส่งอีเมล
    private string $host;
    // พอร์ต SMTP (เช่น 1025 สำหรับ MailHog หรือ 587 สำหรับ SMTP จริง)
    private int $port;
    // ชื่อผู้ใช้ SMTP (ถ้ามี)
    private string $username;
    // รหัสผ่าน SMTP (ถ้ามี)
    private string $password;
    // อีเมลผู้ส่งของระบบ
    private string $senderEmail;
    // ชื่อผู้ส่งของระบบ
    private string $senderName;

    public function __construct(
        string $host,
        int $port,
        string $username,
        string $password,
        string $senderEmail,
        string $senderName
    )
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->senderEmail = $senderEmail;
        $this->senderName = $senderName;
    }

    /**
     * @return array{ok: bool, error: string|null}
     */
    public function send(string $fromEmail, string $fromName, string $toEmail, string $subject, string $body): array
    {
        try {
            $mail = new PHPMailerClass(true);

            // 1) ตั้งค่าการเชื่อมต่อ SMTP
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->Port = $this->port;
            $mail->SMTPAuth = $this->username !== '' && $this->password !== '';
            if ($mail->SMTPAuth) {
                $mail->Username = $this->username;
                $mail->Password = $this->password;
            }
            $mail->SMTPAutoTLS = false;
            $mail->SMTPSecure = false;

            // 2) ใช้อีเมลผู้ส่งของระบบ (ทำงานได้เสถียรกว่ากับ SMTP จริง)
            $mail->setFrom($this->senderEmail, $this->senderName);
            // 3) เก็บอีเมลผู้ใช้ไว้ที่ Reply-To เพื่อให้ผู้ดูแลกดตอบกลับได้ทันที
            if (filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
                $mail->addReplyTo($fromEmail, $fromName);
            }

            $mail->addAddress($toEmail);
            // ป้องกันค่าว่างด้วยค่า fallback
            $mail->Subject = $subject !== '' ? $subject : 'ข้อความใหม่จากเว็บไซต์';
            $mail->Body = $body !== '' ? $body : '(ไม่มีข้อความ)';
            $mail->AltBody = $mail->Body;
            $mail->isHTML(false);

            // สั่งส่งอีเมลจริงผ่าน SMTP server
            $mail->send();

            return ['ok' => true, 'error' => null];
        } catch (MailerException $exception) {
            return ['ok' => false, 'error' => $exception->getMessage()];
        }
    }
}
