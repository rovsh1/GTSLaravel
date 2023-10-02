<?php

namespace Module\Hotel\Application\RequestDto;

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
