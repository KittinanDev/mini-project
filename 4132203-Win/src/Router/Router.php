<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\EmailController;

class Router
{
    private string $action;
    private string $method;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? 'send';
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Dispatch the request to appropriate controller action
     */
    public function dispatch(): void
    {
        $controller = new EmailController();

        match ($this->action) {
            'send' => $this->handleSend($controller),
            default => $this->handleSend($controller),
        };
    }

    private function handleHome(EmailController $controller): void
    {
        if ($this->method === 'POST') {
            $controller->sendEmail();
        } else {
            $controller->showSendForm();
        }
    }

    private function handleInbox(EmailController $controller): void
    {
        $controller->inbox();
    }
}
