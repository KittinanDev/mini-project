<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Router\Router;

$router = new Router();
$router->dispatch();
