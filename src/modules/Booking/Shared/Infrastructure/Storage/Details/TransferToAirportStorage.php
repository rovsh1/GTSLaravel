<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Module\Booking\Shared\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

class TransferToAirportStorage extends AbstractStorage
{
    public function store(TransferToAirport $details): bool
    {
        return (bool)Transfer::whereId($details->id()->value())->update([
            'date_start' => $details->departureDate(),
            'booking_transfer_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'airportId' => $details->airportId()->value(),
                'flightNumber' => $details->flightNumber(),
                'meetingTablet' => $details->meetingTablet(),
                'carBids' => $details->carBids()->toData(),
            ]
        ]);
    }
}
