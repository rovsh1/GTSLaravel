<?php

namespace GTS\Services\FileStorage\Infrastructure\Facade;

use GTS\Services\FileStorage\Application\Dto\FileDto;
use GTS\Services\FileStorage\Application\Dto\FileInfoDto;

interface ReaderFacadeInterface
{
    public function find(string $guid): ?FileDto;

    public function findEntityImage(string $fileType, ?int $entityId): ?FileDto;

    public function getEntityImages(string $fileType, ?int $entityId): array;

    public function getContents(string $guid, int $part = null): ?string;

    public function fileInfo(string $guid, int $part = null): FileInfoDto;

    public function url(string $guid, int $part = null): string;
}
