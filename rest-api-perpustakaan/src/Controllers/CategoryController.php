<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\CategoryService;

class CategoryController
{
    private CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(): void
    {
        Response::success('Daftar kategori', $this->service->list());
    }

    public function show(int $id): void
    {
        Response::success('Detail kategori', $this->service->detail($id));
    }

    public function store(Request $request): void
    {
        Response::success(
            'Kategori berhasil ditambahkan',
            $this->service->create($request->body()),
            201
        );
    }

    public function update(int $id, Request $request): void
    {
        Response::success(
            'Kategori berhasil diperbarui',
            $this->service->update($id, $request->body())
        );
    }

    public function destroy(int $id): void
    {
        $this->service->delete($id);
        Response::success('Kategori berhasil dihapus');
    }
}
