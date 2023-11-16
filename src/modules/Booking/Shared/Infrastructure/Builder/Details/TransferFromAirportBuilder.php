<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Shared\Domain\Booking\ValueObject\AirportId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

class TransferFromAirportBuilder extends AbstractServiceDetailsBuilder
{
    public function build(Transfer $details): TransferFromAirport
    {
        $detailsData = $details->data;

        return new TransferFromAirport(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            airportId: new AirportId($detailsData['airportId']),
            flightNumber: $detailsData['flightNumber'] ?? null,
            arrivalDate: $details->date_start,
            meetingTablet: $detailsData['meetingTablet'] ?? null,
            carBids: CarBidCollection::fromData($detailsData['carBids'])
        );
    }
}
