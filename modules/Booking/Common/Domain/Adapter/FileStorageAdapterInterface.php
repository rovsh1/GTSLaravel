<?php

namespace Module\Booking\Common\Domain\Adapter;

interface FileStorageAdapterInterface
{
    public function create(string $fileType, int $entityId, string $name = null, string $contents = null): mixed;

    public function getEntityFile(int $entityId, string $fileType): ?object;
}
