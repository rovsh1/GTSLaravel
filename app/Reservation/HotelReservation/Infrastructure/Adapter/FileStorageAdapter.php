<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Adapter;

use GTS\Shared\Infrastructure\Adapter\AbstractPortAdapter;

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
