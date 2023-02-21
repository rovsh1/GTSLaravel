<?php

namespace Module\Services\FileStorage\Infrastructure\Facade;

use Module\Services\FileStorage\Application\Dto\FileDto;

interface WriterFacadeInterface
{
    public function create(string $fileType, ?int $entityId, string $name = null, string $contents = null): FileDto;

    public function put(string $guid, string $contents): bool;

    public function delete(string $guid): bool;
}
