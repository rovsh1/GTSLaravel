<?php

declare(strict_types=1);

namespace App\Hotel\Support\Adapters\Booking;

use Module\Booking\Moderation\Application\UseCase\HotelBooking\UpdateExternalNumber;

class DetailsAdapter
{
    public function updateExternalNumber(int $bookingId, int $type, ?string $number): void
    {
        app(UpdateExternalNumber::class)->execute($bookingId, $type, $number);
    }
}
