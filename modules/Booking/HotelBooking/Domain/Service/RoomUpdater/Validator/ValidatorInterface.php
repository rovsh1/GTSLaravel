<?php

namespace Module\Booking\HotelBooking\Domain\Service\RoomUpdater\Validator;

use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\UpdateDataHelper;

interface ValidatorInterface
{
    public function validate(UpdateDataHelper $dataHelper): void;
}