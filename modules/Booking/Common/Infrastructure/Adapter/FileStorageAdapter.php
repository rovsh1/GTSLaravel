<?php

namespace Module\Booking\Common\Infrastructure\Adapter;

use Module\Booking\Common\Domain\Adapter\FileStorageAdapterInterface;
use Module\Shared\Infrastructure\Adapter\AbstractModuleAdapter;

class FileStorageAdapter extends AbstractModuleAdapter implements FileStorageAdapterInterface
{
    public function create(string $fileType, int $entityId, string $name = null, string $contents = null): mixed
    {
        return $this->request('create', [
            'fileType' => $fileType,
            'entityId' => $entityId,
            'name' => $name,
            'contents' => $contents
        ]);
    }

    public function getEntityFile(int $entityId, string $fileType): ?object
    {
        return $this->request('getEntityFile', [
            'entityId' => $entityId,
            'fileType' => $fileType
        ]);
    }

    protected function getModuleKey(): string
    {
        return 'files';
    }
}
