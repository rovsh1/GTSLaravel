<?php

namespace Module\Booking\Hotel\Domain\Adapter;

use Module\Hotel\Application\Response\HotelDto;

interface HotelAdapterInterface
{
    public function findById(int $id): ?HotelDto;

    public function getMarkupSettings(int $id): mixed;
}
