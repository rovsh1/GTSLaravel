<?php

namespace Module\Booking\Domain\HotelBooking\Service\RoomUpdater\Validator;

use Module\Booking\Domain\HotelBooking\Service\RoomUpdater\UpdateDataHelper;

interface ValidatorInterface
{
    public function validate(UpdateDataHelper $dataHelper): void;
}
