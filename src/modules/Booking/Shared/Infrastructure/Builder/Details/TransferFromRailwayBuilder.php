<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\Details\TransferFromRailway;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\RailwayStationId;
use Sdk\Module\Support\DateTimeImmutable;

class TransferFromRailwayBuilder extends AbstractServiceDetailsBuilder
{
    public function build(Transfer $details): TransferFromRailway
    {
        $detailsData = $details->data;

        return new TransferFromRailway(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            railwayStationId: new RailwayStationId($detailsData['railwayStationId']),
            trainNumber: $detailsData['trainNumber'],
            arrivalDate: $details->date_start !== null ? DateTimeImmutable::createFromInterface($details->date_start) : null,
            meetingTablet: $detailsData['meetingTablet'],
        );
    }
}
