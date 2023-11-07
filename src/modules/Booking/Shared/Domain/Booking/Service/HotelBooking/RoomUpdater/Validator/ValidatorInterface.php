<?php

namespace Module\Booking\Shared\Domain\Booking\Service\HotelBooking\RoomUpdater\Validator;

use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\RoomUpdater\UpdateDataHelper;

interface ValidatorInterface
{
    public function validate(UpdateDataHelper $dataHelper): void;
}
