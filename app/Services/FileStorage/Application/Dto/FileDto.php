<?php

namespace GTS\Services\FileStorage\Application\Dto;

class FileDto
{
    public function __construct(
        public readonly string $guid,
        public readonly string $type,
        public readonly ?int $entityId,
        public readonly ?string $name,
        public readonly string $url,
    ) {}
}
