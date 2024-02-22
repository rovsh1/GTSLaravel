<?php

namespace Module\Hotel\Moderation\Application\RequestDto;

use Sdk\Shared\Dto\UploadedFileDto;

final class AddImageRequestDto
{
    public function __construct(
        public readonly int $hotelId,
        public readonly ?int $roomId,
        public readonly UploadedFileDto $uploadedFile,
    ) {
    }
}
