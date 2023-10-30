<?php

namespace Module\Support\FileStorage\Application\Dto;

class FileInfoDto
{
    public function __construct(
        public readonly string $guid,
        public readonly string $name,
        public readonly string $url,
        public readonly string $filename,
        public readonly int $size,
        public readonly string $mimeType,
        public readonly int $lastModified,
    ) {}
}
