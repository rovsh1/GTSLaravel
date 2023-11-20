<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Domain\Booking\Entity\TransferFromRailway;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\RailwayStationId;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

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
            arrivalDate: $details->date_start,
            meetingTablet: $detailsData['meetingTablet'],
            carBids: CarBidCollection::fromData($detailsData['carBids'])
        );
    }
}
