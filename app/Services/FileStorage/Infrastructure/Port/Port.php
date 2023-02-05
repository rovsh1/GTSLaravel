<?php

namespace GTS\Services\FileStorage\Infrastructure\Port;

use GTS\Services\FileStorage\Application\Dto\FileDto;
use GTS\Services\FileStorage\Application\Dto\FileInfoDto;
use GTS\Services\FileStorage\Infrastructure\Facade\ReaderFacadeInterface;
use GTS\Services\FileStorage\Infrastructure\Facade\WriterFacadeInterface;

class Port implements PortInterface
{
    public function __construct(
        private readonly ReaderFacadeInterface $readerFacade,
        private readonly WriterFacadeInterface $writerFacade
    ) {}

    public function find(string $guid): ?FileDto
    {
        return $this->readerFacade->find($guid);
    }

    public function findEntityImage(string $fileType, ?int $entityId): ?FileDto
    {
        return $this->readerFacade->findEntityImage($fileType, $entityId);
    }

    public function getEntityImages(string $fileType, ?int $entityId): array
    {
        return $this->readerFacade->getEntityImages($fileType, $entityId);
    }

    public function getContents(string $guid, int $part = null): ?string
    {
        return $this->readerFacade->getContents($guid, $part);
    }

    public function fileInfo(string $guid, int $part = null): FileInfoDto
    {
        return $this->readerFacade->fileInfo($guid, $part);
    }

    public function url(string $guid, int $part = null): string
    {
        return $this->readerFacade->url($guid, $part);
    }

    public function create(string $fileType, ?int $entityId, string $name = null): FileDto
    {
        return $this->writerFacade->create($fileType, $entityId, $name);
    }

    public function put(string $guid, string $contents): bool
    {
        return $this->writerFacade->put($guid, $contents);
    }

    public function delete(string $guid): bool
    {
        return $this->writerFacade->delete($guid);
    }
}
