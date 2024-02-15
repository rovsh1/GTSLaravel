<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\Details\TransferToAirport;
use Sdk\Booking\ValueObject\AirportId;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Module\Support\DateTimeImmutable;

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
            departureDate: $details->date_start !== null ? DateTimeImmutable::createFromInterface($details->date_start) : null,
        );
    }
}
