<?php

namespace Module\Booking\Moderation\Application\Dto\Details;

use Module\Booking\Moderation\Application\Dto\Details\Accommodation\AccommodationDetailsDto;
use Module\Booking\Moderation\Application\Dto\Details\Accommodation\RoomInfoDto;
use Module\Booking\Moderation\Application\Dto\Details\Accommodation\RoomPriceDto;
use Sdk\Module\Foundation\Support\Dto\Dto;

class AccommodationDto extends Dto
{
    public function __construct(
        public readonly int $id,
        public readonly RoomInfoDto $roomInfo,
        /** @var int[] $guestIds */
        public readonly array $guestIds,
        public readonly AccommodationDetailsDto $details,
        public readonly RoomPriceDto $price
    ) {}
}
