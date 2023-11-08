<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\RoomUpdater\Validator;

use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\RoomUpdater\UpdateDataHelper;

interface ValidatorInterface
{
    public function validate(UpdateDataHelper $dataHelper): void;
}
