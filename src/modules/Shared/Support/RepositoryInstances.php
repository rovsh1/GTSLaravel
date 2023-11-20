<?php

namespace Module\Shared\Support;

class RepositoryInstances
{
    private array $entities = [];

    public function add(mixed $id, mixed $entity): void
    {
        $this->entities[(string)$id] = $entity;
    }

    public function has(mixed $id): bool
    {
        return isset($this->entities[(string)$id]);
    }

    public function get(mixed $id): mixed
    {
        return $this->entities[(string)$id] ?? null;
    }

    public function remove(mixed $id): void
    {
        unset($this->entities[(string)$id]);
    }

    public function all(): array
    {
        return $this->entities;
    }

    public function clear(): void
    {
        $this->entities = [];
    }
}