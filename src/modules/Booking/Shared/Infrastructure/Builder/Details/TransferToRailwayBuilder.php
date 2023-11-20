<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Domain\Booking\Entity\TransferToRailway;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\DetailsId;
use Module\Booking\Shared\Domain\Booking\ValueObject\RailwayStationId;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

class TransferToRailwayBuilder extends AbstractServiceDetailsBuilder
{
    public function build(Transfer $details): TransferToRailway
    {
        $detailsData = $details->data;

        return new TransferToRailway(
            id: new DetailsId($details->id),
            bookingId: new BookingId($details->booking_id),
            serviceInfo: $this->buildServiceInfo($detailsData['serviceInfo']),
            railwayStationId: new RailwayStationId($detailsData['railwayStationId']),
            trainNumber: $detailsData['trainNumber'],
            meetingTablet: $detailsData['meetingTablet'],
            departureDate: $details->date_start,
            carBids: CarBidCollection::fromData($detailsData['carBids'])
        );
    }
}
