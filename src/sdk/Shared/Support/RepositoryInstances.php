<?php

namespace Sdk\Shared\Support;

class RepositoryInstances
{
    private static array $entities = [];

    public function add(mixed $id, mixed $entity): void
    {
        self::$entities[(string)$id] = $entity;
    }

    public function has(mixed $id): bool
    {
        return isset(self::$entities[(string)$id]);
    }

    public function get(mixed $id): mixed
    {
        return self::$entities[(string)$id] ?? null;
    }

    public function remove(mixed $id): void
    {
        unset(self::$entities[(string)$id]);
    }

    public function all(): array
    {
        return self::$entities;
    }

    public function clear(): void
    {
        self::$entities = [];
    }
}
