<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use InvalidArgumentException;
use RuntimeException;

class CategoryService
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /** @return array<int, array<string, int|string|null>> */
    public function list(): array
    {
        return array_map(
            static fn (Category $category): array => $category->toArray(),
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

        $category = new Category(null, $validated['nama'], $validated['deskripsi']);
        $id = $this->repository->save($category);

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

        $category = new Category($id, $validated['nama'], $validated['deskripsi']);
        $this->repository->update($category);

        return $this->detail($id);
    }

    public function delete(int $id): void
    {
        $this->findOrFail($id);
        $this->repository->delete($id);
    }

    private function findOrFail(int $id): Category
    {
        $category = $this->repository->findById($id);

        if ($category === null) {
            throw new RuntimeException('Kategori tidak ditemukan', 404);
        }

        return $category;
    }

    /**
     * @param array<string, mixed> $data
     * @return array{nama: string, deskripsi: ?string}
     */
    private function validate(array $data): array
    {
        $nama = trim((string) ($data['nama'] ?? ''));
        $deskripsi = trim((string) ($data['deskripsi'] ?? ''));

        if ($nama === '') {
            throw new InvalidArgumentException('Nama kategori wajib diisi', 422);
        }

        return [
            'nama' => $nama,
            'deskripsi' => $deskripsi === '' ? null : $deskripsi,
        ];
    }
}
