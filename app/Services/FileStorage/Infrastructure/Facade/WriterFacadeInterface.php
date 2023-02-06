<?php

namespace GTS\Services\FileStorage\Infrastructure\Facade;

use GTS\Services\FileStorage\Application\Dto\FileDto;

interface WriterFacadeInterface
{
    public function create(string $fileType, ?int $entityId, string $name = null, string $contents = null): FileDto;

    public function put(string $guid, string $contents): bool;

    public function delete(string $guid): bool;
}
