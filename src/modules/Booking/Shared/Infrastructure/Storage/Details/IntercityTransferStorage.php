<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Module\Booking\Shared\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

class IntercityTransferStorage extends AbstractStorage
{
    public function store(IntercityTransfer $details): void
    {
        Transfer::whereId($details->id()->value())->update([
            'date_start' => $details->departureDate(),
            'booking_transfer_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'fromCityId' => $details->fromCityId()->value(),
                'toCityId' => $details->toCityId()->value(),
                'returnTripIncluded' => $details->isReturnTripIncluded(),
                'carBids' => $details->carBids()->toData(),
            ]
        ]);
    }
}
