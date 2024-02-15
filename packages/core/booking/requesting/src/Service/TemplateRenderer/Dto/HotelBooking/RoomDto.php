<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\HotelBooking;

use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\BookingPriceDto;
use Pkg\Booking\Requesting\Service\TemplateRenderer\Dto\GuestDto;

class RoomDto
{
    public function __construct(
        public readonly string $accommodationId,
        public readonly string $name,
        public readonly string $rate,
        public readonly string $checkInTime,
        public readonly string $checkOutTime,
        /** @var GuestDto[] */
        public readonly array $guests,
        public readonly string|null $note,
        public readonly BookingPriceDto $supplierPrice,
        public readonly BookingPriceDto $clientPrice,
    ) {}
}
