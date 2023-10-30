<?php

namespace Module\Support\FileStorage\Application\Dto;

class CreateFileRequestDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $contents,
    ) {
    }
}
