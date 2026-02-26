<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\EmailController;

class Router
{
    // action ที่ถูกเรียกผ่าน query string เช่น ?action=send
    private string $action;
    // HTTP method ของคำขอปัจจุบัน (GET/POST)
    private string $method;

    public function __construct()
    {
        // อ่าน action และ HTTP method ครั้งเดียว แล้วนำไปใช้ตอน dispatch
        $this->action = $_GET['action'] ?? 'send';
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * ส่งคำขอไปยังเมธอดของคอนโทรลเลอร์ที่เหมาะสม
     */
    public function dispatch(): void
    {
        // สร้าง controller หลักสำหรับจัดการฟอร์มติดต่อ
        $controller = new EmailController();

        // ตั้งใจให้ routing กระชับสำหรับมินิโปรเจกต์นี้
        match ($this->action) {
            'send' => $this->handleHome($controller),
            default => $this->handleHome($controller),
        };
    }

    private function handleHome(EmailController $controller): void
    {
        // POST = ส่งฟอร์ม, GET = แสดงฟอร์ม
        if ($this->method === 'POST') {
            $controller->sendEmail();
        } else {
            // แสดงหน้าฟอร์มครั้งแรกหรือหลังรีเฟรช
            $controller->showSendForm();
        }
    }
}
