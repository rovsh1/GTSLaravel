<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\BookingDetails\TransferFromAirport;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\DetailsId;

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
