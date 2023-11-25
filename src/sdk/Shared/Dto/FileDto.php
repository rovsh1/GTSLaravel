<?php

namespace Sdk\Shared\Dto;

class FileDto
{
    public function __construct(
        public readonly string $guid,
        public readonly string $name,
        public readonly string $url,
    ) {
    }
}
