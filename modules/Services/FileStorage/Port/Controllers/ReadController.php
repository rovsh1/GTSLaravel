<?php

namespace Module\Services\FileStorage\Port\Controllers;

use Custom\Framework\Port\Request;
use Module\Services\FileStorage\Application\Dto\DataMapper;
use Module\Services\FileStorage\Application\Dto\FileDto;
use Module\Services\FileStorage\Application\Dto\FileInfoDto;
use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Module\Services\FileStorage\Domain\Service\UrlGeneratorInterface;

class ReadController
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly StorageRepositoryInterface $storageRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function find(Request $request): ?FileDto
    {
        $request->validate([
            'guid' => 'required|string',
        ]);

        $file = $this->databaseRepository->find($request->guid);

        return $file ? (new DataMapper($this->urlGenerator))->fileToDto($file) : null;
    }

    public function findEntityImage(Request $request): ?FileDto
    {
        $request->validate([
            'fileType' => 'required|string',
            'entityId' => 'nullable|int',
        ]);

        $file = $this->databaseRepository->findEntityImage($request->fileType, $request->entityId);

        return $file ? (new DataMapper($this->urlGenerator))->fileToDto($file) : null;
    }

    public function getEntityImages(Request $request): array
    {
        $request->validate([
            'fileType' => 'required|string',
            'entityId' => 'nullable|int',
        ]);

        $mapper = new DataMapper($this->urlGenerator);

        return array_map(fn($r) => $mapper->fileToDto($r), $this->databaseRepository->getEntityImages($request->fileType, $request->entityId));
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

    public function url(Request $request): string
    {
        $request->validate([
            'guid' => 'required|string',
            'part' => 'nullable|int',
        ]);

        return $this->urlGenerator->url($request->guid, $request->part);
    }
}
