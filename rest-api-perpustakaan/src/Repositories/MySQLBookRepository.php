<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;
use PDO;

class MySQLBookRepository implements BookRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** @return Book[] */
    public function findAll(): array
    {
        $statement = $this->pdo->query(
            'SELECT id, judul, penulis, tahun, stok FROM books ORDER BY id ASC'
        );

        $books = [];

        foreach ($statement->fetchAll() as $row) {
            $books[] = $this->toBook($row);
        }

        return $books;
    }

    public function findById(int $id): ?Book
    {
        $statement = $this->pdo->prepare(
            'SELECT id, judul, penulis, tahun, stok FROM books WHERE id = :id LIMIT 1'
        );
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        return $row === false ? null : $this->toBook($row);
    }

    public function save(Book $book): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO books (judul, penulis, tahun, stok) VALUES (:judul, :penulis, :tahun, :stok)'
        );
        $statement->execute([
            'judul' => $book->getJudul(),
            'penulis' => $book->getPenulis(),
            'tahun' => $book->getTahun(),
            'stok' => $book->getStok(),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Book $book): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE books SET judul = :judul, penulis = :penulis, tahun = :tahun, stok = :stok WHERE id = :id'
        );

        return $statement->execute([
            'id' => $book->getId(),
            'judul' => $book->getJudul(),
            'penulis' => $book->getPenulis(),
            'tahun' => $book->getTahun(),
            'stok' => $book->getStok(),
        ]);
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM books WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }

    /** @param array<string, mixed> $row */
    private function toBook(array $row): Book
    {
        return new Book(
            (int) $row['id'],
            (string) $row['judul'],
            (string) $row['penulis'],
            (int) $row['tahun'],
            (int) $row['stok']
        );
    }
}
