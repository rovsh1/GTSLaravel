<?php

namespace GTS\Services\FileStorage\Domain\Repository;

use GTS\Services\FileStorage\Domain\Entity\File;

interface DatabaseRepositoryInterface
{
    public function find(string $guid): ?File;

    public function findEntityImage(string $fileType, ?int $entityId): ?File;

    public function getEntityImages(string $fileType, ?int $entityId);

    public function create(string $fileType, ?int $entityId, string $name = null): File;

    public function update(string $guid, array $attributes): bool;

    public function delete(string $guid): bool;

    public function put(string $guid, string $contents): bool;
}
