<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Airport;
use Sdk\Booking\Entity\Details\CIPMeetingInAirport;

class CIPMeetingInAirportStorage extends AbstractStorage
{
    public function store(CIPMeetingInAirport $details): void
    {
        $model = Airport::findOrFail($details->id()->value());

        $model->data = [
            'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
            'airportId' => $details->airportId()->value(),
            'flightNumber' => $details->flightNumber(),
        ];

        $model->update([
            'date' => $details->arrivalDate(),
            'guestIds' => $details->guestIds()->serialize(),
        ]);
    }
}
