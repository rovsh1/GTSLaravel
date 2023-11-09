<?php

namespace Module\Hotel\Moderation\Application\RequestDto;

use Module\Shared\Dto\UploadedFileDto;

final class AddImageRequestDto
{
    public function __construct(
        public readonly int $hotelId,
        public readonly ?int $roomId,
        public readonly UploadedFileDto $uploadedFile,
    ) {
    }
}
