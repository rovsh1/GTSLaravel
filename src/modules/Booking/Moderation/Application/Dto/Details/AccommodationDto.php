<?php

namespace Module\Booking\Moderation\Application\Dto\Details;

use Module\Booking\Moderation\Application\Dto\Details\Accommodation\AccommodationDetailsDto;
use Module\Booking\Moderation\Application\Dto\Details\Accommodation\RoomInfoDto;
use Module\Booking\Moderation\Application\Dto\Details\Accommodation\RoomPriceDto;
use Module\Booking\Moderation\Application\Dto\GuestDto;

final class AccommodationDto
{
    public function __construct(
        public readonly int $id,
        public readonly RoomInfoDto $roomInfo,
        /** @var GuestDto[] $guests */
        public readonly array $guests,
        public readonly AccommodationDetailsDto $details,
        public readonly RoomPriceDto $price
    ) {}
}
