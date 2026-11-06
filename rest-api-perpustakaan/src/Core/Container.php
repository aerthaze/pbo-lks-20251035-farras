<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

class Container
{
    /** @var array<string, callable> */
    private array $bindings = [];

    /** @var array<string, mixed> */
    private array $instances = [];

    public function bind(string $id, callable $resolver): void
    {
        $this->bindings[$id] = $resolver;
    }

    public function make(string $id): mixed
    {
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        if (isset($this->bindings[$id])) {
            return $this->instances[$id] = ($this->bindings[$id])($this);
        }

        if (class_exists($id)) {
            return $this->instances[$id] = new $id();
        }

        throw new RuntimeException("Binding tidak ditemukan: {$id}");
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || array_key_exists($id, $this->instances);
    }
}
