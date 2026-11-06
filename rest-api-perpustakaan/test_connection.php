<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Config\Env;
use App\Database\Connection;

Env::load(__DIR__ . '/.env');

$pdo = Connection::get();

echo "Koneksi database berhasil!\n";
echo 'Versi MySQL: ' . $pdo->query('SELECT VERSION()')->fetchColumn() . "\n";
