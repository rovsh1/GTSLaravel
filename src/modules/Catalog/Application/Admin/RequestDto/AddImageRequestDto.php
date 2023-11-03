<?php

namespace Module\Catalog\Application\Admin\RequestDto;

use Module\Shared\Dto\UploadedFileDto;

class AddImageRequestDto
{
    public function __construct(
        public readonly int $hotelId,
        public readonly ?int $roomId,
        public readonly UploadedFileDto $uploadedFile,
    ) {
    }
}