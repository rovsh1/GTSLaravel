<?php

namespace Module\Hotel\Application\Dto;

use Module\Shared\Dto\FileDto;

class ImageDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $isMain,
        public readonly int $index,
        public readonly FileDto $file,
    ) {
    }
}