<?php

namespace Module\Services\FileStorage\Domain\Repository;

interface StorageRepositoryInterface
{
    public function get(string $guid, int $part = null): ?string;

    public function put(string $guid, string $contents): bool;

    public function delete(string $guid): bool;
}
