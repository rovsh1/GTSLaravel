<?php

namespace Module\Booking\Moderation\Application\Dto\Details;

use Module\Booking\Moderation\Application\Dto\Details\Accommodation\AccommodationDetailsDto;
use Module\Booking\Moderation\Application\Dto\Details\Accommodation\RoomInfoDto;
use Module\Booking\Moderation\Application\Dto\Details\Accommodation\RoomPriceDto;

final class AccommodationDto
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
