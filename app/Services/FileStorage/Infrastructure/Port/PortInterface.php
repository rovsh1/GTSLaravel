<?php

namespace GTS\Services\FileStorage\Infrastructure\Port;

use GTS\Services\FileStorage\Application\Dto\FileDto;
use GTS\Services\FileStorage\Application\Dto\FileInfoDto;

interface PortInterface
{
    public function find(string $guid): ?FileDto;

    public function findEntityImage(string $fileType, ?int $entityId): ?FileDto;

    public function getEntityImages(string $fileType, ?int $entityId): array;

    public function getContents(string $guid, int $part = null): ?string;

    public function fileInfo(string $guid, int $part = null): FileInfoDto;

    public function url(string $guid, int $part = null): string;

    public function create(string $fileType, ?int $entityId, string $name = null): FileDto;

    public function put(string $guid, string $contents): bool;

    public function delete(string $guid): bool;
}
