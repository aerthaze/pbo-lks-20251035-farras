<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\BookService;

class BookController
{
    private BookService $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    public function index(): void
    {
        Response::success('Daftar buku', $this->service->list());
    }

    public function show(int $id): void
    {
        Response::success('Detail buku', $this->service->detail($id));
    }

    public function store(Request $request): void
    {
        Response::success(
            'Buku berhasil ditambahkan',
            $this->service->create($request->body()),
            201
        );
    }

    public function update(int $id, Request $request): void
    {
        Response::success(
            'Buku berhasil diperbarui',
            $this->service->update($id, $request->body())
        );
    }

    public function destroy(int $id): void
    {
        $this->service->delete($id);
        Response::success('Buku berhasil dihapus');
    }
}
