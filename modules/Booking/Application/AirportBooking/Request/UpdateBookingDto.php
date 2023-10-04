<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\Request;

use Carbon\CarbonInterface;

class UpdateBookingDto
{
    public function __construct(
        public readonly int $id,
        public readonly CarbonInterface $date,
        public readonly string $flightNumber,
        public readonly ?string $note = null
    ) {}
}
