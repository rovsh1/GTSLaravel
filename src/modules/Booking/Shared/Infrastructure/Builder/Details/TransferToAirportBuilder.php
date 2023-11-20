<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Shared\Domain\Booking\ValueObject\AirportId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

class TransferToAirportBuilder extends AbstractServiceDetailsBuilder
{
    public function build(Transfer $details): TransferToAirport
    {
        $detailsData = $details->data;

        return new TransferToAirport(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            airportId: new AirportId($detailsData['airportId']),
            flightNumber: $detailsData['flightNumber'] ?? null,
            meetingTablet: $detailsData['meetingTablet'] ?? null,
            departureDate: $details->date_start,
            carBids: CarBidCollection::fromData($detailsData['carBids'])
        );
    }
}
