<?php

declare(strict_types=1);

// โหลด Composer autoload เพื่อเรียกใช้คลาสใน src ผ่าน PSR-4
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Router\Router;

// เริ่มต้นตัวจัดการเส้นทางคำขอของแอป
$router = new Router();
// ส่งคำขอไปยังเมธอดที่ตรงกับ action/method
$router->dispatch();
