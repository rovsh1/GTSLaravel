<?php

namespace GTS\Administrator\Infrastructure\Adapter;

use GTS\Administrator\Domain\Adapter\FilesAdapterInterface;
use GTS\Shared\Infrastructure\Adapter\AbstractPortAdapter;

class FilesAdapter extends AbstractPortAdapter implements FilesAdapterInterface
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
