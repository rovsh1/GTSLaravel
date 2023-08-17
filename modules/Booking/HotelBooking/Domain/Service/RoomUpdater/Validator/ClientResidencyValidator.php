<?php

namespace Module\Booking\HotelBooking\Domain\Service\RoomUpdater\Validator;

use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\UpdateDataHelper;

class ClientResidencyValidator implements ValidatorInterface
{

    public function validate(UpdateDataHelper $dataHelper): void
    {
        $client = $this->clientAdapter->find($dataHelper->bookingId());

        if ($client->isLegal && !$this->isResidencyValid($dataHelper->residency, $client->residency)) {
            throw new InvalidClientResidencyException();
        }
    }

    private function isResidencyValid($roomResidency, $clientResidency): bool
    {
        //if ($dataHelper->residencyType)
    }
}