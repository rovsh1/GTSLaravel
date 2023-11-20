<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Module\Booking\Shared\Domain\Booking\Entity\TransferFromRailway;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

class TransferFromRailwayStorage extends AbstractStorage
{
    public function store(TransferFromRailway $details): bool
    {
        return (bool)Transfer::whereId($details->id()->value())->update([
            'date_start' => $details->arrivalDate(),
            'booking_transfer_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'railwayStationId' => $details->railwayStationId()->value(),
                'trainNumber' => $details->trainNumber(),
                'meetingTablet' => $details->meetingTablet(),
                'carBids' => $details->carBids()->toData(),
            ]
        ]);
    }
}
