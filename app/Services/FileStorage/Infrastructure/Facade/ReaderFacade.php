<?php

namespace GTS\Services\FileStorage\Infrastructure\Facade;

use GTS\Services\FileStorage\Application\Dto\FileDto;
use GTS\Services\FileStorage\Application\Dto\FileInfoDto;
use GTS\Services\FileStorage\Domain\Entity\File;
use GTS\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use GTS\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use GTS\Services\FileStorage\Domain\Service\UrlGeneratorInterface;

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

        return $file ? $this->fileToDto($file) : null;
    }

    public function findEntityImage(string $fileType, ?int $entityId): ?FileDto
    {
        $file = $this->databaseRepository->findEntityImage($fileType, $entityId);

        return $file ? $this->fileToDto($file) : null;
    }

    public function getEntityImages(string $fileType, ?int $entityId): array
    {
        return array_map(fn($r) => $this->fileToDto($r), $this->databaseRepository->getEntityImages($fileType, $entityId));
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

    private function fileToDto(File $file): FileDto
    {
        return new FileDto(
            $file->guid(),
            $file->type(),
            $file->entityId(),
            $file->name(),
            $this->urlGenerator->url($file->guid())
        );
    }
}
