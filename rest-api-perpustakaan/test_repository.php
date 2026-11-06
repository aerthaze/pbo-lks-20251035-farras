<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Config\Env;
use App\Database\Connection;
use App\Repositories\MySQLBookRepository;

Env::load(__DIR__ . '/.env');

$repository = new MySQLBookRepository(Connection::get());
$books = $repository->findAll();

echo 'Total buku: ' . count($books) . "\n";

foreach ($books as $book) {
    echo '- ' . $book->getJudul() . ' (' . $book->getPenulis() . ")\n";
}
