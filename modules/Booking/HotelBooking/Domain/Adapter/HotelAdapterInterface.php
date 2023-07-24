<?php

namespace Module\Booking\HotelBooking\Domain\Adapter;

use Carbon\CarbonInterface;
use Module\Hotel\Application\Response\HotelDto;

interface HotelAdapterInterface
{
    public function findById(int $id): ?HotelDto;

    public function getRoomPrice(
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CarbonInterface $date
    ): ?float;

    public function getMarkupSettings(int $id): mixed;
}
