<?php

namespace Module\Support\FileStorage\Application\Dto;

class UpdateFileRequestDto
{
    public function __construct(
        public readonly string $guid,
        public readonly string $name,
        public readonly string $contents,
    ) {
    }
}
