<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Module\Booking\Shared\Domain\Booking\Entity\CIPSendoffInAirport;
use Module\Booking\Shared\Infrastructure\Models\Details\Airport;

class CIPSendoffInAirportStorage extends AbstractStorage
{
    public function store(CIPSendoffInAirport $details): void
    {
        Airport::whereId($details->id()->value())->update([
            'date' => $details->departureDate(),
            'booking_airport_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'airportId' => $details->airportId()->value(),
                'flightNumber' => $details->flightNumber(),
                'guestIds' => $details->guestIds()->toData(),
            ]
        ]);
    }
}
