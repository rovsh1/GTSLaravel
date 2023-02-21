<?php

namespace Module\Services\FileStorage\Infrastructure\Facade;

use Module\Services\FileStorage\Application\Dto\FileDto;
use Module\Services\FileStorage\Application\Dto\FileInfoDto;
use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Module\Services\FileStorage\Domain\Service\UrlGeneratorInterface;

class ReaderFacade implements ReaderFacadeInterface
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly StorageRepositoryInterface $storageRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function find(string $guid): ?FileDto
    {
        $file = $this->databaseRepository->find($guid);

        return $file ? (new DataMapper($this->urlGenerator))->fileToDto($file) : null;
    }

    public function findEntityImage(string $fileType, ?int $entityId): ?FileDto
    {
        $file = $this->databaseRepository->findEntityImage($fileType, $entityId);

        return $file ? (new DataMapper($this->urlGenerator))->fileToDto($file) : null;
    }

    public function getEntityImages(string $fileType, ?int $entityId): array
    {
        $mapper = new DataMapper($this->urlGenerator);

        return array_map(fn($r) => $mapper->fileToDto($r), $this->databaseRepository->getEntityImages($fileType, $entityId));
    }

    public function getContents(string $guid, int $part = null): ?string
    {
        return $this->storageRepository->get($guid, $part);
    }

    public function fileInfo(string $guid, int $part = null): FileInfoDto
    {
        return $this->storageRepository->fileInfo($guid, $part);
    }

    public function url(string $guid, int $part = null): string
    {
        return $this->urlGenerator->url($guid, $part);
    }
}
