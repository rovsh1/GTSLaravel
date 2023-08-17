<?php

namespace Module\Booking\HotelBooking\Domain\Service\RoomUpdater\Validator;

use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\UpdateDataHelper;

class QuotaAvailabilityValidator implements ValidatorInterface
{
    public function validate(UpdateDataHelper $dataHelper): void
    {
        if ($bookingType) {
            throw new HotelQuotaUnavailableException();
        }
    }
}