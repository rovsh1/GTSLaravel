<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Module\Booking\Shared\Domain\Booking\Entity\CIPMeetingInAirport;
use Module\Booking\Shared\Infrastructure\Models\Details\Airport;

class CIPMeetingInAirportStorage extends AbstractStorage
{
    public function store(CIPMeetingInAirport $details): bool
    {
        return (bool)Airport::whereId($details->id()->value())->update([
            'date' => $details->arrivalDate(),
            'booking_airport_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'airportId' => $details->airportId()->value(),
                'flightNumber' => $details->flightNumber(),
                'guestIds' => $details->guestIds()->toData(),
            ]
        ]);
    }
}
