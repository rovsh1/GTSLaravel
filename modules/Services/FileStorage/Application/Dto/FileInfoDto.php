<?php

namespace Module\Services\FileStorage\Application\Dto;

class FileInfoDto
{
    public function __construct(
        public readonly bool $exists,
        public readonly int $size,
        public readonly string $mimeType,
        public readonly int $lastModified,
    ) {}
}
