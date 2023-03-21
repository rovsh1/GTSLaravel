<?php

namespace Module\Services\FileStorage\Domain\Repository;

use Module\Services\FileStorage\Domain\Entity\File;

interface DatabaseRepositoryInterface
{
    public function find(string $guid): ?File;

    public function getEntityFile(string $fileType, ?int $entityId): ?File;

    public function getEntityFiles(string $fileType, ?int $entityId);

    public function create(string $fileType, ?int $entityId, string $name = null): File;

    public function update(string $guid, array $attributes): bool;

    public function delete(string $guid): bool;

    public function touch(string $guid): void;
}
