<?php

declare(strict_types=1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Max-Age: 86400');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

use App\Config\Env;
use App\Contracts\BookRepositoryInterface;
use App\Contracts\CategoryRepositoryInterface;
use App\Controllers\BookController;
use App\Controllers\CategoryController;
use App\Core\Container;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Database\Connection;
use App\Repositories\MySQLBookRepository;
use App\Repositories\MySQLCategoryRepository;
use App\Services\BookService;
use App\Services\CategoryService;

require __DIR__ . '/../vendor/autoload.php';

Env::load(__DIR__ . '/../.env');

$container = new Container();

$container->bind(PDO::class, static fn (): PDO => Connection::get());

$container->bind(
    BookRepositoryInterface::class,
    static fn (Container $c): BookRepositoryInterface => new MySQLBookRepository($c->make(PDO::class))
);

$container->bind(
    BookService::class,
    static fn (Container $c): BookService => new BookService($c->make(BookRepositoryInterface::class))
);

$container->bind(
    BookController::class,
    static fn (Container $c): BookController => new BookController($c->make(BookService::class))
);

$container->bind(
    CategoryRepositoryInterface::class,
    static fn (Container $c): CategoryRepositoryInterface => new MySQLCategoryRepository($c->make(PDO::class))
);

$container->bind(
    CategoryService::class,
    static fn (Container $c): CategoryService => new CategoryService($c->make(CategoryRepositoryInterface::class))
);

$container->bind(
    CategoryController::class,
    static fn (Container $c): CategoryController => new CategoryController($c->make(CategoryService::class))
);

$router = new Router();

$router->get('/api/books', static fn (): mixed => $container->make(BookController::class)->index());
$router->get('/api/books/{id}', static fn (array $params): mixed => $container->make(BookController::class)->show((int) $params['id']));
$router->post('/api/books', static fn (): mixed => $container->make(BookController::class)->store(new Request()));
$router->put('/api/books/{id}', static fn (array $params): mixed => $container->make(BookController::class)->update((int) $params['id'], new Request()));
$router->delete('/api/books/{id}', static fn (array $params): mixed => $container->make(BookController::class)->destroy((int) $params['id']));

$router->get('/api/categories', static fn (): mixed => $container->make(CategoryController::class)->index());
$router->get('/api/categories/{id}', static fn (array $params): mixed => $container->make(CategoryController::class)->show((int) $params['id']));
$router->post('/api/categories', static fn (): mixed => $container->make(CategoryController::class)->store(new Request()));
$router->put('/api/categories/{id}', static fn (array $params): mixed => $container->make(CategoryController::class)->update((int) $params['id'], new Request()));
$router->delete('/api/categories/{id}', static fn (array $params): mixed => $container->make(CategoryController::class)->destroy((int) $params['id']));

$request = new Request();

try {
    $router->dispatch($request);
} catch (Throwable $exception) {
    $status = (int) $exception->getCode();

    if (!in_array($status, [400, 404, 405, 422], true)) {
        $status = 500;
    }

    $message = $status === 500
        ? 'Terjadi kesalahan pada server'
        : $exception->getMessage();

    Response::error($message, $status);
}
