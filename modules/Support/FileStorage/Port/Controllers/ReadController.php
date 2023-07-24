<?php

namespace Module\Support\FileStorage\Port\Controllers;

use Module\Support\FileStorage\Application\Dto\FileDto;
use Module\Support\FileStorage\Application\Dto\FileInfoDto;
use Module\Support\FileStorage\Application\Query\FindByEntity;
use Module\Support\FileStorage\Application\Query\FindByGuid;
use Module\Support\FileStorage\Application\Query\GetEntityImages;
use Module\Support\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Module\Support\FileStorage\Domain\Service\UrlGeneratorInterface;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\PortGateway\Request;

class ReadController
{
    public function __construct(
        private readonly StorageRepositoryInterface $storageRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function find(Request $request): ?FileDto
    {
        $request->validate([
            'guid' => 'required|string',
        ]);

        return $this->queryBus->execute(new FindByGuid($request->guid));
    }

    public function getEntityFile(Request $request): ?FileDto
    {
        $request->validate([
            'fileType' => 'required|string',
            'entityId' => 'nullable|int',
        ]);

        return $this->queryBus->execute(new FindByEntity($request->fileType, $request->entityId));
    }

    public function getEntityFiles(Request $request): array
    {
        $request->validate([
            'fileType' => 'required|string',
            'entityId' => 'nullable|int',
        ]);

        return $this->queryBus->execute(new GetEntityImages($request->fileType, $request->entityId));
    }

    public function getContents(Request $request): ?string
    {
        $request->validate([
            'guid' => 'required|string',
            'part' => 'nullable|int',
        ]);

        return $this->storageRepository->get($request->guid, $request->part);
    }

    public function fileInfo(Request $request): FileInfoDto
    {
        $request->validate([
            'guid' => 'required|string',
            'part' => 'nullable|int',
        ]);

        return $this->storageRepository->fileInfo($request->guid, $request->part);
    }

    public function url(Request $request): ?string
    {
        $request->validate([
            'guid' => 'required|string',
            'part' => 'nullable|int',
        ]);

        return $this->urlGenerator->url($request->guid, $request->part);
    }
}
