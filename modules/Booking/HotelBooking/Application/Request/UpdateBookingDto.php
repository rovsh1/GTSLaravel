<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Request;

use Carbon\CarbonPeriod;

class UpdateBookingDto
{
    public function __construct(
        public readonly int $id,
        public readonly CarbonPeriod $period,
        public readonly ?string $note = null
    ) {}
}
