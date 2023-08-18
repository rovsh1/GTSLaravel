<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Factory;

use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\UpdateDataHelper;

class RoomUpdateDataHelperFactory
{
    public function build(): UpdateDataHelper {
        return new UpdateDataHelper();
    }
}
