<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Infrastructure\Models\Details\Hotel;

class HotelBookingStorage
{
    public function store(HotelBooking $details): void
    {
        Hotel::whereId($details->id()->value())->update([
            'date_start' => $details->bookingPeriod()->dateFrom(),
            'date_end' => $details->bookingPeriod()->dateTo(),
            'nights_count' => $details->bookingPeriod()->nightsCount(),
            'data' => [
                'hotelInfo' => $details->hotelInfo()->serialize(),
                'period' => $details->bookingPeriod()->serialize(),
                'externalNumber' => $details->externalNumber()?->serialize(),
            ]
        ]);
    }
}
