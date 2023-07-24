<?php

namespace Module\Booking\HotelBooking\Domain\Adapter;

interface HotelRoomAdapterInterface
{
    public function findById(int $id): mixed;
}
