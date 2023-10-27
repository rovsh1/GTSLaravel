<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Hotel;

use Carbon\CarbonPeriod;
use Module\Booking\Application\Admin\HotelBooking\UseCase\Update;
use Module\Booking\Application\Admin\HotelBooking\UseCase\UpdateExternalNumber;

class DetailsAdapter
{
    public function updateExternalNumber(int $bookingId, int $type, ?string $number): void
    {
        app(UpdateExternalNumber::class)->execute($bookingId, $type, $number);
    }

    public function update(int $bookingId, CarbonPeriod $period, string|null $note): void
    {
        app(Update::class)->execute($bookingId, $period, $note);
    }
}
