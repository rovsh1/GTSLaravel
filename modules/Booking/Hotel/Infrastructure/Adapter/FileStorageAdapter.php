<?php

namespace Module\Booking\Hotel\Infrastructure\Adapter;

use Module\Shared\Infrastructure\Adapter\AbstractModuleAdapter;
use Module\Booking\Hotel\Domain\Adapter\FileStorageAdapterInterface;

class FileStorageAdapter extends AbstractModuleAdapter implements FileStorageAdapterInterface
{
    public function create(string $fileType, int $entityId, string $name = null, string $contents = null)
    {
        return $this->request('create', [
            'fileType' => $fileType,
            'entityId' => $entityId,
            'name' => $name,
            'contents' => $contents
        ]);
        //$request = new \PortGateway\FileStorage\CreateRequest();
        //return $this->portGateway->call($request);
    }

    protected function getModuleKey(): string
    {
        return 'files';
    }
}
