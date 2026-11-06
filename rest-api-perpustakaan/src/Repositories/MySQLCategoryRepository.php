<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use PDO;

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** @return Category[] */
    public function findAll(): array
    {
        $statement = $this->pdo->query(
            'SELECT id, nama, deskripsi FROM categories ORDER BY id ASC'
        );

        $categories = [];

        foreach ($statement->fetchAll() as $row) {
            $categories[] = $this->toCategory($row);
        }

        return $categories;
    }

    public function findById(int $id): ?Category
    {
        $statement = $this->pdo->prepare(
            'SELECT id, nama, deskripsi FROM categories WHERE id = :id LIMIT 1'
        );
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        return $row === false ? null : $this->toCategory($row);
    }

    public function save(Category $category): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO categories (nama, deskripsi) VALUES (:nama, :deskripsi)'
        );
        $statement->execute([
            'nama' => $category->getNama(),
            'deskripsi' => $category->getDeskripsi(),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Category $category): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE categories SET nama = :nama, deskripsi = :deskripsi WHERE id = :id'
        );

        return $statement->execute([
            'id' => $category->getId(),
            'nama' => $category->getNama(),
            'deskripsi' => $category->getDeskripsi(),
        ]);
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM categories WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }

    /** @param array<string, mixed> $row */
    private function toCategory(array $row): Category
    {
        return new Category(
            (int) $row['id'],
            (string) $row['nama'],
            $row['deskripsi'] === null ? null : (string) $row['deskripsi']
        );
    }
}
