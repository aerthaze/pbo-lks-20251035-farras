<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Core\Container;
use App\Core\Request;
use App\Core\Router;

$router = new Router();

$router->get('/api/hello', static function (): void {
    echo "Router bekerja! Endpoint /api/hello terpanggil.\n";
});

$request = new Request('GET', '/api/hello', [], []);
$router->dispatch($request);

$container = new Container();
$container->bind('pesan', static fn (): string => 'Container bekerja!');
echo $container->make('pesan') . "\n";
