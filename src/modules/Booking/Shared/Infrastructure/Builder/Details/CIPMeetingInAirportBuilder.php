<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Airport;
use Sdk\Booking\Entity\Details\CIPMeetingInAirport;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\GuestIdCollection;

class CIPMeetingInAirportBuilder extends AbstractServiceDetailsBuilder
{
    public function build(Airport $details): CIPMeetingInAirport
    {
        $detailsData = $details->data;

        return new CIPMeetingInAirport(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            airportId: new AirportId($detailsData['airportId']),
            flightNumber: $detailsData['flightNumber'],
            arrivalDate: $details->date,
            guestIds: GuestIdCollection::deserialize($detailsData['guestIds']),
        );
    }
}
