<?php

namespace Module\Booking\Deprecated\HotelBooking\Service\RoomUpdater\Validator;

use Module\Booking\Deprecated\HotelBooking\Service\RoomUpdater\UpdateDataHelper;

interface ValidatorInterface
{
    public function validate(UpdateDataHelper $dataHelper): void;
}
