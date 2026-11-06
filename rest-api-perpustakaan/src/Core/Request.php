<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    private string $method;
    private string $uri;

    /** @var array<string, mixed> */
    private array $body;

    /** @var array<string, mixed> */
    private array $query;

    /**
     * @param array<string, mixed>|null $body
     * @param array<string, mixed>|null $query
     */
    public function __construct(
        ?string $method = null,
        ?string $uri = null,
        ?array $body = null,
        ?array $query = null
    ) {
        $this->method = strtoupper($method ?? (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

        $rawUri = $uri ?? (string) ($_SERVER['REQUEST_URI'] ?? '/');
        $path = parse_url($rawUri, PHP_URL_PATH);
        $path = is_string($path) && $path !== '' ? $path : '/';
        $this->uri = rtrim($path, '/') ?: '/';
        $this->query = $query ?? $_GET;

        if ($body !== null) {
            $this->body = $body;
        } else {
            $raw = file_get_contents('php://input');
            $decoded = json_decode($raw === false ? '' : $raw, true);
            $this->body = is_array($decoded) ? $decoded : [];
        }
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    /** @return array<string, mixed> */
    public function body(): array
    {
        return $this->body;
    }

    /** @return array<string, mixed> */
    public function query(): array
    {
        return $this->query;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    public function queryParam(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }
}
