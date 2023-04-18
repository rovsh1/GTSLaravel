<?php

namespace Module\Booking\Hotel\Infrastructure\Adapter;

use Module\Shared\Infrastructure\Adapter\AbstractPortAdapter;
use Module\Booking\Hotel\Domain\Adapter\FileStorageAdapterInterface;

class FileStorageAdapter extends AbstractPortAdapter implements FileStorageAdapterInterface
{
    public function create(string $fileType, int $entityId, string $name = null, string $contents = null)
    {
        return $this->request('files/create', [
            'fileType' => $fileType,
            'entityId' => $entityId,
            'name' => $name,
            'contents' => $contents
        ]);
        //$request = new \PortGateway\FileStorage\CreateRequest();
        //return $this->portGateway->call($request);
    }
}
