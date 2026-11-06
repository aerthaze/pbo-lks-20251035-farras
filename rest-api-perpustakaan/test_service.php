<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Config\Env;
use App\Database\Connection;
use App\Repositories\MySQLBookRepository;
use App\Services\BookService;

Env::load(__DIR__ . '/.env');

$repository = new MySQLBookRepository(Connection::get());
$service = new BookService($repository);

$books = $service->list();
echo 'Daftar buku: ' . count($books) . "\n";

try {
    $service->create(['judul' => '']);
    echo "Validasi GAGAL: input kosong diterima.\n";
} catch (Throwable $exception) {
    echo 'Validasi: ' . $exception->getMessage() . ' (kode ' . $exception->getCode() . ")\n";
}

$created = $service->create([
    'judul' => 'Pulang',
    'penulis' => 'Leila S. Chudori',
    'tahun' => 2012,
    'stok' => 4,
]);

echo 'Buku dibuat: ID ' . $created['id'] . ' - ' . $created['judul'] . "\n";
