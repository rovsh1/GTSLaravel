<?php

namespace Module\Booking\Hotel\Domain\Adapter;

interface HotelRoomAdapterInterface
{
    public function findById(int $id): mixed;
}
