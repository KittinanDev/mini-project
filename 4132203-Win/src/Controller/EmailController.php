<?php

declare(strict_types=1);

namespace App\Controller;

use App\Mailer;
use App\MailhogClient;

class EmailController
{
    private Mailer $mailer;
    private string $adminEmail;

    public function __construct()
    {
        $this->mailer = new Mailer(
            getenv('SMTP_HOST') ?: '127.0.0.1',
            (int) (getenv('SMTP_PORT') ?: 1025)
        );

        $this->adminEmail = getenv('ADMIN_EMAIL') ?: 'admin@example.com';
    }

    public function showSendForm(): void
    {
        $this->render('send.php', [
            'title' => 'Contact Form',
            'flash' => null,
            'formData' => ['name' => '', 'email' => '', 'message' => '']
        ]);
    }

    public function sendEmail(): void
    {
        $formData = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'message' => trim($_POST['message'] ?? '')
        ];

        $flash = $this->validateEmailForm($formData);

        if ($flash === null) {
            $subject = 'ข้อความใหม่จากเว็บไซต์';
            $body = "ชื่อ: {$formData['name']}\n";
            $body .= "อีเมล: {$formData['email']}\n\n";
            $body .= "ข้อความ:\n{$formData['message']}";

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
     * Validate email form data
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
     * Render a template with data
     *
     * @param array<string, mixed> $data
     */
    private function render(string $view, array $data): void
    {
        $viewName = $view;
        extract($data, EXTR_SKIP);
        require dirname(__DIR__) . '/../templates/layout.php';
    }
}
