<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Adapter;

use GTS\Shared\Domain\Port\PortGatewayInterface;

class FileStorageAdapter implements FileStorageAdapterInterface
{
    public function __construct(
        private readonly PortGatewayInterface $portGateway
    ) {}

    public function create(string $fileType, int $entityId, string $name = null, string $contents = null)
    {
        $request = new \PortGateway\FileStorage\CreateRequest([
            'fileType' => $fileType,
            'entityId' => $entityId,
            'name' => $name,
            'contents' => $contents
        ]);

        return $this->portGateway->call($request);
    }
}
