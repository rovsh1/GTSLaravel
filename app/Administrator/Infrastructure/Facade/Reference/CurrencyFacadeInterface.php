<?php

namespace GTS\Administrator\Infrastructure\Facade\Reference;

interface CurrencyFacadeInterface
{
    public function findById(int $id);

    public function search(mixed $params = null);

    public function count(mixed $params = null): int;

    public function store(array $params): ?int;

    public function update(int $id, array $params): ?int;
}
