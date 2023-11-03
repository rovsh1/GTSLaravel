<?php

namespace Module\Catalog\Application\Admin\Dto;

use Module\Shared\Dto\FileDto;

final class ImageDto
{
    public function __construct(
        public readonly int $id,
        public readonly bool $isMain,
        public readonly int $index,
        public readonly FileDto $file,
    ) {
    }
}