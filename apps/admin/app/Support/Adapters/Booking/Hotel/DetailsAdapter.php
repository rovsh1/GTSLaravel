<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Hotel;

use Module\Booking\Application\Admin\HotelBooking\UseCase\UpdateExternalNumber;

class DetailsAdapter
{
    public function updateExternalNumber(int $bookingId, int $type, ?string $number): void
    {
        app(UpdateExternalNumber::class)->execute($bookingId, $type, $number);
    }
}
