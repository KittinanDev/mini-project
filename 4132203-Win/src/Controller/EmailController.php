<?php

declare(strict_types=1);

namespace App\Controller;

use App\Mailer;

class EmailController
{
    // service สำหรับส่งอีเมลผ่าน SMTP
    private Mailer $mailer;
    // อีเมลปลายทางของผู้ดูแลระบบ
    private string $adminEmail;

    public function __construct()
    {
        // โหลดค่าคอนฟิกจาก environment พร้อมค่าเริ่มต้นที่ปลอดภัย
        $smtpHost = getenv('SMTP_HOST') ?: '127.0.0.1';
        $smtpPort = (int) (getenv('SMTP_PORT') ?: 1025);
        $smtpUsername = getenv('SMTP_USERNAME') ?: '';
        $smtpPassword = getenv('SMTP_PASSWORD') ?: '';
        $senderEmail = getenv('SENDER_EMAIL') ?: 'noreply@yoursite.com';
        $senderName = getenv('SENDER_NAME') ?: 'Contact Form System';

        $this->mailer = new Mailer(
            $smtpHost,
            $smtpPort,
            $smtpUsername,
            $smtpPassword,
            $senderEmail,
            $senderName
        );

        // ผู้รับข้อความจากฟอร์มติดต่อ
        $this->adminEmail = getenv('ADMIN_EMAIL') ?: 'admin@example.com';
    }

    public function showSendForm(): void
    {
        // แสดงหน้าเริ่มต้นพร้อมค่า form ว่าง
        $this->render('send.php', [
            'title' => 'Contact Form',
            'flash' => null,
            'formData' => ['name' => '', 'email' => '', 'message' => '']
        ]);
    }

    public function sendEmail(): void
    {
        // จัดรูปแบบและรวบรวมข้อมูลที่ผู้ใช้ส่งมาจาก POST
        $formData = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'message' => trim($_POST['message'] ?? '')
        ];

        // ตรวจสอบข้อมูลก่อนพยายามส่งอีเมล
        $flash = $this->validateEmailForm($formData);

        if ($flash === null) {
            // เตรียมหัวเรื่องและเนื้อหาอีเมลให้อยู่ในรูปแบบอ่านง่าย
            $subject = 'ข้อความใหม่จากเว็บไซต์';
            $body = "ชื่อ: {$formData['name']}\n";
            $body .= "อีเมล: {$formData['email']}\n\n";
            $body .= "ข้อความ:\n{$formData['message']}";

            // ส่งเข้าอีเมลผู้ดูแล โดยเก็บอีเมลผู้ใช้ไว้ใน Reply-To
            $result = $this->mailer->send(
                $formData['email'],
                $formData['name'],
                $this->adminEmail,
                $subject,
                $body
            );

            if ($result['ok']) {
                $flash = ['type' => 'success', 'message' => 'ส่งข้อความเรียบร้อย ระบบได้ส่งอีเมลถึงผู้ดูแลแล้ว'];
                $formData = ['name' => '', 'email' => '', 'message' => ''];
            } else {
                // ส่งข้อความผิดพลาดจริงจาก SMTP/transport กลับไปเพื่อ debug ได้ง่าย
                $flash = ['type' => 'error', 'message' => $result['error'] ?? 'ส่งข้อความไม่สำเร็จ'];
            }
        }

        $this->render('send.php', [
            'title' => 'Contact Form',
            'flash' => $flash,
            'formData' => $formData
        ]);
    }

    /**
     * ตรวจสอบความถูกต้องของข้อมูลฟอร์มอีเมล
     *
     * @param array<string, string> $formData
     * @return array{type: string, message: string}|null
     */
    private function validateEmailForm(array $formData): ?array
    {
        if ($formData['name'] === '' || $formData['email'] === '' || $formData['message'] === '') {
            return ['type' => 'error', 'message' => 'กรุณากรอกชื่อ อีเมล และข้อความให้ครบถ้วน'];
        }

        if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            return ['type' => 'error', 'message' => 'รูปแบบอีเมลไม่ถูกต้อง'];
        }

        return null;
    }

    /**
     * แสดงผลเทมเพลตพร้อมข้อมูลที่ส่งเข้ามา
     *
     * @param array<string, mixed> $data
     */
    private function render(string $view, array $data): void
    {
        // กำหนดชื่อไฟล์ view ที่ต้องการ render ภายใน layout
        $viewName = $view;
        // แตกข้อมูลในอาเรย์ออกเป็นตัวแปรสำหรับ template
        extract($data, EXTR_SKIP);
        // โหลด layout หลักซึ่งจะ include view ย่อยตาม $viewName
        require dirname(__DIR__) . '/../templates/layout.php';
    }
}
