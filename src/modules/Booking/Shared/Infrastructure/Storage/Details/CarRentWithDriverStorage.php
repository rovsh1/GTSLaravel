<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\Details\CarRentWithDriver;

class CarRentWithDriverStorage extends AbstractStorage
{
    public function store(CarRentWithDriver $details): bool
    {
        return (bool)Transfer::whereId($details->id()->value())->update([
            'date_start' => $details->bookingPeriod()?->dateFrom(),
            'date_end' => $details->bookingPeriod()?->dateTo(),
            'booking_transfer_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'cityId' => $details->cityId()->value(),
                'carBids' => $details->carBids()->toData(),
                'meetingTablet' => $details->meetingTablet(),
                'meetingAddress' => $details->meetingAddress(),
            ]
        ]);
    }
}
