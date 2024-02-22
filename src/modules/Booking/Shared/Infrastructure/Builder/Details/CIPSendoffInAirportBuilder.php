<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Airport;
use Sdk\Booking\Entity\Details\CIPSendoffInAirport;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Module\Support\DateTimeImmutable;

class CIPSendoffInAirportBuilder extends AbstractServiceDetailsBuilder
{
    public function build(Airport $details): CIPSendoffInAirport
    {
        $detailsData = $details->data;

        return new CIPSendoffInAirport(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            airportId: new AirportId($detailsData['airportId']),
            flightNumber: $detailsData['flightNumber'],
            departureDate: $details->date !== null ? DateTimeImmutable::createFromInterface($details->date) : null,
            guestIds: GuestIdCollection::deserialize($details->guestIds),
        );
    }
}
