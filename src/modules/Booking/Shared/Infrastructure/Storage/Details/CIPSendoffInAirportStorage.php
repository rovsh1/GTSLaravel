<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Airport;
use Sdk\Booking\Entity\Details\CIPSendoffInAirport;

class CIPSendoffInAirportStorage extends AbstractStorage
{
    public function store(CIPSendoffInAirport $details): void
    {
        $model = Airport::findOrFail($details->id()->value());

        $model->update([
            'date' => $details->departureDate(),
            'guestIds' => $details->guestIds()->serialize(),
            'booking_airport_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'airportId' => $details->airportId()->value(),
                'flightNumber' => $details->flightNumber(),
            ]
        ]);
    }
}
