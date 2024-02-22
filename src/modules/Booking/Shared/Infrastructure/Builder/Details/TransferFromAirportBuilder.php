<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\Details\TransferFromAirport;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Shared\Support\DateTimeImmutableFactory;

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
            arrivalDate: $details->date_start !== null ? DateTimeImmutableFactory::createFromTimestamp($details->date_start->getTimestamp()) : null,
            meetingTablet: $detailsData['meetingTablet'] ?? null,
        );
    }
}
