<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;
use InvalidArgumentException;
use RuntimeException;

class BookService
{
    private BookRepositoryInterface $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /** @return array<int, array<string, int|string|null>> */
    public function list(): array
    {
        return array_map(
            static fn (Book $book): array => $book->toArray(),
            $this->repository->findAll()
        );
    }

    /** @return array<string, int|string|null> */
    public function detail(int $id): array
    {
        return $this->findOrFail($id)->toArray();
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, int|string|null>
     */
    public function create(array $data): array
    {
        $validated = $this->validate($data);

        $book = new Book(
            null,
            $validated['judul'],
            $validated['penulis'],
            $validated['tahun'],
            $validated['stok']
        );

        $id = $this->repository->save($book);

        return $this->detail($id);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, int|string|null>
     */
    public function update(int $id, array $data): array
    {
        $this->findOrFail($id);
        $validated = $this->validate($data);

        $book = new Book(
            $id,
            $validated['judul'],
            $validated['penulis'],
            $validated['tahun'],
            $validated['stok']
        );

        $this->repository->update($book);

        return $this->detail($id);
    }

    public function delete(int $id): void
    {
        $this->findOrFail($id);
        $this->repository->delete($id);
    }

    private function findOrFail(int $id): Book
    {
        $book = $this->repository->findById($id);

        if ($book === null) {
            throw new RuntimeException('Buku tidak ditemukan', 404);
        }

        return $book;
    }

    /**
     * @param array<string, mixed> $data
     * @return array{judul: string, penulis: string, tahun: int, stok: int}
     */
    private function validate(array $data): array
    {
        $judul = trim((string) ($data['judul'] ?? ''));
        $penulis = trim((string) ($data['penulis'] ?? ''));
        $tahun = filter_var($data['tahun'] ?? null, FILTER_VALIDATE_INT);
        $stok = filter_var($data['stok'] ?? null, FILTER_VALIDATE_INT);

        if ($judul === '') {
            throw new InvalidArgumentException('Judul wajib diisi', 422);
        }

        if ($penulis === '') {
            throw new InvalidArgumentException('Penulis wajib diisi', 422);
        }

        if ($tahun === false || $tahun < 1) {
            throw new InvalidArgumentException('Tahun tidak valid', 422);
        }

        if ($stok === false || $stok < 0) {
            throw new InvalidArgumentException('Stok tidak valid', 422);
        }

        return [
            'judul' => $judul,
            'penulis' => $penulis,
            'tahun' => $tahun,
            'stok' => $stok,
        ];
    }
}
